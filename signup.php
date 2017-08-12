<!DOCTYPE html>

<?php
include_once "includes/functions.php";

function signup($user, $password) {
    // strip the user
    $user = strip($user, $password);

    $people = "ou=people,dc=nnbox,dc=org";

    // admin user
    $ldap = connect();
    $ldap_manager = "uid=manager,ou=services,dc=nnbox,dc=org";
    $ldap_password = "";

    $bind = ldap_bind($ldap, $ldap_manager, $ldap_password);

    if ($bind) {
        // we check if the user exists
        $filter = "(uid=".$user.")";
        $result = ldap_search($ldap, $people, $filter);
        $entries = ldap_get_entries($ldap, $result);

        if (count($entries, COUNT_RECURSIVE) == 1) {
            $dn = "cn=".$user.",ou=people,dc=nnbox,dc=org";
            $info = array(
                "cn"            => $user,
                "mail"          => $user."@nonamebox.org",
                "maildrop"      => array(
                    $user."@nonamebox.com",
                    $user."@nnbox.org"
                ),
                "objectClass"   => array(
                    "inetOrgPerson",
                    "uidObject",
                    "postfixUser"
                ),
                "userPassword"  => '{crypt}'.crypt($password),
                "uid"           => array(
                    $user."@nonamebox.org",
                    $user
                )
            );

            if ($add = ldap_add($ldap, $dn, $info)) {
                echo "You have signed up successfully.";
            }
        }
        else {
            echo "This user already exists.";
        }

        ldap_close($ldap);
    }
    else {
        echo "Couldn't bind to the LDAP server.";
        ldap_close($ldap);
    }
}

// check if the form has run
if (isset($_POST["new_user"])) {
    $new_user = htmlspecialchars($_POST["new_user"]);
    $new_password = htmlspecialchars($_POST["new_password"]);
    signup($new_user, $new_password);
}

?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Signup | NoNameBox</title>
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
            <h2>Sign up now!</h2>
            <form method="post" action="signup.php" id="signup" name="signup">
              <div class="form-group">
                  <label><strong>User:</strong></label>
                  <input type="text" name="new_user" required>
              </div>
              <div class="form-group">
                  <label><strong>Password:</strong></label>
                  <input type="password" name="new_password" required>
              </div>
              <div class="form-group">
                  <input name="terms" required type="checkbox" >
                  By clicking "Sign up", you agree to our <a href="#" target="_blank">terms of service</a>.
              </div>


              <div class="form-group">
              <input type="submit" value="Sign Up" class="btn btn-submit nnb_submit">
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
