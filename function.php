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
        $sql = "SELECT count(*) FROM `user` where email = ?"; //To get the number of email from user table based on the email inputted by the user
        $stmt = $conn->prepare($sql); //to prepare
        $stmt->bind_param("s", $email); //to bind the parameter of email to query

        if($stmt->execute()) { //to execute
            $stmt->bind_result($count); //to bind the result of count to count
            $stmt->fetch(); //to fetch
            
            if($count > 0) { //count greater than 0 means that the email exist in our database
                $uniqEmail = false; //return the email is not unique or uniqEmail is false
                // echo "<span class='errorMsg'>Email Address Already Exist!</span>"; //print error message
            }
            else { //count smaller than or is 0 means that the email does not exist in our database
                $uniqEmail = true; //return the email is unique or uniqEmail is true
            }
        }
        else { //if stmt can't execute
            echo "<span class='errorMsg'>".$conn->error."</span>"; //error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $uniqEmail;
    }

    function post($content) { //function to create post in database
        $msg = ""; 
        if($content == "") { //if the content of post is empty
            $msg = "<span class='errorMsg'>Content is empty</span>"; //error message will prompt
        }
        else if(strlen($content) > 255){ //if content more than 255 characters
            $msg = "<span class='errorMsg'>Your content exceeded 255 characters.</span>"; //error message will prompt
        }
        else { //if content meets all the requirements
            $id = md5(microtime());; //get unique id
            $userId = $_SESSION["userId"]; //get userId from session's userId

            date_default_timezone_set("Asia/Kuala_Lumpur"); //to set the timezone to KL
            $datetime = new DateTime(); //to get the current time
            $dt= $datetime->format('Y-m-d\TH:i:s'); //to chg the format of the datetime
            $newDateTime = date("Y-m-d H:i:s", strtotime($dt)); //to chg the datetime to string

            $conn = connectDb();
            $sql = "INSERT INTO `post` (id, `user_id`, content, created_at) VALUES (?, ?, ?, ?)"; //to insert id, userid, content, created_at into post table

            $stmt = $conn->prepare($sql); //to prepare
            $stmt->bind_param("ssss", $id, $userId, $content, $newDateTime); //to bind the parameter of id, userid, content, and datetime into the statement
            
            if($stmt->execute()) { //statement execute successfully
                $msg = "<span class='successMsg'>Post successfully posted!</span>"; //alert tag will be present if the statement is successfully submitted
            }
            else { //if $stmt cant execute
                $msg = "<span class='errorMsg'>Failed to create Post. Reason: ".$conn->error."</span>"; //error message will be present if failed to execute the statement
            }
            $stmt->close();
            $conn->close();
        }
        return $msg;
    }

    function deletePost($postId) { //function to delete post from database
        $msg = "";
        $conn = connectDb();        
        $sql = "DELETE FROM comment WHERE post_id = ?"; //to delete from comment table based on the post_id
        $stmt = $conn->prepare($sql); //to prepare
        $stmt->bind_param("s", $postId); //to bind the parameter of postId to the statement

        if($stmt->execute()) { //execute the stmt
            $stmt->close();

            $sql = "DELETE FROM post WHERE id = ?"; //to delete from post table based on the id 
            $stmt = $conn->prepare($sql); //to preapre
            $stmt->bind_param("s", $postId); //to bind the parameter of postId to the statement

            if($stmt->execute()) { //successfully execute
                echo "<script>alert('Post deleted!')</script>"; //error message will prompt
                echo "<script> location.href='index.php' </script>"; //to redirect user to index.php
            }
            else { //error executing stmt
                $msg = "<span class='errorMsg'>You cannot delete the post</span>"; //erro message will prompt
            }
        }
        else { //error executing stmt
            $msg = "<span class='errorMsg'>".$conn->error."</span>"; //error message will prompt
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
                ORDER BY p.created_at DESC"; 
                //Get postid, post userId, username, and post content from post table by inner join user table on userid and postid order by descending order of post created_at

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
        //To get post userid, user name, post content from post table inner join user table on userid and post userid based on the postid
        $stmt = $conn->prepare($sql); //to prepare
        $stmt->bind_param("s",  $postId); //to bind the parameter of postId to the statement

        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($userId, $name, $content); //to bind the results to userId, name, and content
            $stmt->fetch(); //to fetch
            $post = array("user_id" => $userId, "name" => $name, "content" => $content); //to assign the results to an array with key
            array_push($postArr, $post); //push the array to postArr
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
                ORDER BY c.created_at DESC"; 
        //To get userid, user name, contentid, content from comment table inner join user table on userid and comment userid based on the comment postid order by descending order of comment created_at
        $stmt = $conn->prepare($sql); //to preapre
        $stmt->bind_param("s",  $postId); //to bind postId into stmt

        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($userId, $username, $commentId, $content); //to bind result to userId, username, commentId, and content based on the sql
            while($stmt->fetch()){ //get all return value
                $comment = array("postId" => $postId, "userId" => $userId, "username" => $username, "commentId" => $commentId, "content" => $content); //assign result into an array with key
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
        $sql = "SELECT count(*) FROM post WHERE id = ? and `user_id` = ?"; //get count of all from post table based on id and userid
        $stmt = $conn->prepare($sql); //to preapre
        $stmt->bind_param("ss", $postId, $_SESSION["userId"]); //to bind postId and session's userId to stmt

        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($count); //to bind result to count
            $stmt->fetch(); //to fetch

            if($count > 0) { //if count is greater than 0 means the user have permission to delete/ is the owner of the post
                $postPermission = true;
            }
            else { //else count is smaller than 0 means that the user do not have permission to delete/ not the owner of the post
                $postPermission = false;
            }
        }
        else{ //error executing stmt
            echo "<span class='errorMsg'>".$conn->error."</span>"; //error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $postPermission;
    }

    function commentDeletePermission($commentId) { //check whether the user have permission to delete the comment (if the user is the owner of the comment)
        $conn = connectDb();
        $sql = "SELECT count(*) FROM comment WHERE id = ? and `user_id` = ?"; //to get count of all from comment table based on the id and userid
        $stmt = $conn->prepare($sql); //to prepare
        $stmt->bind_param("ss", $commentId, $_SESSION["userId"]); //to bind commentId and session's userId

        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($count); //to bind result to count
            $stmt->fetch(); //to fetch

            if($count > 0) { //if count is greater than 0 means the user have permission to delete/ is the owner of the comment
                $commentPermissionm = true;
            }
            else { //else count is smaller than 0 means that the user do not have permission to delete/ not the owner of the comment
                $commentPermissionm = false;
            }
        }
        else{ //error executing stmt
            echo "<span class='errorMsg'>".$conn->error."</span>"; //error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $commentPermissionm;
    }

    function postExist($postId) { //check whether the post exist in database using post's id
        $conn = connectDb();
        $sql = "SELECT count(*) FROM post WHERE id = ?"; //to get count of all from post table based on the id
        $stmt = $conn->prepare($sql); //to prepapre
        $stmt->bind_param("s", $postId); //to bind postid

        if($stmt->execute()) { //successfully executed stmt
            $stmt->bind_result($count); //to bind result to count
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
            $msg = "<span class='errorMsg'>Comment is empty.</span>"; //error message will prompt
        }
        else if(strlen($comment) > 150){ //If the comment exceeded 150 characters
            $msg = "<span class='errorMsg'>Comment cannot exceed 150 characters.</span>"; //error message will prompt
        }
        else{
            $conn = connectDb(); //connect to databsae
            $commentId = md5(microtime()); //Auto generate a string comment id
            $userId = $_SESSION["userId"]; //Get user_id from session that was set when user login

            date_default_timezone_set("Asia/Kuala_Lumpur"); //Set the timezone to Kuala Lumpur/ Malaysia/ Asia
            $datetime = new DateTime(); //to get the current time
            $dt= $datetime->format('Y-m-d\TH:i:s'); //to chg the format of the datetime
            $dateTime = date("Y-m-d H:i:s", strtotime($dt)); //to chg the datetime to string

            $sql = "INSERT INTO comment (id, `user_id`, post_id, content, created_at) VALUES (?, ?, ?, ?, ?)"; //to insert value of id, user_id, post_id, content, and created_at into comment table
            $stmt = $conn->prepare($sql); //to prepare
            $stmt->bind_param("sssss", $commentId, $userId, $postId, $comment, $dateTime); //to bind commentId, userId, postId, comment, and dateTime to stmt

            if($stmt->execute()) { //successfully executed stmt
                echo "<script> location.href='post_content.php?post=".$postId."' </script>"; //will redirect 
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
        $sql = "DELETE FROM comment WHERE id = ?"; //to delete from comment table based on the id
        $stmt = $conn->prepare($sql); //to prepare
        $stmt->bind_param("s", $commentId); //to bind commentId to stmt

        if($stmt->execute()) { //successfully executed stmt
            echo "<script>alert('Comment deleted!')</script>"; //alert tag will prompt
        } 
        else{ //error executing stmt
            $msg = "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
        }
        $stmt->close();
        $conn->close();
        return $msg;
    }
