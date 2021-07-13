<html>
<head>
    <?php
    require_once "navbar.php";
    ?>
</head>

<body>

<!-- <div class="card">
                <h5 class="card-header">Posts</h5>
                <?php
                    $getPost = getPost($postId);
                    getPost($_GET["post"]);

                    if(count($getPost) > 0) {
                        foreach ($getPost as $i) {
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
                   
                ?>   -->
    
</body>
</html>