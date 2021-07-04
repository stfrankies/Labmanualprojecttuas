<?php

    session_start();

    $error = "";

    if (array_key_exists("logout", $_GET)) {

        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";

        session_destroy();

    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {

        header("Location: loggedinpage.php");

    }

    if (array_key_exists("submit", $_POST)) {

        include("connection.php");

        if (!$_POST['email']) {

            $error .= "An email address is required<br>";

        }

        if (!$_POST['password']) {

            $error .= "A password is required<br>";

        }

        if ($error != "") {

            $error = "<p>There were error(s) in your form:</p>".$error;

        } else {

            if ($_POST['signUp'] == '1') {

                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                } else {

                    $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";

                    } else {

                        $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        $id = mysqli_insert_id($link);

                        mysqli_query($link, $query);

                        $_SESSION['id'] = $id;

                        if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("id", $id, time() + 60*60*24*365);

                        }

                        header("Location: loggedinpage.php");

                    }

                }

            } else {

                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

                    $result = mysqli_query($link, $query);

                    $row = mysqli_fetch_array($result);

                    if (isset($row)) {

                        $hashedPassword = md5(md5($row['id']).$_POST['password']);

                        if ($hashedPassword == $row['password']) {

                            $_SESSION['id'] = $row['id'];

                            if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            }

                            header("Location: loggedinpage.php");

                        } else {

                            $error = "That email/password combination could not be found.";

                        }

                    } else {

                        $error = "That email/password combination could not be found.";

                    }

                }

        }


    }


?>

<?php include("header.php"); ?>
<body data-spy="scroll" data-target="navbar-fixed-top" data-offset="80">
  <nav class="navbar nav-default bg-faded navbar-fixed-top" id="navbar-fixed-top">
  <ul class="nav nav-tabs" role="tablist">
<li class="nav-item"><a class="navbar-brand" href="#Home">Home</a></li>
<li class="nav-item"><a class="navbar-brand" href="#Projects">Projects</a></li>
<li class="nav-item"><a class="navbar-brand" href="#LabWorks">Lab Work </a></li>
<li class="nav-item"><a class="navbar-brand" href="#FQA">FQA </a></li>
<li class="nav-item"><a class="navbar-brand" href="#">Login Here </a></li>
  </ul>
</nav>

<h1 id="Home"> Home </h1>
<div>What is Lorem Ipsum?
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p> </div>

<h1 id="Projects"> Projects </h1>
<div>What is Lorem Ipsum?
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p> </div>

<h1 id="LabWorks"> Lab Works </h1>
<div>What is Lorem Ipsum?
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p> </div>

<h1 id="FAQ"> FAQ </h1>
<div>What is Lorem Ipsum?
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p> </div>
      <div class="container" id="homePageContainer">

    <h2>Login Here</h2>

          <p><strong>Save your work here and securely.</strong></p>

          <div id="error"><?php if ($error!="") {
    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

} ?></div>

<form method="post" id = "signUpForm">


    <fieldset class="form-group">

        <input class="form-control" type="email" name="email" placeholder="Your Email">

    </fieldset>

    <fieldset class="form-group">

        <input class="form-control" type="password" name="password" placeholder="Password">

    </fieldset>

    <div class="checkbox">

        <label>

        <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in

        </label>

    </div>

    <fieldset class="form-group">

        <input type="hidden" name="signUp" value="1">

        <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">

    </fieldset>

    <p><a class="toggleForms">Log in</a></p>

</form>

<form method="post" id = "logInForm">

    <p>Log in using your username and password.</p>

    <fieldset class="form-group">

        <input class="form-control" type="email" name="email" placeholder="Your Email">

    </fieldset>

    <fieldset class="form-group">

        <input class="form-control"type="password" name="password" placeholder="Password">

    </fieldset>

    <div class="checkbox">

        <label>

            <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in

        </label>

    </div>

        <input type="hidden" name="signUp" value="0">

    <fieldset class="form-group">

        <input class="btn btn-success" type="submit" name="submit" value="Log In!">

    </fieldset>

    <p><a class="toggleForms">Sign up</a></p>

</form>

      </div>

<?php include("footer.php"); ?>
