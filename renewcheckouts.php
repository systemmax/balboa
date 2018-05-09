<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/config.php');
$config = new config();

// the post variable should be an array of checkout id's
if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/sierra-patron-checkouts-expanded.php');
    $successCounter = 0;
    foreach ($_POST as $ckoID => $checkedStatus) {
        $result = sierraPatronCheckouts::renewACheckout($config,$ckoID);
        echo $result;
        // if the renewal was successful, the API returns the checkout record id (among other fields)
        // otherwise, we get an array and in the description it has the WebPAC error (which we're not handling at present)
        if (key_exists('id',$result)) {
            $successCounter++;
        }
    }
    $_SESSION["itemsRenewed"] = $successCounter;
}

header("location: " . $_GET['returnPage']);