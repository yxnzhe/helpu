<html>

<head>
    <?php
    require_once "navbar.php";
    if (isset($_POST["post_comment_button"])) { //if the post comment button is clicked
        if (empty($_POST["comment"])) { //if the comment input field is empty
            $postCommMsg = "<span class='errorMsg'>Comment is Empty</span>";
        } else { //if the comment input field is not empty
            if (postExist($_POST["postId"])) {
                $postId = $_POST["postId"];
                $comment = strip_tags($_POST["comment"]); //strip_tags is a php function to remove html tags from input for example <b></b>
                $postCommMsg = addComment($postId, $comment);
            }
        }
    }

    if (isset($_POST["post_content_deleteComment"])) { //else if the delete button is clicked
        if (isset($_POST["commentId"]) && commentDeletePermission($_POST["commentId"])) {
            $deleteCommMsg = deleteComment($_POST["commentId"]);
        } else {
            echo "<script>alert('You Do Not Have Permission!')</script>";
        }
    }

    if (isset($_POST["post_content_deletePost"])) { //else if delete post button is clicked
        if (isset($_POST["post_id"]) && postDeletePermission($_POST["post_id"])) { //if the user have permission to delete the post (is the owner of the post)
            $deletePostMsg = deletePost($_POST["post_id"]); //delete the post
        } else { //the user do not have permission to delete the post (not the owner of the post)
            echo "<script>alert('You Do Not Have Permission!')</script>";
        }
    }
    ?>
</head>

<body style="background-color: #f3f3f3">
    <?php
    if (isset($_GET["post"])) {
        $getPost = getPost($_GET["post"]);
        if ($getPost[0]["user_id"] != "" && $getPost[0]["name"] != "" && $getPost[0]["content"] != "") {
    ?>
            <div class="row justify-content-center pt-2 " style="margin-bottom: 4rem!important;">
                <div class="col-2"> </div>
                <div class="col-8">
                    <div class="card">
                        <h5 class="card-header">Posts</h5>
                        <?php
                        foreach ($getPost as $i) {
                        ?>
                            <div class="row justify-content-center">
                                <div class="card-body mb-1 mx-2">
                                    <h5 class="card-title"><?php echo $i["name"] ?></h5>
                                    <p class="card-text"><?php echo $i["content"] ?></p>

                                    <?php
                                    if (isset($_SESSION["userId"])) {
                                    ?>

                                        <form method="POST" class="mb-0">
                                            <div class="mb-3 row">
                                                <div class="col-10 col-lg-11 p-0 pr-1 px-md-3">
                                                    <input type="hidden" value=<?php echo $_GET["post"]; ?> name="postId" />
                                                    <textarea maxlength="150" class="form-control" name="comment" rows="1" placeholder="Add a comment..." required></textarea>
                                                </div>
                                                <div class="col-2 p-0 col-lg-1">
                                                    <input type=submit name="post_comment_button" class="btn btn-primary" value="Post" />
                                                </div>
                                            </div>
                                        </form>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <a href="login.php">
                                                    <span class="font-weight-bold" style="font-size: 16px">Login/Sign Up </span>
                                                </a>
                                                <span class="font-weight-bold" style="font-size: 16px">now to comment</span>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                    if (isset($postCommMsg)) {
                                        echo $postCommMsg;
                                    }

                                    if (isset($_SESSION["userId"])) {
                                        if ($i["user_id"] == $_SESSION["userId"]) {
                                        ?>
                                            <form method="POST">
                                                <input type="hidden" value=<?php echo $_GET["post"]; ?> name="post_id" />
                                                <input type="submit" class="btn btn-danger" name="post_content_deletePost" value="Delete Post">
                                            </form>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        if (isset($deleteMsg)) {
                            echo $deleteMsg;
                        }
                        ?>
                    </div>
                    <br />
                    <div class="card">
                        <h5 class="card-header">Comments</h5>
                        <?php
                        $getComment = getComment($_GET["post"]);
                        if (count($getComment) > 0) {
                            foreach ($getComment as $c) {
                        ?>
                                <div class="card-body mb-1">
                                    <h5 class="card-title"><?php echo $c["username"]; ?></h5>
                                    <p class="card-text"><?php echo $c["content"]; ?></p>
                                    <?php
                                    if (isset($_SESSION["userId"])) {
                                        if ($c["userId"] == $_SESSION["userId"]) {
                                    ?>
                                            <div class="row justify-content-end">
                                                <form method="POST" class="mb-0">
                                                    <input type="hidden" value=<?php echo $c["commentId"]; ?> name="commentId" />
                                                    <input type="submit" class="btn btn-danger mr-5" name="post_content_deleteComment" value="Delete Comment">
                                                </form>
                                                <?php
                                                if (isset($deleteMsg)) {
                                                    echo $deleteMsg;
                                                }
                                                ?>
                                            </div>
                                    <?php
                                        }
                                    }
                                    if (isset($_POST["post_content_commentId"]) && $_POST["post_content_commentId"] == $c['commentId']) {
                                        if (isset($deleteCommMsg)) {
                                            echo $deleteCommMsg;
                                        }
                                    }
                                    ?>
                                </div>
                                <hr style="border-top: 25px solid #f3f3f3; margin: 0">
                        <?php
                            }
                        } else {
                            echo "  <div class='row justify-content-center py-4'>
                                    <span style='font-size: 20px;'>No Comment</span>
                                </div>";
                        }
                        ?>
                    </div>

                <?php
            } else {
                echo "  <div class='row justify-content-center py-4'>
                            <span class='errorMsg'>Undefined Post ID</span>
                        </div>";
            }
                ?>
                </div>
                <div class="col-2"> </div>
            </div>
        <?php
    } else {
        echo "  <div class='row justify-content-center py-4'>
                    <span class='errorMsg'>Undefined Post ID</span>
                </div>";
    }
        ?>
</body>
<?php include 'footer.php'; ?>

</html>