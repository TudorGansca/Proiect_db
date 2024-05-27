<?php 

session_start();

?>

<?php include('layouts/header.php'); ?>

    <!--Payment-->
    <section class="my-5 py5">
        <div class="container text-center mt-3 pt-5">
            <br><br>
            <h2 class="form-weight-bold">Payment</h2>
        </div>
        <div class="mx-auto container text-center">
            <p>Order places successfully</p>
            <p>Total payment: <?php if(isset($_GET['total']) && $_SESSION['total']){echo $_SESSION['total'];} ?> RON</p>
            <?php if(isset($_SESSION['total']) && $_SESSION['total']!=0) { ?>
            <input class="btn btn-primary" type="submit" value="Pay now"/>
            <?php }else{ ?>
                <p>Your cart is empty</p>
            <?php } ?>
        </div>
    </section>

<?php include('layouts/footer.php'); ?>