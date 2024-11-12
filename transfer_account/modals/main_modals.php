
<link rel="stylesheet" href="../../dist/css/modals.css">

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">CAR Payment Details</h5>
                <button onclick="closeModal()" class="btn customized-modal" data-dismiss="modal">x</button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="confirm_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm_modal_label">Confirmation</h5>
                <button onclick="closeModal()" class="btn customized-modal" data-dismiss="modal">x</button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm"> 
                Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createTransferModal" tabindex="-1" role="dialog" aria-labelledby="createTransferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTransferModalLabel">Create New Transfer</h5>
                <button onclick="closeModal()" class="btn customized-modal"" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>