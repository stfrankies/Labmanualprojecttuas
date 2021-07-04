<?php

    session_start();
    //$diaryContent="";

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

        $_SESSION['id'] = $_COOKIE['id'];

    }

    if (array_key_exists("id", $_SESSION)) {

      include("connection.php");

      $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
      $row = mysqli_fetch_array(mysqli_query($link, $query));

      $diaryContent = $row['diary'];

    } else {

        header("Location: tunde2.php");

    }

	include("header.php");

?>
<nav class="navbar navbar-light bg-faded navbar-fixed-top" id="labfolder">
  <a class="navbar-brand" href="#">LabFolder</a>
    <div class="pull-xs-right">
      <a href ='tunde2.php?logout=1'>
        <button class="btn btn-success-outline" type="submit">Logout</button></a>
    </div>
</nav>

    <div class="container-fluid" id="containerLoggedInPage">
        <textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>
    </div>

    <div class="container">
        <div class="row">
          <form action="updatedatabase.php" method="post" enctype="multipart/form-data" >
            <h3>Upload File</h3>
            <input type="file" name="myfile"> <br>
            <button type="submit" name="save">upload</button>
          </form>
        </div>
      </div>

    <?php

        include("footer.php");
    ?>
