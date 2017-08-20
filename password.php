<!DOCTYPE html>

<?php
include_once "includes/functions.php";
$_SESSION["last_page"] = "password";

if (!$_SESSION["loggedin"]) {
    header('Location: login');
}

function password($old_pass, $new_pass, $new_pass_repeat) {
    // check if passwords are provided
    if (empty($old_pass) || empty($new_pass) || empty($new_pass_repeat)) {
        exit('No passwords provided.');
    }

    $user = "cn=".$_SESSION["user"].",ou=people,dc=nnbox,dc=org";
    $ldap = connect();

    $bind = ldap_bind($ldap, $user, $old_pass);

    if ($bind) {
        if ($new_pass == $new_pass_repeat) {
            $info = array("userPassword" => '{crypt}'.crypt($new_pass));

            if ($modify = ldap_modify($ldap, $user, $info)) {
                echo "Your password has been changed.";
            }
        }
        else {
            $_SESSION["error"] =  "Passwords don't match.";
        }

    }
    else {
        $_SESSION["error"] =  "Current password is wrong.";
    }

    ldap_close($ldap);
    if (!empty($_SESSION["error"])){
      header('Location: error');
    }
}

// check if the form has run
if (isset($_POST["new_pass"])) {
    $old_pass = htmlspecialchars($_POST["old_pass"]);
    $new_pass = htmlspecialchars($_POST["new_pass"]);
    $new_pass_repeat = htmlspecialchars($_POST["new_pass_repeat"]);
    password($old_pass, $new_pass, $new_pass_repeat);
}

?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Account Management | NoNameBox</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/main.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/nnb_custom.css" type="text/css" rel="stylesheet" />
    <link href="assets/css/custom.css" type="text/css" rel="stylesheet" />

  </head>
  <body>
    <?php include "includes/header.php" ?>

    <div class="container">
      <div class="row">
          <div class="col-md-4" >
            <ul class="nav nav-pills nav-stacked nav-bracket">
              <li class="nav-item"><a href="/" title="Home" class="nav-link"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
              <li class="nav-item"><a><a href="personal-information" title="Home" class="nav-link"><i class="fa fa-user" aria-hidden="true"></i>Personal Information</a></a></li>
              <li class="nav-item"><a><a href="password" title="Home" class="nav-link active"><i class="fa fa-lock" aria-hidden="true"></i>Password</a></a></li>
            </ul>
          </div>
          <div class="col-md-8">
            <h1>Account Manager</h1>
            <h2>Password</h2>
            <div class="row">
              <p>Here you can modify information on your account. All services on NoNameBox (mail, cloud...) use the same user information, password and login. So you only have to remember one password!</p>
              <hr>
              <div class="container content">
                <form action="password" method="post">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label><strong>Old password:</strong></label>
                            </div>
                            <div class="col-md-6">
                                <input type="password" name="old_pass" value=""/>
                            </div>
                        </div>
                    </div>

                  <div class="form-group">
                      <div class="row">
                        <div class="col-md-4">
                          <label><strong>Password:</strong></label>
                        </div>
                        <div class="col-md-6">
                          <input type="password" name="new_pass" value=""/>
                        </div>
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="row">
                        <div class="col-md-4">
                          <label><strong>Repeat password:</strong></label>
                        </div>
                        <div class="col-md-6">
                          <input type="password" name="new_pass_repeat" value=""/>
                        </div>
                      </div>
                  </div>

                  <div class="form-group">
                    <input type="submit" value="Submit" class="nnb_submit">
                  </div>

                </form>
              </div>
            </div>
          </div>
      </div>
    </div>

    <?php include_once "includes/header.php" ?>

    <script src="assets/jquery/jquery-3.2.1.min.js" type="text/javascript"></script>
  </body>
</html>
