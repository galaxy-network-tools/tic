<?
session_start();
include 'sessionhelpers.inc.php';
echo 'Sie sind ';
if (!logged_in())
    echo 'nicht ';
echo 'eingeloggt.<p />';
echo '<a href="login.php">Start</a>';
?> 