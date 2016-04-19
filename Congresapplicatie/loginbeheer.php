<?php
/**
* Created by PhpStorm.
* User: erike
* Date: 19-4-2016
* Time: 10:16
*/
?>

<html>
    <head>
        <title>Login Beheer</title>
    </head>
    <body>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="input-username">Gebruikersnaam</label>
            <input type="text" id="input-username" placeholder="Gebruikersnaam" name="input-username">

            <label for="input-password">Wachtwoord</label>
            <input type="password" id="input-password" placeholder="Wachtwoord" name="input-password">

            <input type="submit" value="Inloggen">
        </form>
    </body>
</html>
<?php

?>