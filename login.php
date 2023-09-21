<?php
ob_start(); // Start output buffering

include "config.php";
include "header.php";
session_start();

if(isset($_SESSION['user_data'])) {
    header("location: http://localhost/blog/admin/index.php");
    exit(); // Make sure to exit after sending the header
}  
?>

<link rel="stylesheet" href="style2.css">

<div class="container">
    <div class="row">
        <div class="col-xl-5 col-md-4  bg-info" id="lgn">
            <form action="" method="POST">
                <p>Blog! Login your account. </p>
                <div class="mb-3">
                    <input type="email" name="email" placeholder="Email" class="form-control" Required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" placeholder="password" class="form-control" Required>
                </div>
                <div class="mb-3">
                    <input type="submit" name="login_btn" class="btn btn-primary" value="Login">
                </div>


                <?php
                if (isset($_SESSION['error'])) {
                    $error = $_SESSION['error'];
                    echo "<p class='bg-danger p-2 text-white'>".$error."</p>";
                    unset($_SESSION['error']);
                } 
                ?>
            </form>
            <div class="mb-3 ">
                <a href="register.php"><input type="submit" name="register_btn" class="btn btn-primary" value="register">
                </a>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($config, $_POST['email']);
    $pass = mysqli_real_escape_string($config, sha1($_POST['password']));
    $sql = "SELECT * FROM `user` WHERE email = '{$email}' AND password = '{$pass}'";
    $query = mysqli_query($config, $sql);
    $data = mysqli_num_rows($query);
    if ($data) {
        $result = mysqli_fetch_assoc($query);
        $user_data = array($result['user_id'], $result['username'], $result['role']);
        $_SESSION['user_data'] = $user_data;
        header("location: admin/index.php");
        exit(); // Make sure to exit after sending the header
    } else {
        $_SESSION['error'] = "Invalid email/Password";
        header("location: login.php");
        exit(); // Make sure to exit after sending the header
    }
}

ob_end_flush(); // Send buffered output to the browser
?>