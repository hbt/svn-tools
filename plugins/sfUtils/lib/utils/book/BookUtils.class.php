<?php
class BookUtils {

	public static function removeDashes($field) {
		$field = str_ireplace("-", "", $field);
		return $field;
	}

	public static function isISBN10($isbn) {
		//		$isbn = self::removeDashes ($isbn);
		//		if (strlen($isbn) == 10 && is_numeric ($isbn)) {
		//			return true;
		//		} else {
		//			return false;
		//		}

		$isbn = self :: removeDashes($isbn);
		if (strlen ($isbn) != 10) {
			return false;
		}

		if (is_numeric ($isbn)) {
			return true;
		}

		$chkSum = substr($isbn, -1, 1);
		$isbn = substr($isbn, 0, -1);

		if (!is_numeric($isbn)) {
			return false;
		}

		if (preg_match('/X/i', $chkSum)) {
			$chkSum = 10;
		}

		$sum = self :: genChkSumTen($isbn);

		if ($chkSum == $sum) {
			return true;
		} else {
			return false;
		}
	}

	static public function genChkSumThirteen($isbn) {
		$t = 2;
		$isbn = self :: removeDashes($isbn);
		$b = 0;

		for ($i = 1; $i <= 12; $i++) {
			$c = substr($isbn, ($i -1), 1);

			if (($i % 2) == 0) {
				$a = (3 * $c);
			} else {
				$a = (1 * $c);
			}

			$b = $b + $a;
		}

		$sum = 10 - ($b % 10);

		$sum = $sum == 10 ? 0 : $sum;

		return $sum;
	}

	public static function isISBN13($isbn) {
		$isbn = self::removeDashes ($isbn);

		if (strlen($isbn) == 13 && is_numeric ($isbn)) {
			return true;
		} else {
			return false;
		}
	}

	public static function convertISBN13toISBN10($isbn13) {
		if (self :: isISBN13($isbn13)) {
			$isbn2 = substr(self :: removeDashes($isbn13), 3, 9);
			$sum10 = self :: genChkSumTen($isbn2);
			$sum10 = $sum10 == 10 ? 'X' : $sum10;
			$isbn10 = $isbn2 . $sum10;

			return $isbn10;
		} else {
			return $isbn13;
		}
	}

	static public function genChkSumTen($isbn) {
		$t = 2;

		$isbn = self :: removeDashes($isbn);

		$b = 0;
		for ($i = 0; $i < 9; $i++) {
			$c = substr($isbn, $i, 1);
			$a = (($i +1) * $c);
			$b = $b + $a;
		}

		$sum = ($b % 11);

		return $sum;
	}

	public static function convertISBN10ToISBN13($isbn) {
		$isbn = self :: removeDashes($isbn);
		if (self :: isISBN10($isbn)) {
			$isbn13 = "978" . substr($isbn, 0, strlen($isbn) - 1);

			$digit = BookUtils :: calculateEANCheckDigit($isbn13);
			$isbn13 .= $digit;
			return $isbn13;
		} else {
			return $isbn;
		}

	}

	public static function getFormattedISBN13($isbn) {
		if (BookUtils :: isISBN13($isbn)) {
			$digits = str_split($isbn);
			$f_isbn = $digits[0] . $digits[1] . $digits[2] . "-" . $digits[3] . "-" . $digits[4] . $digits[5] . $digits[6] . $digits[7] . $digits[8] . $digits[9] . "-" . $digits[10] . $digits[11] . "-" . $digits[12];
			return $f_isbn;
		} else {
			return $isbn;
		}
	}

	public static function getFormattedISBN10 ($isbn) {
		if (BookUtils :: isISBN10($isbn)) {
			$digits = str_split($isbn);
			$f_isbn = $digits[0] . '-' . $digits[1] . $digits[2] .  $digits[3]  . $digits[4] . '-' . $digits[5] . $digits[6] . $digits[7] . $digits[8] . '-' . $digits[9];
			return $f_isbn;
		} else {
			return $isbn;
		}
	}

	public static function calculateEANCheckDigit($isbn13) {
		$sum = 0;
		$digits = str_split($isbn13);
		$j = 0;
		for ($i = 12; $i > 0; $i--) {
			if (($j % 2) == 0) {
				$sum += $digits[$j];
			} else {
				$sum += $digits[$j] * 3;
			}

			$j++;
		}

		$digit = $sum % 10;
		$digit = 10 - $digit;

		if ($digit == 10) {
			return 0;
		}

		return $digit;
	}

	public static function isISBNValid($isbn) {
		$isbn = self :: removeDashes($isbn);

		if (self :: isISBN13($isbn)) {
			return true;
		}

		if (self :: isISBN10($isbn)) {
			return true;
		}

		return false;
	}

}
?>