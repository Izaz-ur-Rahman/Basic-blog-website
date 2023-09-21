<?php include"header.php";
if (isset($_SESSION['user_date'])) {
   $author_id = $_SESSION['user_data']['0'];  // login page mai 0 index py user_id save hai es wja sy mai nai [0]    used ki that's simple

}

$sql = "SELECT * FROM categories";
$query = mysqli_query($config,$sql);


?>

<div class="container">
    <h5 class="mb-2 text-gray-800">BLOGS</h5>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary mt-2">Publish Blog/Article</h6>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class=" mb-3">
                        <input type="text" name="blog_title" placeholder="Title" class="form-control" Required>
                    </div>
                    <div class="mb-3">
                        <label>Body/Description
                        </label>
                        <textarea required name="blog_body" class="form-control" id="blog" rows="2">
                            </textarea>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="blog_image" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php
                                while ($cats = mysqli_fetch_assoc($query)) { ?>
                            <option value="<?= $cats['cat_id'] ?>"><?= $cats['cat_name'] ?></option>
                            <?php }

                            ?>

                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="add_blog" value="Add" class="btn btn-primary">
                        <a href="index.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php";

if (isset($_POST['add_blog'])) {
    $title = mysqli_real_escape_string($config,$_POST['blog_title']);
    $body = mysqli_real_escape_string($config,$_POST['blog_body']);
    $filename = $_FILES['blog_image']['name'];
    $tmp_name = $_FILES['blog_image']['tmp_name'];
    $size = $_FILES['blog_image']['size'];
    $image_ext = strtolower(pathinfo($filename,PATHINFO_EXTENSION));   // THIS IS USED FOR EXTENSION SET as well as we used STRTOLOWER() FUNCTION this is used for agr koi bhe extension select kary chahy wu capital mai hu ya small wu khud ba khud lower case mai convert hu jaiga es strtolower function ki madat sy bs that's fine 

    $allow_type = ['jpg','png','jpeg'];
    $destination = "upload/".$filename;
    $author_id = $_SESSION['user_data']['0']; 
    $category = mysqli_real_escape_string($config,$_POST['category']);
    if(in_array($image_ext,$allow_type))
    {
        if($size <= 2000000)     // its check the size of the image if it is <= to 2 MB or not
        {
              move_uploaded_file($tmp_name,$destination);
              $sql2 = "INSERT INTO blog(blog_title,blog_body,blog_image,category,author_id) VALUES('$title','$body',
              '$filename','$category','$author_id')";
              $query2 = mysqli_query($config,$sql2);
              if($query2)
              {
                $msg = ['Post published successfully','alert-success'];
                $_SESSION['msg'] = $msg;
                header("location: add_blog.php");
              }
              else
              {
                $msg = ['Failed! Please try again','alert-danger'];
                $_SESSION['msg'] = $msg;
                header("location: add_blog.php");
              }
        }
        else
        {
           
            $msg = ['Image size should not greater than 2 MB','alert-danger'];
            $_SESSION['msg'] = $msg;
            header("location: add_blog.php");
        }
    }
    else
    {
        
        $msg = ['File type is not allowed  (only jpg, png or jpeg ) extension are allowed','alert-danger'];
        $_SESSION['msg'] = $msg;
        header("location: add_blog.php");
    }
}




?>