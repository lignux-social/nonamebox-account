<?php
/**
 * Created by PhpStorm.
 * User: aniol
 * Date: 8/11/17
 * Time: 4:30 PM
 */

function authenticate($user, $password) {
    // check if a user and password are provided
    if(empty($user) || empty($password)) {
        exit('No user provided.');
    }

    // ldap server variables
    $ldap_host = "chase.nnbox.org";
    $ldap_port = 389;
    $ldap_user = "cn=".$user.",ou=people,dc=nnbox,dc=org";

    // connect
    $ldap = ldap_connect($ldap_host, $ldap_port);

    // configure ldap params
    ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
    ldap_start_tls($ldap);

    // bind
    $bind = @ldap_bind($ldap, $ldap_user, $password);
    if ($bind) {
        echo "true";
    }
    else {
        echo "false";
    }

}

$user = htmlspecialchars($_POST["user"]);
$password = htmlspecialchars($_POST["password"]);
authenticate($user, $password);