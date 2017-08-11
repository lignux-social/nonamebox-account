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

function login($user, $password) {
    // check if a user and password are provided
    if(empty($user) || empty($password)) {
        exit('No user provided.');
    }

    // strip user
    if (!strpos("@", $user)) {
        $split = explode("@", $user);
        $user = $split[0];
    }

    // connect
    $ldap_user = "cn=".$user.",ou=people,dc=nnbox,dc=org";
    $ldap = ldap_connect(HOST, PORT);

    // configure ldap params
    ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
    ldap_start_tls($ldap);

    // bind
    $bind = @ldap_bind($ldap, $ldap_user, $password);

    if ($bind) {
        echo "true";
        @ldap_close($ldap);
    }
    else {
        echo "Couldn't bind to the LDAP server.";
        @ldap_close($ldap);
    }
}

function signup($user, $password) {
    // check if a user and password are provided
    if(empty($user) || empty($password)) {
        exit('No user provided.');
    }

    // strip user
    if (!strpos("@", $user)) {
        $split = explode("@", $user);
        $user = $split[0];
    }

    // admin user
    $ldap_manager = "uid=manager,ou=services,dc=nnbox,dc=org";
    $ldap_password = "";

    // connect
    $ldap = ldap_connect(HOST, PORT);

    // configure ldap params
    ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
    ldap_start_tls($ldap);

    // bind
    $bind = @ldap_bind($ldap, $ldap_manager, $ldap_password);

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
                        "objectclass"   => array(
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
            // TODO: Fix the info array.
            $add = ldap_add($ldap, $dn, $info);
        }
        else {
            exit('This user already exists.');
        }

        @ldap_close($ldap);
    }
    else {
        echo "Couldn't bind to the LDAP server.";
        @ldap_close($ldap);
    }
}

// Login
/*$user = htmlspecialchars($_POST["user"]);
$password = htmlspecialchars($_POST["password"]);
login($user, $password);*/

// Sign up
signup("", "");