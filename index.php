<?php
declare(strict_types=1);

ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);


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
function calcPrice($food, $drinks){
    return "10$ testing";
}
function handleOrder($food, $drinks){
    $objectTime = new DateTime();
    if(isset($_POST['express_delivery'])){
        $timeToAdd = "PT45M";
    } else {
        $timeToAdd = "PT120M";
    }
    $interval = new DateInterval($timeToAdd);
    $deliveryTime = $objectTime->add($interval);
    $price = calcPrice($food, $drinks);
    echo "Your order is complete!<br>";
    echo "you ordered with email= {$_SESSION['email']}<br>";
    echo "You have to pay $price <br>";
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

if(!isset($_SESSION['orders'])){
    $_SESSION['orders'] = [];
}
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

function validateForm($food, $drinks){
    $email = $_POST['email'];
    $street = $_POST['street'];
    $streetNumber = $_POST['streetnumber'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    $orders = $_POST['products'];
    var_dump($orders);
    foreach ($orders as $order){
        foreach (array_merge($food, $drinks) as $item){
            if($order === $item['name'] && !in_array($order, $_SESSION, true)){
                array_push($_SESSION['orders'], $order);
            }
        }
    }
    var_dump($_SESSION['orders']);
    if(empty($email) || !filter_var(trim($email), FILTER_VALIDATE_EMAIL)){
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
    if(!empty($_SESSION['email']) && filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL) && !empty($_SESSION['street']) &&
        !empty($_SESSION['streetnumber']) && is_numeric($_SESSION['streetnumber']) && !empty($_SESSION['city']) && !empty($_SESSION['zipcode']) &&
        is_numeric($_SESSION['zipcode'])) {
        handleOrder($food, $drinks);
    } else {
        echo "<div class=\"alert alert-warning\" role=\"alert\"> Nothing ordered yet...</div>";
    }}
if($_SESSION['isStarted']){
    validateForm($food, $drinks);
} else {
    $_SESSION['isStarted'] = true;
}
require 'form-view.php';