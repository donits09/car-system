
<link rel="stylesheet" href="../../dist/css/modals.css">
<div class="modal fade" id="createCarPrevModal" tabindex="-1" role="dialog" aria-labelledby="createCarModalPrevLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCarModalPrevLabel">Create New Car</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               
            </div>
        </div>
    </div>
</div>


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

<div class="modal fade" id="createCarModal" tabindex="-1" role="dialog" aria-labelledby="createCarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCarModalLabel">Create New Car</h5>
                <button onclick="closeModal()" class="btn customized-modal"" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- 
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Car Details</h5>
                <button onclick="closeModal()" class="btn customized-modal" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <form id="edit-car-form">
                    <div class="form-group">
                        <label for="edit-account-no">Account No.</label>
                        <input type="text" class="form-control" id="edit-c-account-no" name="c_account_no" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-payment-type">Payment Type</label>
                        <select class="form-control" id="edit-c-car-type" name="c_car_type" required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-amount">Amount</label>
                        <input type="text" class="form-control" id="edit-c-car-amount" name="c_car_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-car-no">CAR No.</label>
                        <input type="text" class="form-control" id="edit-c-car-no" name="c_car_no" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-pay-date">Pay Date</label>
                        <input type="date" class="form-control" id="edit-c-car-paydate" name="c_car_paydate" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-encoder">Encoded by</label>
                        <input type="text" class="form-control" id="edit-c-encoded-by" name="c_encoded_by" readonly>
                    </div>
                    <input type="hidden" id="edit-id" name="id">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div> -->

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
                <button type="button" class="btn btn-danger" id="confirm">Delete</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="multipleResultsModal" tabindex="-1" role="dialog" aria-labelledby="multipleResultsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="multipleResultsModalLabel">Select Buyer</h5>
        <button onclick="closeModal()" class="btn customized-modal" data-dismiss="modal">x</button>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Account No</th>
              <th>Last Name</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Location</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="multipleResultsBody">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Preview Car -->
<!-- <div class="modal fade" id="previewCarModal" tabindex="-1" role="dialog" aria-labelledby="previewCarModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="height: 80vh;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewCarModalLabel">CAR Preview</h5>
            </div>
            <div class="modal-body" onclick="closePreviewModal()">
                <iframe id="previewCarIframe" style="width: 100%; height: 500px;" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelled</button>
            </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="previewCarModal" tabindex="-1" role="dialog" aria-labelledby="previewCarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewCarModalLabel">CAR Preview</h5>
            </div>
            <div class="modal-body" onclick="closePreviewModal()">
                <iframe id="previewCarIframe" style="width: 100%; height: 310px;" frameborder="0"></iframe>
            </div>
            </div>
        </div>
    </div>
</div>






