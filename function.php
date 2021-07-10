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

    function register($name, $email, $password){ //Register function
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

            if($stmt->execute()){
                echo "Account created successfully!";    
            }
            else{
                echo "Error: ".$sql."<br>".mysqli_error($conn);
            }

            $stmt->close();
            $conn->close();
        }
    }

    function login($email, $password) {
        if($email == "" || $password == "") {
            echo "All field is Mandatory!";
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
        }
        else {
            $conn = connectDb();
            $sql = "SELECT `id`, password FROM user WHERE email = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);

            if($stmt->execute()) {
                $stmt->bind_result($id, $pass);
                if($stmt->fetch()) {
                    if(password_verify($password, $pass)) {
                        $_SESSION["userId"] = $id;
                    }
                    else {
                        echo "Login Failed. Invalid Username/Password.";
                    }
                }
                else {
                    echo "Login Failed. Invalid Username/Password.";
                }
            }
            else {
                echo $conn->error;
            }
        }
        $stmt->close();
        $conn->close();
    }
?>