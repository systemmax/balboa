<div id="deleteAddressModal" class="modal fade" tabindex="-1" role="dialog">
    <form id="deleteAddressForm" name="deleteAddressForm" class="form-horizontal" onsubmit="setAddressArrayElementValueToDelete()" action="/includes/redirect-delete-address.php" method="post">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header my-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Address</h4>
                </div>
                <div class="modal-body">
                    <br>
                    Delete this address?
                    <br><br>
                    <fieldset>
                         <input type="hidden" name="addressArray" value="<?php echo htmlentities(serialize($thisPatron->getPatronAddresses())); ?>">
                        <input type="hidden" name="elementToDelete" value="">
                    </fieldset>
                </div>

                <div class="modal-footer my-modal-footer">
                    <!-- this is really a submit button, but we let the javascript do the submitting -->
                    <button type="submit" name="deleteAddressBtn" class="btn btn-success">Yes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->

<script>
    function setAddressArrayElementValueToDelete(){
        document.deleteAddressForm.elementToDelete.value = arrayElementNo;
        return true;
    }
</script>