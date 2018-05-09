<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/config.php");
$config = new config();

// default alert values
$alertMessage = "You must be a Sierra staff member to update the website configuration.";
$alertType = "alert-info";


// validate patron credentials if we've been passed login data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "classes/sierra-staff-authentication.php";
    $staffAuthObj = new sierraStaffAuthentication($config,$_POST["username"],$_POST["password"]);
    if ($staffAuthObj->isValidLogin() == true) {

        // we're gold so let them on to the account page.
        $_SESSION['validStaffUser'] = true;
        header("Location: admin.php");
    } else {
        // Staff credentials don't authenticate login was INvalid
        $alertMessage = "NOT a valid staff username/password!  Please try again.";
        $alertType = "alert-danger";

        unset($_SESSION['validStaffUser']);
    }
}
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <?php include 'includes/head-link.php' ?>
    <title><?php echo $config->getInstitutionName() ?> | Staff Login</title>
</head>
<body>
<?php include "includes/header.php" ?>
<div class="container well">

    <h1><?php echo $config->getInstitutionName() ?> <span class="text-danger">Staff Login</span></h1>
    <br>
    <form class="form-horizontal" method="post">
        <fieldset>
            <div class="alert <?php echo $alertType ?>" role="alert"><?php echo $alertMessage ?></div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="barcode">Username:</label>
                <div class="col-md-4">
                    <input id="username" name="username" type="text" autofocus placeholder="Authorized Sierra staff username" class="form-control input-md" required="">
                </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="password">Password:</label>
                <div class="col-md-4">
                    <input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required="">
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
                        Login
                    </button>
                    <button id="clear" name="clear" type="button" class="btn btn-danger" onclick="window.location='index.php';">Cancel</button>
                </div>
            </div>

        </fieldset>
    </form>
</div>

<?php include 'includes/footer.php' ?>
</body>
</html>