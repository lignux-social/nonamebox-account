<?php
session_start();

function connect() {
    // ldap variables
    $host = "chase.nnbox.org";
    $port = 389;

    // connect
    $ldap = ldap_connect($host, $port);

    // configure ldap params
    ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
    ldap_start_tls($ldap);

    return $ldap;

}

function strip($user, $password) {
    // check if a user and password are provided
    if(empty($user) || empty($password)) {
        exit('No user provided.');
    }

    // strip user
    if (strpos($user, "@") !== false) {
        $split = explode("@", $user);
        $user = $split[0];
    }

    return $user;
}

/*function signup($user, $password) {
    // check if a user and password are provided
    if(empty($user) || empty($password)) {
        exit('No user provided.');
    }

    // strip user
    if (strpos($user, "@") !== false) {
        $split = explode("@", $user);
        $user = $split[0];
    }

    // admin user
    $ldap = connect();
    $ldap_manager = "uid=manager,ou=services,dc=nnbox,dc=org";
    $ldap_password = "";

    $bind = ldap_bind($ldap, $ldap_manager, $ldap_password);

    if ($bind) {
        // we check if the user exists
        $filter = "(uid=".$user.")";
        $result = ldap_search($ldap,PEOPLE, $filter);
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
            exit('This user already exists.');
        }

        ldap_close($ldap);
    }
    else {
        echo "Couldn't bind to the LDAP server.";
        ldap_close($ldap);
    }
}*/

function password($old_pass, $new_pass, $new_pass_repeat) {
    // check if passwords are provided
    if (empty($old_pass) || empty($new_pass) || empty($new_pass_repeat)) {
        exit('No passwords provided.');
    }

    session_start();
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
            echo "Your passwords don't match.";
        }

    }
    else {
        echo "Current password is wrong.";
        ldap_close($ldap);
    }
}

// check origin and run functions
/*
// sign up
if (strpos($_SERVER['HTTP_REFERER'], "signup.php") !== false) {
    $new_user = htmlspecialchars($_POST["new_user"]);
    $new_password = htmlspecialchars($_POST["new_password"]);
    signup($new_user, $new_password);
}

// login
else if (strpos($_SERVER['HTTP_REFERER'], "login.php") !== false) {
    $user = htmlspecialchars($_POST["user"]);
    $password = htmlspecialchars($_POST["password"]);
    login($user, $password);
}

// password
else if (strpos($_SERVER['HTTP_REFERER'], "password.php") !== false) {
    $old_pass = htmlspecialchars($_POST["old_pass"]);
    $new_pass = htmlspecialchars($_POST["new_pass"]);
    $new_pass_repeat = htmlspecialchars($_POST["new_pass_repeat"]);
    password($old_pass, $new_pass, $new_pass_repeat);
}*/


