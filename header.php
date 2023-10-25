<<<<<<< HEAD
<?php
include 'connect_db.php';
?>
=======
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
<nav class="navbar navbar-expand-lg navbar-light bg-primary" style="height: 60px;">
  <a class="navbar-brand text-white" href="#">FYP PORTAL</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
<<<<<<< HEAD
        <a class="nav-link text-white" href="teacher.php">Proposal<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link text-white" href="three_chapters_teacher.php">Three Chapters<span
            class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link text-white" href="final_project_teacher.php">Final Project<span
            class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="checked_teacher.php">Checked</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="news_teacher.php">News</a>
=======
        <a class="nav-link text-white" href="submitted_student.php">SUBMITTED<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="checked_students.php">CHECKED</a>
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <ul>
        <li style="list-style-type:none; margin-top: 10px; " class="nav-item dropdown">
          <a class=" nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<<<<<<< HEAD
            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 0);
            session_start();
            $teacherName = $_SESSION["teacher_name"];
            echo $teacherName;
            ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="logout-professor.php">LOGOUT</a>
=======
            IZAZ UR RAHMAN
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="login-student.php">LOGOUT</a>
>>>>>>> c31ce7ba3f969ba68344d18f0e3286603692987e
            <div class="dropdown-divider"></div>
          </div>
        </li>
      </ul>
    </form>
  </div>
</nav>