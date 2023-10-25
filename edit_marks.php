<?php
include 'connect_db.php';

$name = $_GET["name"];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    // Handle the form submission to update the marks
    $name = $_POST['name'];
    $presentationMarks = $_POST['presentation_marks'];
    $thesisMarks = $_POST['thesis_marks'];
    $projectMarks = $_POST['project_marks'];
    $communicationMarks = $_POST['communication_marks'];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $updateQuery = "UPDATE marks SET presentation_marks = '$presentationMarks', communication_marks = '$communicationMarks', thesis_marks = '$thesisMarks', project_marks = '$projectMarks' WHERE name = '$name'";

    if ($conn->query($updateQuery) === TRUE) {
        // Redirect back to the checked_teacher.php page after a successful update
        header('Location: checked_teacher.php');
        exit;
    } else {
        echo "Error updating marks: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Marks</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
  <div class="container mt-4">
    <h1>Edit Marks</h1>
    <form method="post" action="edit_marks.php">
      <input type="hidden" name="name" value="<?php echo $name; ?>">
      <div class="form-group">
        <label for="presentation_marks">Presentation Marks:</label>
        <input type="number" class="form-control" id="presentation_marks" name="presentation_marks"
          value="<?php echo $presentationMarks; ?>">
      </div>
      <div class="form-group">
        <label for="thesis_marks">Thesis Marks:</label>
        <input type="number" class="form-control" id="thesis_marks" name="thesis_marks"
          value="<?php echo $thesisMarks; ?>">
      </div>
      <div class="form-group">
        <label for="project_marks">Project Marks:</label>
        <input type="number" class="form-control" id="project_marks" name="project_marks"
          value="<?php echo $projectMarks; ?>">
      </div>
      <div class="form-group">
        <label for="communication_marks">Communication Marks:</label>
        <input type="number" class="form-control" id="communication_marks" name="communication_marks"
          value="<?php echo $communicationMarks; ?>">
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>
  <!-- Include Bootstrap JavaScript (for any interactive features) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
</body>

</html>