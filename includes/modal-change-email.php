<div id="changeEmailModal" class="modal fade" tabindex="-1" role="dialog">
    <form id="newEmailForm" name="newEmailForm" onsubmit="return validateEmail()" class="form-horizontal" action="/includes/redirect-update-email.php" method="post">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header my-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update Email Address</h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <!-- New Email Address-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="newEmailAddress">New Email Address</label>
                            <div class="col-md-4">
                                <input id="newEmailAddress" name="newEmailAddress" type="email" placeholder="yourname@domain.com" class="form-control input-md" required="">
                            </div>
                        </div>

                        <!-- Confirm Email Address-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="confirmNewEmailAddress">Confirm New Email Address</label>
                            <div class="col-md-4">
                                <input id="confirmNewEmailAddress" name="confirmNewEmailAddress" type="email" placeholder="yourname@domain.com" class="form-control input-md" required="">

                            </div>
                        </div>

                        <input type="hidden" name="emailArray" value="<?php echo htmlentities(serialize($thisPatron->getEmails())); ?>">
                        <input type="hidden" name="elementToUpdate" value="add">

                    </fieldset>
                    <div id="errorMsg" style="color: red; visibility: hidden">The two email addresses must match!</div>
                </div>

                <div class="modal-footer my-modal-footer">
                    <!-- this is really a submit button, but we let the javascript do the submitting -->
                    <button type="submit" name="newEmailAddressBtn" class="btn btn-primary">OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->

<script>
    function validateEmail() {
        // the two email addresses must match
        if (document.getElementById("newEmailAddress").value != document.getElementById("confirmNewEmailAddress").value) {
            document.getElementById("errorMsg").style.visibility = "visible";
            return false;
        } else {
            document.newEmailForm.elementToUpdate.value = arrayElementNo;
            return true;
        }
    }
</script>