
<link rel="stylesheet" href="../../dist/css/modals.css">
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">ATAP Details</h5>
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
                <!-- <button type="button" class="btn btn-danger" id="confirm"> -->
                Confirm
                <!-- </button> -->
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
