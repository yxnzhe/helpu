<html>
<script>
    function countCharPost(val) {
        var len = val.value.length;
        if (len >= 255) {
            val.value = val.value.substring(0, 255);
        } 
        else {
            $('#charNumPost').text(255 - len);
        }
    };
    function countCharComment(val) {
        var len = val.value.length;
        if (len >= 155) {
            val.value = val.value.substring(0, 155);
        } 
        else {
            $('#charNumComment').text(155 - len);
        }
    };
</script>
<head>
    <?php
        require_once "navbar.php";
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
                                    <textarea onkeyup="countCharPost(this)" class="form-control" name="post_content" rows="3" placeholder="What is your question?" required></textarea>
                                    <div class="pt-1 text-right" id="charNumPost"></div>
                                </div>
                                <input type="submit" name="postPost" class="btn btn-primary" value="Post">
                            </form>
                            <?php
                                if(isset($_POST["postPost"])) {
                                    if(empty($_POST["post_content"])) {
                                        echo "<span style='color: red; font-size: 20px;'>Content is Empty</span>";
                                    }
                                    else {
                                        $postContent = $_POST["post_content"];
                                        post($postContent);
                                    }
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
                if (isset($_POST["post_button"])) {
                    if(empty($_POST["comment"])) {
                        echo "<span style='color: red; font-size: 20px;'>Comment is Empty</span>";
                    }
                    else {
                        $postId = $_POST["post_id"];
                        $comment = $_POST["comment"];
                        addComment($postId, $comment);
                    }
                }
                
                if(isset($_POST["deletePost"])) {
                    if(postDeletePermission($_POST["post_id"])) {
                        deletePost($_POST["post_id"]);
                    }
                    else {
                        echo "<span style='color: red; font-size: 20px;'>You Do Not Have Permission!</span>";
                    }
                }
            ?>
            <br />
            <div class="card">
                <h5 class="card-header">Posts</h5>
                <?php
                    $getAllPost = getAllPosts();

                    if(count($getAllPost) > 0) {
                        foreach ($getAllPost as $i) {
                            print_r(getComment($i['id']));
                ?>
                            <div class="card-body mb-1">
                                <h5 class="card-title"><?php echo $i["name"]; ?></h5>
                                <p class="card-text"><?php echo $i["content"];?></p>
                                <form method="POST" class="mb-0">
                                    <div class="mb-3 row">
                                        <div class="col-10 col-lg-11 p-0 pr-1 px-md-3">
                                            <textarea onkeyup="countCharComment(this)" class="form-control" name="comment" rows="1" placeholder="Add a comment..." required></textarea>
                                            <div class="pt-1 text-right" id="charNumComment"></div>
                                        </div>
                                        <div class="col-2 p-0 col-lg-1">
                                            <input type=submit name="post_button" class="btn btn-primary" value="Post" />
                                        </div>
                                        <input type="hidden" value= <?php echo $i["id"]?> name="post_id" />
                                        <!-- <p><?php echo count(getPost($i["id"])); ?></p> -->
                                    </div>
                                </form>
                                <?php 
                                    if(isset($_SESSION["userId"])) { 
                                        if($i["user_id"] == $_SESSION["userId"]) { ?>
                                            <form method="POST">
                                                <input type="hidden" value= <?php echo $i["id"]?> name="post_id" />
                                                <input type="submit" class="btn btn-danger" name="deletePost" value="Delete Post">
                                            </form>
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