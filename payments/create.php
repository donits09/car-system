<div class="card-footer text-right">
    <a class="btn btn-flat btn-default bg-maroon" href="./?page=inventory/house-list">
        <i class='fa fa-arrow-left'></i> Back to List
    </a>
</div>
<div class="card card-outline rounded-0 card-maroon">
    <div class="card-header">
        <h3 class="card-title"><b><i><?php echo isset($_GET['id']) ? "House Details" : "Add House" ?></i></b></h3>
    </div>

    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="info-section">
                        <span class="info-label">Phase:</span>
                        <span class="info-value"><?php echo isset($meta['c_acronym']) ? $meta['c_acronym'] : 'N/A'; ?></span>
                    </div>
                    
                    <div class="info-section">
                        <span class="info-label">Block:</span>
                        <span class="info-value"><?php echo isset($meta['c_block']) ? $meta['c_block'] : 'N/A'; ?></span>
                    </div>

                    <div class="info-section">
                        <span class="info-label">Lot:</span>
                        <span class="info-value"><?php echo isset($meta['c_lot']) ? $meta['c_lot'] : 'N/A'; ?></span>
                    </div>

                    <div class="info-section">
                        <span class="info-label">Floor Area (sqm):</span>
                        <span class="info-value"><?php echo isset($meta['c_floor_area']) ? $meta['c_floor_area'] : 'N/A'; ?></span>
                    </div>

                    <div class="info-section">
                        <span class="info-label">House Price per SQM:</span>
                        <span class="info-value"><?php echo isset($meta['c_h_price_sqm']) ? number_format($meta['c_h_price_sqm'], 2) : 'N/A'; ?></span>
                    </div>

                    <div class="info-section">
                        <span class="info-label">House Contract Price:</span>
                        <span class="info-value"><?php echo isset($meta['prod_hcp']) ? number_format($meta['prod_hcp'], 2) : 'N/A'; ?></span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="info-section">
                        <span class="info-label">House Model:</span>
                        <span class="info-value"><?php echo isset($meta['c_model']) ? $meta['c_model'] : 'N/A'; ?></span>
                    </div>

                    <div class="info-section">
                        <span class="info-label">House Status:</span>
                        <span class="info-value"><?php echo isset($meta['c_status']) ? $meta['c_status'] : 'N/A'; ?></span>
                    </div>

                    <div class="info-section">
                        <span class="info-label">Unit Status:</span>
                        <span class="info-value"><?php echo isset($meta['c_unit_status']) ? $meta['c_unit_status'] : 'N/A'; ?></span>
                    </div>

                    <div class="info-section">
                        <span class="info-label">Remarks:</span>
                        <textarea class="textarea-view" readonly><?php echo isset($meta['c_remarks']) ? $meta['c_remarks'] : 'N/A'; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
</div>