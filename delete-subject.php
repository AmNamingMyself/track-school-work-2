<!DOCTYPE html>
<html lang="en">
<?php
include('templates/header.php');
//require('db_connect.php');

$subjectsArr = [];
$fail = [];
$success = [];

if ($conn) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT `name`, `sub_average` FROM `subject` WHERE `user_id` = $user_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        //user has subjects already on database
        $subjectsArr = mysqli_fetch_all($result);

        $_SESSION['hasSubjects'] = "yes";
    } else {
        $fail['added'] = "You Don't have any subjects to update from";
    }
}

//collect selected data from the form
if (isset($_POST['submit'])) {
    $subjects = $_POST['options'];

    //var_dump($subjects);

    //create an array of  'quoted' string of subjects
    $quotedSubjects = array_map(function ($subject) use ($conn) {
        return "'" . $conn->real_escape_string($subject) . "'";
    }, $subjects);

    //convert the array of quotedSubjects into a comma-separated String 
    //$subjectList = implode(",", $subjects);
    //echo $subjectList;

    $subjectList = "";

    for ($i = 0; $i < count($quotedSubjects); $i++) {
        if (count($quotedSubjects) < $i) {
            $subjectList .= ($quotedSubjects[$i] . ",");
        } else
            $subjectList .= $quotedSubjects[$i];
    }

    $user_id = $_SESSION['user_id'];

    //create an sql statement
    $sql = "DELETE FROM `subject` WHERE `name` IN ( $subjectList) AND `user_id` = $user_id";

    if (mysqli_query($conn, $sql)) {
        $success['added'] = 'Rows deleted Successfully';
    } else {
        echo 'Error deleting rows: ' . $conn->error;
    }
}

function createForm($subjectArr)
{
    echo '<h2>Delete Subject</h2>';
    echo '<form action="delete-subject.php" method="post" class="delete-form">';
    //echo '<label for="subjects">Select options: </label><br>';
    foreach ($subjectArr as $subject) {
        echo '<div class="item">';
        echo '<input type="checkbox" name="options[]" 
                value="' . htmlspecialchars($subject[0]) . '" id="' . htmlspecialchars($subject[0]) . '">';
        echo '<label for="' . htmlspecialchars($subject[0]) . '">' . htmlspecialchars($subject[0]) . '</label> <br>';
        echo '</div>';
    }
    echo '<input type="submit" value="Delete" name="submit">';

    echo '</form>';
}

?>

<div class="wrapper">

    <?php
    if (isset($_SESSION['hasSubjects'])) {
        createForm($subjectsArr);
        unset($_SESSION['hasSubjects']);
    } else {
        echo '<div class="fail">';
        echo '<p> You don\'t have any Subject yet </p>';
        echo '</div>';

        echo '<div class="no-content">';
        echo '<img src="img/enemy 2.png" alt="Empty">';
        echo '<p>';
        echo 'To delete a subject, you first need to add the Subject. Click <a href="add-subject.php">Here</a> to add a new subject!';
        echo '</p>';
        echo '</div>';
    }
    ?>

    <?php if (isset($success['added'])) { ?>
        <div class="success">
            <p><?php echo htmlspecialchars($success['added']) . '.' ?></p>
        </div>
    <?php } ?>
</div>

<?php include('templates/footer.php'); ?>

</html>