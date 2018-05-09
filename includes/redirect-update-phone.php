<?php

session_start();

if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-patron.php");

    // we need to pass the API the full array of all values. So we stored them in a hidden variable and now can access
    // the old array values here and update just the one element that was changed.
    $phoneArray = unserialize($_POST['phoneArray']);

    if ($_POST['elementToUpdate'] == 'add') {
        $addedPhone = array('number' => $_POST['newPhoneNumber'],'type' => 't');
        $phoneArray[] = $addedPhone;
    } else {
    $phoneArray[$_POST['elementToUpdate']]->number = $_POST['newPhoneNumber'];
}
    sierraPatron::updatePatronPhone($_SESSION['patronId'],$phoneArray);
}

header("location: /account.php");

