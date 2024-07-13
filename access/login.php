<?php

session_start();

require('validate_input.php');
require('../db_connect.php');

if (isset($_POST['login-submit'])) {

    if (array_filter($loginErrors)) { //returns true if $signupErrors array is not empty
        //echo 'errors in the form';
    } else {
        //returns false if $signupErrors array has values in it
        //echo 'form is valid';

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $query = "SELECT id FROM users WHERE username = '$username' AND `password` = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            //echo 'access granted';
            //get data user login id
            $user = mysqli_fetch_row($result);
            //returns an associative array: col_nul => value

            //var_dump($user);

            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user[0];

            header('location: ../index.php');
        } else {
            $loginErrors['password'] = 'Invalid log in details';
        }
    }

    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="access_style.css">
    <title>Log In</title>
</head>

<body>

    <h2>Log In</h2>
    <form action="login.php" method="post">
        Username: <br>
        <input type="text" name="username"> <br>
        <?php
        if (isset($loginErrors['username'])) {
            echo '<p class="error">' . htmlspecialchars($loginErrors['username']) . '</p>';
        }
        ?>
        Password: <br>
        <input type="password" name="password"> <br>
        <?php
        if (isset($loginErrors['password'])) {
            echo '<p class="error">' . htmlspecialchars($loginErrors['password']) . '</p>';
        }
        ?>
        <input type="submit" name="login-submit" value="Log In" class="btn" />
        <a href="signup.php">Create an account</a>
    </form>


</body>

</html>