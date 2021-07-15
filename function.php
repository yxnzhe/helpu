<?php
    function connectDb() { //Connect to database
        $servername = "localhost";
        $username = "root";
        $password = "mlxh011001";
        $db = "helpu";

        $conn = mysqli_connect($servername, $username, $password, $db);

        if(!$conn) {
            die ("Connection failed: ". mysqli_connect_error()); //Return error message if there's error when connecting to the database
        }
        return $conn;
    }

    function checkEmail($email) {
        $conn = connectDb();
        $sql = "SELECT count(*) FROM `user` where email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);

        if($stmt->execute()) {
            $stmt->bind_result($count);
            $stmt->fetch();
            
            if($count > 0) {
                $uniqEmail = false;
                echo "<span class='errorMsg'>Email Address Already Exist!</span>";
            }
            else {
                $uniqEmail = true;
            }
        }
        $stmt->close();
        $conn->close();

        return $uniqEmail;
    }

    function register($name, $email, $password, $confirmPassword) { //function to allow user to register to the website
        if($name == "" || $email == "" || $password == "") { //Check whether all field is filled up with value
            echo "<span class='errorMsg'>All field is Mandatory!</span>";
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Check whether the email format is correct or not
            echo "<span class='errorMsg'>Invalid email format!</span>";
        }
        else if($password != $confirmPassword){ //Check whether the password and confirm password is match or not
            echo "<span class='errorMsg'>Password does not match!</span>";
        }
        else { //If all field is with value and the email format is correct, then will create the user in our database
            $conn = connectDb();
            $password = password_hash($password, PASSWORD_DEFAULT);
            $userId = md5(microtime()); //Auto generate a string + number comment id

            $sql = "INSERT INTO `user` (id, name, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $userId, $name, $email, $password);

            if($stmt->execute()) {
                $_SESSION["userId"] = $userId;
                $_SESSION["isLogin"] = true;
                echo "<script> location.href='index.php'; </script>";    
            }
            else{
                echo "<span class='errorMsg'>Error: ".$sql."<br>".$conn->error."</span>";
            }

            $stmt->close();
            $conn->close();
        }
    }

    function login($email, $password) { //function to allow user to login to the website
        $errorMsg = "";
        if($email == "" || $password == "") { //to validate whether email or password is empty
            echo "<span class='errorMsg'>All field is Mandatory!</span>";//if email or password is empty, error message will prompt
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //to validate whether user has entered a valid email format
            echo "<span class='errorMsg'>Invalid email format!</span>";//if email format is invalid, this error message will prompt
        }
        else { //if email and password is entered as required
            $conn = connectDb(); //connect to databsae
            $sql = "SELECT `id`, password FROM user WHERE email = ?"; //query to select id and password from user table based on email

            $stmt = $conn->prepare($sql); //to prepare the query
            $stmt->bind_param("s", $email); //to bind the email with the email entered by user

            if($stmt->execute()) { //to execute
                $stmt->bind_result($id, $pass); //to bind the result of id and password based on the query
                if($stmt->fetch()) { //to fetch
                    if(password_verify($password, $pass)) { //to verify whether the password entered by user is equal to the password in the database
                        $_SESSION["userId"] = $id; //if password is entered correctly, $_SESSION["userId] will set to the user's id
                        $_SESSION["isLogin"] = true;
                        echo "<script> location.href='index.php'; </script>";
                    }
                    else { //if password is invalid
                        echo "<span class='errorMsg'>Login Failed. Invalid Email/Password.</span>";//error message will prompt
                    }
                }
                else { //if cant fetch
                    echo "<span class='errorMsg'>Login Failed. Invalid Email/Password.</span>";//error message will prompt
                }
            }
            else { //if $stmt cant execute
                echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
            }
            $stmt->close();
            $conn->close();
        }
    }

    function post($content) {
        $msg = "";
        if($content == "") {
            $msg = "<span class='errorMsg'>Content is empty</span>";
        }
        else if(strlen($content) > 255){
            $msg = "<span class='errorMsg'>Your content exceeded 255 characters.</span>";
        }
        else {
            $id = md5(microtime());;
            $userId = $_SESSION["userId"];

            date_default_timezone_set("Asia/Kuala_Lumpur"); //to set the timezone to KL
            $datetime = new DateTime(); //to get the current time
            $dt= $datetime->format('Y-m-d\TH:i:s'); //to chg the format of the datetime
            $newDateTime = date("Y-m-d H:i:s", strtotime($dt)); //to chg the datetime to string

            $conn = connectDb();
            $sql = "INSERT INTO `post` (id, `user_id`, content, created_at) VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $id, $userId, $content, $newDateTime);
            
            if($stmt->execute()) { //to execute the statement
                $msg = "<span class='successMsg'>Post successfully posted!</span>"; //alert tag will be present if the statement is successfully submitted
            }
            else {
                $msg = "<span class='errorMsg'>Failed to create Post. Reason: ".$conn->error."</span>"; //error message will be present if failed to execute the statement
            }
            $stmt->close();
            $conn->close();
            return $msg;
        }
    }

    function deletePost($postId) {
        $conn = connectDb();
        $msg = "";
        $sql = "DELETE FROM comment WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $postId);

        if($stmt->execute()) {
            $stmt->close();

            $sql = "DELETE FROM post WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $postId);

            if($stmt->execute()) {
                echo "<script>alert('Post successfully deleted')</script>";
                echo "<script> location.href='index.php' </script>";
            }
            else {
                $msg = "<span class='errorMsg'>You cannot delete the post</span>";
            }
        }
        else {
            $msg = $conn->error; //error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $msg;
    }

    function getAllPosts() {
        $conn = connectDb();
        $postArr = array();

        $sql = "SELECT p.id, p.user_id, u.name, p.content FROM post p
                INNER JOIN `user` u ON u.id = p.user_id
                ORDER BY p.created_at DESC";

        if($result = $conn->query($sql)) {
            while($row = $result->fetch_assoc()) {
                array_push($postArr, $row); //Return all post in array
            }
        }
        else {
            echo $conn->error; //error message will prompt
        }
        $conn->close();
        return $postArr;
    }

    function getPost($postId) {
        $conn = connectDb();
        $postArr = array();

        $sql = "SELECT p.user_id, u.name, p.content FROM post p
                INNER JOIN `user` u ON u.id = p.user_id
                WHERE p.id = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",  $postId);

        if($stmt->execute()) {
            $stmt->bind_result($userId, $name, $content);
            $stmt->fetch();
            $post = array("user_id" => $userId, "name" => $name, "content" => $content);
            array_push($postArr, $post);
        }
        else {
            echo $conn->error; //error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $postArr;
    }

    function getComment($postId) {
        $conn = connectDb();
        $commentArr = array();

        $sql = "SELECT u.id, u.name, c.id, c.content  FROM comment c
                INNER JOIN `user` u ON u.id = c.user_id
                WHERE c.post_id = ?
                ORDER BY c.created_at DESC";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",  $postId);

        if($stmt->execute()) {
            $stmt->bind_result($userId, $username, $commentId, $content);
            while($stmt->fetch()){
                $comment = array("postId" => $postId, "userId" => $userId, "username" => $username, "commentId" => $commentId, "content" => $content);
                array_push($commentArr, $comment);
            }
     
        }
        else {
            echo $conn->error; //error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $commentArr;
    }

    function postDeletePermission($postId) {
        $conn = connectDb();

        $sql = "SELECT count(*) FROM post WHERE id = ? and `user_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $postId, $_SESSION["userId"]);
        if($stmt->execute()) {
            $stmt->bind_result($count);
            $stmt->fetch();

            if($count > 0) {
                $postPermission = true;
            }
            else {
                $postPermission = false;
            }
        }
        $stmt->close();
        $conn->close();
        
        return $postPermission;
    }

    function commentDeletePermission($commentId) {
        $conn = connectDb();

        $sql = "SELECT count(*) FROM comment WHERE id = ? and `user_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $commentId, $_SESSION["userId"]);
        if($stmt->execute()) {
            $stmt->bind_result($count);
            $stmt->fetch();

            if($count > 0) {
                $commentPermissionm = true;
            }
            else {
                $commentPermissionm = false;
            }
        }
        $stmt->close();
        $conn->close();
        
        return $commentPermissionm;
    }

    function postExist($postId) {
        $conn = connectDb();

        $sql = "SELECT count(*) FROM post WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $postId);
        if($stmt->execute()) {
            $stmt->bind_result($count);
            $stmt->fetch();

            if($count > 0) {
                $postExist = true;
            }
            else {
                $postExist = false;
            }
        }
        $stmt->close();
        $conn->close();
        
        return $postExist;
    }

    function addComment($postId, $comment){ //function to allow user to post their comments
        $msg = "";
        if($comment == ""){ //If comment is submitted without any values
            $msg = "<span class='errorMsg'>Comment is empty.</span>";
        }
        else if(strlen($comment) > 150){ //If the comment exceeded 150 characters
            $msg = "<span class='errorMsg'>Comment cannot exceed 150 characters.</span>";
        }
        else{
            $conn = connectDb(); //connect to databsae
            $commentId = md5(microtime()); //Auto generate a string + number comment id
            $userId = $_SESSION["userId"]; //Get user_id from session that was set when user login

            date_default_timezone_set("Asia/Kuala_Lumpur"); //Set the timezone to Kuala Lumpur/ Malaysia/ Asia
            $datetime = new DateTime(); //to get the current time
            $dt= $datetime->format('Y-m-d\TH:i:s'); //to chg the format of the datetime
            $dateTime = date("Y-m-d H:i:s", strtotime($dt)); //to chg the datetime to string

            $sql = "INSERT INTO comment (id, `user_id`, post_id, content, created_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $commentId, $userId, $postId, $comment, $dateTime);

            if($stmt->execute()) {
                $msg = "<span class='successMsg'>Comment successfully posted!</span>";
                echo "<script> location.href='post_content.php?post=".$postId."' </script>";    
            }
            else{
                echo $conn->error; //error message will prompt
            }
            $stmt->close();
            $conn->close();
            return $msg;
        }
    }

    function deleteComment($commentId) { //function to allow user to delete their comment
        $conn = connectDb();
        $msg = "";
        $sql = "DELETE FROM comment WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $commentId);

        if($stmt->execute()) {}
        else{
            $msg = $conn->error; //error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $msg;
    }
