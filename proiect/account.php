<?php 

session_start();
include('server/connection.php');

if(!isset($_SESSION['logged_in'])){
    header('location: login.php');
    exit;
}

if(isset($_GET['logout'])){
    if(isset($_SESSION['logged_in'])){
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        header('location: login.php');
        exit;
    }
}

if(isset($_POST['change_password'])){
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    if($password !== $confirmPassword){// pw dont match
        header('location: account.php?error=Passwords dont match');
    }else if(strlen($password) < 6){//pw too short
        header('location: account.php?error=Password must be at least 6 characters');
    }else{// no errors
        $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
        $stmt->bind_param('ss',md5($password),$user_email);
        if($stmt->execute()){
            header('location: account.php?message=Password updated successfully');
        }else{
            header('location: account.php?error=Could not update password');
        }
    }

}

// get orders
if(isset($_SESSION['logged_in'])){
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=?"); 
    $stmt->bind_param('i',$user_id);
    $stmt->execute();
    $orders = $stmt->get_result();
}

?>

<?php include('layouts/header.php'); ?>

    <!--Account-->
    <section class="my-2 py-5">
    <div class="container">
        <div class="row" justify-content-center>
            <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
                <br><br>
                <p class="text-center" style="color:green"><?php if(isset($_GET['register_success'])){echo $_GET['register_success']; } ?></p>
                <p class="text-center" style="color:green"><?php if(isset($_GET['login_success'])){echo $_GET['login_success']; } ?></p>
                <h3 class="font-weight-bold">Account info</h3>
                <hr class="mx-auto">
                <div class="account-info">
                    <p>Name: <span><?php if(isset($_SESSION['user_name'])){ echo $_SESSION['user_name']; } ?></span></p>
                    <p>Email: <span><?php if(isset($_SESSION['user_email'])){ echo $_SESSION['user_email']; } ?></span></p>
                    <p><a href="#orders" id="orders-btn">Your orders</a></p>
                    <p><a href="account.php?logout=1" id="logout-btn">Log out</a></p>
                </div>
            </div>
            <div class="mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
                <form id="account-form" method="POST" action="account.php">
                    <h3>Change password</h3>
                    <hr class="mx-auto">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="account-password" name="password" placeholder="New password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm password</label>
                        <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Confirm new password" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Change password" name="change_password" class="btn" id="change-pass-btn">
                    </div>
                    <p class="text-center" style="color:red"><?php if(isset($_GET['error'])){echo $_GET['error']; } ?></p>
                    <p class="text-center" style="color:green"><?php if(isset($_GET['message'])){echo $_GET['message']; } ?></p>
                </form>
            </div>
        </div>
    </div>
    </section>


    <!--Orders-->
    <section id="orders" class="orders container">
        <div class="container">
            <br><br><br>
            <h2 class="font-weight-bolde text-center">Your orders</h2>
            <hr class="mx-auto">
        </div>
        <table class="mt-5 pt-5">
            <tr>
                <th>Order id</th>
                <th>Cost</th>
                <th>Status</th>
                <th>Date</th>
                <th>Order detail</th>
            </tr>
            <?php while($row = $orders->fetch_assoc()){ ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <!--<img src="assets/imgs/clothes1.jpg">-->
                            <div>
                                <p class="mt-3"><?php echo $row['order_id']; ?></p>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <span><?php echo $row['order_cost'];?></span>
                    </td>
                    <td>
                        <span><?php echo $row['order_status'];?></span>
                    </td>
                    <td>
                        <span><?php echo $row['order_date'];?></span>
                    </td>
                    <td>
                        <form method="POST" action="order_details.php">
                            <input type="hidden" value="<?php echo $row['order_status']; ?>" name="order_status"/>
                            <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id"/>
                            <input class="btn order-details-btn" name="order_details_btn" type="submit" value="Details"/>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>

<?php include('layouts/footer.php'); ?>