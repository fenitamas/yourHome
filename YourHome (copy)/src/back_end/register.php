<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// $servername = "localhost";
// $username = "root";
// $password = "880011mysqlfeni";
// $database = "realstate_website";

include 'db_connection.php';


$first_name = test_input($_POST['first_name']);
$last_name = test_input($_POST['last_name']);
$email = test_input($_POST['email']);
$phone_number = test_input($_POST['phone_number']);
$city = test_input($_POST['city']);
$password = test_input($_POST['password']);

$userPicture = $_FILES['profile_picture']['name'];
print_r($userPicture);
$targetDir = "images/users/";
$targetFile = $targetDir . basename($userPicture);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
$check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
echo($imageFileType);
if ($check !== false) {
    $uploadOk = 1;
} else {
    echo "File is not an image.";
    $uploadOk = 0;
}

if ($_FILES["profile_picture"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
        echo "The file " . htmlspecialchars(basename($userPicture)) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
$errors = array();


if (empty($first_name)) {
    $errors['first_name'] = "First name is required.";
}
if (empty($last_name)) {
    $errors['last_name'] = "Last name is required.";
}
if (empty($email)) {
    $errors['email'] = "Email is required.";
}
if (empty($phone_number)) {
    $errors['phone_number'] = "Phone number is required.";
}
if (empty($city)) {
    $errors['city'] = "City is required.";
}
if (empty($password)) {
    $errors['password'] = "Password is required.";
}


if (!empty($first_name) && !preg_match("/^[a-zA-Z ]*$/", $first_name)) {
    $errors['first_name'] = "First name should only contain letters and white spaces.";
}


if (!empty($last_name) && !preg_match("/^[a-zA-Z ]*$/", $last_name)) {
    $errors['last_name'] = "Last name should only contain letters and white spaces.";
}


if (!empty($city) && !preg_match("/^[a-zA-Z ]*$/", $city)) {
    $errors['city'] = "City should only contain letters and white spaces.";
}


if (!empty($phone_number) && !preg_match("/^\+251[0-9]{9}$/", $phone_number)) {
    $errors['phone_number'] = "Invalid Ethiopian phone number. It should start with +251 and have 12 digits.";
}


if (!empty($password) && strlen($password) < 8) {
    $errors['password'] = "Password must be at least 8 characters long.";
}


if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
    exit(); 
}


$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (first_name, last_name, email, phone_number, city,user_picture, password) 
        VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$city','$targetFile', '$hashed_password')";


if ($conn->query($sql) === TRUE) {
    echo "connected";
    header("Location: ../app/pages/signIn.php");
    exit(); 
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
