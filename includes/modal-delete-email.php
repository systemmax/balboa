<div id="deleteEmailModal" class="modal fade" tabindex="-1" role="dialog">
    <form id="deleteEmailForm" name="deleteEmailForm" class="form-horizontal" onsubmit="setEmailArrayElementValueToDelete()" action="/includes/redirect-delete-email.php" method="post">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header my-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Email Address</h4>
                </div>
                <div class="modal-body">
                    <br>
                    Delete this email address?
                    <br><br>
                    <fieldset>
                         <input type="hidden" name="emailArray" value="<?php echo htmlentities(serialize($thisPatron->getEmails())); ?>">
                        <input type="hidden" name="elementToDelete" value="">
                    </fieldset>
                </div>

                <div class="modal-footer my-modal-footer">
                    <!-- this is really a submit button, but we let the javascript do the submitting -->
                    <button type="submit" name="deleteEmailAddress" class="btn btn-success">Yes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->

<script>
    function setEmailArrayElementValueToDelete(){
        document.deleteEmailForm.elementToDelete.value = arrayElementNo;
        return true;
    }
</script>