<?php
<<<<<<< HEAD
session_start();

if (isset($_SESSION['teacher_name'])) {
// Session variable 'your_variable_name' exists.
echo $_SESSION['teacher_name'];

} else {
// Session variable 'your_variable_name' does not exist.
echo "not entered";
var_dump($_SESSION);
}

=======
if (isset($_SESSION['teacher_cnic'])) {
// Session variable 'your_variable_name' exists.
echo $_SESSION['teacher_cnic'];
} else {
// Session variable 'your_variable_name' does not exist.
echo "not entered";
echo "var_dump($_SESSION)";
}
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
?>