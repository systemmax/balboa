<?php

session_start();

if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-patron.php");

    // we need to pass the API the full array of all values. So we stored them in a hidden variable and now can access
    // the old array values here and update just the one element that was changed.
    $emailArray = unserialize($_POST['emailArray']);

    if ($_POST['elementToUpdate'] == 'add') {
        $emailArray[] = $_POST['confirmNewEmailAddress'];
    } else {
        $emailArray[$_POST['elementToUpdate']] = $_POST['confirmNewEmailAddress'];
    }
    sierraPatron::updatePatronEmail($_SESSION['patronId'], $emailArray);

}

header("location: /account.php");

