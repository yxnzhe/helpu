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

    function checkEmail($email) { //function to check whether the email exist in our database or not
        $conn = connectDb();
        $sql = "SELECT count(*) FROM `user` where email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);

        if($stmt->execute()) {
            $stmt->bind_result($count);
            $stmt->fetch();
            
            if($count > 0) { //count greater than 0 means that the email exist in our database
                $uniqEmail = false; //return the email is not unique or uniqEmail is false
                // echo "<span class='errorMsg'>Email Address Already Exist!</span>"; //print error message
            }
            else { //count smaller than 0 means that the email does not exist in our database
                $uniqEmail = true; //return the email is unique or uniqEmail is true
            }
        }
        else{ //if stmt can't execute
            echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $uniqEmail;
    }

    function post($content) { //function to create post in database
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
            
            if($stmt->execute()) { //statement execute successfully
                $msg = "<span class='successMsg'>Post successfully posted!</span>"; //alert tag will be present if the statement is successfully submitted
            }
            else { //if $stmt cant execute
                $msg = "<span class='errorMsg'>Failed to create Post. Reason: ".$conn->error."</span>"; //error message will be present if failed to execute the statement
            }
            $stmt->close();
            $conn->close();
            return $msg;
        }
    }

    function deletePost($postId) { //function to delete post from database
        $conn = connectDb();
        $msg = "";
        $sql = "DELETE FROM comment WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $postId);

        if($stmt->execute()) { //execute the stmt
            $stmt->close();

            $sql = "DELETE FROM post WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $postId);

            if($stmt->execute()) { //successfully execute
                echo "<script>alert('Post deleted!')</script>"; //error message will prompt
                echo "<script> location.href='index.php' </script>";
            }
            else { //error executing stmt
                $msg = "<span class='errorMsg'>You cannot delete the post</span>";
            }
        }
        else { //error executing stmt
            $msg = "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $msg;
    }

    function getAllPosts() { //function to retrieve all posts from database
        $conn = connectDb();
        $postArr = array();

        $sql = "SELECT p.id, p.user_id, u.name, p.content FROM post p
                INNER JOIN `user` u ON u.id = p.user_id
                ORDER BY p.created_at DESC"; //Descending order by created_at

        if($result = $conn->query($sql)) { //successfully executed stmt
            while($row = $result->fetch_assoc()) {
                array_push($postArr, $row); //Return all post in array
            }
        }
        else { //error executing stmt
            echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $conn->close();
        return $postArr;
    }

    function getPost($postId) { //function to get details of a single post
        $conn = connectDb();
        $postArr = array();

        $sql = "SELECT p.user_id, u.name, p.content FROM post p
                INNER JOIN `user` u ON u.id = p.user_id
                WHERE p.id = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",  $postId);

        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($userId, $name, $content);
            $stmt->fetch();
            $post = array("user_id" => $userId, "name" => $name, "content" => $content);
            array_push($postArr, $post);
        }
        else { //error when executing stmt
            echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $postArr;
    }

    function getComment($postId) { //function to retrieve all comments for a single post
        $conn = connectDb();
        $commentArr = array();

        $sql = "SELECT u.id, u.name, c.id, c.content  FROM comment c
                INNER JOIN `user` u ON u.id = c.user_id
                WHERE c.post_id = ?
                ORDER BY c.created_at DESC"; //Descending order by created_at
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",  $postId);

        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($userId, $username, $commentId, $content);
            while($stmt->fetch()){ //get all return value
                $comment = array("postId" => $postId, "userId" => $userId, "username" => $username, "commentId" => $commentId, "content" => $content);
                array_push($commentArr, $comment); //push all array value into comment array
            }
        }
        else {//error executing stmt
            echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $commentArr;
    }

    function postDeletePermission($postId) { //check whether the user have permission to delete the post (if the user is the owner of the post)
        $conn = connectDb();

        $sql = "SELECT count(*) FROM post WHERE id = ? and `user_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $postId, $_SESSION["userId"]);

        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($count);
            $stmt->fetch();

            if($count > 0) { //if count is greater than 0 means the user have permission to delete/ is the owner of the post
                $postPermission = true;
            }
            else { //else count is smaller than 0 means that the user do not have permission to delete/ not the owner of the post
                $postPermission = false;
            }
        }
        else{ //error executing stmt
            echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $postPermission;
    }

    function commentDeletePermission($commentId) { //check whether the user have permission to delete the comment (if the user is the owner of the comment)
        $conn = connectDb();

        $sql = "SELECT count(*) FROM comment WHERE id = ? and `user_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $commentId, $_SESSION["userId"]);
        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($count);
            $stmt->fetch();

            if($count > 0) { //if count is greater than 0 means the user have permission to delete/ is the owner of the comment
                $commentPermissionm = true;
            }
            else { //else count is smaller than 0 means that the user do not have permission to delete/ not the owner of the comment
                $commentPermissionm = false;
            }
        }
        else{ //error executing stmt
            echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $commentPermissionm;
    }

    function postExist($postId) { //check whether the post exist in database using post's id
        $conn = connectDb();

        $sql = "SELECT count(*) FROM post WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $postId);
        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($count);
            $stmt->fetch();

            if($count > 0) { //if count is greater than 0 means that the post exist in our database
                $postExist = true;
            }
            else { //else if count is smaller than 0 means that the post do not exist in our database
                $postExist = false;
            }
        }
        else{ //error executing stmt
            echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
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

            if($stmt->execute()) { //successfully executed stmt
                echo "<script> location.href='post_content.php?post=".$postId."' </script>";    
            }
            else{ //error executing stmt
                $msg = "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
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

        if($stmt->execute()) {} //successfully executed stmt
        else{ //error executing stmt
            $msg = "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $msg;
    }
