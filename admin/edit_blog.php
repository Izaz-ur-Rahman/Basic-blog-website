<?php include"header.php";
// GET BLOG ID
$blogID = $_GET['id'];
if (empty($blogID)) {
    header("location: index.php");
}
if (isset($_SESSION['user_date'])) {
   $author_id = $_SESSION['user_data']['0'];  // login page mai 0 index py user_id save hai es wja sy mai nai [0]    used ki that's simple
}
// fetch categories from database   
$sql = "SELECT * FROM categories";
$query = mysqli_query($config,$sql);

$sql2 = "SELECT * FROM blog LEFT JOIN categories ON blog.category = categories.cat_id 
LEFT JOIN user ON blog.author_id = user.user_id WHERE blog_id = '$blogID'";      // 3 tables sy data fetch kar raha hy lwft join ki madat sy
$query2 = mysqli_query($config,$sql2);
$result = mysqli_fetch_assoc($query2);
?>

<div class="container">
    <h5 class="mb-2 text-gray-800">BLOGS</h5>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary mt-2">Edit Blog/Article</h6>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class=" mb-3">
                        <input type="text" name="blog_title" placeholder="Title" class="form-control" Required
                            value="<?= $result['blog_title'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Body/Description
                        </label>
                        <textarea required name="blog_body" class="form-control" id="blog" rows="2">
                            <?= $result['blog_body'] ?>
                            </textarea>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="blog_image" class="form-control">
                        <img src="upload/<?= $result['blog_image'] ?>" width=100px>
                    </div>
                    <div class="mb-3">
                        <select name="category" class="form-control" required>

                            <?php  // simply ternary conditional operator are used bs that's set
                                while ($cats = mysqli_fetch_assoc($query)) { ?>
                            <option value="<?= $cats['cat_id'] ?>"
                                <?=($result['category'] == $cats['cat_id'])?"selected" : '';?>> <?= $cats['cat_name']?>
                            </option>
                            <?php } ?>

                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="edit_blog" value="Update" class="btn btn-primary">
                        <a href="index.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php";

if (isset($_POST['edit_blog'])) {
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
    if(!empty($filename))
    {
       if(in_array($image_ext,$allow_type))
    { 
        $unlink = "upload/".$result['blog_image']; // agr user chahta hai kai image bhe update hujai tu old wala image ku upload folder sy khtm karny kailia hum ny pihly $unlink variable mai store ki and then delete ki through unlink() function ki madat sy

        unlink($unlink);
        if($size <= 2000000)     // its check the size of the image if it is <= to 2 MB or not
        {
              move_uploaded_file($tmp_name,$destination);
              $sql3 = "UPDATE blog SET blog_title = '$title',blog_body = '$body', blog_image = '$filename', category = '$category',author_id = '$author_id' WHERE blog_id = '$blogID'";
              $query3 = mysqli_query($config,$sql3);
              if($query3)
              {
                $msg = ['Post Updated successfully','alert-success'];
                $_SESSION['msg'] = $msg;
                header("location: index.php");
              }
              else
              {
                $msg = ['Failed! Please try again','alert-danger'];
                $_SESSION['msg'] = $msg;
                header("location: index.php");
              }
        }
        else
        {
           
            $msg = ['Image size should not greater than 2 MB','alert-danger'];
            $_SESSION['msg'] = $msg;
            header("location: index.php");
        }
    }
    else
    {
        
        $msg = ['File type is not allowed  (only jpg, png or jpeg ) extension are allowed','alert-danger'];
        $_SESSION['msg'] = $msg;
        header("location: index.php");
    }
    }
    else
    {
       $sql3 = "UPDATE blog SET blog_title = '$title',blog_body = '$body', category = '$category',author_id = '$author_id' WHERE blog_id = '$blogID'";
              $query3 = mysqli_query($config,$sql3);
              if($query3)
              {
                $msg = ['Post Updated successfully','alert-success'];
                $_SESSION['msg'] = $msg;
                header("location: index.php");
              }
              else
              {
                $msg = ['Failed! Please try again','alert-danger'];
                $_SESSION['msg'] = $msg;
                header("location: index.php");
              }
    }
}





?>