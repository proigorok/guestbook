<?php
namespace Records\Model;

class Helper 
{
    static public function resizeImage($image, $cfg_x, $cfg_y, $width, $height, $path = '')
    {
        if (!$path)
            $path = $image;

        if (!$info = getimagesize($image))
			return false;

		switch ($info[2])
		{
			case 1: $image = imagecreatefromgif($image);
			break;

			case 2: $image = imagecreatefromjpeg($image);
			break;

			case 3: $image = imagecreatefrompng($image);
			break;

			default: return false;
		}

                if ($width > $cfg_x)
			$x_pr = $cfg_x / $width;
		else
			$x_pr = 1;

		if ($height > $cfg_y)
			$y_pr = $cfg_y / $height;
		else
			$y_pr = 1;

		if ($x_pr <= $y_pr)
			$pr = $x_pr;
		else
			$pr = $y_pr;

		$new_width = ceil($width * $pr);
		$new_height = ceil($height * $pr);

		$new_image = imageCreateTrueColor($new_width, $new_height);

		imageCopyResampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		imagedestroy($image);

		switch ($info[2])
		{
			case 1: imagegif($new_image, $path);
			break;

			case 2: imagejpeg($new_image, $path, 100);
			break;

			case 3: imagepng($new_image, $path);
			break;
		}

        return true;
    }
}