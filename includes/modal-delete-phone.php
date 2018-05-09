<div id="deletePhoneModal" class="modal fade" tabindex="-1" role="dialog">
    <form id="deletePhoneForm" name="deletePhoneForm" class="form-horizontal" onsubmit="setPhoneArrayElementValueToDelete()" action="/includes/redirect-delete-phone.php" method="post">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header my-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Phone Number</h4>
                </div>
                <div class="modal-body">
                    <br>
                    Delete this phone number?
                    <br><br>
                    <fieldset>
                         <input type="hidden" name="phoneArray" value="<?php echo htmlentities(serialize($thisPatron->getPhones())); ?>">
                        <input type="hidden" name="elementToDelete" value="">
                    </fieldset>
                </div>

                <div class="modal-footer my-modal-footer">
                    <!-- this is really a submit button, but we let the javascript do the submitting -->
                    <button type="submit" name="deletePhoneNumberBtn" class="btn btn-success">Yes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->

<script>
    function setPhoneArrayElementValueToDelete(){
        document.deletePhoneForm.elementToDelete.value = arrayElementNo;
        return true;
    }
</script>