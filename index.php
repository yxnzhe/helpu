<html>

<head>
    <?php
    require_once "navbar.php";
    ?>
</head>

<body style="background-color: #f3f3f3">
    <div class="row justify-content-center" style="margin-bottom: 4rem!important;">
        <div class="col-2"> </div>
        <div class="col-8">
            <div class="card">
                <h5 class="card-header">Create Post</h5>
                <div class="card-body">
                    <form method="POST" class="mb-0">
                        <div class="mb-3">
                            <textarea class="form-control" name="post_content" rows="3" placeholder="What is your question?" required></textarea>
                        </div>
                        <a href="#" class="btn btn-primary">Post</a>
                    </form>

                </div>
            </div>

            <br />
            <div class="card">
                <h5 class="card-header">Posts</h5>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <form method="POST" class="mb-0">
                        <div class="mb-3 row">
                            <div class="col-10 col-lg-11 p-0 pr-1 px-md-3">
                                <textarea class="form-control" name="comment" rows="1" placeholder="Add a comment..." required></textarea>
                            </div>
                            <div class="col-2 p-0 col-lg-1">
                               <input type=submit name="post_button" class="btn btn-primary" placeholder="Post" />
                            </div>
                            <?php
                            if(isset($_POST["post_button"])){
                                addComment($postId, $comment);
                            }
                            ?>
                        </div>

                        <button type="button" class="btn btn-danger">Delete post</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-2"> </div>
    </div>

</body>
<?php include 'footer.php'; ?>