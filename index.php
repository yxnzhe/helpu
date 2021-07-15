<html>
<head>
    <?php
        require_once "navbar.php";
        if(isset($_POST["postPost"])) { //if the post post button is clicked
            if(empty($_POST["post_content"])) { //if the post input field is empty
                $postErrMsg = "<span class='errorMsg'>Content is Empty</span>";
            }
            else { //if post input field is not empty
                $postContent = strip_tags($_POST["post_content"]); //strip_tags is a php function to remove html tags from input for example <b></b>
                $postErrMsg = post($postContent);
            }
        }

        if (isset($_POST["postComment"])) { //else if the post comment button is clicked
            if(empty($_POST["comment"])) { //if the comment input field is empty
                $commentErrMsg = "<span class='errorMsg'>Comment is Empty</span>";
            }
            else { //if comment input field is not empty
                if(postExist($_POST["post_id"])) {
                    $postId = $_POST["post_id"];
                    $comment = strip_tags($_POST["comment"]); //strip_tags is a php function to remove html tags from input for example <b></b>
                    $commentErrMsg = addComment($postId, $comment);
                }
            }
        }

        if (isset($_POST["deletePost"])) { //else if delete post button is clicked
            if(isset($_POST["post_id"]) && postDeletePermission($_POST["post_id"])) { //if the user have permission to delete the post (is the owner of the post)
                $deleteMsg = deletePost($_POST["post_id"]); //delete the post
            }
            else { //the user do not have permission to delete the post (not the owner of the post)
                echo "<script>alert('You Do Not Have Permission!')</script>";
            }
        }
    ?>
</head>

<body style="background-color: #f3f3f3">
    <div class="row justify-content-center pt-2" style="margin-bottom: 4rem!important;">
        <div class="col-2"> </div>
        <div class="col-8">
            <?php 
                if (isset($_SESSION["userId"])) { 
            ?>
                    <div class="card">
                        <h5 class="card-header">Create Post</h5>
                        <div class="card-body">
                            <form method="POST" class="mb-0">
                                <div class="mb-3">
                                    <textarea maxlenght="255" class="form-control" name="post_content" rows="3" placeholder="What is your question?" required></textarea>
                                </div>
                                <input type="submit" name="postPost" class="btn btn-primary" value="Post">
                            </form>
                            <?php
                                if(isset($postErrMsg)){
                                    echo $postErrMsg;
                                }
                            ?>
                        </div>
                    </div>
            <?php 
                }
                else {
            ?>
                    <div class="row">
                        <div class="col-2"> </div>
                        <div class="col-8 text-center mt-3"> 
                            <a href="login.php">
                                <span class="font-weight-bold" style="font-size: 25px">Login/Sign Up </span>
                            </a>
                            <span class="font-weight-bold" style="font-size: 25px">now to post</span>
                        </div>
                        <div class="col-2"> </div>
                    </div>
            <?php
                }
                if(isset($deleteMsg)){
                    echo $deleteMsg;
                }
            ?>
            <br />
            <div class="card">
                <h5 class="card-header">Posts</h5>
                <?php
                    $getAllPost = getAllPosts();

                    if(count($getAllPost) > 0) {
                        foreach ($getAllPost as $i) {
                ?>
                            <div class="card-body mb-1">
                                <h5 class="card-title"><?php echo $i["name"]; ?></h5>
                                <p class="card-text"><?php echo $i["content"];?></p>
                            <?php
                                if(isset($_SESSION["userId"])) {
                            ?>
                                <form method="POST" class="mb-0">
                                    <div class="mb-3 row">
                                        <div class="col-10 col-lg-11 p-0 pr-1 px-md-3">
                                            <textarea maxlenght="150" class="form-control" name="comment" rows="1" placeholder="Add a comment..." required></textarea>
                                        </div>
                                        <div class="col-2 p-0 col-lg-1">
                                            <input type=submit name="postComment" class="btn btn-primary" value="Post" />
                                        </div>
                                        <input type="hidden" value= <?php echo $i["id"]?> name="post_id" />
                                    </div>
                                </form>
                                <?php
                                    if(isset($_POST["post_id"]) && $_POST["post_id"] == $i['id']) {
                                        if(isset($commentErrMsg)){
                                            echo $commentErrMsg;
                                        }
                                    }
                                }
                                else {
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
                                ?>
                                    <div class="row">
                                        <div class="col-6"> 
                                <?php
                                    if(isset($_SESSION["userId"])) { 
                                        if($i["user_id"] == $_SESSION["userId"]) { 
                                ?>
                                            <form method="POST">
                                                <input type="hidden" value= <?php echo $i["id"];?> name="post_id" />
                                                <input type="submit" class="btn btn-danger" name="deletePost" value="Delete Post">
                                            </form>
                                <?php 
                                        } 
                                    }
                                ?>
                                        </div>
                                        <div class="col-6 text-right"> 
                                            <a href="post_content.php?post=<?php echo $i["id"];?>" style="color: darkblue;">
                                                <span class=" mr-3" style="font-size: 16px">View <?php echo count(getComment($i["id"]));?> Comment(s)</span>
                                            </a>
                                        </div>
                                    </div>
                            </div>
                            <hr style="border-top: 25px solid #f3f3f3; margin: 0">
                <?php
                        }
                    }
                    else {
                ?>  
                        <div class="row justify-content-center">
                            <div class="col-2"> </div>
                            <div class="col-8 text-center my-5"> 
                                <span class="font-weight-bold" style="font-size: 25px">There is no Post</span><br />
                                <span class="font-weight-bold" style="font-size: 25px">Post your first post now</span><br />
                                <?php 
                                    if (!isset($_SESSION["userId"])) { 
                                ?>
                                    <a href="login.php">
                                        <span class="font-weight-bold" style="font-size: 25px">Login/Sign Up now to post</span>
                                    </a>
                                <?php 
                                    }
                                ?>
                            </div>
                            <div class="col-2"> </div>
                        </div>
                <?php
                    }
                ?>
            </div>
        </div>
        <div class="col-2"> </div>
    </div>

</body>
<?php include 'footer.php'; ?>