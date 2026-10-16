<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($name)) {
        echo "Name is required.";
        exit;
    }

    if (empty($email)) {
        echo "Email is required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Incorrect email.";
        exit;
    }

    if (empty($password)) {
        echo "Password is required.";
        exit;
    }

    if (strlen($password) < 6) {
        echo "Password must be at least 6 characters long.";
        exit;
    }

    echo "Registration successful for $name!";
} else {
    echo "Please submit the form.";
}
?>