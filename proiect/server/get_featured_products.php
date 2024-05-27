<?php 

include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products WHERE product_name='Shoes' OR product_name='Hat' OR product_image='hoodie2.jpg' OR product_image='jeans2.jpg' LIMIT 4"); 
$stmt->execute();
$featured_products = $stmt->get_result();

?>