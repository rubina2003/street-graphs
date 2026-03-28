<?php
session_start();
// Store billing info into session
$_SESSION['billing_name'] = $_POST['inputName'] ?? 'N/A';
$_SESSION['billing_email'] = $_POST['inputEmail'] ?? 'N/A';
$_SESSION['billing_address'] = $_POST['inputAddress'] ?? 'N/A';
$_SESSION['billing_phone'] = $_POST['inputPhone'] ?? 'N/A';

if (isset($_POST['submit'])) {
    $amount = $_POST['grand_total']*100; // convert the amount to paisa as the amount should be in paisa for khalti
    $name = $_POST['inputName'];
    $email = $_POST['inputEmail'];
    $phone = $_POST['inputPhone'];
    $address = $_POST['inputAddress'];


    //here validate the data
    if(empty($amount) || empty($address) || empty($name) || empty($email) || empty($phone)){
        $_SESSION["validate_msg"] = '<script>
        Swal.fire({
            icon: "error",
            title: "All fields are required",
            showConfirmButton: false,
            timer: 1500
        });
    </script>';
        header("Location: checkout.php");
        exit();
    }
    //check if the amount is a number
    if(!is_numeric($amount)){
        $_SESSION["validate_msg"] = '<script>
        Swal.fire({
            icon: "error",
            title: "Amount must be a number",
            showConfirmButton: false,
            timer: 1500
        });
    </script>';
        header("Location: checkout.php");
        exit();
    }

    //check if the phone number is a number
    if(!is_numeric($phone)){
        $_SESSION["validate_msg"] = '<script>
        Swal.fire({
            icon: "error",
            title: "Phone must be a number",
            showConfirmButton: false,
            timer: 1500
        });
    </script>';
        header("Location: checkout.php");
        exit();
    }

    //check if the email is valid
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION["validate_msg"] = '<script>
        Swal.fire({
            icon: "error",
            title: "Email is not valid",
            showConfirmButton: false,
            timer: 1500
        });
    </script>';
        header("Location: checkout.php");
        exit();
    }

 $purchase_order_id = "SG-" . uniqid();
    $purchase_order_name = "StreetGraphs Order";

    $postFields = array(
        "return_url" => "http://localhost/StreetGraphs/payment-response.php",
        "website_url" => "http://localhost/StreetGraphs/",
        "amount" => $amount,
        "purchase_order_id" => $purchase_order_id,
        "purchase_order_name" => $purchase_order_name,
        "customer_info" => array(
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        )
    );

}
$jsonData = json_encode($postFields);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $jsonData,
    CURLOPT_HTTPHEADER => array(
        'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455',
        'Content-Type: application/json',
    ),
));

$response = curl_exec($curl);


if (curl_errno($curl)) {
    echo 'Error:' . curl_error($curl);
} else {
    $responseArray = json_decode($response, true);

    if (isset($responseArray['error'])) {
        echo 'Error: ' . $responseArray['error'];
    } elseif (isset($responseArray['payment_url'])) {
        // Redirect the user to the payment page
        header('Location: ' . $responseArray['payment_url']);
    } else {
        echo 'Unexpected response: ' . $response;
    }
}

curl_close($curl);

