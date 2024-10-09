<?php 
session_start();

require_once('../../inc/check_session.php');
check_user_group(1);

include('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');     
$current_date = date('Y-m-d');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url; ?>dist/css/jquery-ui.css" rel="stylesheet">
    <script src="<?php echo base_url; ?>dist/js/jquery-3.5.1.min.js"></script>
    <script src="<?php echo base_url; ?>dist/js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/car_reports.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">
</head>
<body>
<div class="container mt-5">
    <div class="card mt-3">
        <div class="main_header">
            <div id="header">ASIAN LAND STRATEGIES CORPORATION</div>
            <div id="subheader">DAILY COLLECTION & DEPOSIT REPORT</div>
            <div id="current_date"><?php echo date("Y-m-d"); ?></div>
            <div id="subheader">OR</div>
        </div>
        <hr>
        <div class="sub_container">
                <div class="date_container">
                    <b>Search by Transaction Date</b><hr>
                    <div class="pd-20">
                        <label for="start_date">Start Date:</label>
                        <input type="text" id="start_date" class="form-control datepicker" value="<?php echo date('m/d/Y'); ?>" />
                        <label for="end_date" class="mt-2">End Date:</label>
                        <input type="text" id="end_date" class="form-control datepicker" value="<?php echo date('m/d/Y'); ?>" />
                        <button id="filter" class="btn btn-primary mt-2"><span class="fa fa-filter"></span> Filter</button>
                        <button id="reset" class="btn btn-secondary mt-2"><span class="fa fa-refresh"></span> Reset</button>
                    </div>
                </div>
                <div class="btn_container">
                    <button id="export_pdf" class="btn btn-danger mt-2" href="javascript:void(0)"><span class="fa fa-download"></span> Export as PDF</button>
                    <button id="export_csv" class="btn btn-flat btn-success mt-2" href="javascript:void(0)"><span class="fa fa-download"></span> Export as CSV</button>
                </div>
            </div>
        <hr>
        <div class="table-container">
            <table class="table table-bordered table-striped" id="car-table">
                <thead>
                <tr>
                        <th>No</th>
                        <th>Account No.</th>
                        <th>OR No.</th>
                        <th>Transaction Type</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Cash/Online</th>
                        <th>Check</th>
                        <th>Bank</th>
                        <th>Total</th>
                        <th>MoP</th>
                        <th>Status</th>
                        <th>Transaction Date</th>
                        <th>Pay Date</th>
                        <th>Encoder</th>
                    </tr>
                </thead>
                <tbody id="car-type-body">
                    <?php
                    $or_list = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                    a.c_or_paydate,a.c_or_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop, c_bank, b.c_name, b.c_phase,
                    b.c_block, b.c_lot, a.status
                        FROM t_or_payment a
                        LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no ORDER BY a.c_or_no ASC";
                    $stmt = odbc_prepare($conn, $or_list);
                    
                    if ($stmt && odbc_execute($stmt)) {
                        $i = 1;
                        while ($row = odbc_fetch_array($stmt)): 
                    ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center">
                                    <?php 
                                        echo htmlspecialchars(!empty($row['c_account_no']) ? $row['c_account_no'] : '----------'); 
                                    ?>
                                </td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_or_no']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_or_type']); ?></td>
                                <td class="text-center">
                                <?php
                                    $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';

                                    if (!empty($c_buyer_acc)) {
                                        $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
                                        $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);
                                        
                                        if (odbc_execute($buyer_stmt, array($c_buyer_acc))) {
                                            $buyer_details = odbc_fetch_array($buyer_stmt);
                                            
                                            if ($buyer_details) {
                                                echo htmlspecialchars($buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]);
                                            } else {
                                                echo "-";
                                            }
                                        } else {
                                            echo "-";
                                        }
                                    } else {
                                        echo htmlspecialchars($row['c_name']);
                                    }
                                    ?>
                                </td>
                                <td>
                                <?php
                                $c_account_no = $row['c_account_no'];
                                try {
                                    if (!empty($c_account_no)) {
                                        $c_phase = substr($c_account_no, 0, 3);
                                        $c_block = ltrim(substr($c_account_no, 3, 3), '0'); 
                                        $c_lot = substr($c_account_no, 6, 2);

                                        $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                                        $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                                        if (odbc_execute($phase_stmt, array($c_phase))) {
                                            $phase_details = odbc_fetch_array($phase_stmt);

                                            if ($phase_details) {
                                                echo htmlspecialchars($phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot);
                                            } else {
                                                echo "-----";
                                            }
                                        } else {
                                            echo "-----";
                                        }
                                    } else {
                                        $c_phase = $row['c_phase'];
                                        $c_block = $row['c_block'];
                                        $c_lot = $row['c_lot'];

                                        if (empty($c_phase) && empty($c_block) && empty($c_lot)) {
                                            echo "-------------";
                                        } else {
                                            $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                                            $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                                            if (odbc_execute($phase_stmt, array($c_phase))) {
                                                $phase_details = odbc_fetch_array($phase_stmt);

                                                if ($phase_details) {
                                                    echo htmlspecialchars($phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot);
                                                } else {
                                                    echo "-----";
                                                }
                                            } else {
                                                echo "-----";
                                            }
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo "-----";
                                }
                                ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    $cash = $row['c_mop'] == 1 ? $row['c_or_amount'] : 0;
                                    $online = $row['c_mop'] == 3 ? $row['c_or_amount'] : 0;

                                    $cashOnline = $cash + $online;
                                    echo number_format($cashOnline, 2); 
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    $amount = $row['c_mop'] == 2 ? $row['c_or_amount'] : 0;
                                    echo number_format($amount, 2); 
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if ($row['c_bank'] == '') {
                                        echo "-";
                                    }else {
                                        echo htmlspecialchars($row['c_bank']);
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php echo number_format($row['c_or_amount'], 2); ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if ($row['c_mop'] == 1) {
                                        echo "Cash";
                                    } elseif ($row['c_mop'] == 2) {
                                        echo "Check";
                                    } elseif ($row['c_mop'] == 3) {
                                        echo "Online";
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if ($row['status'] == 0) {
                                        echo "-----";
                                    } elseif ($row['status'] == 1) {
                                        echo "CANCELLED";
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                                <td class="text-center tran-date">
                                    <?php
                                    $dateTime = new DateTime($row['c_tran_date']);
                                    echo htmlspecialchars($dateTime->format('Y-m-d')); 
                                    ?>
                                </td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_or_paydate']); ?></td>
                                <td class="text-center">
                                    <?php
                                    $c_encoded_by = $row['c_encoded_by'];
                                    $get_encoder_details_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
                                    $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
                                    
                                    if (odbc_execute($encoder_stmt, array($c_encoded_by)) && $encoder = odbc_fetch_array($encoder_stmt)) {
                                        echo htmlspecialchars($encoder["c_realname"]);
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                            </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No data available or error executing query.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<script src="../../dist/js/table.js"></script>
<!-- <script src="../../dist/js/car_reports.js"></script> -->
 <script>
    $(document).ready(function(){
    $('.datepicker').datepicker({
        dateFormat: 'mm/dd/yy',
        autoclose: true,
        todayHighlight: true
    });

    $('#filter').click(function() {
        let startDate = parseDate($('#start_date').val());
        let endDate = parseDate($('#end_date').val());
        let rows = $('#car-type-body tr');
        
        rows.each(function() {
            let dateText = $(this).find('.tran-date').text().trim();
            let payDate = parseYMDDate(dateText);
            
            if ((isNaN(startDate.getTime()) || payDate >= startDate) && (isNaN(endDate.getTime()) || payDate <= endDate)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#reset').click(function() {
        $('#start_date').val(formatDate(new Date()));
        $('#end_date').val(formatDate(new Date()));
        $('#car-type-body tr').show();
    });

    function parseDate(dateString) {
        let parts = dateString.split('/');
        return new Date(parts[2], parts[0] - 1, parts[1]);
    }

    function parseYMDDate(dateString) {
        let parts = dateString.split('-');
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function formatDate(date) {
        let month = ('0' + (date.getMonth() + 1)).slice(-2);
        let day = ('0' + date.getDate()).slice(-2);
        let year = date.getFullYear();
        return month + '/' + day + '/' + year;
    }
});
 </script>

<script>
    /* Changes don sa export_csv vs old export_csv /galing csr_report.js/ */
    document.getElementById('export_csv').addEventListener('click', function() {
        let table = document.getElementById('car-table'); 
        let csv = convertToCSV(table);
        let today = new Date();

        let year = today.getFullYear();
        let month = (today.getMonth() + 1);
        let day = today.getDate();

        let formattedMonth = month < 10 ? '0' + month : month.toString();
        let formattedDay = day < 10 ? '0' + day : day.toString();

        let filename = `car_list_asof_${year}-${formattedMonth}-${formattedDay}.csv`;
        console.log('CSV Filename:', filename);

        downloadCSV(csv, filename);
    });

    function convertToCSV(table) {
        let rows = table.querySelectorAll('tr');
        let csv = [];

        for (let row of rows) {
            if (row.style.display === 'none') {
                continue;
            }
            let cols = row.querySelectorAll('th, td');
            let rowData = [];
            for (let col of cols) {
                rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
            }
            csv.push(rowData.join(','));
        }

        return csv.join('\n');
    }

    function downloadCSV(csv, filename) {
        let csvFile = new Blob([csv], { type: 'text/csv' });
        let downloadLink = document.createElement('a');

        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';

        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    /* Export para sa PDF Reports */
    document.getElementById('export_pdf').addEventListener('click', function() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        startDate = formatToISO(startDate);
        endDate = formatToISO(endDate);

        var url = '../../print/pdf_report_main_or.php?start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);
        window.open(url, '_blank');
    });

    function formatToISO(dateString) {
        var parts = dateString.split('/');
        return parts[2] + '-' + (parts[0].length === 1 ? '0' + parts[0] : parts[0]) + '-' + (parts[1].length === 1 ? '0' + parts[1] : parts[1]);
    }
</script>

<?php include('../../inc/footer.php'); ?>
