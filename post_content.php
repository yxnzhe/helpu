<html>

<head>
    <?php
    require_once "navbar.php";
    ?>
</head>

<body>
    <?php
    if(isset($_GET["post"])) {
        getPost($_GET["post"]);
    ?>
    <div class="row justify-content-center pt-2 " style="margin-bottom: 4rem!important;">
        <div class="col-2"> </div>
        <div class="col-8">
            <div class="card ">
                <h5 class="card-header">Posts</h5>
                <div class="row justify-content-center">

                    <div class="card-body mb-1 mx-2">
                        <h5 class="card-title">name</h5>
                        <p class="card-text">post content</p>

                        <form method="POST" class="mb-0">
                            <div class="mb-3 row">
                                <div class="col-10 col-lg-11 p-0 pr-1 px-md-3">
                                    <textarea class="form-control" name="comment" rows="1" placeholder="Add a comment..." required></textarea>
                                    <div class="pt-1 text-right" id="charNumComment"></div>
                                </div>
                                <div class="col-2 p-0 col-lg-1">
                                    <input type=submit name="post_button" class="btn btn-primary" value="Post" />
                                </div>

                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-2"> </div>
    </div>
    <?php
    }
    else {
        echo "  <div class='row justify-content-center py-4'>
                    <span style='color: red; font-size: 20px;'>Undefined Post ID</span>
                </div>";
    }
    ?>
</body>

</html>