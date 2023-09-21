<?php include"header.php";
if ($admin != 1) {
    header("location:index.php");
} 

$id = $_GET['id'];       // id ku get karny kailia mai nay $_GET() function used ki bs that's simple
if(empty($id))      // es ki mtlb ye hai kai without id directly koi bhe bnda categories edit nahi krskty.
{
    header("location: categories.php");
}
$sql = "SELECT * FROM categories WHERE cat_id = '$id'";
$query = mysqli_query($config,$sql);
$row = mysqli_fetch_assoc($query);


?>

<div class="container">
    <h5 class="mb-2 text-gray-800">Categories</h5>
</div>

<div class="row">
    <div class="col-xl-6 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary mt-2">Edit Category</h6>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <input type="text" name="cat_name" placeholder="Category Name" class="form-control" Required
                            value="<?= $row['cat_name'];?>">
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="update_cat" value="Update" class="btn btn-primary">
                        <a href="categories.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include"footer.php";

if (isset($_POST['update_cat'])) {
    $cat_name = mysqli_real_escape_string($config,$_POST['cat_name']);
  $sql2 = "UPDATE categories SET cat_name = '{$cat_name}' WHERE cat_id = '{$id}'";
  $update = mysqli_query($config,$sql2);
  if ($update)
  {
      $msg = ['Category Successfully updated💪💪💪','alert-success'];  // just simple array mai nai used ki.
      $_SESSION['msg'] = $msg;
      header("location: categories.php");

  }
  else
  {
      $msg = ['Failed😭, Please try again','alert-danger']; // just simple array mai nai used ki.
      $_SESSION['msg'] = $msg;
      header("location: categories.php");
  }
}



?>