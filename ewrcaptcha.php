<?php
session_start();
?>
<?php include_once "rcfg11.php" ?>
<?php

class crCaptcha {
	var $font = 'aftershock';
	var $background_color = 'FFFFFF'; // Hex string
	var $text_color = '003359'; // Hex string
	var $noise_color = '64A0C8'; // Hex string
	var $width = 250; // P61
	var $height = 50; // P61
	var $characters = 6;
	var $font_size;
	var $image_type = IMG_PNG;

	function __construct() {
		$this->font_size = $this->height * 0.55; // P61
	}

	function GenerateCode($characters) {
		$possible = '23456789BCDFGHJKMNPQRSTVWXYZ'; // Possible characters // P61
		$code = '';
		$i = 0;
		while ($i < $characters) {
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	function HexToRGB($hexstr) {
		$int = hexdec($hexstr);
		return array("R" => 0xFF & ($int >> 0x10),
			"G" => 0xFF & ($int >> 0x8),
			"B" => 0xFF & $int);
	}

	function Show() {
		$code = $this->GenerateCode($this->characters);
		$ocode = $code; // P61
		$code = "";
		$len = strlen($ocode);
		for ($i=0; $i<$len; $i++) {
			$code .= $ocode[$i];
			if ($i < $len-1)
				$code .= " ";
		}
		$code = trim($code);
		$image = imagecreate($this->width, $this->height) or die('Cannot initialize new GD image stream');
		$RGB = $this->HexToRGB($this->background_color);
		$background_color = imagecolorallocate($image, $RGB['R'], $RGB['G'], $RGB['B']);
		$RGB = $this->HexToRGB($this->text_color);
		$text_color = imagecolorallocate($image, $RGB['R'], $RGB['G'], $RGB['B']);
		$RGB = $this->HexToRGB($this->noise_color);
		$noise_color = imagecolorallocate($image, $RGB['R'], $RGB['G'], $RGB['B']);

		// Generate random dots in background
		for ($i=0; $i<($this->width*$this->height)/3; $i++) {
			imagefilledellipse($image, mt_rand(0,$this->width), mt_rand(0,$this->height), 1, 1, $noise_color);
		}

		// Generate random lines in background
		for ($i=0; $i<($this->width*$this->height)/150; $i++) {
			imageline($image, mt_rand(0,$this->width), mt_rand(0,$this->height), mt_rand(0,$this->width), mt_rand(0,$this->height), $noise_color);
		}
		$font_file = $this->font;

		// Always use full path
		if (strrpos($font_file, '.') === FALSE)
			$font_file .= '.ttf';
		$font_file = $GLOBALS["EWR_FONT_PATH"] . EWR_PATH_DELIMITER . $font_file;

		// Create textbox and add text
		$textbox = imagettfbbox($this->font_size, 0, $font_file, $code) or die('Error in imagettfbbox function');
		$x = ($this->width - $textbox[4])/2;
		$y = ($this->height - ($textbox[5] - $textbox[3]))/2;
		imagettftext($image, $this->font_size, 0, $x, $y, $text_color, $font_file , $code) or die('Error in imagettftext function');

		// Output captcha image to browser
		switch ($this->image_type) {
			case IMG_JPG:
				header("Content-Type: image/jpeg");
				imagejpeg($image, null, 90);
				break;
			case IMG_GIF:
				header("Content-Type: image/gif");
				imagegif($image);
				break;
			default: // PNG
				header("Content-Type: image/png");
				imagepng($image);
				break;
		}
		imagedestroy($image);
		$_SESSION["EWR_CAPTCHA_CODE"] = $ocode;
	}
}
$captcha = new crCaptcha();
$captcha->Show();
?>
