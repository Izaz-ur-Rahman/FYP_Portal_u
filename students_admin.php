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
$sql = "SELECT * FROM student";
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
    <title>Students</title>
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Sno</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Reg No#</th>
                <th scope="col">Edit</th>
                <th scope="col">Remove</th>

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
                    echo '<td>' . $row['registration_no'] . '</td>';

                    echo '<td>
                            <button type="button" class="btn btn-primary edit-student-btn" data-toggle="modal" data-target="#editStudentModal"
                                data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"
                                data-email="' . $row['email'] . '" data-regno="' . $row['registration_no'] . '">
                                Edit
                            </button>
                        </td>';

                    echo '<td>
                            <a href="#" class="btn btn-danger remove-student-btn" data-id="' . $row['id'] . '">Remove</a>
                          </td>';
                    echo '</tr>';
                    $sno++;
                }
            } else {
                echo '<tr><td colspan="6">No records found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <!-- Modal for Editing Student -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editStudentForm" action="update_student.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" id="editStudentId" name="id" value="">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="editRegNo">Registration Number</label>
                            <input type="text" class="form-control" id="editRegNo" name="regno" required>
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

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this student?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
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

    <!-- Custom JavaScript -->
    <script>
        $('.remove-student-btn').click(function () {
            var id = $(this).data('id');
            $('#confirmDeleteModal').data('id', id).modal('show');
        });

        $('#confirmDelete').click(function () {
            var id = $('#confirmDeleteModal').data('id');
            window.location.href = 'delete_student.php?id=' + id;
        });

        $(document).ready(function () {
            $('.edit-student-btn').click(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');
                var regNo = $(this).data('regno');

                $('#editStudentId').val(id);
                $('#editName').val(name);
                $('#editEmail').val(email);
                $('#editRegNo').val(regNo);
            });

        });
    </script>

</body>

</html>