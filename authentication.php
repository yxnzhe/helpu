<?php
    session_start();
    require_once "function.php";
    
    if(isset($_POST["type"])){
        if($_POST["type"] == "register"){
            $name = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirmPassword = $_POST["confirmPassword"];

            if($name == "" || $email == "" || $password == "") { //Check whether all field is filled up with value
                echo "<span class='errorMsg'>All field is Mandatory!</span>";
            }
            else if(!checkEmail($email)){ //if the email exist in our database
                echo "<span class='errorMsg'>Email Address Already Exist!</span>"; //print error message
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
                    $_SESSION["userId"] = $userId; //if successfully register will set the user id to userId's session
                }
                else{ //if stmt can't execute
                    echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
                }
    
                $stmt->close();
                $conn->close();
            }
        }
        else if($_POST["type"] == "login"){
            $email = $_POST["email"];
            $password = $_POST["password"];

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
    }
?>