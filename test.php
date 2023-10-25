<?php
session_start();

if (isset($_SESSION['teacher_name'])) {
// Session variable 'your_variable_name' exists.
echo $_SESSION['teacher_name'];

} else {
// Session variable 'your_variable_name' does not exist.
echo "not entered";
var_dump($_SESSION);
}

?>