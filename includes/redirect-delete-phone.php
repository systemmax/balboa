<?php

session_start();

if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-patron.php");

    // we need to pass the API the full array of all values. So we stored them in a hidden variable and now can access
    // the old array values here and update just the one element that was changed.
    $phoneArray = unserialize($_POST['phoneArray']);

    // delete the selected email from the array
    unset($phoneArray[$_POST['elementToDelete']]);

    // re-sort the element keys numerically
    $reindexedPhoneArray = array_values($phoneArray);

    // update the email array in the database via the api
    sierraPatron::updatePatronPhone($_SESSION['patronId'],$reindexedPhoneArray);
}

header("location: /account.php");
