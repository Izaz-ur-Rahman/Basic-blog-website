<?php  include "header.php"; 
if ($admin != 1) {
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
   $limit = 5;   
   $offset = ($page - 1) * $limit;   // this is the formula for offset.
   //--------------------------------------------
   

?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h5 class="mb-2 text-gray-800">Categories</h5>
    <!-- DataTales Example -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between">
            <div>
                <a href="add_cat.php">
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
                            <th>Category Name</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            
                            $sql = "SELECT * FROM categories limit $offset,$limit";
                            $query = mysqli_query($config,$sql);
                            $rows = mysqli_num_rows($query);
                            if($rows)
                            {
                                while($row = mysqli_fetch_assoc($query))
                                {
                        ?>
                        <tr>
                            <td><?= ++$offset ?> </td>
                            <td><?= $row['cat_name'] ?></td>
                            <td>
                                <a href="edit_cat.php?id=<?= $row['cat_id'] ?>" class="btn btn-sm btn-success">Edit</a>


                            </td>
                            <td>
                                <form action="" method="POST" onsubmit="return confirm('Are you sure want to delete?')">
                                    <input type="hidden" name="catID" value="<?= $row['cat_id'] ?>">
                                    <input type="submit" name="deleteCat" value="Delete" class="btn btn-sm btn-danger">
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
                            <td colspan="4">No Record Found</td>
                        </tr>
                        <?php
                             
                            }
                            ?>
                    </tbody>
                </table>
                <!-- pagination began -->

                <?php
            $pagination = "SELECT * FROM categories";
            $run_q = mysqli_query($config,$pagination);
            $total_categories = mysqli_num_rows($run_q);
            $pages =ceil( $total_categories/$limit);   // this function is used for ager points value aagia tu wu consider karayga aik value greater means that i-e 3.66 -> convert into 4.
            
            if ($total_categories > $limit) {   // this is condition for agr number of categories zyada hu tu pagination aana chahia wrna nahi chahia 
        
           
        ?>
                <ul class="pagination pt-2 pb-5">
                    <?php for ($i=1; $i <= $pages; $i++) { ?>
                    <li class="page-item <?=($i == $page)? $active= "active" : "";?>">
                        <a href="categories.php?page=<?=$i ?>" class="page-link">
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
if (isset($_POST['deleteCat'])) {
    $id = $_POST['catID'];
    $delete = "DELETE FROM categories WHERE cat_id = '$id'";
    $run = mysqli_query($config,$delete);
    if ($run) {
        $msg = ['Category Successfully deleted💪💪💪','alert-success'];  // just simple array mai nai used ki.
            $_SESSION['msg'] = $msg;
            header("location: categories.php");
    }
    else
    {
        $msg = ['Failed😭😭,Please try again','alert-danger'];  // just simple array mai nai used ki.
            $_SESSION['msg'] = $msg;
            header("location: categories.php");
    }
}
?>