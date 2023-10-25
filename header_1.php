<nav class="navbar navbar-expand-lg navbar-dark bg-primary navcolor" style="height: 60px;">
  <a class="navbar-brand" href="submitted_student.php">FYP PORTAL</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarSupportedContent">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="submitted_student.php">Submitted <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="navcolor nav-link active" href="checked_students.php">Checked <span
            class="sr-only">(current)</span></a>
      </li>
    </ul>

    <form class="form-inline my-2 my-lg-0">
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
            <a class="dropdown-item" href="logout-student.php">LOGOUT</a>
            <div class="dropdown-divider"></div>
          </div>
        </li>
      </ul>
    </form>
  </div>
</nav>