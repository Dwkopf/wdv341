<!DOCTYPE html>
<html>
  <head>
    <style>
      background-color:yellow;
      font-size:1.4em;


    </style>
    

  </head>
<body>
<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// echo "<script>alert('file was uploaded')</script>";


// Check if image file is a actual image or fake image

if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $message= "File is an image - " . $check["mime"] . ".";
    //echo $message;
    //echo "<script>alert('$message')</script>";
    $uploadOk = 1;
  } else {
    $message= "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  $message .= "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  $message  .="Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  $message .="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $message .="Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $message .="The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
  } else {
    $message .= "Sorry, there was an error uploading your file.";
  }
}
echo"<script>alert('$message')</script>";
echo"<script>window.location.replace('fireStore.html#pictures')</script>";
?>
</body>
</html>