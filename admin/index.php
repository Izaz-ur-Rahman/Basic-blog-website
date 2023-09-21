<?php  include "header.php"; 
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_SESSION['user_data'])) {
     $userID = $_SESSION['user_data']['0'];
}
// ---------------------Pagination---------------------------------------
if (!isset($_GET['page'])) {
    $page = 1; 
   }
   else
   {
       $page = $_GET['page'];
   }
   $postsPerPage = 3;   
   $offset = ($page - 1) * $postsPerPage;   // this is the formula for offset.
   //--------------------------------------------
?>

<!-- Begin Page Content -->
<div class="container-fluid" id="adminpage">
    <!-- Page Heading -->
    <h5 class="mb-2 text-gray-800">Blog Posts</h5>
    <!-- DataTales Example -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between">
            <div>
                <a href="add_blog.php">
                    <h6 class="font-weight-bold text-primary mt-2">Add New</h6>
                </a>
            </div>
            <div>
                <form class="navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-white border-0 small" placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button"> <i class="fa fa-search "></i> </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody class="data11">
                        <?php
                      $sql = "SELECT * FROM blog LEFT JOIN categories ON blog.category = categories.cat_id 
                      LEFT JOIN user ON blog.author_id = user.user_id WHERE user_id = '$userID' ORDER BY blog.publish_date DESC LIMIT $offset ,$postsPerPage";
                      $query = mysqli_query($config,$sql);
                      $rows = mysqli_num_rows($query);
                      if ($rows) {
                        while($result = mysqli_fetch_assoc($query))
                        {
                            ?>

                        <tr>
                            <td><?= ++$offset ?></td>
                            <td><?=  $result['blog_title'] ?></td>
                            <td><?=  $result['cat_name'] ?></td>
                            <td><?=  $result['username'] ?></td>
                            <td>
                                <?=  date("d-M-Y",strtotime($result['publish_date'])) // we use a strtotime() funtion just only for to display published Date in appropriate form
                            
                            ?></td>
                            <td><a href="edit_blog.php?id=<?= $result['blog_id'] ?>"
                                    class="btn btn-sm btn-success">Edit</a></td>
                            <td>
                                <form class="mt-2" method="POST"
                                    onsubmit="return confirm('Are you sure want to delete?')">
                                    <input type="hidden" name="id" value="<?= $result['blog_id'] ?>">
                                    <input type="hidden" name="image" value="<?= $result['blog_image'] ?>">
                                    <input type="submit" name="deletePost" value="Delete" class="btn btn-sm btn-danger">
                                </form>
                            </td>
                        </tr>
                        <?php
                        }
                      }
                      else
                      {

                        ?>
                        <tr>
                            <td colspan=" 7">No Record Found</td>
                        </tr>
                        <?php
                      }


?>
                    </tbody>
                </table>
                <!-- pagination began -->

                <?php
$pagination = "SELECT * FROM `blog` where author_id = '$userID'";
$run_q = mysqli_query($config, $pagination);
$total_post_count = mysqli_num_rows($run_q); // Get the total count of posts

$pages = ceil($total_post_count / $postsPerPage); // Calculate the number of pages

if ($total_post_count > $postsPerPage) { // Condition for displaying pagination
?>

                <ul class="pagination pt-2 pb-5">
                    <?php for ($i = 1; $i <= $pages; $i++) { ?>
                    <li class="page-item <?= ($i == $page) ? "active" : ""; ?>">
                        <a href="index.php?page=<?= $i ?>" class="page-link">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php } // for loop close bracket ?>
                </ul>
                <?php } // if statement close bracket ?>

                <!-- --------------------------------------------- -->

            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>

<?php  include "footer.php";
if (isset($_POST['deletePost'])) {
    $id = $_POST['id'];
    $image = "upload/".$_POST['image'];
    $delete = "DELETE FROM blog WHERE blog_id = '$id'";
    $run = mysqli_query($config,$delete);
    if ($run) {
        unlink($image);   // this function is used for to delete the images from database as well as from their specific folder that's fine .
        $msg = ['Blog Post Successfully deleted💪💪💪','alert-success'];  // just simple array mai nai used ki.
            $_SESSION['msg'] = $msg;
            header("location: index.php");
    }
    else
    {
        $msg = ['Failed😭😭,Please try again','alert-danger'];  // just simple array mai nai used ki.
            $_SESSION['msg'] = $msg;
            header("location: index.php");
    }
}

?>