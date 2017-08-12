<!DOCTYPE html>

<?php
/**
 * Created by PhpStorm.
 * User: aniol
 * Date: 8/12/17
 * Time: 4:16 PM
 */
?>

<html>
    <head>
        <title>Sign up NoNameBox</title>
        <meta charset="UTF-8">
    </head>

    <body>
        <form method="post" action="includes/auth.php" id="signup" name="signup">
            <h5>User:</h5>
            <input type="text" name="new_user" required>

            <h5>Password:</h5>
            <input type="password" name="new_password" required>

            <br>
            <h5><input name="terms" required type="checkbox" > By clicking "Sign up", you agree to our <a href="#" target="_blank">terms of service</a>.</h5>
            <br>

            <input type="submit" value="Sign up">
    </body>
</html>
