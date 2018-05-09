<?php
session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/config.php");
$config = new config();

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <?php include 'includes/head-link.php' ?>
    <title><?php echo $config->getInstitutionName() ?> | Registration</title>
</head>
<body>
<?php include "includes/header.php" ?>

<div class="container well">

    <div class="page-header">
        <h1>Registration Form</h1>
    </div>
    <div class="well">
        <form onsubmit="return validateRegistrationForm()" id="registrationForm" name="registrationForm" class="form-horizontal" action="/includes/redirect-register-patron.php" method="post">
            <fieldset>

                <div id="registration-alert" class="alert alert-info" role="alert">All fields are required.</div>

                <!-- First Name-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="firstname">First Name</label>
                    <div class="col-md-4">
                        <input id="firstname" name="firstname" type="text" placeholder="" class="form-control input-md" required="" autofocus>

                    </div>
                </div>

                <!-- Last name-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="lastname">Last Name</label>
                    <div class="col-md-4">
                        <input id="lastname" name="lastname" type="text" placeholder="" class="form-control input-md" required="">

                    </div>
                </div>

                <!-- Address-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="streetaddress">Street Address</label>
                    <div class="col-md-4">
                        <input id="streetaddress" name="streetaddress" type="text" placeholder="e.g. 1234 5th Street" class="form-control input-md" required="">

                    </div>
                </div>

                <!-- City, State ZIP-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="citystatezip">City, State ZIP</label>
                    <div class="col-md-4">
                        <input id="citystatezip" name="citystatezip" type="text" placeholder="e.g. Emeryville, CA 94608" class="form-control input-md" required="">

                    </div>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="phone">Phone Number</label>
                    <div class="col-md-4">
                        <input id="phone" name="phone" type="text" placeholder="e.g. 5555555555" class="form-control input-md" required="">

                    </div>
                </div>

                <!-- Email address-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="email">Email Address</label>
                    <div class="col-md-4">
                        <input id="email" name="email" type="text" placeholder="e.g. yourname@domain.com" class="form-control input-md" required="">

                    </div>
                </div>

                <!-- PIN-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="pin">PIN</label>
                    <div class="col-md-4">
                        <input id="pin" name="pin" type="password" placeholder="" class="form-control input-md" pattern="^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$" required="">
                        <span class="help-block">Must be at least 4 characters long, and contain letters and numbers ONLY</span>
                    </div>
                </div>

                <!-- PIN confirm-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="confirmpin">Confirm PIN</label>
                    <div class="col-md-4">
                        <input id="confirmpin" name="confirmpin" type="password" placeholder="same as above" class="form-control input-md" required="">

                    </div>
                </div>

                <!-- Submit or clear form -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="submit"></label>
                    <div class="col-md-8">
                        <button id="submit" name="submitRegForm" class="btn btn-success">Submit</button>
                        <button id="cancel" name="cancel" class="btn btn-danger">Clear Form</button>
                    </div>
                </div>

            </fieldset>
        </form>
    </div>


</div>

<?php include 'includes/footer.php' ?>

<script>
    function validateRegistrationForm() {
        // all fields are required
        var a = document.forms["registrationForm"]["firstname"].value;
        var b = document.forms["registrationForm"]["lastname"].value;
        var c = document.forms["registrationForm"]["streetaddress"].value;
        var d = document.forms["registrationForm"]["citystatezip"].value;
        var e = document.forms["registrationForm"]["email"].value;
        var f = document.forms["registrationForm"]["pin"].value;
        var g = document.forms["registrationForm"]["confirmpin"].value;
        if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="",e==null || e=="",f==null || f=="",g==null || g=="")
            {
            document.getElementById("registration-alert").classList.remove("alert-info");
            document.getElementById("registration-alert").classList.add("alert-danger");
            document.getElementById("registration-alert").innerHTML = "Please fill in all fields.";
            return false;
        }
        // the two PINs must match
        if (document.forms["registrationForm"]["pin"].value === document.forms["registrationForm"]["confirmpin"].value) {
            return true;
        } else {
            document.getElementById("registration-alert").classList.remove("alert-info");
            document.getElementById("registration-alert").classList.add("alert-danger");
            document.getElementById("registration-alert").innerHTML = "PINs must match.";
            return false;
        }
    }
</script>

</body>
</html>