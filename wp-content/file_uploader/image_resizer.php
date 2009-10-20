<?php
	 function resize_image($path,$size1,$file_name)
	 {
	 	 /* Image resize section begins */
	 //Assign Filename
	 $filename 		= $path.$file_name;
	 $newheight		= $size1;
	 $newwidth 		= $size1;
    //Searches image name string to select extension (everything after . )
    $image_type = strstr($file_name,  '.');
    //Switches the image create function based on file extension
        switch($image_type) {
            case '.jpg':
                $source = imagecreatefromjpeg($filename);
				$flag_image = 1;
                break;
            case '.png':
                $source = imagecreatefrompng($filename);
				$flag_image = 2;
                break;
            case '.gif':
                $source = imagecreatefromgif($filename);
				$flag_image = 3;
                break;
            default:
                echo("Error Invalid Image Type");
                die;
                break;
            }
    //Created the name of the saved file
    $file = $file_name ;
    //Created the path to the saved file
    $fullpath = $filename;
    //Finds size of the old file
    list($width,  $height) = getimagesize($filename);
    //Create Thumb
    $thumb = imagecreatetruecolor($newwidth,  $newheight);
	//Transparent codes begin
	imagealphablending($thumb, false);
	
	imagesavealpha($thumb,true);
	
	$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
	
	imagefilledrectangle($thumb, 0, 0, $newwidth,  $newheight, $transparent);
	//Transparent codes ends	
    //Resizes old image to new sizes
    imagecopyresized($thumb,  $source,  0,  0,  0,  0,  $newwidth,  $newheight,  $width,  $height);
    //Saves image and set quality || numerical value = quality on scale of 1 - 100
	if($flag_image == 1)
	{
    @imagejpeg($thumb,$fullpath,60);
	}
	if($flag_image == 2)
	{
	@imagepng($thumb,$fullpath);
	}
	if($flag_image == 3)
	{
	@imagegif($thumb,$fullpath);
	}		
    //Creating filename to write to database
    $filepath = $fullpath;
	 /* Image resize section ends */
	 }
?>