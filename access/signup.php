<?php

session_start();

require('validate_input.php');

if (isset($_POST['signup-submit'])) {

    if (array_filter($signupErrors)) { //returns true if $signupErrors array is not empty
        //echo 'errors in the form';
    } else {
        //returns false if $signupErrors array has values in it
        //echo 'form is valid';

        //add user into database
        $sql = "INSERT INTO users(username, `password`, email) 
            VALUES ('$username', '$password', '$email')";

        //save to bd and check
        if (mysqli_query($conn, $sql)) {
            //success

            $query = "SELECT id FROM users WHERE username = '$username' AND `password` = '$password'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                //get data user login id
                $user = mysqli_fetch_row($result);
                //returns an associative array: col_nul => value

                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user[0];

                header('location: ../index.php');
            } else {
                echo 'An unknown error occurred and we are unable to log you in.';
            }
        } else {
            //error
            echo 'Query error: ' . mysqli_error($conn);
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
    <title>Signup</title>

</head>

<body>
    <h2>Sign Up</h2>
    <form action="signup.php" method="post">
        Username: <br>
        <input type="text" name="username"> <br>
        <?php
        if (isset($signupErrors['username'])) {
            echo '<p class="error">' . htmlspecialchars($signupErrors['username']) . '</p>';
        }
        ?>
        Password: <br>
        <input type="password" name="password"> <br>
        <?php
        if (isset($signupErrors['password'])) {
            echo '<p class="error">' . htmlspecialchars($signupErrors['password']) . '</p>';
        }
        ?>
        Email: <br>
        <input type="text" name="email"> <br>
        <?php
        if (isset($signupErrors['email'])) {
            echo '<p class="error">' . htmlspecialchars($signupErrors['email']) . '</p>';
        }
        ?>
        <input type="submit" name="signup-submit" value="Sign Up" class="btn" />
        <a href="login.php">Log in instead</a>
    </form>

</body>

</html>