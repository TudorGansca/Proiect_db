<?php 

session_start();
include('server/connection.php');
if(isset($_SESSION['logged_in'])){
    header('location: account.php');
    exit;
}


if(isset($_POST['login_btn'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT  user_id,user_name,user_email,user_password FROM users WHERE user_email=? AND user_password=? LIMIT 1");

    $stmt->bind_param('ss',$email,$password);
    if($stmt->execute()){
        $stmt->bind_result($user_id,$user_name,$user_email,$user_password);
        $stmt->store_result();
        if($stmt->num_rows() == 1){
            $row = $stmt->fetch();
            $_SESSION['user_id']=$user_id;
            $_SESSION['user_name']=$user_name;
            $_SESSION['user_email']=$user_email;
            $_SESSION['logged_in']=true;
            header('location: account.php?login_success=Logged in succesfully');
        }else{ header('location: login.php?error=Could not verify your account'); }
    }else{
        //error
        header('location: login.php?error=Something went wrong');
    }
}

?>


<?php include('layouts/header.php'); ?>

    <!--Login-->
    <section class="my-5 py5">
        <div class="container text-center mt-3 pt-5">
            <br><br>
            <h2 class="form-weight-bold">Log in</h2>
        </div>
        <div class="mx-auto container">
            <form id="login-form" method="POST" action="login.php">
                <p style="color:red" class="text-center"><?php if(isset($_GET['error'])){ echo $_GET['error'];} ?></p>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="login-email" name="email" placeholder="Your email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="login-password" name="password" placeholder="Your password" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" id="login-btn" name="login_btn" value="Log in">
                </div>
                <div class="form-group">
                    <a id="register-url" href="register.php" class="btn">Don't have an accoount? Register</a>
                </div>
            </form>
        </div>
    </section>

<?php include('layouts/footer.php'); ?>