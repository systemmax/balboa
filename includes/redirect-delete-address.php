<?php

session_start();

if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-patron.php");

    // we need to pass the API the full array of all values. So we stored them in a hidden variable and now can access
    // the old array values here and update just the one element that was changed.
    $addressArray = unserialize($_POST['addressArray']);

    // delete the selected address from the array
    unset($addressArray[$_POST['elementToDelete']]);

    // re-sort the element keys numerically
    $reindexedAddressArray = array_values($addressArray);

    // update the address array in the database via the api
    sierraPatron::updatePatronAddress($_SESSION['patronId'],$reindexedAddressArray);
}

header("location: /account.php");
