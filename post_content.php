<html>
<head>
    <?php
        require_once "navbar.php"; //require navbar to the page
        if (isset($_POST["post_comment_button"])) { //if the post comment button is clicked
            if(empty($_POST["comment"])) { //if the comment input field is empty
                $postCommMsg = "<span class='errorMsg'>Comment is Empty</span>"; //error message will prompt
            }
            else { //if the comment input field is not empty
                if(postExist($_POST["postId"])) { //the post exist in database or postExist function return value 1
                    $postId = $_POST["postId"]; //get postId from $_POST and set to $postId
                    $comment = strip_tags($_POST["comment"]); //strip_tags is a php function to remove html tags from input for example <b></b>
                    $postCommMsg = addComment($postId, $comment); //call the add comment function by passing postId and comment message
                }
            }
        }
        if (isset($_POST["post_content_deleteComment"])) { //else if the delete comment button is clicked
            if(isset($_POST["commentId"]) && commentDeletePermission($_POST["commentId"])) { //user have permission to delete and the comment is with valid comment id
                $deleteCommMsg = deleteComment($_POST["commentId"]); //call the deleteComment function by passing the commendId
            }
            else { //if the comment dont have valid commentId or dont have permission to delete
                echo "<script>alert('You Do Not Have Permission!')</script>"; //error message will prompt
            }
        }
        if (isset($_POST["post_content_deletePost"])) { //if delete post button is clicked
            if(isset($_POST["post_id"]) && postDeletePermission($_POST["post_id"])) { //if the user have permission to delete the post (is the owner of the post)
                $deletePostMsg = deletePost($_POST["post_id"]); //call deletePost function by passing postId
            }
            else { //the user do not have permission to delete the post (not the owner of the post)
                echo "<script>alert('You Do Not Have Permission!')</script>"; //error message will prompt
            }
        }
    ?>
</head>

<body style="background-color: #f3f3f3">
    <?php
    if (isset($_GET["post"])) { //if there's postId in the url or ?post= isset with value
        $getPost = getPost($_GET["post"]); //call the getPost function to get the post that the user want to see by passing postId
        if ($getPost[0]["user_id"] != "" && $getPost[0]["name"] != "" && $getPost[0]["content"] != "") { //only will show if user_id, name and content is not empty, if any of that is empty then will not print the post out
    ?>
            <div class="row justify-content-center pt-2 " style="margin-bottom: 4rem!important;">
                <div class="col-2"> </div>
                <div class="col-8">
                    <div class="card">
                        <h5 class="card-header">Posts</h5> <!--Posts Section-->
                        <?php
                        foreach ($getPost as $i) { //print out the post
                        ?>
                            <div class="row justify-content-center">
                                <div class="card-body mb-1 mx-3">
                                    <!-- User's name -->
                                    <h5 class="card-title"><?php echo $i["name"] ?></h5>
                                    <!-- Post content -->
                                    <p class="card-text"><?php echo $i["content"] ?></p>

                                    <?php
                                    if (isset($_SESSION["userId"])) { //if user is login
                                    ?>

                                        <form method="POST" class="mb-0">
                                            <div class="mb-3 row">
                                                <div class="col-10 col-lg-11 p-0 pr-1 px-md-3">
                                                    <input type="hidden" value=<?php echo $_GET["post"]; ?> name="postId" />
                                                    <!-- add comment textarea -->
                                                    <textarea maxlength="150" class="form-control" name="comment" rows="1" placeholder="Add a comment..." required></textarea>
                                                    <div id="count" class="text-right">
                                                        <!-- current character count -->
                                                        <span id="current_count">0</span>
                                                        <!-- maximum character count is 150 -->
                                                        <span id="maximum_count">/ 150</span>
                                                    </div>
                                                </div>
                                                <div class="col-2 p-0 col-lg-1">
                                                    <!-- submit comment button -->
                                                    <input type=submit name="post_comment_button" class="btn btn-primary" value="Post" />
                                                </div>
                                            </div>
                                        </form>
                                    <?php
                                    } 
                                    else { //user is not login
                                    ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <a href="entry.php">
                                                    <!-- link for them to redirect to entry.php -->
                                                    <span class="font-weight-bold" style="font-size: 16px">Login/Sign Up </span>
                                                </a>
                                                <span class="font-weight-bold" style="font-size: 16px">now to comment</span>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                    if (isset($postCommMsg)) { //if the postComment message isset (error or success messages)
                                        echo $postCommMsg; //print out the message
                                    }

                                    if (isset($_SESSION["userId"])) { //if user is login
                                        if ($i["user_id"] == $_SESSION["userId"]) { //if the post is belongs to the current login user
                                        ?>
                                            <form method="POST">
                                                <!-- postId -->
                                                <input type="hidden" value=<?php echo $_GET["post"]; ?> name="post_id" />
                                                <!-- delete post button -->
                                                <input type="submit" class="btn btn-danger" name="post_content_deletePost" value="Delete Post">
                                            </form>
                                    <?php
                                            if(isset($deletePostMsg)) { //if there is message for delete post
                                                echo $deletePostMsg; //message will prompt
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <br />
                    <div class="card">
                        <h5 class="card-header">Comments</h5> <!--Comments Section-->
                        <?php
                        $getComment = getComment($_GET["post"]); //get all comment for that post
                        if (count($getComment) > 0) { //if the comment count is greater than 0 means there's comment for this post
                            foreach ($getComment as $c) { //foreach all the comment
                        ?>
                                <div class="card-body mb-1">
                                    <h5 class="card-title"><?php echo $c["username"]; ?></h5> <!--Display Username of Comment-->
                                    <p class="card-text"><?php echo $c["content"]; ?></p> <!--Display Content of Comment-->
                                    <?php
                                    if (isset($_SESSION["userId"])) { //user is login
                                        if ($c["userId"] == $_SESSION["userId"]) { //the comment is belong to the current login user
                                    ?>
                                            <div class="row justify-content-end">
                                                <form method="POST" class="mb-0">
                                                    <input type="hidden" value=<?php echo $c["commentId"]; ?> name="commentId" />
                                                    <!-- delete comment button -->
                                                    <input type="submit" class="btn btn-danger mr-5" name="post_content_deleteComment" value="Delete Comment">
                                                </form>
                                            </div>
                                    <?php
                                        }
                                    }
                                    if(isset($_POST["commentId"]) && $_POST["commentId"] == $c['commentId']) { //if commentId isset and commentId is the same as the commentId that we foreach out (valid commentId)
                                        if (isset($deleteCommMsg)) { //if delete comment message isset
                                            echo $deleteCommMsg; //echo the message
                                        }
                                    }
                                    ?>
                                </div>
                                <hr style="border-top: 25px solid #f3f3f3; margin: 0">
                        <?php
                            }
                        } 
                        else { //there's no comment for this post or count = 0
                            echo "  <div class='row justify-content-center py-4'>
                                    <span style='font-size: 20px;'>No Comment</span>
                                </div>";
                        }
                        ?>
                    </div>

                <?php
            } else { //can't find the postId or postId is invalid
                echo "  <div class='row justify-content-center py-4'>
                            <span class='errorMsg'>Undefined Post ID</span>
                        </div>";
            }
                ?>
                </div>
                <div class="col-2"> </div>
            </div>
        <?php
    } else { //can't find the postId or postId is invalid
        echo "  <div class='row justify-content-center py-4'>
                    <span class='errorMsg'>Undefined Post ID</span>
                </div>";
    }
        ?>
</body>
<?php require_once "footer.php"; //require footer at the bottom of the page?> 
</html>
<script>
    // count for character for textarea
    $('textarea').keyup(function() {    
        var characterCount = $(this).val().length,
            current_count = $('#current_count'), //current character count
            maximum_count = $('#maximum_count'), //maximum chatacter count
            count = $('#count');    
            current_count.text(characterCount);        
    });
</script>