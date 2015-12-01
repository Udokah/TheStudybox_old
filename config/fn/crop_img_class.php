<?php

/* Crop Image Class */

class Crop_Image_To_Square {

var $source_image;
var $new_image_name;
var $save_to_folder;

function crop($location = 'center'){
$info = GetImageSize($this->source_image);

$width = $info[0];
$height = $info[1];
$mime = $info['mime'];

if($width !== $height){
// What sort of image?

$type = substr(strrchr($mime, '/'), 1);

switch ($type)
{
case 'jpeg':
    $image_create_func = 'ImageCreateFromJPEG';
    $image_save_func = 'ImageJPEG';
	$new_image_ext = 'jpg';
    break;

case 'png':
    $image_create_func = 'ImageCreateFromPNG';
    $image_save_func = 'ImagePNG';
	$new_image_ext = 'png';
    break;

case 'bmp':
    $image_create_func = 'ImageCreateFromBMP';
    $image_save_func = 'ImageBMP';
	$new_image_ext = 'bmp';
    break;

case 'gif':
    $image_create_func = 'ImageCreateFromGIF';
    $image_save_func = 'ImageGIF';
	$new_image_ext = 'gif';
    break;

case 'vnd.wap.wbmp':
    $image_create_func = 'ImageCreateFromWBMP';
    $image_save_func = 'ImageWBMP';
	$new_image_ext = 'bmp';
    break;

case 'xbm':
    $image_create_func = 'ImageCreateFromXBM';
    $image_save_func = 'ImageXBM';
	$new_image_ext = 'xbm';
    break;

default:
	$image_create_func = 'ImageCreateFromJPEG';
    $image_save_func = 'ImageJPEG';
	$new_image_ext = 'jpg';
}

// Coordinates calculator

   if($width > $height) // Horizontal Rectangle?
   {
	   if($location == 'center')
       {
       $x_pos = ($width - $height) / 2;
       $x_pos = ceil($x_pos);

       $y_pos = 0;
	   }
	   else if($location == 'left')
	   {
	   $x_pos = 0;
	   $y_pos = 0;
	   }
	   else if($location == 'right')
	   {
	   $x_pos = ($width - $height);
	   $y_pos = 0;
	   }

       $new_width = $height;
       $new_height = $height;
   }
   else if($height > $width) // Vertical Rectangle?
   {
	   if($location == 'center')
       {
       $x_pos = 0;

       $y_pos = ($height - $width) / 2;
       $y_pos = ceil($y_pos);
       }
	   else if($location == 'left')
	   {
	   $x_pos = 0;
	   $y_pos = 0;
	   }
	   else if($location == 'right')
	   {
	   $x_pos = 0;
	   $y_pos = ($height - $width);
	   }

       $new_width = $width;
       $new_height = $width;

   }

$image = $image_create_func($this->source_image);

$new_image = ImageCreateTrueColor($new_width, $new_height);

// Crop to Square using the given dimensions
ImageCopy($new_image, $image, 0, 0, $x_pos, $y_pos, $width, $height);

if($this->save_to_folder)
		{
		$save_path = $this->save_to_folder.$this->source_image ;
		}
		else
		{
		/* Show the image (on the fly) without saving it to a folder */
		   header("Content-Type: ".$mime);

	       $image_save_func($new_image);

		   $save_path = '';
		}

// Save image 

$process = $image_save_func($new_image, $save_path) or die("There was a problem in saving the new file.");

return array('result' => $process, 'new_file_path' => $save_path);
}
}

function new_image_name($filename){
	return $filename;
	}
}
?>