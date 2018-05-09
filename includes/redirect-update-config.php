<?php

session_start();

// the post variable should be an array of hold id's and the value of the button that was selected
if (isset($_POST)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/config.php');
    $config = new config();

    $config->setInstitutionName(htmlentities($_POST['institutionName']));
    $config->setLogoFileName(htmlentities($_POST['logoFileName']));
    $config->setBootstrapTheme($_POST['bootstrapTheme']);
    $config->setLocale($_POST['locale']);
    $config->setSupportToDoList($_POST['supportToDoList']);

    $config->setDB(htmlentities($_POST['db']));
    $config->setPacServer(htmlentities($_POST['pacServer']));
    $config->setIrServer(htmlentities($_POST['irServer']));
    $config->setVitalServer(htmlentities($_POST['vitalServer']));

    $config->setApiVer(htmlentities($_POST['apiVer']));
    $config->setApiKey(htmlentities($_POST['apiKey']));
    $config->setApiSecret(htmlentities($_POST['apiSecret']));

    $config->setContentCafeID(htmlentities($_POST['contentCafeID']));
    $config->setContentCafePassword(htmlentities($_POST['contentCafePassword']));

    $config->setCommentSendEmail(htmlentities($_POST['commentSendEmail']));
    $config->setCommentSender(htmlentities($_POST['commentSender']));
    $config->setSendTo(htmlentities($_POST['replyTo']));
    $config->setCommentRecipient(htmlentities($_POST['commentRecipient']));

    $config->setCarouselTitle(htmlentities($_POST['carouselTitle']));
    $config->setCarouselQueryFilename(htmlentities($_POST['carouselQueryString']));
    $config->setCarouselDataRefresh($_POST['carouselDataRefresh']);

    $config->setExcludeBibsWithoutISBN($_POST['excludeBibsWithoutISBN']);
    $config->setHyperlinkBibsToEncore($_POST['hyperlinkBibsToEncore']);

    $config->setDefaultHoldPickupLocationCode(htmlentities($_POST['defaultHoldPickupLocationCode']));


    $config->serializeConfigData();
}

header("location: /index.php");

/* FUNCTION DEFINITIONS (must exist OUTSIDE conditional statements or Php doesn't see them) */

