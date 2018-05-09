<?php

session_start();

include_once("root-config.php");

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/config.php');
$config = new config();

/* Go no further if we don't have a Bib Id */
if (!isset($_GET['bibid'])) {
    die();
}

include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/locale-strings.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-bib.php");
include_once 'classes/sierra-item.php';
include_once 'classes/sierra-item-status.php';

$bibData = new sierraBib($config,$_GET['bibid']);
$itemIdArray = $bibData->getItemIds();
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <?php include 'includes/head-link.php' ?>
    <script type="text/javascript" src="https://books.google.com/books/previewlib.js"></script>

    <title><?php echo $config->getInstitutionName() ?> | Bibliographic Detail</title>
</head>
<body>
<?php include "includes/header.php";

// the marc record modal.
include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/modal-marc-record.php');
?>
<div class="container well">
    <?php
    // if we just submitted the hold request, display a message with the outcome.
    if (isset($_SESSION['holdAttemptResponse'])) {
        if ($_SESSION['holdAttemptResponse'] === 'successful') {
            // successful hold
            $alertMessage = "Hold placed successfully";
            echo '<div class="alert alert-success" role="alert">' .
                '<a href="#" class="close" data-dismiss="alert">&times;</a>' .
                $alertMessage .
                '</div>';
        } else {
            // a value came back from the hold request api call, so that means something went wrong
            $alertMessage = $_SESSION['holdAttemptResponse']["description"];
            echo '<div class="alert alert-danger" role="alert">' .
                '<a href="#" class="close" data-dismiss="alert">&times;</a>' .
                $alertMessage .
                '</div>';
        }
    }
    // clear the session variable now that we've dealt with it.
    unset($_SESSION['holdAttemptResponse']);

    // if we just submitted the renewal request, display a message with the outcome.
    if (isset($_SESSION['itemsRenewed'])) {
        if ($_SESSION['itemsRenewed'] > 0) {
            // successful renewal
            $alertMessage = "Checkout renewed.";
            echo '<div class="alert alert-success" role="alert">' .
                '<a href="#" class="close" data-dismiss="alert">&times;</a>' .
                $alertMessage .
                '</div>';
        } else {
            // it didn't renew
            $alertMessage = "Checkout NOT renewed successfully.";
            echo '<div class="alert alert-danger" role="alert">' .
                '<a href="#" class="close" data-dismiss="alert">&times;</a>' .
                $alertMessage .
                '</div>';
        }
    }
    // clear the session variable now that we've dealt with it.
    unset($_SESSION['itemsRenewed']);
    ?>

    <!-- LEFT SECTION -->

    <div class="col-xs-12 col-md-3 bib-detail-bookjacket-col">
        <img class="bookjacket-image" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="<?php echo 'http://' . $bibData->getBookjacketImageURL(); ?>">
        <br>
        <br>
        <?php
        // if the patron is logged in, check to see if they have this on hold or checked out
        $alreadyOnHold = false;
        $alreadyCheckedOut = false;
        if (isset($_SESSION["barcode"])) {

            // check for checked-out

            // look for these item id's in the list of items checked out to this patron
            include_once 'classes/sierra-patron-checkouts-concise.php';
            $myCheckouts = new sierraPatronCheckoutsConcise($config,$_SESSION['patronId']);

            $checkedOutIdKey = -1;
            $dueDate = "";

            // look through each checkout record and see if its also among this bib's items
            for ($i=0; $i < $myCheckouts->getTotalCheckouts(); $i++) {
                $thisItemId = $myCheckouts->getCheckoutItemIdNumber($i);
                // look through each bib id
                for ($x=0; $x < count($itemIdArray); $x++) {
                    if ($itemIdArray[$x]['id'] == $thisItemId) {
                        $checkedOutIdKey = $myCheckouts->getCheckoutIdNumber($i);
                        $dueDate = $myCheckouts->getDueDateFormatted($i);
                        $alreadyCheckedOut = true;

                        if ($alreadyCheckedOut) {
                            if ($myCheckouts->isOverdue($i)) {
                                echo '<span class="text-danger">This title is checked out to you. It was due back on ' . $dueDate . ' and is now <font-weight-italic>overdue</font-weight-italic>.</span>';
                            } else {
                                echo '<span class="text-success">This title is checked out to you. It is due back on ' . $dueDate . '.</span>';
                            }
                            echo '<br><br>';
                            $actionString = 'renewcheckouts.php?returnPage=bibdetail.php?bibid=' . $_GET['bibid'];
                            echo '<form name="renewMe" method="POST" action="' . $actionString . '">';
                            echo '<a href="#" onclick="document.forms[\'renewMe\'].submit();">Click here to <font-weight-bold>renew it</font-weight-bold>.</a>';
                            echo '<input type="hidden" name="' . $checkedOutIdKey . '" value="checked">';
                            echo '</form>';
                        }
                        break 2;
                    }
                }
            }

            // check for a hold (if we don't have it checked out)

            if (!$alreadyCheckedOut) {
                include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-holds.php");
                $myHolds = new sierraPatronHolds($config,$_SESSION['patronId']);
                if ($myHolds->hasCurrentlyOnHold($_GET['bibid'])) {
                    $alreadyOnHold = true;
                    echo '<span class="text-danger">You currently have this title on hold.</span>';
                }
            }
        }
        ?>
    </div>

    <!-- MIDDLE SECTION -->

    <div class="col-xs-12 col-md-7">
        <h1><?php echo $bibData->getBibTitle(); ?></h1>

                <?php
                if ($bibData->getBibAuthor() != null) {
                    echo '<h3>by ' . $bibData->getBibAuthor() . '</h3>';
                }
                ?>

                <!-- the format image & pub year -->
                <div>
            <span id="materialTypeImage">
                <img src="<?php echo $bibData->getMaterialTypeFilename(); ?>">
            </span>
                    <span id="bibFormat">
                <?php echo $bibData->getBibFormat() ?>
            </span>

                    <span id="pubYear">
                <?php echo " - " . $bibData->getPubYear(); ?>
            </span>
                </div>

                <br>

                <!-- the bib summary -->

                <div><em><?php echo $bibData->getBibSummary(); ?></em></div>

                <br><br>

                <!-- item availability -->

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a role="button" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Item Availability&nbsp&nbsp<span class="badge" style="background-color: darkblue"><?php echo ' ' . count($itemIdArray) . ' ' ?></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <table class="table table-striped table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Call Number</th>
                                        <th>Format</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $itemStatusArray = new sierraItemStatus();

                                    foreach ($itemIdArray as $key=>$thisItem) {
                                        $thisItem = new sierraItem($config,$thisItem['id']);

                                        echo '<tr>';
                                        echo '<td>';
                                        echo $thisItem->getLocation();
                                        echo '</td>';

                                        echo '<td>';
                                        echo $thisItem->getCallNumber();
                                        echo '</td>';

                                        echo '<td>';
                                        echo $thisItem->getItemType();
                                        echo '</td>';

                                        echo '<td>';
                                        if ($thisItem->getDueDate() == '') {
                                            $statusToDisplay = $itemStatusArray->getItemStatusDescription($thisItem->getStatusCode());
                                        } else {
                                            $statusToDisplay = 'Due: ' . date(localeStrings::shortFormatString($config->getLocale()), strtotime($thisItem->getDueDate()));
                                        }
                                        echo $statusToDisplay;
                                        echo '</td>';

                                        echo '</tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
    </div>


    <!-- RIGHT SECTION -->

    <div class="col-xs-12 col-md-2">
        <div class="bib-detail-side-panel well">

            <!-- Encore link -->
            <div class="bib-detail-side-panel-link">
                <a href="http://<?php echo $config->getPacServer() . '/iii/encore/record/C__Rb' . $_GET['bibid'] . '__Orightresult__U'?>" title="View this record in Encore to see even more information." target="_blank"><img class="panel-image" src="images/encoreDuet.png"></a>
            </div>

            <!-- INN-Reach link -->
            <div class="bib-detail-side-panel-link">
                <a href="http://<?php echo $config->getIrServer() . '/iii/encore/search/C__S' . $bibData->getBibTitle() . '__Orightresult__U' ?>" title="Search for this title in the statewide Inn-Reach system." target="_blank"><img class="panel-image" src="images/INN-Reach_logo_sml.png"></a>
            </div>

            <!-- goodreads badge -->
            <div class="bib-detail-side-panel-link">
                <?php
                echo '<a href="http://www.goodreads.com/book/isbn/' . $bibData->getFirstISBN() . '"
                        title="Read reviews and learn more about this title." target="_blank"><img class="goodreads-badge panel-image" src="images/goodreads.png"/></a>';
                ?>
            </div>

            <!--google preview badge -->
            <?php
            echo '<script type="text/javascript">';
            echo "GBS_insertPreviewButtonPopup('ISBN:" . $bibData->getFirstISBN() . "','OCLC:" . $bibData->getOclcNumber() . "')";
            echo '</script>';
            ?>

            <hr/>


            <div class="btn-group-sm btn-group-vertical">
                <!-- Hold Button -->
                <form id="holdForm" action="/includes/redirect-process-holds.php" method="post">
                    <?php

                    echo '<input type="hidden" id="bibid" name="bibid" value ="' . $_GET["bibid"] . '" />';

                    // if the user is logged in, enable the button so that it invokes the options dialog.
                    // otherwise, make it so the button submits the form - but it'll redirect to the login page.
                    if (isset($_SESSION["barcode"])) {
                        // the place a hold modal.  It brings up the "select location" control in a lightbox, but essentially it's
                        // another field on this form.  Its OK button, however is THE submit button for this form.
                        include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/modal-place-hold.php');
                        echo '<button type="button" class="btn btn-info" name="startHoldsProcess"';
                        if ($alreadyOnHold) {
                            echo 'title="You already have this title on hold" disabled ';
                        }
                        if ($alreadyCheckedOut) {
                            echo 'title="You already have this title checked out." disabled ';
                        }
                        echo 'data-toggle="modal" data-target="#placeHoldModal" data-backdrop="static" class="accountButton btn btn-primary">Place Hold</button>';
                    } else {
                        echo '<input type="hidden" id="needsLogin" name="needsLogin" value="needsLogin"/>';
                        echo '<button type="submit" name="startHoldsProcess" class="accountButton btn btn-primary">Place Hold</button>';
                    }
                    ?>
                </form>
                <br>
                <!-- MARC Record link -->
                <button type="button" class="btn btn-info" title="The MARC record is a librarian's view of the data" data-toggle="modal" data-target="#marcRecordModal">
                    MARC Record
                </button>
                <br>
                <!-- qr code -->
                <img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&chl=<?php echo $config->getPacServer() . '/iii/encore/record/C__Rb' . $_GET['bibid'] . '__Orightresult__U'?>&chld=H|0">
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php' ?>
<script>
    $(".alert").delay(4000).slideUp(200, function () {
        $(this).alert('close');
    });

    // delay load the bookjacket image
    function init() {
        var imgDefer = document.getElementsByTagName('img');
        for (var i=0; i<imgDefer.length; i++) {
            if(imgDefer[i].getAttribute('data-src')) {
                imgDefer[i].setAttribute('src',imgDefer[i].getAttribute('data-src'));
            } } }
    window.onload = init;
</script>
</body>
</html>