<?php

session_start();

if (!isset($_SESSION["validStaffUser"])) {
    header("location: staff-login.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/config.php');

if (isset($_GET['reset'])) {
    config::removeConfigFile();
}

$config = new config();
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <?php include 'includes/head-link.php' ?>
    <title><?php echo $config->getInstitutionName() ?> | Settings</title>
</head>
<body data-spy="scroll" data-target="#myScrollSpy" data-offset="60">
<?php include "includes/header.php" ?>
<div class="container well">
    <h1><span class="glyphicon glyphicon-cog"></span>  Website Configuration</h1>
    <div class="col-xs-12 col-md-9">
        <form id="config-form" class="form-horizontal" method="post" action="includes/redirect-update-config.php" onsubmit="return validateForm();">
            <fieldset>
                <br>
                You can customize the configuration of this website or <a href="admin.php?reset=true">reset the form to its default settings.</a>
                <br><br>
                <!-- Form Name -->
                <div id="general" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">General</a></h3>
                    </div>
                    <div class="panel-body">
                        <!-- Institution Name-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="institutionName">Institution Name:</label>
                            <div class="col-md-4">
                                <input id="institutionName" name="institutionName" type="text" placeholder="e.g. Innovative University Library" class="form-control input-md" value="<?php echo $config->getInstitutionName(); ?>" required="">
                                <span class="help-block">Be sure to include "Library" as part of the name.</span>
                            </div>
                        </div>

                        <!-- Logo File Name-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="logoFileName">Logo File Name:</label>
                            <div class="col-md-4">
                                <input id="logoFileName" name="logoFileName" type="text" placeholder="e.g. images/library-logo.png" class="form-control input-md" value="<?php echo $config->getLogoFileName(); ?>">
                                <span class="help-block">Provide the path and filename of a logo image (preferably 50 X 150 pixels). If you leave this field blank, the institution name will display as the logo. This can also be the URL of an image file located on a remote server.</span>
                            </div>
                        </div>

                        <!-- Theme -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="bootstrapTheme">Theme:</label>
                            <div class="col-md-4">
                                <select id="bootstrapTheme" name="bootstrapTheme" class="form-control">
                                    <?php
                                    // read the list of themes from a json file and iterate through them to populate the control
                                    $themeData = file_get_contents('json/themes.json');
                                    $themeArray = json_decode($themeData, true);

                                    foreach ($themeArray['bootstrapThemes'] as $thisTheme) {
                                        echo '<option value="' . $thisTheme['url'] . '"';
                                        if ($thisTheme['url'] == $config->getBootstrapTheme()) {
                                            echo ' selected="selected"';
                                        }
                                        echo '>' . $thisTheme['description'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="help-block">Change the look of your website. To see examples of the various themes, visit: <a href="https://bootswatch.com/" target="_blank">Bootswatch.com</a>.</span>
                            </div>
                        </div>

                        <!-- Locale -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="locale">Locale:</label>
                            <div class="col-md-4">
                                <select id="locale" name="locale" class="form-control">
                                    <?php
                                    // read the list of locales from a json file and iterate through them to populate the control
                                    $localeData = file_get_contents('json/locales.json');
                                    $localeArray = json_decode($localeData, true);

                                    foreach ($localeArray['locales'] as $thisLocale) {
                                        echo '<option value="' . $thisLocale['code'] . '"';
                                        if ($thisLocale['code'] == $config->getLocale()) {
                                            echo ' selected="selected"';
                                        }
                                        echo '>' . $thisLocale['description'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="help-block">The locale setting mostly affects date formats and how the word catalog/catalogue is spelled.</span>
                            </div>
                        </div>

                        <!-- Support To Do List -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="supportToDoList">Support To Do List?</label>
                            <div class="col-md-4">
                                <div class="radio">
                                    <label for="supportToDoList-1">
                                        <input type="radio" name="supportToDoList" id="supportToDoList-1" value="1"<?php  if ($config->getSupportToDoList() == '1') echo ' checked="checked"' ?>>
                                        Yes
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="supportToDoList-0">
                                        <input type="radio" name="supportToDoList" id="supportToDoList-0" value="0"<?php  if ($config->getSupportToDoList() == '0') echo ' checked="checked"' ?>>
                                        No
                                    </label>
                                </div>
                                <span class="help-block">The To-do list should only be enabled during the development process. Turn support OFF during demos to optimize website performance.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="servers" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Servers</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Sierra Server-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="db">Sierra Server:</label>
                            <div class="col-md-4">
                                <input id="db" name="db" type="text" placeholder="e.g. sierra-academic.iii.com" class="form-control input-md" value="<?php echo $config->getDB(); ?>" required="">

                            </div>
                        </div>

                        <!-- Encore Server-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="pacServer">Encore Server:</label>
                            <div class="col-md-4">
                                <input id="pacServer" name="pacServer" type="text" placeholder="e.g. encore-academic.iii.com" class="form-control input-md" value="<?php echo $config->getPacServer(); ?>" required="">

                            </div>
                        </div>

                        <!-- INN-Reach Server-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="irServer">INN-Reach Server:</label>
                            <div class="col-md-4">
                                <input id="irServer" name="irServer" type="text" placeholder="e.g. encorecalstate.iii.com" class="form-control input-md" value="<?php echo $config->getIrServer(); ?>" required="">

                            </div>
                        </div>

                        <!-- Vital Server-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="vitalServer">Vital Server:</label>
                            <div class="col-md-4">
                                <input id="vitalServer" name="vitalServer" type="text" placeholder="e.g. vital-share.iii.com:8080" class="form-control input-md" value="<?php echo $config->getVitalServer(); ?>" required="">

                            </div>
                        </div>

                    </div>
                </div>

                <div id="sierraApi" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sierra API</h3>
                    </div>
                    <div class="panel-body">
                        <!-- API Version-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="apiVer">API Version:</label>
                            <div class="col-md-4">
                                <input id="apiVer" name="apiVer" type="text" placeholder="e.g. 3" class="form-control input-md" value="<?php echo $config->getApiVer(); ?>" required="">
                                <span class="help-block">Version number currently installed on the Sierra server</span>
                            </div>
                        </div>

                        <!-- API Key -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="apiKey">API Key:</label>
                            <div class="col-md-4">
                                <input id="apiKey" name="apiKey" type="text" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxx" class="form-control input-md" value="<?php echo $config->getApiKey(); ?>" required="">
                                <span class="help-block">A 28 character string provided by your Sierra Administrator.</span>
                            </div>
                        </div>

                        <!-- API Secret-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="apiSecret">API Secret:</label>
                            <div class="col-md-4">
                                <input id="apiSecret" name="apiSecret" type="text" placeholder="**********" class="form-control input-md" value="<?php echo $config->getApiSecret(); ?>" required="">
                                <span class="help-block">Generated by a user once they've been assigned an API Key.</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="enrichedContentCredentials" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Enriched Content Credentials</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Content Cafe Username-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="contentCafeID">Content Cafe ID:</label>
                            <div class="col-md-4">
                                <input id="contentCafeID" name="contentCafeID" type="text" placeholder="e.g. Innovative" class="form-control input-md" value="<?php echo $config->getContentCafeID(); ?>" required="">
                                <span class="help-block">Required for providing cover art images on the carousel and title display.</span>
                            </div>
                        </div>

                        <!-- Content Cafe Password-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="contentCafePassword">Content Cafe Password:</label>
                            <div class="col-md-4">
                                <input id="contentCafePassword" name="contentCafePassword" type="text" placeholder="e.g. Goldengate" class="form-control input-md" value="<?php echo $config->getContentCafePassword(); ?>" required="">

                            </div>
                        </div>

                    </div>
                </div>

                <div id="userCommentEmail" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">User Comment Email</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Send Email -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="commentSendEmail">Send Email?</label>
                            <div class="col-md-4">
                                <div class="radio">
                                    <label for="commentSendEmail-1">
                                        <input type="radio" name="commentSendEmail" id="commentSendEmail-1" value="1"<?php  if ($config->getCommentSendEmail() == '1') echo ' checked="checked"' ?>>
                                        Yes
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="commentSendEmail-0">
                                        <input type="radio" name="commentSendEmail" id="commentSendEmail-0" value="0"<?php  if ($config->getCommentSendEmail() == '0') echo ' checked="checked"' ?>>
                                        No
                                    </label>
                                </div>
                                <span class="help-block">You can have each user comment sent as an email to a designated library email address. (All user comments are saved to a log file regardless of your email preference.)</span>

                            </div>
                        </div>

                        <!-- Originating Email-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="commentSender">Originating Email:</label>
                            <div class="col-md-4">
                                <input id="commentSender" name="commentSender" type="text" placeholder="e.g. ed.sanchez@library.edu" class="form-control input-md" value="<?php echo $config->getCommentSender(); ?>">
                                <span class="help-block">When the system sends an email with a patron comment, this is the address the email will show as the originator.</span>
                            </div>
                        </div>

                        <!-- Reply To-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="replyTo">Reply To:</label>
                            <div class="col-md-4">
                                <input id="replyTo" name="replyTo" type="text" placeholder="e.g. NO.REPLY@library.edu" class="form-control input-md" value="<?php echo $config->getReplyTo(); ?>">
                                <span class="help-block">A reply-to address must be supplied, but in most cases, a dummy address is sufficient.</span>
                            </div>
                        </div>

                        <!-- Comment Recipient-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="commentRecipient">Comment Recipient:</label>
                            <div class="col-md-4">
                                <input id="commentRecipient" name="commentRecipient" type="text" placeholder="e.g. ed.sanchez@library.edu" class="form-control input-md" value="<?php echo $config->getCommentRecipient(); ?>">
                                <span class="help-block">The email address where comments left on the website will be sent.</span>
                            </div>
                        </div>

                    </div>
                </div>


                <div id="carousel-admin" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Carousel</h3>
                    </div>
                    <div class="panel-body">

                        <!-- Carousel Title -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="carouselTitle">Carousel Title:</label>
                            <div class="col-md-4">
                                <input id="carouselTitle" name="carouselTitle" type="text" placeholder="e.g. Faculty Publications" class="form-control input-md" value="<?php echo $config->getCarouselTitle(); ?>" required="">
                                <span class="help-block">The header which displays above the rotating book carousel.</span>
                            </div>
                        </div>

                        <!-- Carousel Query String -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="carouselQueryString">Carousel Query (JSON): </label>
                            <div class="col-md-4">
                                <textarea id="carouselQueryString" name="carouselQueryString" class="form-control input-md" required=""><?php echo $config->getCarouselQueryString(); ?></textarea>
                                <span class="help-block">A JSON string copied from a review file whose contents we want to show in the carousel.</span>
                            </div>
                        </div>

                        <!-- Carousel Refresh -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="carouselDataRefresh">Carousel Data Refresh (hours):</label>
                            <div class="col-md-4">
                                <select id="carouselDataRefresh" name="carouselDataRefresh" class="form-control">
                                    <?php
                                    // create 1-24 as options in the control.  If we hit the value currently defined in the
                                    // config object, we set that as selected by default.
                                    for ($i=0; $i<=24; $i++) {
                                        echo '<option value="' . $i . '"';
                                        if ($i == $config->getCarouselDataRefresh()) {
                                            echo ' selected="selected"';
                                        }
                                        echo '>' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="help-block">The carousel data takes time to compile so we cache it and only refresh it after the number of hours specified here. Enter 0 to suppress automatic refreshes on page load.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="searchResults" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Search Results</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Exclude bibs without ISBNs -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="excludeBibsWithoutISBN">Exclude Bibs Without ISBNs?</label>
                            <div class="col-md-4">
                                <div class="radio">
                                    <label for="excludeBibsWithoutISBN-1">
                                        <input type="radio" name="excludeBibsWithoutISBN" id="excludeBibsWithoutISBN-1" value="1"<?php  if ($config->getExcludeBibsWithoutISBN() == '1') echo ' checked="checked"' ?>>
                                        Yes
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="excludeBibsWithoutISBN-0">
                                        <input type="radio" name="excludeBibsWithoutISBN" id="excludeBibsWithoutISBN-0" value="0"<?php  if ($config->getExcludeBibsWithoutISBN() == '0') echo ' checked="checked"' ?>>
                                        No
                                    </label>
                                </div>
                                <span class="help-block">A title needs to have an ISBN to display cover art. Say "YES" here to possibly reduce the number of "No image" book jackets in your carousel.</span>

                            </div>
                        </div>

                        <!-- Link to Encore -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="hyperlinkBibsToEncore">Titles Hyperlink To:</label>
                            <div class="col-md-4">
                                <select id="hyperlinkBibsToEncore" name="hyperlinkBibsToEncore" class="form-control">
                                    <option value="0" <?php if ($config->getHyperlinkBibsToEncore() == 0) echo ' selected="selected"'; ?>>This Site</option>
                                    <option value="1" <?php if ($config->getHyperlinkBibsToEncore() == 1) echo ' selected="selected"'; ?>>Encore</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="holdPickUp" class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Holds</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Default Pickup Location for Holds-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="defaultHoldPickupLocationCode">Default Hold Pickup Location Code:</label>
                            <div class="col-md-4">
                                <input id="defaultHoldPickupLocationCode" name="defaultHoldPickupLocationCode" type="text" placeholder="e.g. m" class="form-control input-md" value="<?php echo $config->getDefaultHoldPickupLocationCode(); ?>" required="">
                                <span class="help-block">Must be the letter code representing the pickup location; for example, m for Main Library</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Button (Double) -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="submit"></label>
                    <div class="col-md-8">
                        <button
                                id="submit"
                                name="submit"
                                type="submit"
                                class="btn btn-success"
                                data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>"
                        >
                            Save Configuration
                        </button>
                        <button id="clear" name="clear" type="button" class="btn btn-danger" onclick="window.location='index.php'">Cancel</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>


    <!--Navigation menu -->
    <div class="col-xs-0 col-md-3 visible-lg-* visible-md-* hidden-sm hidden-xs">
        <nav class="navbar" id="myScrollSpy" data-spy="affix">
            <ul class="nav nav-pills nav-stacked">
                <li class=""><a href="#general">General</a></li>
                <li class=""><a href="#servers">Servers</a></li>
                <li class=""><a href="#sierraApi">Sierra API</a></li>
                <li class=""><a href="#enrichedContentCredentials">Enriched Content Credentials</a></li>
                <li class=""><a href="#userCommentEmail">User Comment Email</a></li>
                <li class=""><a href="#carousel-admin">Carousel</a></li>
                <li class=""><a href="#searchResults">Search Results</a></li>
                <li class=""><a href="#holdPickUp">Default Hold Pickup Location</a></li>
            </ul>
        </nav>
    </div>
</div>

<?php include 'includes/footer.php' ?>

<script>
    // necessary because we use a fixed navbar at the top.  Without this code when you hyperlink to a section,
    // the top 50 pixels are hidden by the top navbar.
    var offset = 60;
    $('#myScrollSpy li a').click(function(event) {
        event.preventDefault();
        $($(this).attr('href'))[0].scrollIntoView();
        scrollBy(0, -offset);
    });

    // disable email fields if send email is NO.
    jQuery(function($) {
        $('#commentSendEmail-0,#commentSendEmail-1').click(function() {
            var sendNo = $('#commentSendEmail-0').is(':checked');
            $('#commentSender, #replyTo, #commentRecipient').prop('disabled', (sendNo));
        });
    });

    function validateForm() {
        $carouselQueryString = document.getElementById('carouselQueryString').value;
        // we allow a blank query string
        if ($carouselQueryString == '') {
            return true;
        }
        // but if you enter a query string, it has to be valid JSON
        try {
            JSON.parse($carouselQueryString);
        } catch (e) {
            alert("Your Carousel Query String is not valid JSON. Please correct it.");
            return false;
        }
    }
</script>
</body>
</html>s