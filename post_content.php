<html>

<head>
    <?php
        require_once "navbar.php";
    ?>
</head>

<body>
    <?php
    if(isset($_GET["post"])) {
        $getPost = getPost($_GET["post"]);
        if($getPost[0]["user_id"] != "" && $getPost[0]["name"] != "" && $getPost[0]["content"] != ""){
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
                        <form method="POST" class="mb-0">
                            <div class="mb-3 row">
                                <div class="col-10 col-lg-11 p-0 pr-1 px-md-3">
                                    <input type="hidden" value= <?php echo $_GET["post"];?> name="post_content_postId" />
                                    <textarea maxlength="150" class="form-control" name="post_content_comment" rows="1" placeholder="Add a comment..." required></textarea>
                                </div>
                                <div class="col-2 p-0 col-lg-1">
                                    <input type=submit name="post_comment_button" class="btn btn-primary" value="Post" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        <?php
                }
        ?>  </div>
            <br />
        <?php
            if (isset($_POST["post_comment_button"])) {
                if(empty($_POST["post_content_comment"])) {
                    echo "<span class='errorMsg'>Comment is Empty</span>";
                }
                else {
                    $postId = $_POST["post_content_postId"];
                    $comment = $_POST["post_content_comment"];
                    addComment($postId, $comment);
                }
            }

            if(isset($_POST["post_content_deleteComment"])) {
                deleteComment($_POST["post_content_commentId"]);
            }
        ?>
            <div class="card">
                <h5 class="card-header">Comments</h5>
                <?php
                    $getComment = getComment($_GET["post"]);
                    if(count($getComment) > 0) {
                        foreach ($getComment as $c) {
                ?>
                    <div class="card-body mb-1">
                        <h5 class="card-title"><?php echo $c["username"]; ?></h5>
                        <p class="card-text"><?php echo $c["content"];?></p>
                    <?php
                        if(isset($_SESSION["userId"])) { 
                            if($c["userId"] == $_SESSION["userId"]) { 
                    ?>      
                            <div class="row justify-content-end">
                                <form method="POST" class="mb-0">
                                    <input type="hidden" value= <?php echo $c["commentId"];?> name="post_content_commentId" />
                                    <input type="submit" class="btn btn-danger mr-5" name="post_content_deleteComment" value="Delete Comment">
                                </form>
                            </div>
                    <?php
                            } 
                        }
                    ?>
                    </div>
                    <hr style="border-top: 25px solid #f3f3f3; margin: 0">
                <?php
                        }
                    }
                    else {
                        echo "  <div class='row justify-content-center py-4'>
                                    <span style='font-size: 20px;'>No Comment</span>
                                </div>";
                    }
                ?>
            </div>

        <?php   
            }
            else {
                echo "  <div class='row justify-content-center py-4'>
                            <span class='errorMsg'>Undefined Post ID</span>
                        </div>";
            }           
        ?>
        </div>
        <div class="col-2"> </div>
    </div>
    <?php
    }
    else {
        echo "  <div class='row justify-content-center py-4'>
                    <span class='errorMsg'>Undefined Post ID</span>
                </div>";
    }
    ?>
</body>
<?php include 'footer.php'; ?>
</html>