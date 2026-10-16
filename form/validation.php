<?php

if ($_SERVER['REQUEST_METHOD'] === "POST")
    {
$name = htmlspecialchars($_POST["name"]);
if(trim($_POST['name']) == "")
    {
    echo "Enter your name";
    }
    else{
        echo "Welcome " . htmlspecialchars($name) . ".<br>";
    }

$email = htmlspecialchars($_POST["email"]);
if(trim($_POST['email']) == "")
    {
        echo "Enter your email";
    }else 
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            echo "Invalid email format";
        else {
            echo "Your email is " . htmlspecialchars($email) . ".";
        }
    }

$password = $_POST["password"];
if(trim($_POST['password']) == "")
    {
        echo "Enter password";
        exit;
    }
$hashedPassword = password_hash( $password, PASSWORD_DEFAULT);
echo $hashedPassword;

if(empty($_POST['gender']))
    {
        echo"Choose a radio button";
        exit;
    }
     $gender =htmlspecialchars($_POST["gender"]);    
    echo "Your gender is " . htmlspecialchars($gender) . ".<br>";

    }


?>