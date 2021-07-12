<html>

<head>
    <?php
    require_once "navbar.php";

    if (isset($_POST["postPost"])) {
        $postContent = $_POST["post_content"];
        post($postContent);
    }
    ?>
</head>

<body style="background-color: #f3f3f3">
    <div class="row justify-content-center pt-2" style="margin-bottom: 4rem!important;">
        <div class="col-2"> </div>
        <div class="col-8">
            <?php if (isset($_SESSION["userId"])) { ?>
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
            <?php } ?>
            <br />
            <div class="card">
                <h5 class="card-header">Posts</h5>
                <?php

                $getAllPost = getAllPosts();
                foreach ($getAllPost as $i){
                ?>
                    <div class="card-body mb-1">
                        <h5 class="card-title"><?php echo $i["name"]; ?></h5>
                        <p class="card-text"><?php echo $i["content"];?></p>
                        <form method="POST" class="mb-0">
                            <div class="mb-3 row">
                                <div class="col-10 col-lg-11 p-0 pr-1 px-md-3">
                                    <textarea class="form-control" name="comment" rows="1" placeholder="Add a comment..." required></textarea>
                                </div>
                                <div class="col-2 p-0 col-lg-1">
                                    <input type=submit name="post_button" class="btn btn-primary" value="Post" />
                                </div>
                                <input type="hidden" value= <?php echo $i["id"]  ?> name="post_id" />
                                <?php
                                if (isset($_POST["post_button"])) {
                                    $postId = $_POST["post_id"];
                                    $comment = $_POST["comment"];
                                    addComment($postId, $comment);
                                }
                                ?>
                            </div>

                            <button type="button" class="btn btn-danger">Delete post</button>
                        </form>
                    </div>
                    <br />
                <?php
                }
                ?>
            </div>
        </div>
        <div class="col-2"> </div>
    </div>

</body>
<?php include 'footer.php'; ?>