<?php
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
                    return $userId;
                    $_SESSION["userId"] = $userId;
                    $_SESSION["isLogin"] = true; 
                    // echo "<span class='successMsg'>Register Successfully!</span>";
                }
                else{ //if stmt can't execute
                    echo "<span class='errorMsg'>".$conn->error."</span>";//error message will prompt
                }
    
                $stmt->close();
                $conn->close();
            }
        }
    }
?>