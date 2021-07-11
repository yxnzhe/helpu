<?php
    function connectDb() { //Connect to database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $db = "helpu";

        $conn = mysqli_connect($servername, $username, $password, $db);

        if(!$conn) {
            die ("Connection failed: ". mysqli_connect_error()); //Return error message if there's error when connecting to the database
        }
        return $conn;
    }

    function register($name, $email, $password) { //function to allow user to register to the website
        if($name == "" || $email == "" || $password == "") { //Check whether all field is filled up with value
            echo "All field is Mandatory!";
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Check whether the email format is correct or not
            echo "Invalid email format!";
        }
        else { //If all field is with value and the email format is correct, then will create the user in our database
            $conn = connectDb();
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO `user` (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $password);

            if($stmt->execute()) {
                echo "Account created successfully!";    
            }
            else{
                echo "Error: ".$sql."<br>".$conn->error;
            }

            $stmt->close();
            $conn->close();
        }
    }

    function login($email, $password) { //function to allow user to login to the website
        if($email == "" || $password == "") { //to validate whether email or password is empty
            echo "All field is Mandatory!"; //if email or password is empty, error message will prompt
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //to validate whether user has entered a valid email format
            echo "Invalid email format!"; //if email format is invalid, this error message will prompt
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
                    }
                    else { //if password is invalid
                        echo "Login Failed. Invalid Username/Password."; //error message will prompt
                    }
                }
                else { //if cant fetch
                    echo "Login Failed. Invalid Username/Password."; //error message will prompt
                }
            }
            else { //if $stmt cant execute
                echo $conn->error; //error message will prompt
            }
            $stmt->close();
            $conn->close();
        }
    }

    function post($content) {
        if($content == "") {
            echo "Content is empty";
        }
        else if (strlen($content) > 255){
            echo "Your content exceeded 255 characters.";
        }
        else {
            $id = uniqid();
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
                echo 'too be decided line97 of function.php'; //alert tag will be present if the statement is successfully submitted
            }
            else {
                echo "Failed to create Post. Reason: ".$conn->error; //error message will be present if failed to execute the statement
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

    function addComment($postId, $comment){ //function to allow user to post their comments
        if($comment == ""){ //If comment is submitted without any values
            echo "Your cannot post an empty comment.";
        }
        else if(strlen($comment) > 150){ //If the comment exceeded 150 characters
            echo "Your comment exceeded 150 characters.";
        }
        else{
            $conn = connectDb(); //connect to databsae
            $commentId = uniqid(); //Auto generate a string + number comment id
            $user_id = $_SESSION["userId"]; //Get user_id from session that was set when user login

            date_default_timezone_set("Asia/Kuala_Lumpur"); //Set the timezone to Kuala Lumpur/ Malaysia/ Asia
            $datetime = new DateTime(); //to get the current time
            $dt= $datetime->format('Y-m-d\TH:i:s'); //to chg the format of the datetime
            $dateTime = date("Y-m-d H:i:s", strtotime($dt)); //to chg the datetime to string

            $sql = "INSERT INTO comment (id, `user_id`, post_id, content, created_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $commentId, $userId, $postId, $comment, $dateTime);

            if($stmt->execute()) {
                echo "Comment successfully posted!";    
            }
            else{
                echo $conn->error; //error message will prompt
            }
            $stmt->close();
            $conn->close();
        }
    }

    function deleteComment($commentId){ //function to allow user to delete their comment
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
?>