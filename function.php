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
                echo "<span style='color: red; font-size: 20px;'>Email Address Already Exist!</span>";
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
            echo "<span style='color: red; font-size: 20px;'>All field is Mandatory!</span>";
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Check whether the email format is correct or not
            echo "<span style='color: red; font-size: 20px;'>Invalid email format!</span>";
        }
        else if($password != $confirmPassword){ //Check whether the password and confirm password is match or not
            echo "<span style='color: red; font-size: 20px;'>Password does not match!</span>";
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
                echo "<span style='color: red; font-size: 20px;'>Error: ".$sql."<br>".$conn->error."</span>";
            }

            $stmt->close();
            $conn->close();
        }
    }

    function login($email, $password) { //function to allow user to login to the website
        $errorMsg = "";
        if($email == "" || $password == "") { //to validate whether email or password is empty
            echo "<span style='color: red; font-size: 20px;'>All field is Mandatory!</span>";//if email or password is empty, error message will prompt
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //to validate whether user has entered a valid email format
            echo "<span style='color: red; font-size: 20px;'>Invalid email format!</span>";//if email format is invalid, this error message will prompt
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
                        echo "<span style='color: red; font-size: 20px;'>Login Failed. Invalid Email/Password.</span>";//error message will prompt
                    }
                }
                else { //if cant fetch
                    echo "<span style='color: red; font-size: 20px;'>Login Failed. Invalid Email/Password.</span>";//error message will prompt
                }
            }
            else { //if $stmt cant execute
                echo "<span style='color: red; font-size: 20px;'>".$conn->error."</span>";//error message will prompt
            }
            $stmt->close();
            $conn->close();
        }
    }

    function post($content) {
        if($content == "") {
            echo "<span style='color: red; font-size: 20px;'>Content is empty</span>";
        }
        else if (strlen($content) > 255){
            echo "<span style='color: red; font-size: 20px;'>Your content exceeded 255 characters.</span>";
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
                echo "<span style='color: green; font-size: 18px;'>Post successfully posted!</span>"; //alert tag will be present if the statement is successfully submitted
            }
            else {
                echo "<span style='color: red; font-size: 18px;'>Failed to create Post. Reason: ".$conn->error."</span>"; //error message will be present if failed to execute the statement
            }
            $stmt->close();
            $conn->close();
        }
    }

    function deletePost($postId) {
        $conn = connectDb();

        $sql = "DELETE FROM comment WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $postId);

        if($stmt->execute()) {
            $stmt->close();
            echo "Comment successfully deleted!";

            $sql = "DELETE FROM post WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $postId);

            if($stmt->execute()) {
                echo "Post successfully deleted!";
            }
            else {
                echo $conn->error;
            }
        }
        else {
            echo $conn->error; //error message will prompt
        }
        $stmt->close();
        $conn->close();
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

    // function getComment($post_id) {
    //     $conn = connectDb();
    //     $postArr = array();

    //     $sql = "SELECT c.id, p.user_id, u.name, p.content, c.content FROM comment c
    //             INNER JOIN `user` u ON u.id = p.user_id
    //             INNER JOIN `post` p ON p.id = c.post_id";
                

    //     if($result = $conn->query($sql)) {
    //         while($row = $result->fetch_assoc()) {
    //             array_push($postArr, $row); //Return all post in array
    //         }
    //     }
    //     else {
    //         echo $conn->error; //error message will prompt
    //     }
    //     $conn->close();
    //     return $postArr;
    // }

    function postDeletePermission($postId) {
        $conn = connectDb();

        $sql = "SELECT count(*) FROM post WHERE id = ? and `user_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $postId, $_SESSION["userId"]);
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
        if($comment == ""){ //If comment is submitted without any values
            echo "<span style='color: red; font-size: 20px;'>Comment is empty.</span>";
        }
        else if(strlen($comment) > 150){ //If the comment exceeded 150 characters
            echo "<span style='color: red; font-size: 20px;'>Comment cannot exceed 150 characters.</span>";
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
                echo "Comment successfully posted!";
                echo "<script> location.href='post_content.php'; </script>";    
            }
            else{
                echo $conn->error; //error message will prompt
            }
            $stmt->close();
            $conn->close();
        }
    }

    function deleteComment($commentId) { //function to allow user to delete their comment
        $conn = connectDb();

        $sql = "DELETE FROM comment WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $commentId);

        if($stmt->execute()) {
            echo "Comment successfully deleted!";    
        }
        else{
            echo $conn->error; //error message will prompt
        }
        $stmt->close();
        $conn->close();
    }
