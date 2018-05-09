<?php

session_start();

if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-patron.php");

    // we need to pass the API the full array of all values. So we stored them in a hidden variable and now can access
    // the old array values here and update just the one element that was changed.
    $emailArray = unserialize($_POST['emailArray']);

    // delete the selected email from the array
    unset($emailArray[$_POST['elementToDelete']]);

    // re-sort the element keys numerically
    $reindexedEmailArray = array_values($emailArray);

    // update the email array in the database via the api
    sierraPatron::updatePatronEmail($_SESSION['patronId'],$reindexedEmailArray);
}

header("location: /account.php");
