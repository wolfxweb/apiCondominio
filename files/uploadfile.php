<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
if(isset($_FILES['file'])){
      $errors= array();
	  $file_dir="upload/";
      $file_name = $_FILES['file']['name'];
      $file_size =$_FILES['file']['size'];
      $file_tmp =$_FILES['file']['tmp_name'];
      $file_type=$_FILES['file']['type'];
	  $tmp_ext = explode('.', $file_name);
      $file_ext=strtolower(end($tmp_ext));
      
      $extensions= array("pdf","txt","docx");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 5097152){
         $errors[]='File size must be excately 5 MB';
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

