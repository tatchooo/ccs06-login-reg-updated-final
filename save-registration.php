<?php

require "config.php";

use App\User;

// Save the user information, and automatically login the user

try {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form fields
    $errors = [];
    if (empty($first_name)) {
        $errors[] = 'First name is required';
    }
    if (empty($last_name)) {
        $errors[] = 'Last name is required';
    }
    if (empty($email)) {
        $errors[] = 'Email address is required';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    } else if (strlen($password) < 8) {
        $errors[] = 'Password should be at least 8 characters long';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Password and Confirm Password do not match';
    }

    if (!empty($errors)) {
        // Display validation errors
        echo "<h1 style='color: red'>Please correct the following errors:</h1>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li style='color: red'>$error</li>";
        }
        echo "</ul>";
    } else {
        // Register the new user
        $user = new User();
        $result = $user->register($first_name, $last_name, $email, $password);

        if ($result) {
            // Set the logged in session variable and redirect user to index page
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user'] = [
                'id' => $result,
                'fullname' => $first_name . ' ' . $last_name,
                'email' => $email
            ];
            header('Location: index.php');
        } else {
            echo "<h1 style='color: red'>Error registering the user. Please go back.</h1>";
        }
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<h1 style='color: red'>" . $e->getMessage() . "</h1>";
}