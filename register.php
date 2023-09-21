<?php include "header.php";
// if ($admin != 1) {
//     header("location:index.php");
// } 
if (isset($_POST['register_btn'])) {
   $username = mysqli_real_escape_string($config,$_POST['username']);
   $email = mysqli_real_escape_string($config,$_POST['email']);
   $pass = mysqli_real_escape_string($config,sha1($_POST['password']));
   $c_pass = mysqli_real_escape_string($config,sha1($_POST['c_pass']));
   if (strlen($username) < 4 || strlen($username) > 100) {
    $error =  "Username must be between 4 and 100 characters";
   }
   elseif(strlen($pass) < 4)
   {
    $error = "Password must be 4 characters long";
   }
   elseif ($pass != $c_pass) {
       $error = "Password does not matched";
   }
   else 
   {
     $sql = "SELECT * FROM user WHERE email = '$email'";
     $query = mysqli_query($config,$sql);
     $rows = mysqli_num_rows($query);
     if ($rows >= 1) {
        $error = "Email already Exist";
     }
     else
     {
       // echo "User added successfully";
       $sql2 = "INSERT INTO user (username,email,password,role) value ('$username','$email','$pass','$role')";
       $query2 = mysqli_query($config,$sql2);
       if($query2)
       {

        $msg = ['User Successfully inserted💪💪💪','alert-success'];  // just simple array mai nai used ki.
        $_SESSION['msg'] = $msg;
        header("location: index.php");

       }
       else
       {
        $error = "Failed, Please try again";
       }
     }
   }
}

?>
<link rel="stylesheet" href="style2.css">
<div class="container">
    <div class="row">
        <div class="col-md-5 m-auto bg-info p-4">
            <?php
                if(!empty($error))
                {
                    echo "<p class = 'bg-danger text-white p-2'>" . $error. "</p>";
                }

            ?>
            <form action="" method="POST">
                <p class="text-center">Registeration</p>
                <div class="mb-3">
                    <input type="text" name="username" placeholder="Username" class="form-control" required
                        value="<?= (!empty($error))?$username: '';?>">
                </div>
                <div class="mb-3">
                    <input type="email" name="email" placeholder="Email" class="form-control" required
                        value="<?= (!empty($error))?$email: '';?>">
                </div>
                <div class="mb-3">
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="c_pass" placeholder="Confirm Password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <input type="submit" name="register_btn" class="btn btn-primary" value="submit">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"?>