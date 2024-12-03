<!-- comment.php -->

<?php
include 'includes/db.php';

$photo_id = $_GET['photo_id']; // 获取图片ID
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h3>Comments:</h3>
    <div id="comments"></div> <!-- 评论区域 -->
    
    <form id="commentForm">
        <input type="hidden" name="photo_id" value="<?= $photo_id ?>">
        <textarea name="comment" placeholder="Add a comment" required></textarea>
        <button type="submit">Submit</button>
    </form>

    <script>
        $(document).ready(function() {
            const photoId = $('input[name="photo_id"]').val();

            // 获取评论
            function loadComments() {
                $.ajax({
                    url: 'get_comments.php',
                    type: 'GET',
                    data: { photo_id: photoId },
                    success: function(response) {
                        $('#comments').html(response);
                    }
                });
            }

            loadComments(); // 页面加载时立即加载评论

            // 提交评论
            $('#commentForm').submit(function(e) {
                e.preventDefault();
                const comment = $('textarea[name="comment"]').val();

                $.ajax({
                    url: 'comment.php',
                    type: 'POST',
                    data: {
                        photo_id: photoId,
                        comment: comment
                    },
                    success: function() {
                        loadComments(); // 提交后重新加载评论
                    }
                });
            });
        });
    </script>
</body>
</html>
