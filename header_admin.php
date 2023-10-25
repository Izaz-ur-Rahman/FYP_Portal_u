<?php
include 'connect_db.php';
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

     if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $search = $_POST["search"];
      $_SESSION["search"] = $search;
     }
      ?>
<nav class="navbar navbar-expand-lg navbar-light bg-primary" style="height: 60px;">
  <a class="navbar-brand text-white" href="#">FYP PORTAL</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownProjects" role="button"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          PROJECTS
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownProjects">
          <a class="dropdown-item" href="submitted_admin.php">Submitted</a>
          <a class="dropdown-item" href="checked_admin.php">Checked</a>
          <a class="dropdown-item" href="add_new_admin.php">Add New</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownProjects" role="button"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          ACCOUNTS
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownProjects">
          <a class="dropdown-item" href="teachers_admin.php">Teachers</a>
          <a class="dropdown-item" href="students_admin.php">Students</a>
        </div>
      </li>
    </ul>
    <ul>
      <li style="list-style-type:none; margin-top: 10px; " class="nav-item dropdown">
        <a class=" nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php
          error_reporting(E_ALL);
          ini_set('display_errors', 1);
          $teacherName = $_SESSION["name"];
          echo $teacherName;
          ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="logout_admin.php">LOGOUT</a>
          <div class="dropdown-divider"></div>
        </div>
      </li>
    </ul>
  </div>
</nav>