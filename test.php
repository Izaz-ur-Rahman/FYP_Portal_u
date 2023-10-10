<?php
if (isset($_SESSION['teacher_cnic'])) {
// Session variable 'your_variable_name' exists.
echo $_SESSION['teacher_cnic'];
} else {
// Session variable 'your_variable_name' does not exist.
echo "not entered";
echo "var_dump($_SESSION)";
}
?>