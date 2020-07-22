<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

//we are going to use session variables so we need to enable sessions
session_start();

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

function handleRest(){
    echo "yeet";
}
function handleOrder(){
    $timeNow = time();
    $objectTime = new DateTime();
    $interval = new DateInterval('PT120M');
    $deliveryTime = $objectTime->add($interval);
    echo "Your order is complete!<br>";
    echo "you ordered with email= {$_SESSION['email']}<br>";
    echo "The delivery will be going to {$_SESSION['street']} {$_SESSION['streetnumber']} in {$_SESSION['city']}<br>";
    echo "Expected delivery time: " . $deliveryTime->format("H:i");
    session_destroy();
    die();
}

//your products with their price.
$food = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

$drinks = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];
if(isset($_SESSION['food']) && !isset($_GET['food'])){
    $_GET['food'] = $_SESSION['food'];
}
if(!isset($_GET['food']) || $_GET['food'] === '1'){
    $products = $food;
    $_SESSION['food'] = $_GET['food'];
} else {
    $products = $drinks;
    $_SESSION['food'] = $_GET['food'];
}

$orders = $_POST['products'];
print_r($orders);
foreach ($orders as $order){
    echo $order . "<br>";
}

$totalValue = 0;

$_SESSION['ordered'] = false;
/*echo whatIsHappening();*/

if($_SESSION['isStarted'] && $_SESSION['ordered']){
    echo "Delivery Time: ";
}

function validateForm(){
    $email = $_POST['email'];
    $street = $_POST['street'];
    $streetNumber = $_POST['streetnumber'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in a valid email.</div>";
    } else{
        $_SESSION['email'] = trim($email);
    }
    if(empty($street)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in your street.</div>";
    } else {
        $_SESSION['street'] = trim($street);
    }
    if(empty($streetNumber) || !is_numeric($streetNumber)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in a valid street number.</div>";
    } else {
        $_SESSION['streetnumber'] = trim($streetNumber);
    }
    if(empty($city)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in the city.</div>";
    } else {
        $_SESSION['city'] = trim($city);
    }
    if(empty($zipcode) || !is_numeric($zipcode)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in a valid zipcode.</div>";
    } else {
        $_SESSION['zipcode'] = trim($zipcode);
    }
    if(empty($email) || !filter_var($email, FILTER_SANITIZE_EMAIL) || empty($street) ||
        empty($streetNumber) || !is_numeric($streetNumber) || empty($city) || empty($zipcode) || !is_numeric($zipcode)){
        handleRest();
    } else {
    handleOrder();
}}
if($_SESSION['isStarted']){
    validateForm();
} else {
    $_SESSION['isStarted'] = true;
}
require 'form-view.php';