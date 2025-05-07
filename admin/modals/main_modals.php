
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

<div class="modal fade" id="create_new_atap_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create New ATAP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
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

<div class="modal fade" id="createOrModal" tabindex="-1" role="dialog" aria-labelledby="createOrModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createOrModalLabel">Create New OR</h5>
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
                <button type="button" class="btn btn-danger" id="confirm"> 
                Confirm
                </button>
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
              <th>Last Name (Buyer 1)</th>
              <th>First Name (Buyer 1)</th>
              <th>Middle Name (Buyer 1)</th>
              <th>Last Name (Buyer 2)</th>
              <th>First Name (Buyer 2)</th>
              <th>Middle Name (Buyer 2)</th>
              <th>Email Address</th>
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

<div class="modal fade" id="previewCarModal" tabindex="-1" role="dialog" aria-labelledby="previewCarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewCarModalLabel">CAR Preview</h5>
                <button onclick="closeModal3()" class="btn customized-modal" data-dismiss="modal">x</button>
            </div>
            <div class="modal-body">
                <iframe id="previewCarIframe" src="<?php echo base_url; ?>print/preview_car.php" style="width: 100%; height: 400px; border: none;"></iframe>
                <div id="previewCarContent" style="display: none;"></div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewORModal" tabindex="-1" role="dialog" aria-labelledby="previewORModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewORModalLabel">OR Preview</h5>
                <button onclick="closeModal3()" class="btn customized-modal" data-dismiss="modal">x</button>
            </div>
            <div class="modal-body">
                <iframe id="previewORIframe" src="<?php echo base_url; ?>print/preview_or.php" style="width: 100%; height: 500px; border: none;"></iframe>
                <div id="previewORContent" style="display: none;"></div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- Hindi ubra yung modal-lg, kaya hindi responsive yung resizing ng modal ihh nasa (modals.css) -->
<div class="modal fade" id="viewModalsummary" tabindex="-1" role="dialog" aria-labelledby="viewModalSummaryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalSummaryLabel">CAR Transaction List</h5>
                <button onclick="closeModal4()" class="btn customized-modal" data-dismiss="modal">x</button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="transferHistoryModal" tabindex="-1" role="dialog" aria-labelledby="transferHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferHistoryModalLabel">Transfer History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="resultsContainer">Loading...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createTinModal" tabindex="-1" role="dialog" aria-labelledby="createTinModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 70vw;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTinModalLabel">TIN Number List</h5>
                <button onclick="closeModal()" class="btn customized-modal"" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
