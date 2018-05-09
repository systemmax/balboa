<?php

session_start();

if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-patron.php");

    $newBarcode = "2222" . mt_rand(10000000,99999999);

    $addressArray = array(
                    'lines' => array($_POST['streetaddress'], $_POST['citystatezip']),
                    'type' => "a");

    $phoneArray = array(
                'number' => $_POST['phone'],
                'type' => "t");

    $newPatronArray = array(
        'names' => array($_POST['lastname'] . ", " . $_POST['firstname']),
        'addresses' => array($addressArray),
         'phones' => array($phoneArray),
        'emails' => array($_POST['email']),
        'pin' => $_POST['pin'],
        'patronType' => 15,
        'expirationDate' => date('Y-m-d', strtotime("+30 days")),
        'barcodes' => array($newBarcode)
    );

    $result = sierraPatron::addPatron($newPatronArray);

    // when a patron has registered successfully, the API returns an array where the key is the word "link" and the value is a URI containing the new patron's record ID number
    if (array_key_exists('link',$result)) {

        $_SESSION['registration-succeeded'] = true;

        $lastSlash = strrpos($result['link'],'/');
        $newPatronId = substr($result['link'],$lastSlash+1,strlen($result['link']));

        $_SESSION['barcode'] = $newBarcode;
        $_SESSION['pin'] = $newPatronArray['pin'];
        $_SESSION['patronName'] = $_POST['firstname'] . " " . $_POST['lastname'];
        $_SESSION['patronId'] = $newPatronId;
        $_SESSION['patronEmail'] = $newPatronArray['emails'][0];

        header("location: /account.php");

    } else {
        $_SESSION['registration-succeeded'] = false;
        header("location: /index.php");
    }
}



