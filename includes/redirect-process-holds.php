<?php

session_start();

// the post variable should be an array of hold id's and the value of the button that was selected
if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/sierra-holds.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/config.php');
    $config = new config();

    switch(true) {
        case isset($_POST['needsLogin']): {
            header("location: /login.php?referrer=bibdetail&bibid=" . $_POST['bibid']);
            break;
        }
        case isset($_POST['changePickup']): {
            changePickupLocations($config);
            header("location: /account.php#holds");
            break;
        }
        case isset($_POST['cancelHold']): {
            cancelSelectedHolds($config);
            header("location: /account.php#holds");
            break;
        }
        case isset($_POST['freezeHold']): {
            freezeSelectedHolds($config);
            header("location: /account.php#holds");
            break;
        }
        case isset($_POST['unFreezeHold']): {
            unFreezeSelectedHolds($config);
            header("location: /account.php#holds");
            break;
        }
        case isset($_POST['placeHold']): {
            placeHold($config);
            header("location: /bibdetail.php?bibid=" . $_POST['bibid']);
            break;
        }
    }
}

/* FUNCTION DEFINITIONS (must exist OUTSIDE conditional statements or Php doesn't see them) */

function changePickupLocations($config) {
    $successCounter = 0;
    foreach ($_POST as $holdId => $checkedStatus) {
        if ($checkedStatus == "on") {
            $result = sierraPatronHolds::changePickupLocation($config,$holdId, $_POST['newLocation']);
            if ($result == "") {
                $successCounter++;
            }
        }
    }
    $_SESSION["locationsChanged"] = $successCounter;
}

function cancelSelectedHolds($config)
{
    $successCounter = 0;
    foreach ($_POST as $holdId => $checkedStatus) {
        if ($checkedStatus == "on") {
            $result = sierraPatronHolds::cancelAHold($config,$holdId);
            if ($result == "") {
                $successCounter++;
            }
        }
    }
    $_SESSION["holdsCanceled"] = $successCounter;
}

function freezeSelectedHolds($config)
{
    $successCounter = 0;
    foreach ($_POST as $holdId => $checkedStatus) {
        if ($checkedStatus == "on") {
            $result = sierraPatronHolds::freezeAHold($config,$holdId);
            if ($result == "") {
                $successCounter++;
            }
        }
    }
    $_SESSION["holdsFrozen"] = $successCounter;
}

function unFreezeSelectedHolds($config)
{
    $successCounter = 0;
    foreach ($_POST as $holdId => $checkedStatus) {
        if ($checkedStatus == "on") {
            $result = sierraPatronHolds::unFreezeAHold($config,$holdId);
            if ($result == "") {
                $successCounter++;
            }
        }
    }
    $_SESSION["holdsUnfrozen"] = $successCounter;
}

function placeHold($config)
{
    $holdResponse = sierraPatronHolds::placeAHold($config,$_SESSION['patronId'], $_POST['bibid'], $_POST['pickupLocation'], $_POST['notWantedAfter']);
    if ($holdResponse === NULL) {
        $_SESSION["holdAttemptResponse"] = 'successful';
    } else {
        $_SESSION["holdAttemptResponse"] = $holdResponse;
    }
}
