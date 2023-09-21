<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = $_POST['blog_id'];
    $user_name = $_POST['user_name'];
    $comment_text = $_POST['comment_text'];

    $insert_query = "INSERT INTO comments (blog_id, user_name, comment_text, comment_date) 
                     VALUES ('$blog_id', '$user_name', '$comment_text', NOW())";

    if (mysqli_query($config, $insert_query)) {
        // Comment added successfully, redirect back to the blog post
        header("Location: single_post.php?id=$blog_id");
        exit;
    } else {
        echo "Error adding comment: " . mysqli_error($config);
    }
}
?>