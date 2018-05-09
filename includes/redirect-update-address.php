<?php

session_start();

if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-patron.php");

    // we need to pass the API the full array of all values. So we stored them in a hidden variable and now can access
    // the old array values here and update just the one element that was changed.
    $addressArray = unserialize($_POST['addressArray']);
    
    if ($_POST['elementToUpdate'] == 'add') {
        $addedAddress = array(
                            'lines' => array($_POST['newAddress0'], $_POST['newAddress1']),
                            'type' => 'a');
        $addressArray[] = $addedAddress;
    } else {
        $addressArray[$_POST['elementToUpdate']]->lines[0] = $_POST['newAddress0'];
        $addressArray[$_POST['elementToUpdate']]->lines[1] = $_POST['newAddress1'];
    }
    sierraPatron::updatePatronAddress($_SESSION['patronId'],$addressArray);
}

header("location: /account.php");

