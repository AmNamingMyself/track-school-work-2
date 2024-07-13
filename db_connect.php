<?php

$conn = mysqli_connect('localhost', 'sandziso', 'test1234', 'student_progress');

if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
