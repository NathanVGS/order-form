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

if($_GET['food'] === '0'){
    $products = $drinks;
} else {
    $products = $food;
}
$totalValue = 0;
echo whatIsHappening();

function validateForm(){
    $email = $_POST['email'];
    $street = $_POST['street'];
    $streetNumber = $_POST['streetnumber'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in a valid email.</div>";
    } else{
        $_SESSION['email'] = $email;
    }
    if(empty($street)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in your street.</div>";
    } else {
        $_SESSION['street'] = $street;
    }
    if(empty($streetNumber)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in your street number.</div>";
    } else {
        $_SESSION['streetnumber'] = $streetNumber;
    }
    if(empty($city)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in the city.</div>";
    } else {
        $_SESSION['city'] = $city;
    }
    if(empty($zipcode)){
        echo "<div class=\"alert alert-danger\" role=\"alert\"> Fill in the zipcode.</div>";
    } else {
        $_SESSION['zipcode'] = $zipcode;
    }
    var_dump(filter_var($email, FILTER_VALIDATE_EMAIL));
}
if($_SESSION['isStarted']){
    validateForm();
} else {
    $_SESSION['isStarted'] = true;
}

require 'form-view.php';