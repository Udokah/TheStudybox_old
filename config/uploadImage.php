<?php
session_start();
ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes
ini_set('memory_limit', '20000M'); // increase memory limit

include("inc/connect.php") ;
include("fn/lib.php") ;
include("fn/simpleimage.php"); 
include("fn/crop_img_class.php"); 

if($_POST['action'] !== 'UploadAvatar'){
	echo 'Not allowed' ;
	exit();
}

$max_size = 3145728 ; /// 3MB
$valid_formats = array('png', 'gif', 'jpg' , 'jpeg') ; /// valid formats
$path = '../avatars-main/' ;

$avatar = $_FILES['avatar'] ;

if($avatar == ''){ echo 'No image selected' ; exit(); }


    $name =  $avatar['name']  ;
	$size =  $avatar['size']  ;
	$tmp_name =  $avatar['tmp_name']  ;
	
	// Check if file is an image
	list($txt, $ext) = explode('.', $name);
	$ext = strtolower($ext) ; 
	if(!in_array($ext,$valid_formats)){
    echo 'The file '.$name.' is not an image' ;
	exit();
	}
 	// Check if image exceeds file size
	if($size > $max_size){
    echo 'The image '.$name.' exceeded the file size limit' ;
	exit();
	}
	
	$name = $txt.'.'.$ext ;  // Join name back with extension to lowercase
	
	/// Unset Variables to free memory
	unset($max_size,$valid_formats,$avatar,$ext,$size,$txt);
	
	/// Upload Images
	$doUpload = Upload_image( $name , $tmp_name , $path) ;
	
	// Unset unused variables to free more memory
	unset($name , $tmp_name);
	
   if($doUpload !== false){  // If image upload was successful
   
   /// first Crop Image
   $crop = new Crop_Image_To_Square;
   $crop->source_image = $path.$doUpload ;
   $crop->save_to_folder = $path;
   /* left, center or right; If none is set, center will be used as default */
   $processCrop = $crop->crop('center');
      
   // Secondly resize image 
   # Create main image
   $compImg = new SimpleImage();
   $compImg->load($path.$doUpload);
   $compImg->resizeToWidth(100);  /// resize
   $compImg->save($path.$doUpload);
   
   #Create thumbnail
   $thumbpath = '../avatars-thumb/' ;
   $compImg->load($path.$doUpload);
   $compImg->resizeToWidth(50);  /// resize
   $compImg->save($thumbpath.$doUpload); 
	
   /// Update image in database
   $uid = $_SESSION['uid'] ;
   
   // Remove old avatar
   $q = mysql_query("SELECT avatar FROM std_users WHERE uid = '$uid'");
   $r = mysql_fetch_array($q);
   if(isset($r['avatar'])){
	$oldAvatar = $r['avatar'] ; 
   if(file_exists($path.$oldAvatar)){
   @unlink($path.$oldAvatar) ;  /// remove old main image
   }  
   if(file_exists($thumbpath.$oldAvatar)){
   @unlink($thumbpath.$oldAvatar) ;  /// remove old thumbnail
   }  
   }
   
   // Unset unused variables to free memory
   unset($path,$oldAvatar,$thumbpath);
   
   mysql_query("UPDATE std_users SET avatar = '$doUpload' WHERE uid = '$uid'");
   
   echo "<img src='avatars-main/$doUpload' />";
   
   // Unset unused variables
   unset($doUpload);
   
   }
   else{
   echo 'Error while uploading' ;
   exit();
   }
?>