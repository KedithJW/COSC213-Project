<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;

/*
logs the user out by destroying the session and redirects to the login page.
We will need the following code at the top of any page that if only accessible to logged in users : 
    <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
NOTE : prof might try window backbutton after having us sign out to make sure it doesnt just go back to our logged in thing.
this means if they havnet logged in they cant access the page and are redirected to login page.

for main push
*/


?>