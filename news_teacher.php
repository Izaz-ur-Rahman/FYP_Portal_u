<?php
session_start();
$alertMessage = ''; // Initialize the alert message
include ('connect_db.php'); // Include your database connection
include('header.php');


  // Check if the teacher is authenticated (i.e., if the teacher's name is in the session)
  if (!isset($_SESSION["teacher_name"])) {
      // Redirect to the login page or display an error message
      echo '<div class="alert alert-danger" role="alert">Access denied. Please log in first.</div>';
      // You can also add a link to the login page for the user to log in.
      echo '<p ><a class="btn btn-primary" style="color:white; text-align:center;" href="login-professor.php">Log in</a></p>';
      // Optionally, you can exit the script to prevent further code execution.
      exit();
  }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['news_title']) && isset($_POST['news_content'])) {
    $newsTitle = $_POST['news_title'];
    $newsContent = $_POST['news_content'];
    $query= "Select * from teacher";
    $teacherName = $_SESSION["teacher_name"];
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    // Assuming you have a session variable for teacher_id
    $teacherID = $row['id'];

    // Insert news into the database with teacher ID
    $newsInsertQuery = "INSERT INTO news (title, content, date, teacher_id, teacher) VALUES ('$newsTitle', '$newsContent', NOW(), '$teacherID', '$teacherName')";
    if ($conn->query($newsInsertQuery) === TRUE) {
        $alertMessage = '<div class="alert alert-success" role="alert">News posted successfully!</div>';
    } else {
        $alertMessage = '<div class="alert alert-danger" role="alert">Error posting news: ' . $conn->error . '</div>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Teacher News Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php echo $alertMessage; ?>
    <!-- Main Content Section -->
    <div class="container mt-5">
        <h1>Add News</h1>
        <!-- Add News Form -->
        <form method="POST" action="news_teacher.php">
            <div class="form-group">
                <label for="news_title">Title</label>
                <input type="text" class="form-control" id="news_title" name="news_title" required>
            </div>
            <div class="form-group">
                <label for="news_content">Content</label>
                <textarea class="form-control" id="news_content" name="news_content" rows="3" required></textarea>
            </div>
            <!-- Add a hidden input field for teacher ID -->
            <input type="hidden" name="teacher_id" value="<?php echo $teacherID; ?>">
            <button type="submit" class="btn btn-primary">Add News</button>
        </form>
        <!-- Add a modal for editing news -->
        <div class="modal fade" id="editNewsModal" tabindex="-1" role="dialog" aria-labelledby="editNewsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editNewsModalLabel">Edit News</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for editing news -->
                        <form id="editNewsForm">
                            <div class="form-group">
                                <label for="edit_news_title">Title</label>
                                <input type="text" class="form-control" id="edit_news_title" name="news_title" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_news_content">Content</label>
                                <textarea class="form-control" id="edit_news_content" name="news_content" rows="3"
                                    required></textarea>
                            </div>
                            <input type="hidden" name="news_id" id="edit_news_id">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Existing News Section -->
        <div class="container mt-5">
            <h1>Existing News</h1>
            <ul class="list-group">
                <?php
$alertMessage = ''; // Initialize the alert message
include ('connect_db.php'); // Include your database connection

// Fetch existing news from the database
$newsQuery = "SELECT * FROM news";
$result = mysqli_query($conn, $newsQuery);

// Check if the query was successful
if ($result) {
    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        // Loop through the rows of data and display them in the list
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li class="list-group-item">';
            echo '<h5 class="mb-1">' . $row['title'] . '</h5>';
            echo '<p class="mb-1">' . $row['content'] . '</p>';
            echo '<button type="button" class="btn btn-danger btn-sm delete-news" data-newsid="' . $row['id'] . '">Delete</button>';

            echo '</li>';
        }
    } else {
        // No records found
        echo '<li class="list-group-item">No news available.</li>';
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    // Error handling if the query fails
    echo 'Error: ' . mysqli_error($conn);
}

$conn->close();
?>

                <!-- Add more news items as needed -->
            </ul>
        </div>

        <!-- Bootstrap JS and dependencies -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>



<!-- Inside your HTML, after Bootstrap JS includes -->
<script>
    $(document).ready(function () {
        // Edit News Modal
        $('.edit-news').click(function () {
            var newsID = $(this).data('newsid');
            $.ajax({
                url: 'get_news_details.php', // PHP file to fetch news details
                type: 'post',
                data: { news_id: newsID },
                success: function (response) {
                    var news = JSON.parse(response);
                    $('#edit_news_title').val(news.title);
                    $('#edit_news_content').val(news.content);
                    $('#edit_news_id').val(news.id);
                    $('#editNewsModal').modal('show');
                }
            });
        });

        // Delete News
        $('.delete-news').click(function () {
            var newsID = $(this).data('newsid');
            var confirmDelete = confirm('Are you sure you want to delete this news?');
            if (confirmDelete) {
                window.location.href = 'delete_news.php?news_id=' + newsID;
            }
        });

        // Submit Edit News Form
        $('#editNewsForm').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: 'edit_news.php', // PHP file to edit news details
                type: 'post',
                data: form.serialize(),
                success: function (response) {
                    alert('News edited successfully!');
                    $('#editNewsModal').modal('hide');
                    // You may need to refresh the news list here
                }
            });
        });
    });
</script>