<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $public_key = $_POST['public_key'];
    $tx_ref = $_POST['tx_ref'];
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $logo = $_POST['logo'];
    $callback_url = $_POST['callback_url'];
    $return_url = $_POST['return_url'];
    $meta_title = $_POST['meta']['title'];
    $property_data = $_POST['property_data'];

    $_SESSION['property_data'] = $property_data;

    $data = [
        "amount" => $amount,
        "currency" => $currency,
        "email" => $email,
        "first_name" => $first_name,
        "last_name" => $last_name,
        "phone_number" => "0912345678",
        "tx_ref" => $tx_ref,
        "callback_url" => $callback_url,
        "return_url" => $return_url,
        "customization" => [
            "title" => $title,
            "description" => $description
        ]
    ];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.chapa.co/v1/transaction/initialize',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer CHASECK_TEST-U1aKrwXlGX5oY28t31OuSd2pMFes3MY1',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo 'Error:' . curl_error($curl);
    } else {
        $response_data = json_decode($response, true);
        if (isset($response_data['status']) && $response_data['status'] === 'success') {
            
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include 'db_connection.php';

$property_data_json = $_POST['property_data'];
$propertyData = json_decode($property_data_json, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $propertyData['userId'];
            $propertyType = $propertyData['propertyType'];
            $virtualTour = $propertyData['virtualTour'];
            $phoneNumber = $propertyData['phoneNumber'];
            $telegramAccount = $propertyData['telegramAccount'];
            $title = $propertyData['title'];
            $mapAddress = $propertyData['mapAddress'];
            $bedrooms = $propertyData['bedrooms'];
            $bathrooms = $propertyData['bathrooms'];
            $area = $propertyData['area'];
            $details = $propertyData['details'];
            $yearBuilt = $propertyData['yearBuilt'];
            $price = $propertyData['price'];
            $propertyHistory = $propertyData['propertyHistory'];

    $housePicture = $_FILES['house_picture']['name'];
    $targetDir = "images/";
    $targetFile = $targetDir . basename($housePicture);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["house_picture"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        // echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["house_picture"]["size"] > 500000) {
        // echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        // echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["house_picture"]["tmp_name"], $targetFile)) {
            // echo "The file " . htmlspecialchars(basename($housePicture)) . " has been uploaded.";
        } else {
            // echo "Sorry, there was an error uploading your file.";
        }
    }

    $stmt = "INSERT INTO properties (user_id,property_type, house_picture, virtual_tour, phone_number, telegram_account, title, map_address, bedrooms, bathrooms, area, details, year_built, price, property_history) 
                    VALUES ('$userId','$propertyType', '$targetFile', '$virtualTour', '$phoneNumber', '$telegramAccount', '$title', '$mapAddress', '$bedrooms', '$bathrooms', '$area', '$details', '$yearBuilt', '$price', '$propertyHistory')";



    if ($conn->query($stmt) === TRUE) {
        header('Location: ' . $response_data['data']['checkout_url']);

        // echo "New property posted successfully";
    } else {
        // echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();


        } else {
            echo 'Payment initialization failed: ' . $response_data['message'];
        }
    }

    curl_close($curl);
} else {
    echo 'Invalid request method.';
}
?>
