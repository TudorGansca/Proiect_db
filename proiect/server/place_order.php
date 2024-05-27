<?php

session_start();
include('connection.php');

if(!isset($_SESSION['logged_in'])){
    header('location: ../checkout.php?message=Please log in/register to place an order');
    exit;
}else{
    if(isset($_POST['place_order'])){

     // get user info and store in db
     $name = $_POST['name'];
     $email = $_POST['email'];
     $phone = $_POST['phone'];
     $city = $_POST['city'];
     $address = $_POST['address'];
     $order_cost = $_SESSION['total'];
     $order_status = "on hold";
     $user_id = $_SESSION['user_id'];  
     $order_date = date('Y-m-d H:i:s');
 
     $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date)
                     VALUES (?, ?, ?, ?, ?, ?, ?)");
 
     $stmt->bind_param('dsissss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);
     $stmt_status = $stmt->execute();
     if(!$stmt_status){
        header('location: index.php');
        exit;
     }
     $order_id = $stmt->insert_id;

    // get products from cart(session) and store in order_items

    foreach($_SESSION['cart'] as $key => $value){

        $product = $_SESSION['cart'][$key];
        $product_id = $product['product_id'];
        $product_name = $product['product_name'];
        $product_image = $product['product_image'];
        $product_size = $product['product_size'];
        $product_price = $product['product_price'];

        $stmt1 = $conn->prepare("INSERT INTO order_items (order_id,product_id,product_name,product_image,user_id,order_date)
                        VALUES (?,?,?,?,?,?)");
        $stmt1->bind_param('iissis', $order_id, $product_id, $product_name, $product_image, $user_id, $order_date);
        $stmt1->execute();
    }

    // empty cart --> delay until payment is done
    // unset($_SESSION['cart']);


    // inform user order status
    header('location: ../payment.php?oder_status=order placed successfully');

    }
}



?>