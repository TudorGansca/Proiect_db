<?php 
session_start();

if (isset($_POST['add_to_cart'])) {
    // Adding items to the cart
    if (isset($_SESSION['cart'])) {
        $products_array_ids = array_column($_SESSION['cart'], 'product_id');

        if (!in_array($_POST['product_id'], $products_array_ids)) {
            $product_id = $_POST['product_id'];
            $product_array = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_image' => $_POST['product_image'],
                'product_size' => $_POST['product_size'],
                'product_price' => $_POST['product_price']
            );
            $_SESSION['cart'][$product_id] = $product_array;
        } else {
            echo '<script>alert("Product already in the cart!");</script>';
        }
    } else {
        $product_id = $_POST['product_id'];
        $product_array = array(
            'product_id' => $_POST['product_id'],
            'product_name' => $_POST['product_name'],
            'product_image' => $_POST['product_image'],
            'product_size' => $_POST['product_size'],
            'product_price' => $_POST['product_price']
        );
        $_SESSION['cart'][$product_id] = $product_array;
    }
    // calculate total
    calculateTotalCart();
} elseif (isset($_POST['remove_product'])) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $product_id = $_POST['product_id'];
        if (count($_SESSION['cart']) == 1) {
            unset($_SESSION['cart']);
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    calculateTotalCart();
} else {
    // Optional: Redirect to index.php or handle as needed
    // header('location: index.php');
}

function calculateTotalCart(){

    $total = 0;
    
    foreach($_SESSION['cart'] as $key => $value){
            $product = $_SESSION['cart'][$key];
            $price = $product['product_price'];
            $total += $price;
    }
    $_SESSION['total'] = $total;
}

?>

<?php include('layouts/header.php'); ?>

    <!--Cart-->
    <section class="cart container my-5 py-5">
        <div class="container" mt-5>
            <br><br><br>
            <h2 class="font-weight-bolde">Your cart</h2>
        </div>
        <table class="mt-5 pt-5">
            <tr>
                <th>Products</th>
                <th>Subtotal</th>
            </tr>
            <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) { ?>
                <?php foreach($_SESSION['cart'] as $key => $value){ ?>

                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="assets/imgs/<?php echo $value['product_image']; ?>"/>
                                <div>
                                    <p><?php echo $value['product_name']; ?></p>
                                    <small><span>Size <?php echo $value['product_size']; ?></span></small>
                                    <br>
                                    <form method="POST" action="cart.php">
                                        <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                                        <input type="submit" name="remove_product" class="remove-btn" value="Remove"/>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="product-price"><?php echo $value['product_price']; ?> RON</span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="2">Your cart is empty.</td>
                </tr>
            <?php } ?>
        </table>

        <div class="cart-total">
            <table>
                <tr>
                    <td>Total</td>
                    <td><?php if(isset($_SESSION['total'])){echo $_SESSION['total'];}else{echo '0';} ?>RON</td>
                </tr>
            </table>
        </div>
        <div class="checkout-container">
            <form method="POST" action="checkout.php">
                <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout"/>
            </form>
        </div>
    </section>



<?php include('layouts/footer.php'); ?>