<?php
include('../dbconnection.php');

if (isset($_POST['post_id']) && isset($_POST['comment']) && isset($_POST['user_id'])) {
    $postId = $_POST['post_id'];
    $comment = $_POST['comment'];
    $userId = $_POST['user_id'];

    $usernameQuery = "SELECT username FROM users WHERE user_id = '$userId'";
    $usernameResult = mysqli_query($dbcon, $usernameQuery);
    $usernameRow = mysqli_fetch_assoc($usernameResult);
    $username = $usernameRow['username'];

    $query = "INSERT INTO comments (post_id, username, comment, user_id) VALUES ('$postId', '$username', '$comment', '$userId')";
    $result = mysqli_query($dbcon, $query);

    if ($result) {
        echo "Comment added successfully.";
    } else {
        echo "Error adding comment.";
    }
} else {
    echo "Invalid request.";
}
?>
