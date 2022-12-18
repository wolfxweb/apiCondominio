<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
if(isset($_FILES['image'])){
      $errors= array();
	  $file_dir="upload/";
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
	  $tmp_ext = explode('.', $file_name);
      $file_ext=strtolower(end($tmp_ext));
      
      $extensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
		  $new_file_name=uniqid().uniqid().".".$file_ext;
         move_uploaded_file($file_tmp,$file_dir.$new_file_name);
           http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "File upload successful!","document"=> $new_file_name));
      }else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to upload file. Data is incomplete.","document"=> $errors));
      }
   }
?>

