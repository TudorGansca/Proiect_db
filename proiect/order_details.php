<?php 

// on hold = not paid
// delivered 
// shipped

include('server/connection.php');

if(isset($_POST['order_details_btn']) && isset($_POST['order_id'])){

    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];
    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id=?");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $order_details = $stmt->get_result();

    $products = [];
    $total = 0;

    while ($row = $order_details->fetch_assoc()) {
        $product_id = $row['product_id'];
        $stmt1 = $conn->prepare("SELECT * FROM products WHERE product_id=?");
        $stmt1->bind_param('i', $product_id);
        $stmt1->execute();
        $product_details = $stmt1->get_result()->fetch_assoc();
        $products[] = $product_details;
        $total += $product_details['product_price']; // Only add the product price
    }
    
} else {
    header('location: account.php');
    exit;
}

?>

<?php include('layouts/header.php'); ?>

    <!--Order details-->
    <section id="orders" class="orders container">
        <div class="container">
            <br><br><br>
            <h2 class="font-weight-bold text-center">Order details</h2>
            <hr class="mx-auto">
        </div>
        <table class="mt-5 pt-5">
            <tr>
                <th>Product name</th>
                <th>Size</th>
                <th>Price</th>
                <th>Total</th> <!-- Change header from Quantity to Total -->
            </tr>
            <?php foreach($products as $product) { ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="assets/imgs/<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>">
                            <div>
                                <p class="mt-3"><?php echo $product['product_name']; ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span><?php echo $product['product_size']; ?></span>
                    </td>
                    <td>
                        <span><?php echo $product['product_price']; ?></span>
                    </td>
                    <td>
                        <span><?php echo $product['product_price']; ?></span> <!-- Display price as total -->
                    </td>
                </tr>
            <?php } ?>
        </table>
        <div class="mt-3 pt-3" style="text-align: right;">
            <h3>Total Amount: <?php echo $total; ?></h3>
            <?php if($order_status == "on hold"){ ?>
                <form method="POST" action="payment.php">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                    <input type="submit" value="Pay now" class="btn btn-primary mt-3"/>
                </form>
            <?php } ?>
        </div>
    </section>

<?php include('layouts/footer.php'); ?>
