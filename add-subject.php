<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>


<?php

$errors = [];
$success = [];

if (isset($_POST['add-sub'])) {

    //require('db_connect.php');
    if (empty($_POST['subject-name'])) {
        $errors['null'] = 'Subject name cannot be null';
    } else {

        //1. get name and age

        $subName = mysqli_real_escape_string($conn, $_POST['subject-name']);
        $avg = floatval($_POST['average']);
        $user_id = $_SESSION['user_id'];


        //2. check is duplicate in db
        if (hasDuplicate($conn, $subName, $user_id)) {
            $errors['duplicate'] = 'Subject already exists.';
        } else {
            //add subName, avg, user_id
            $sql = "INSERT INTO `subject`(`name`, `sub_average`,`user_id`)
                VALUES('$subName', $avg, $user_id)";

            if (mysqli_query($conn, $sql)) {
                $success['added'] = 'Subject added Successfully';
            }
        }
    }
}

function hasDuplicate($conn, $name, $user_id)
{
    $query = "SELECT `name`, `sub_average` FROM `subject` where `name` = '$name' AND `user_id` = $user_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        //have a duplicate 
        return true;
    } else {
        //no duplicate
        return false;
    }
}

?>

<div class="wrapper">
    <h2>Add New Subject</h2>
    <form action="add-subject.php" method="post" class="main-form">
        Subject name: <br>
        <input type="text" name="subject-name"> <br>
        <?php
        if (isset($errors['null'])) {
            echo '<p class="error">' . htmlspecialchars($errors['null']) . '</p>';
        }
        ?>
        Subject average <br>
        <input type="number" name="average" min="0" max="100"> <br>
        <?php
        if (isset($errors['duplicate'])) {
            echo '<p class="error"> ' . htmlspecialchars($errors['duplicate']) . ' </p>';
        }
        ?>
        <input type="submit" value="Add Subject" name="add-sub" class="btn">
    </form>

    <?php if (isset($success['added'])) { ?>
        <div class="success">
            <p><?php echo htmlspecialchars($success['added']) . '.' ?></p>
        </div>
    <?php } ?>
</div>

<?php include('templates/footer.php'); ?>

</html>