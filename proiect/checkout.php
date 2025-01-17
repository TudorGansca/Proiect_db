<?php 

session_start();
if(!empty($_SESSION['cart'])){
    // let user in
    

}else{
    // send user to home page
    header('location:index.php');
}

?>

<?php include('layouts/header.php'); ?>

    <!--Checkout-->
    <section class="my-5 py5">
        <div class="container text-center mt-3 pt-5">
            <br><br>
            <h2 class="form-weight-bold">Check out</h2>
        </div>
        <div class="mx-auto container">
            <form id="checkout-form" method="POST" action="server\place_order.php">
                <p class="text-center" style="color: red;"><?php if(isset($_GET['message'])){ echo $_GET['message'];}?>
                    <?php if(isset($_GET['message'])){ ?>
                        <a href="login.php" class="btn btn-primary">Log in</a>
                    <?php } ?>
                </p>

                <div class="form-group checkout-small-element">
                    <label>Name</label>
                    <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Your name" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>Email</label>
                    <input type="text" class="form-control" id="checkout-email" name="email" placeholder="Your email" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>Phone</label>
                    <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Your phone number" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>City</label>
                    <input type="text" class="form-control" id="checkout-city" name="city" placeholder="City" required>
                </div>
                <div class="form-group checkout-large-element">
                    <label>Adress</label>
                    <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Address" required>
                </div>
                <div class="form-group checkout-btn-container">
                    <p>Total amount: <?php echo $_SESSION['total']; ?>RON</p>
                    <input type="submit" class="btn" id="checkout-btn" name="place_order" value="Place order">
                </div>
            </form>
        </div>
    </section>
    
<?php include('layouts/footer.php'); ?>