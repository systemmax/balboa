<div id="changeAddressModal" class="modal fade" tabindex="-1" role="dialog">

    <form id="newAddressForm" name="newAddressForm" onsubmit="return validateAddress()" class="form-horizontal" action="/includes/redirect-update-address.php" method="post">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header my-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update Address</h4>
                </div>


                <div class="modal-body">
                    <fieldset>
                        <!-- Change Address-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="newAddress">New Address</label>
                            <div class="col-md-4">
                                <input id="newAddress0" name="newAddress0" type="text" placeholder="1111 Street Name" class="form-control input-md" required="">
                                <input id="newAddress1" name="newAddress1" type="text" placeholder="City, ST 11111" class="form-control input-md" required="">

                            </div>
                        </div>

                        <input type="hidden" name="addressArray" value="<?php echo htmlentities(serialize($thisPatron->getPatronAddresses())); ?>">
                        <input type="hidden" name="elementToUpdate" value="add">

                    </fieldset>
                    <div id="errorMsg" style="color: red; visibility: hidden">Incorrect address format!</div>
                </div>

                <div class="modal-footer my-modal-footer">
                    <!-- this is really a submit button, but we let the javascript do the submitting -->
                    <button type="submit" name="newAddressBtn" class="btn btn-primary">OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </form>


</div><!-- /.modal-dialog -->

<script>
    function validateAddress() {
        /* if (document.getElementById("newEmailAddress").value != document.getElementById("confirmNewEmailAddress").value)*/

        if (true)
        {
            document.newAddressForm.elementToUpdate.value = arrayElementNo;
            return true;
        } else {
            document.getElementById("errorMsg").style.visibility = "visible";
            return false;

        }
    }
</script>