<?php
/**
 * Created by PhpStorm.
 * User: aniol
 * Date: 8/11/17
 * Time: 4:30 PM
 */

// Global constants
define("HOST", "chase.nnbox.org");
define("PORT", 389);
define("PEOPLE", "ou=people,dc=nnbox,dc=org");

function connect() {
    // connect
    $ldap = ldap_connect(HOST, PORT);

    // configure ldap params
    ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
    ldap_start_tls($ldap);

    return $ldap;

}

function login($user, $password) {
    // check if a user and password are provided
    if(empty($user) || empty($password)) {
        exit('No user provided.');
    }

    // strip user
    if (strpos($user, "@") !== false) {
        $split = explode("@", $user);
        $user = $split[0];
    }

    // bind
    $ldap = connect();
    $ldap_user = "cn=".$user.",ou=people,dc=nnbox,dc=org";
    $bind = ldap_bind($ldap, $ldap_user, $password);

    if ($bind) {
        echo "true";
        ldap_close($ldap);

    }
    else {
        echo "Couldn't bind to the LDAP server.";
        ldap_close($ldap);
    }
}

function signup($user, $password) {
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
}

// check origin and run functiond

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

