<!DOCTYPE html>

<?php
/**
 * Created by PhpStorm.
 * User: aniol
 * Date: 8/11/17
 * Time: 5:30 PM
 */
?>

<html>
    <head>
        <title>Log in NoNameBox</title>
        <meta charset="UTF-8">
    </head>

    <body>
        <form method="post" action="includes/auth.php" id="login" name="login">
            <h5>User:</h5>
            <input type="text" name="user" required>

            <h5>Password:</h5>
            <input type="password" name="password" required>

            <br>
            <br>

            <input type="submit" value="Log In">
    </body>

</html>

