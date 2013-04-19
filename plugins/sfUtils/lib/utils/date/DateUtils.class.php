<?php

class DateUtils {

    public static function timeSince ($fromTime) {
	$fromDate = new sfDate ($fromTime);
	$fromDateDump = $fromDate->dump();

	$nowDate = new sfDate();
	$nowDateDump = $nowDate->dump();

	$diff = $nowDate->diff($fromDate);

	if ($diff == 1) {
		return sfContext::getInstance()->getI18N()->__('a second ago');
	}
	else if ($diff < 60) {
		return sfContext::getInstance()->getI18N()->__('%value% seconds ago', array (
			'value' => $diff
		));
	}
	else if ($diff == 60 || (int)ceil(($diff/60)) == 1) {
		return sfContext::getInstance()->getI18N()->__('1 minute ago');
	}
	else if ($diff < 3600) {
		return  sfContext::getInstance()->getI18N()->__('%value% minutes ago', array (
			'%value%' => (int)ceil(($diff/60))
		));
	}
	else if ($diff == 3600 || (int)ceil(($diff/3600)) == 1) {
		return sfContext::getInstance()->getI18N()->__('1 hour ago');
	}
	else if ($diff < 86400) {
		return sfContext::getInstance()->getI18N()->__('%value% hours ago', array(
			'%value%' =>  (int)ceil(($diff/3600))
		));
	}
	else if ($diff == 86400 || (int)ceil(($diff/604800)) == 1) {
		return sfContext::getInstance()->getI18N()->__('yesterday');
	}
	else if ($diff < 604800) {
		return  sfContext::getInstance()->getI18N()->__('%value% days ago', array (
			'%value%' => (int)ceil(($diff/604800))
		));
	}
	else if ($diff == 604800 || (int)ceil(($diff/604800)) == 1) {
		return sfContext::getInstance()->getI18N()->__('1 week ago');
	}
	else if ($diff <= 1814400) {
		return sfContext::getInstance()->getI18N()->__('%value% weeks ago', array (
			'%value%' => (int)ceil(($diff/604800))
		));
	}
	else if ($diff < 31449600) {
		if ((int)ceil(($diff/2419200)) == 1) {
			return sfContext::getInstance()->getI18N()->__('a month ago');
		}
		return sfContext::getInstance()->getI18N()->__('%value% months ago', array (
			'%value%' => (int)ceil(($diff/2419200))
		));
	}
	else if ($diff == 31449600 || (int)ceil(($diff/31449600)) == 1) {
		return sfContext::getInstance()->getI18N()->__(' a year ago');
	}
	else if ($diff > 31449600) {
		return  sfContext::getInstance()->getI18N()->__('%value% years ago', array (
			'%value%' => (int)ceil(($diff/31449600))
		));
	}
	else
	{
		return $fromTime;
	}

	}

	public static function retrieveMonthNumberByName ($monthName) {
		$months = array (
			'Jan' => '01',
			'Feb' => '02',
			'Mar' => '03',
			'Apr' => '04',
			'May' => '05',
			'Jun' => '06',
			'Jul' => '07',
			'Aug' => '08',
			'Sep' => '09',
			'Oct' => '10',
			'Nov' => '11',
			'Dec' => '12'
		);

		return $months[$monthName];
	}

	public static function formatTime ($time_in_secs) {
		$secs = $time_in_secs;
		$mins = (int) ($secs / 60);

		if ($mins < 60) {
			return $mins . ' minutes';
		} else {
			$hrs = (int) ($mins / 60);
			$rem = $mins - $hrs * 60;
			return $hrs . 'h' . $rem;
		}
	}

	public static function getAge ($dateOfBirth) {
		$fromDate = new sfDate ($dateOfBirth);

		$nowDate = new sfDate();
//		$nowDateDump = $nowDate->dump();

		$diff = $nowDate->diff($fromDate);
		$diffDate = new sfDate($diff);

		$year = $diffDate->retrieve(sfTime::YEAR) - 1970;
		if ($year <= 0) {
			return 0;
		}

		return $year;
	}

	public static function getMonth ($time) {
		$d = new sfDate($time);
		return $d->retrieve(sfTime::MONTH);
	}

	public static function getYear ($time) {
		$d = new sfDate($time);
		return $d->retrieve(sfTime::YEAR);
	}
}
?>