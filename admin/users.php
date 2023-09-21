<?php  include "header.php"; 
if ($admin != 1) {   // agr co-admin hu tu ye page directly access nahi karskty....

    header("location:index.php");
}
// pagination

if (!isset($_GET['page'])) {
    $page = 1; 
   }
   else
   {
       $page = $_GET['page'];
   }
   $limit = 2;   
   $offset = ($page - 1) * $limit;   // this is the formula for offset.
   //--------------------------------------------
   

$sql = "SELECT * FROM user limit $offset,$limit";
$query = mysqli_query($config,$sql);
$rows = mysqli_num_rows($query);





?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h5 class="mb-2 text-gray-800">Users</h5>
    <!-- DataTales Example -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between">
            <div>
                <a href="add_user.php">
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
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    if ($rows) {
                        while($result = mysqli_fetch_assoc($query))
                        {
                            ?>

                        <tr>
                            <td><?= ++$offset ?></td>
                            <td><?=  $result['username'] ?></td>
                            <td><?=  $result['email'] ?></td>
                            <td><?php
                            $role = $result['role'] ;
                            if ($role == 1) {
                                echo "Admin";
                            }
                            else
                            {
                                echo "Co-Admin";
                            } ?>

                            </td>

                            <!-- <td><a href="edit_blog.php?id=<?= $result['blog_id'] ?>"
                                    class="btn btn-sm btn-success">Edit</a></td> -->
                            <td>
                                <form class="mt-2 mb-1" method="POST"
                                    onsubmit="return confirm('Are you sure want to delete?')">
                                    <input type="hidden" name="userid" value="<?= $result['user_id'] ?>">
                                    <input type="submit" name="deleteUser" value="Delete" class="btn btn-sm btn-danger">
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
            $pagination = "SELECT * FROM user";
            $run_q = mysqli_query($config,$pagination);
            $total_users = mysqli_num_rows($run_q);
            $pages =ceil( $total_users/$limit);   // this function is used for ager points value aagia tu wu consider karayga aik value greater means that i-e 3.66 -> convert into 4.
            
            if ($total_users > $limit) {   // this is condition for agr number of user zyada hu tu pagination aana chahia wrna nahi chahia 
        
           
        ?>
                <ul class="pagination pt-2 pb-5">
                    <?php for ($i=1; $i <= $pages; $i++) { ?>
                    <li class="page-item <?=($i == $page)? $active= "active" : "";?>">
                        <a href="users.php?page=<?=$i ?>" class="page-link">
                            <?= $i ?>
                        </a>

                    </li>

                    <?php } // for loop close bracket?>
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
if (isset($_POST['deleteUser'])) {
    $id = $_POST['userid'];
    $delete = "DELETE FROM user WHERE user_id = '$id'";
    $run = mysqli_query($config,$delete);
    if ($run) {
        $msg = ['User Successfully deleted💪💪💪','alert-success'];  // just simple array mai nai used ki.
            $_SESSION['msg'] = $msg;
            header("location: users.php");
    }
    else
    {
        $msg = ['Failed😭😭,Please try again','alert-danger'];  // just simple array mai nai used ki.
            $_SESSION['msg'] = $msg;
            header("location: users.php");
    }
}
?>