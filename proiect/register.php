<?php 

session_start();
include('server/connection.php');

if(isset($_SESSION['logged_in'])){//if user is already registered
    header('location: account.php');
    exit;
}


if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // pw dont match
    if($password !== $confirmPassword){
        header('location: register.php?error=Passwords dont match');
    }else if(strlen($password) < 6){//pw too short
        header('location: register.php?error=Password must be at least 6 characters');
    }else{
        // email exists
        $stmt1 = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_email=?");
        $stmt1->bind_param('s',$email);
        $stmt1->execute();
        $stmt1->bind_result($num_rows);
        $stmt1->store_result();
        $stmt1->fetch();

        //if there is a user already registere 
        if($num_rows != 0){
            header('location: register.php?error=Email already exists');
        }else{
            // create user
            $stmt = $conn->prepare("INSERT INTO users (user_name,user_email,user_password)
                            VALUES (?,?,?)");
            $stmt->bind_param('sss',$name,$email,md5($password));
            
            if($stmt->execute()){
                $user_id = $stmt->insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $name;
                $_SESSION['logged_in'] = true;
                header('location: account.php?register_success=You registered successfully');
            }else{//account not created
                header('location: register.php?error=Could not create account');
            }
        } 
    }
}

?>

<?php include('layouts/header.php'); ?>

    <!--Register-->
    <section class="my-5 py5">
        <div class="container text-center mt-3 pt-5">
            <br><br>
            <h2 class="form-weight-bold">Register</h2>
        </div>
        <div class="mx-auto container">
            <form id="register-form" method="POST" action="register.php">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Your name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="register-email" name="email" placeholder="Your email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="register-password" name="password" placeholder="Your password" required>
                </div>
                <div class="form-group">
                    <label>Confirm password</label>
                    <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm your password" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" id="register-btn" name="register"  value="Register">
                </div>
                <div class="form-group">
                    <a id="login-url" href="login.php" class="btn">Already have an account? Log in</a>
                </div>
            </form>
        </div>
    </section>

<?php include('layouts/footer.php'); ?>