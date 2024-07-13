<!DOCTYPE html>
<html lang="en">
<?php

include('templates/header.php');
//require('db_connect.php');

$subjects = [];
$fail = [];

if ($conn) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT `name`, `sub_average` FROM `subject` WHERE `user_id` = $user_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        //user has subjects already on database
        $subjects = mysqli_fetch_all($result);
    } else {
        $fail['added'] = "You Don't have any subjects to update from";
    }
}

$update = [];

//First form initiation 
# getting and listing the required subjects
if (isset($_POST['update-subject'])) {

    if (empty($_POST['subject'])) {
        echo '<div class="fail">';
        echo  '<p>Subject cannot be null.</p>';
        echo '</div>';

        echo '<div class="no-content">';
        echo '<img src="img/enemy 2.png" alt="Empty">';
        echo '<p>';
        echo 'Refresh and try again. Click <a href="update-subject.php">Here</a> and try again!';
        echo '</p>';
        echo '</div>';
        die();
    }

    $subjects = [];
    $fail = [];

    $update['subject'] =  $_POST['subject'];
}



$success = [];

if (isset($_POST['upd-update'])) {

    if (empty($_POST['upd-subject']) || empty($_POST['upd-avg'])) {
        echo '<div class="fail">';
        echo  '<p>Subject OR Average values cannot be null.</p>';
        echo '</div>';

        echo '<div class="no-content">';
        echo '<img src="img/enemy 2.png" alt="Empty">';
        echo '<p>';
        echo 'Refresh and try again. Click <a href="update-subject.php">Here</a> and try again!';
        echo '</p>';
        echo '</div>';
        die();
    }

    //stop updates
    $fail = [];
    $update = [];

    $subName1 = mysqli_real_escape_string($conn, $_POST['upd-subject']);
    $subAvg1 = mysqli_real_escape_string($conn, $_POST['upd-avg']);

    $subId = $_SESSION['sub_id'];
    $userId = $_SESSION['user_id'];

    $sql = "UPDATE `subject` SET `name` = '$subName1',  `sub_average` = $subAvg1
            WHERE `sub_id` = $subId AND `user_id` = $user_id";

    if (mysqli_query($conn, $sql)) {
        //update completed correctly
        $success['added'] = "Book Updated Successfully.";
    }
}

?>

<div class="wrapper">

    <?php
    if (array_filter($subjects)) {
        echo '<h2>Select Subject To Update</h2> <br>';
        echo '<form action="update-subject.php" method="post" class="main-form">';
        echo '<input name="subject" list="subjects" autocomplete="off" placeholder="subject" 
                title="Choose from the provided options">';
        echo '<datalist id="subjects">';


        foreach ($subjects as $subject) {
            echo '<option value="' . $subject[0] . '"></option>';
        }

        echo '</datalist> <br>';
        echo '<input type="submit" value="Update Subject" name="update-subject">';
        echo '</form>';
    } else  if (array_filter($fail)) {
        echo '<div class="fail">';
        echo  '<p>' . htmlspecialchars($fail['added']) . '.</p>';
        echo '</div>';
    ?>

        <div class="no-content">
            <img src="img/enemy 2.png" alt="Empty">
            <p>
                You don't have any subjects as of yet.
                Click <a href="add-subject.php">Here</a> to add a Subject
            </p>
        </div>
    <?php } ?>


    <?php
    if (array_filter($update)) {
        $user_id = $_SESSION['user_id'];
        $subject =  $update['subject'];

        //echo $subject;

        $query = "SELECT sub_id, sub_average FROM `subject` WHERE `name` = '$subject' AND `user_id` = $user_id";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $subject_id_avg = mysqli_fetch_row($result);

            //var_dump($subject_id_avg);

            $_SESSION['sub_id'] = intval($subject_id_avg[0]);
            $_SESSION['sub_name'] = $subject;
            $_SESSION['sub_avg'] = floatval($subject_id_avg[1]);

    ?>
            <h2>From : To </h2>
            <form action="update-subject.php" method="post" class="main-form">
                <?php echo $subject ?>: <br>
                <input type="text" name="upd-subject" autocomplete="off" placeholder="Subject Name"> <br>
                <?php echo $_SESSION['sub_avg'] ?>: <br>
                <input type="number" name="upd-avg" min="0" max="100" placeholder="Average"><br>
                <input type="submit" value="Update" name="upd-update">
            </form>

    <?php
        } else {
            //TODO: make alert user that entered subject does not exist 

            echo '<div class="fail">';
            echo '<p> \'' . $subject . '\' does not exist and cannot be updated. </p>';
            echo '</div>';

            echo '<div class="no-content">';
            echo '<img src="img/enemy 2.png" alt="Empty">';
            echo '<p>';
            echo 'You don\'t have any subjects as of yet. Click <a href="update-subject.php">Here</a> and try again!';
            echo '</p>';
            echo '</div>';
        }
    } ?>

    <?php
    if (array_filter($success)) {
        $_SESSION['success'] = $success['added'];
        header('location: update-subject.php');
    } ?>

    <?php if (isset($_SESSION['success'])) {
        unset($_SESSION['success']);
        echo 'updated';

    ?>
        <div class="success">
            <p><?php echo htmlspecialchars($success['added']) . '.' ?></p>
        </div>
    <?php    }
    ?>

</div>

<?php include('templates/footer.php') ?>

</html>