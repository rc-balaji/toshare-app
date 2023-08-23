<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('location: ../index.php');
    exit();
}

include('header.php');
include('../dbconnection.php');

// Fetch and display posts
$query = "SELECT * FROM posts";
$result = mysqli_query($dbcon, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        .time {
            font-size: 14px;
            color: #888;
        }
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .post-container {
            background-color: white;
            border: 1px solid #ccc;
            margin: 10px;
            padding: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            gap: 30px;
            display: flex;
            gap: 30px;
            flex-direction: column;
            align-items: center;
            height: 100%;
        }

        .posts{
            background-color: lightskyblue;
            border: 2px solid black;
            align-items: center;
            width: 450px;
            height: 600;
            padding: 10px;
            border-radius: 8px;
        }

        .img{
            width: 250px;
            height: 250px;
            justify-content: center;
            border: 3px solid black;
            margin-left: 90px;
            border-radius: 5px;
           
        }

        .btn{
            background-color: white;
            margin: 10px;
            font-size: 22px;
            
        }
        .btn:hover{
            background-color: grey;
            margin: 10px;
            font-size: 23px;
          
        }
        .cap{
            display: flex;
            margin-left:30px ;
            gap:30px
        }
        .cap p{
            font-size: 20px;
            font-weight: bold;
        }
        .top{
            display: flex;
            justify-content: space-between;

        }
    </style>
</head>
<body>
<div class="post-container">
        <h1>Recent Posts</h1>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='posts'>";
                echo "<div class='top'>";
                echo "<h2>🙂{$row['username']}</h2>";
                echo "<span class='time'>{$row['post_date']}</span>";
                echo "</div>";
                echo "<img class='img' src='../post_images/{$row['photo']}' alt='Post Image'><br>";
                echo "<button class='btn' onclick='likePost({$row['post_id']})' data-postid='{$row['post_id']}' data-action='like'>❤️ {$row['likes']}</button>";
                echo "<button class='btn' onclick='dislikePost({$row['post_id']})' data-postid='{$row['post_id']}' data-action='dislike'>👎 {$row['dislikes']}</button>";
                echo "<div class='cap'>";
                echo "<p>{$row['username']} :</p>";
                echo "<p>{$row['caption']}...</p>";
                echo "</div>";

                // Display comments section
                echo "<h4>Comments</h4>";
                echo "<div class='comments' id='comments{$row['post_id']}'>";
                echo "<input type='text' id='commentInput{$row['post_id']}' placeholder='Add a comment'>";
                echo "<button class='btn' onclick='addComment({$row['post_id']}, {$_SESSION['uid']})'>Add Comment</button>";
                
                // Fetch comments using AJAX
                echo "<div class='comments-list' id='commentsList{$row['post_id']}'>";
                echo "</div>";
                echo "</div>";

                // Fetch comments using AJAX
                echo "<script>getComments({$row['post_id']});</script>";

                echo "</div>"; // Close the 'posts' div
            }
        } else {
            echo "<p class='no-posts'>No posts available.</p>";
        }
        ?>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function addComment(postId, userId) {
        var commentInput = document.getElementById('commentInput' + postId);
        var comment = commentInput.value.trim();

        if (comment !== "") {
            $.ajax({
                type: "POST",
                url: "addComment.php",
                data: { post_id: postId, comment: comment, user_id: userId },
                success: function(response) {
                    getComments(postId);
                    commentInput.value = '';
                }
            });
        }
    }

    function getComments(postId) {
        var commentsDiv = document.getElementById('comments' + postId);

        $.ajax({
            type: "GET",
            url: "getComments.php",
            data: { post_id: postId },
            success: function(response) {
                commentsDiv.innerHTML = response;
            }
        });
    }

    function likePost(postId) {
        $.ajax({
            type: "POST",
            url: "likePost.php",
            data: { post_id: postId },
            success: function(response) {
                var responseData = JSON.parse(response);
                var likesCount = responseData.likes;
                var likeButton = document.querySelector(`button[data-postid="${postId}"][data-action="like"]`);
                likeButton.innerText = `❤️ ${likesCount}`;
            }
        });
    }

    function dislikePost(postId) {
        $.ajax({
            type: "POST",
            url: "dislikePost.php",
            data: { post_id: postId },
            success: function(response) {
                var responseData = JSON.parse(response);
                var dislikesCount = responseData.dislikes;
                var dislikeButton = document.querySelector(`button[data-postid="${postId}"][data-action="dislike"]`);
                dislikeButton.innerText = `👎 ${dislikesCount}`;
            }
        });
    }
</script>

</body>
</html>
