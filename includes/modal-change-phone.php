<div id="changePhoneModal" class="modal fade" tabindex="-1" role="dialog">
    <form id="newPhoneForm" name="newPhoneForm" onsubmit="return validatePhone()" class="form-horizontal" action="/includes/redirect-update-phone.php" method="post">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header my-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update Phone Number</h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <!-- Change phone number-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="newPhoneNumber">New Phone Number</label>
                            <div class="col-md-4">
                                <input id="newPhoneNumber" name="newPhoneNumber" type="text" placeholder="5555555555" class="form-control input-md" required="">

                            </div>
                        </div>

                        <input type="hidden" name="phoneArray" value="<?php echo htmlentities(serialize($thisPatron->getPhones())); ?>">
                        <input type="hidden" name="elementToUpdate" value="add">

                    </fieldset>
                    <div id="errorMsg" style="color: red; visibility: hidden">Incorrect phone format!</div>
                </div>

                <div class="modal-footer my-modal-footer">
                    <!-- this is really a submit button, but we let the javascript do the submitting -->
                    <button type="submit" name="newPhoneNumberBtn" class="btn btn-primary">OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->

<script>
    function validatePhone() {
       /* if (document.getElementById("newEmailAddress").value != document.getElementById("confirmNewEmailAddress").value)*/

       if (true)
       {
           document.newPhoneForm.elementToUpdate.value = arrayElementNo;
           return true;
        } else {
           document.getElementById("errorMsg").style.visibility = "visible";
           return false;

        }
    }
</script>