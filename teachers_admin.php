<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connect_db.php';
include 'header_admin.php';
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

$sql = "SELECT * FROM teacher";
$result = $conn->query($sql);
$sno = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css" />
    <title>Teachers</title>
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Sno</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">CNIC</th>
                <th scope="col">Edit</th>
                <th scope="col">Remove</th>
                <th scope="col">Supervisor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $sno . '</td>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['cnic'] . '</td>';
                    echo '<td>
                            <button type="button" class="btn btn-primary edit-teacher-btn" data-toggle="modal" data-target="#editTeacherModal"
                                data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"
                                data-email="' . $row['email'] . '" data-cnic="' . $row['cnic'] . '">
                                Edit
                            </button>
                        </td>';
                    echo '<td>
                            <a href="#" class="btn btn-danger remove-teacher-btn" data-id="' . $row['id'] . '">Remove</a>
                          </td>';
                    echo '<td>';
                    if ($row['supervisor'] == 1) {
                        echo 'Already Supervisor';
                    } else {
                        echo '<a href="update_teacher_supervisor.php?id=' . $row['id'] . '" class="btn btn-success">Make Supervisor</a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                    $sno++;
                }
            } else {
                echo '<tr><td colspan="7">No records found.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Modal for Editing Teacher -->
    <div class="modal fade" id="editTeacherModal" tabindex="-1" role="dialog" aria-labelledby="editTeacherModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTeacherModalLabel">Edit Teacher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editTeacherForm" action="update_teacher.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" id="editTeacherId" name="id" value="">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="editCnic">CNIC</label>
                            <input type="text" class="form-control" id="editCnic" name="cnic" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Confirming Teacher Deletion -->
    <div class="modal fade" id="confirmDeleteTeacherModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteTeacherModalLabel">Confirm Teacher Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this teacher?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteTeacher">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.edit-teacher-btn').click(function () {
                // Retrieve data from the button's data attributes
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');
                var cnic = $(this).data('cnic');

                // Set the data in the modal form fields
                $('#editTeacherId').val(id);
                $('#editName').val(name);
                $('#editEmail').val(email);
                $('#editCnic').val(cnic);

                // Show the modal
                $('#editTeacherModal').modal('show');
            });

            // Handle form submission
            $('#editTeacherForm').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission

                // Get the form data
                var formData = $(this).serialize();

                // Send an AJAX request to update the record
                $.ajax({
                    type: "POST",
                    url: "update_teacher.php", // Specify the correct URL for your server-side script
                    data: formData,
                    success: function (response) {
                        // Handle the response, e.g., show a success message or refresh the page
                        alert('Record updated successfully');
                        location.reload(); // Reload the page to show the updated data
                    },
                    error: function () {
                        alert('Error updating record');
                    }
                });

                // Close the modal
                $('#editTeacherModal').modal('hide');
            });
        });

        // Handle teacher deletion
        $('.remove-teacher-btn').click(function () {
            var id = $(this).data('id');

            $('#confirmDeleteTeacher').attr('href', 'delete_teacher.php?id=' + id);
            $('#confirmDeleteTeacherModal').modal('show');
        });
    </script>

    <!-- JavaScript Dependencies -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>

</html>