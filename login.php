<!DOCTYPE html>

<?php
include_once "includes/functions.php";

function login($user, $password) {
    // strip the user
    $user = strip($user, $password);

    // bind
    $ldap = connect();
    $ldap_user = "cn=".$user.",ou=people,dc=nnbox,dc=org";
    $bind = ldap_bind($ldap, $ldap_user, $password);

    // login and close connection
    if ($bind) {
        $_SESSION["user"] = $user;
        ldap_close($ldap);
        return true;
    }
    else {
        echo "Couldn't bind to the LDAP server.";
        ldap_close($ldap);
        return false;
    }
}

// check if the form has run
if (isset($_POST["user"])) {
    $user = htmlspecialchars($_POST["user"]);
    $password = htmlspecialchars($_POST["password"]);
    login($user, $password);
}

?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | NoNameBox</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/main.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/nnb_custom.css" type="text/css" rel="stylesheet" />
    <link href="assets/css/custom.css" type="text/css" rel="stylesheet" />

  </head>
  <body>
    <header id="header"><div class="clearfix">
      <h1 id="logo"><a href="/"> <img src="assets/img/gus_nnblogo.png" alt="No Name Box" class="header-logo"></a></h1>
      </div>
      <nav id="nav">
          <ul>
              <li><a href="http://nonamebox.org/">Get back to the site</a>
              <div class="clearfix"></div></li>
          </ul>
          <div class="clearfix"></div>
      </nav>
    </header>

    <div class="container">
      <div class="row">
          <div class="col-md-2" >
          </div>
          <div class="col-md-8">
            <h1>Account Manager</h1>
            <h2>Login</h2>
            <form method="post" action="login.php" id="login" name="login">
              <div class="form-group">
                  <label><strong>User:</strong></label>
                  <input type="text" name="user" required>
              </div>
              <div class="form-group">
                  <label><strong>Password:</strong></label>
                  <input type="password" name="password" required>
              </div>
              <div class="form-group">
              <input type="submit" value="Login" class="btn btn-submit nnb_submit">
            </div>
            </form>
          </div>
          <div class="col-md-2" >
          </div>
      </div>
    </div>

    <footer id="footer">
      <ul class="actions">
          <li><a href="https://t.me/NoNameBox" class="icon fa-telegram"></a></li>
          <li><a href="mailto:info@nonamebox.org" class="icon fa-envelope"></a></li>
      </ul>
    </footer>

    <script src="assets/jquery/jquery-3.2.1.min.js" type="text/javascript"></script>
  </body>
</html>