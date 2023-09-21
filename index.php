<?php
include "header.php";
include "config.php";

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

$postsPerPage = 3;
$offset = ($page - 1) * $postsPerPage;

$sql = "SELECT * FROM blog LEFT JOIN categories ON blog.category = categories.cat_id 
LEFT JOIN user ON blog.author_id = user.user_id ORDER BY blog.publish_date DESC LIMIT $offset,$postsPerPage";  
$run = mysqli_query($config, $sql);
$row = mysqli_num_rows($run);
?>

<div class="container mt-2">
    <div class="row">
        <div class="col-lg-8">
            <?php
            if ($row) {
                while ($result = mysqli_fetch_assoc($run)) {
            ?>

            <div class="card shadow">
                <div class="card-body d-flex blog_flex mt-2">
                    <div class="flex-part1">
                        <a href="single_post.php?id=<?= $result['blog_id'] ?>">
                            <?php $img = $result['blog_image']; ?>
                            <img src="admin/upload/<?= $img ?>">
                        </a>
                    </div>
                    <div class="flex-grow-1 flex-part2">
                        <a href="single_post.php?id=<?= $result['blog_id'] ?>" id="title">
                            <?= ucfirst($result['blog_title']) ?>
                        </a>
                        <p>
                            <a href="single_post.php?id=<?= $result['blog_id'] ?>" id="body">
                                <?= strip_tags(substr($result['blog_body'], 0, 100)) . "..." ?>
                            </a>
                            <span><br>
                                <a href="single_post.php?id=<?= $result['blog_id'] ?>"
                                    class="btn btn-sm btn-outline-primary">Continue Reading</a>
                            </span>
                        </p>
                        <ul>
                            <li class="me-2"><a href="" class="text-success">
                                    <span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                    <?= $result['username']; ?>
                                </a>
                            </li>
                            <li class="me-2">
                                <a href="">
                                    <span><i class="fa fa-calendar-o p-1" aria-hidden="true"></i></span>
                                    <?php $date = $result['publish_date'] ?>
                                    <?= date('d-M-y', strtotime($date)); ?>
                                </a>
                            </li>
                            <li>
                                <a href="category.php?id=<?= $result['cat_id'] ?>" class="text-primary">
                                    <span><i class="fa fa-tag p-1" aria-hidden="true"></i></span>
                                    <?= $result['cat_name'] ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <h4>Comments:</h4>
            <?php
                    $post_id = $result['blog_id'];
                    $comment_query = "SELECT * FROM comments WHERE blog_id = $post_id ORDER BY comment_date DESC";
                    $comment_run = mysqli_query($config, $comment_query);

                    while ($comment = mysqli_fetch_assoc($comment_run)) {
                    ?>
            <div class="comment">
                <p><strong><?= $comment['user_name']; ?>:</strong> <?= $comment['comment_text']; ?></p>
                <p class="text-muted"><?= date('d-M-Y', strtotime($comment['comment_date'])); ?></p>
            </div>
            <?php
                    } // end while loop for displaying comments
                    ?>

            <h4>Leave a Comment:</h4>
            <form method="post" action="add_comment.php">
                <input type="hidden" name="blog_id" value="<?= $post_id ?>">
                <div class="mb-3">
                    <label for="user_name" class="form-label">Your Name:</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="comment_text" class="form-label">Your Comment:</label>
                    <textarea name="comment_text" id="comment_text" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Comment</button>
            </form>

            <?php
                } // end while loop for displaying posts
            }
            ?>

            <!-- Pagination section -->
            <?php
            $pagination_query = "SELECT COUNT(*) AS total FROM blog";
            $pagination_result = mysqli_query($config, $pagination_query);
            $pagination_row = mysqli_fetch_assoc($pagination_result);
            $total_posts = $pagination_row['total'];
            $total_pages = ceil($total_posts / $postsPerPage);
            ?>

            <ul class="pagination pt-2 pb-5">
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                    <a href="index.php?page=<?= $i ?>" class="page-link"><?= $i ?></a>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php include "sidebar.php"; ?>
    </div>
</div>
<?php include "footer.php"; ?>