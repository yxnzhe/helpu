<html>

<head>
    <?php
        require_once "navbar.php";

        if(isset($_POST["postPost"])){
            $postContent = $_POST["post_content"];
            post($postContent);
        }

        if(isset($_POST["deletePost"])){
            if(postDeletePermission($_POST["postId"])) {
                deletePost($_POST["postId"]);
            }
            else {
                echo "<span style='color: red; font-size: 20px;'>You Do Not Have Permission!</span>";
            }
        }
    ?>
</head>

<body style="background-color: #f3f3f3">
    <div class="row justify-content-center pt-2" style="margin-bottom: 4rem!important;">
        <div class="col-2"> </div>
            <div class="col-8">
            <?php if(isset($_SESSION["userId"])){ ?>
                <div class="card">
                    <h5 class="card-header">Create Post</h5>
                    <div class="card-body">
                        <form method="POST" class="mb-0">
                            <div class="mb-3">
                                <textarea class="form-control" name="post_content" rows="3" placeholder="What is your question?" required></textarea>
                            </div>
                            <input type="submit" name="postPost" class="btn btn-primary" value="Post">
                        </form>
                    </div>
                </div>
            <?php }?>
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

                            <button type="button" class="btn btn-danger" name="deletePost">Delete post</button>
                        </form>
                    </div>
                <?php

                ?>
            </div>
        </div>
        <div class="col-2"> </div>
    </div>

</body>
<?php include 'footer.php'; ?>