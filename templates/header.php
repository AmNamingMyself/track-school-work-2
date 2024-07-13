<?php

session_start();
//if user is not logged in
if (!isset($_SESSION['username'])) {
    header('location: access/login.php');
}

require("db_connect.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/base-style.css">
    <link rel="stylesheet" href="style/header_style.css">
    <link rel="stylesheet" href="style/index-style.css">
    <link rel="stylesheet" href="style/add-subject.css">
    <link rel="stylesheet" href="style/delete-subject.css">

    <title>School Progress</title>
</head>

<body>
    <div class="menu">
        <div class="user">

            <?php
            if (isset($_SESSION['username'])) {
                $user_id = $_SESSION['user_id'];
                $query = "SELECT `name`, `sub_average` FROM `subject` where user_id = $user_id ORDER BY `sub_average` DESC";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {

                    //echo 'has subjects';
                    $sql = "SELECT AVG(sub_average) FROM `subject` WHERE user_id = $user_id";
                    $result = mysqli_query($conn, $sql);

                    $row = mysqli_fetch_row($result);

                    echo '<div class="circle-avg">' . htmlspecialchars(intval($row[0])) . '</div>';
                } else {
                    echo '<img src="img/person2.png" alt="user img" class="user_img">';
                }
                echo  '<p>' . $_SESSION['username'] . '</p>';
            }
            ?>
        </div>
        <nav>
            <a href="index.php">View</a>
            <a href="add-subject.php">Add</a>
            <a href="update-subject.php">Update</a>
            <a href="delete-subject.php">Delete</a>
        </nav>
        <form action="logout.php" method="post">
            <input type="submit" name="logout-submit" value="Log Out" class="btn" id="logout_btn" />
        </form>
    </div>