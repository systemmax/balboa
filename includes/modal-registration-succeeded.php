<div id="successfulRegistrationModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header my-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $config->getInstitutionName() ?></h4>
            </div>
            <div class="modal-body my-modal-body">
                <p>Registration was successful! You will have 30 days to get a permanent card from the library.</p>
                <p>Your temporary barcode number is: <?php echo $_SESSION['barcode']; ?></p>
            </div>
            <div class="modal-footer my-modal-footer">
                <button type="button" class="btn center-block btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

