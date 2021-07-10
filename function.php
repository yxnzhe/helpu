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

            if($stmt->execute()) {
                echo "Account created successfully!";    
            }
            else{
                echo "Error: ".$sql."<br>".mysqli_error($conn);
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
?>