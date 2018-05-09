<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/config.php');
$config = new config();

// display a result alert when we've been directed back to this page after a button was pushed
// and whatever appropriate processing happened.
include $_SERVER['DOCUMENT_ROOT'] . "/includes/display-result-message.php";

// instantiate an object of holds data.
// this object persists even if we re-sort the rows
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/sierra-holds.php');
$holdsData = new sierraPatronHolds($config,$_SESSION['patronId']);

// serialize the data so we don't have to re-read all the API data when we re-sort the contents
$serialData = serialize($holdsData);
file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/store/patron/holdsData." . session_id(), $serialData);

if ($holdsData->getTotalHolds() == 0) {
    echo '<br>';
    echo 'You have nothing currently on hold from the ' . $config->getInstitutionName() . '.';
} else {
    echo '<div id="hold-data">';
    include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/account-holds-rows.php");
    echo '</div>';
}
?>
<script>
    // support tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
