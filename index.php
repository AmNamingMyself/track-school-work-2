<!DOCTYPE html>
<html lang="en">

<?php

include('templates/header.php');
//require('db_connect.php');

$subjects = [];
$fail = [];

if (isset($_SESSION['username'])) {

    //create a query
    $user_id = $_SESSION['user_id'];
    $query = "SELECT `name`, `sub_average` FROM `subject` where user_id = $user_id ORDER BY `sub_average` DESC";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        //user has subjects already on database
        $subjects = mysqli_fetch_all($result);
    } else {
        $fail['added'] = "You Don't have any subjects to View";
    }
}

//
?>

<div class="wrapper">

    <?php if ($subjects) { ?>
        <table class="sub_table">
            <tr>
                <th>Subject</th>
                <th>Average</th>
            </tr>

            <?php
            foreach ($subjects as $subject) {

                echo '<tr>';

                echo '<td> ' . htmlspecialchars($subject['0']) . ' </td>';
                echo '<td> ' . htmlspecialchars($subject['1']) . ' </td>';

                echo '</tr>';
            }
            ?>
        </table>
    <?php } else {
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



</div>


<?php include('templates/footer.php'); ?>

</html>