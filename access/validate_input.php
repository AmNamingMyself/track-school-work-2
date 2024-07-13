<?php

$signupErrors = array('username' => '', 'password' => '', 'email' => '');
$loginErrors = array('username' => '', 'password' => '');

// ON SIGN UP
if (isset($_POST['signup-submit'])) {

    //check email
    if (empty($_POST['email'])) {
        $signupErrors['email'] = 'Email cannot be null';
    } else {
        $email = ($_POST['email']);

        //check email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $signupErrors['email'] = 'Incorrect email format';
        }
    }

    $errors = validateNameAndPass();
    $signupErrors['username'] = $errors['username'];
    $signupErrors['password'] = $errors['password'];

    require('../db_connect.php');

    //check duplicates 
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (hasDuplicateEmail($email, $conn)) {
        $signupErrors['email'] = 'Email has been taken';
    }
    if (hasDuplicateUsername($username, $conn)) {
        $signupErrors['username'] = 'Username has been taken';
    }
}

//ON LOG IN
else if (isset($_POST['login-submit'])) {

    $loginErrors = validateNameAndPass();
}

function validateNameAndPass()
{
    $ownerErrors = array('username' => '', 'password' => '');

    //check username
    if (empty($_POST['username'])) {
        $ownerErrors['username'] = 'Username cannot be null';
    } else {
        $username = $_POST['username'];
        if (strlen($username) < 4) {
            $ownerErrors['username'] =  "Username cannot be less than 4 characters";
        }
    }

    //check password
    if (empty($_POST['password'])) {
        $ownerErrors['password'] =  'Password cannot be null';
    } else {
        $password = $_POST['password'];
        if (strlen($password) < 6) {
            $ownerErrors['password'] = "Password should at least contain 6 characters";
        }
    }
    return $ownerErrors;
}

function hasDuplicateEmail($email, $conn)
{
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function hasDuplicateUsername($username, $conn)
{
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return true;
    }
    return false;
}
