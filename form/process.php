<?php
$name = $_POST["name"];
$name = trim( $_POST["name"]?? '');
if(empty($name))
    {
        echo "Name is required.";
    exit; 
    }

$surname = $_POST["surname"];
$surname = trim( $_POST["surname"]?? '');
if(empty($surname))
    {
        echo "Surname is required.";
    exit; 
    }
$dateOfBirth= $_POST["dateOfBirth"];
$dateOfBirth = trim( $_POST["dateOfBirth"]?? '');
if(empty($dateOfBirth))
    {
        echo "Date of Birth is required";
    exit; 
    }
$qaulif = $_POST["qaulif"];
$qaulif = trim( $_POST["qaulif"]?? '');
if(empty($qaulif))
    {
        echo "Qaulification is required.";
    exit; 
    }
$email = $_POST["email"];
$email = trim( $_POST["email"]?? '');
if(empty($email))
    {
        echo "email is required.";
    exit; 
    if(filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        echo "Valid email";
        ;
    }else{
        echo "incorrect email";
    }
    }

$gender = $_POST["gender"];
$gender = trim( $_POST["gender"]?? '');
if(empty($gender))
    {
        echo "Geneder is required.";
    exit; 
    }
$submit = $_POST["submit"];
echo"Hi $name $surname .<br> Your are born in  $dateOfBirth. <br> You are doing a degaree in $qaulif.<br>";

?>