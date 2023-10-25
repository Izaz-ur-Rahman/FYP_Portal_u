<?php
include 'connect_db.php';
include('header_admin.php');
session_start();

  // Check if the teacher is authenticated (i.e., if the teacher's name is in the session)
  if (!isset($_SESSION["name"])) {
      // Redirect to the login page or display an error message
      echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
      // You can also add a link to the login page for the user to log in.
      echo '<p ><a class="btn btn-primary" style="color:white; text-align:center;" href="login_admin.php">Log in</a></p>';
      // Optionally, you can exit the script to prevent further code execution.
      exit();
  }
$sql = "SELECT * FROM submitted WHERE checked=1";
$result = $conn->query($sql);
error_reporting(E_ALL);
$sno=0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checked</title>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Sno</th>
      <th scope="col">Name</th>
      <th scope="col">Reg No</th>
      <th scope="col">Assigned To</th>
      <th scop="col">Marks</th>
      <th scope="col">Download</th>
      <th scope="col">Delete</th> <!-- New column for delete button -->
    </tr>
  </thead>
  <tbody>
    <?php
      if ($result) {
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . ++$sno . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['registration_no'] . '</td>';
            echo '<td>' . $row['teacher'] . '</td>';
            echo '<td>' . $row['marks'] . '</td>';
            echo '<td><a href="download.php?file=' . urlencode($row['file_reference']) . '" class="btn btn-success">Download</a></td>';
            echo '<td><a href="delete.php?id=' . $row['id'] . '" class="btn btn-danger">Delete</a></td>'; // Delete button with link to delete.php passing the row id
            echo '</tr>';
          }
          echo '</table>';
        } else {
          echo '<div class="alert alert-info" role="alert">No records found.</div>';
        }
        mysqli_free_result($result);
      } else {
        echo 'Error: ' . mysqli_error($conn);
      }
      ?>
  </tbody>
</table>


</body>

</html>