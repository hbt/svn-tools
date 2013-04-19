<?php
class vbImageUtils {

	/**
	 * resizing image
	 * use full path for source and destination
	 */
	public static function resize ($imageSource, $imageDestination, $newwidth, $newheight, $enableRatioFromOriginal = false) {
		// This is the temporary file created by PHP
//		$uploadedfile = $_FILES['uploadfile']['tmp_name'];

		// Create an Image from it so we can do the resize
		$src = imagecreatefromjpeg($imageSource);

		// Capture the original size of the uploaded image
		list($width,$height)=getimagesize($imageSource);

		// For our purposes, I have resized the image to be
		// 600 pixels wide, and maintain the original aspect
		// ratio. This prevents the image from being "stretched"
		// or "squashed". If you prefer some max width other than
		// 600, simply change the $newwidth variable
		if ($enableRatioFromOriginal) {
			$newheight = ($height/$width)*$newwidth;
		}
//		$newwidth=$destWidth;
//		$newheight=($height/$width)*$destHeight;
		$tmp=imagecreatetruecolor($newwidth,$newheight);

		// this line actually does the image resizing, copying from the original
		// image into the $tmp image
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

		// now write the resized image to disk. I have assumed that you want the
		// resized, uploaded image file to reside in the ./images subdirectory.

		imagejpeg($tmp,$imageDestination,100);

		imagedestroy($src);
		imagedestroy($tmp); // NOTE: PHP will clean up the temp file it created when the request
		// has completed.
	}
}
?>
