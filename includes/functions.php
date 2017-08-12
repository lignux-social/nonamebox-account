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


