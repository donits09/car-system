<?php require ('../config.php'); ?>
<?php include "../inc/header.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
	<script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Armata&display=swap" rel="stylesheet"> -->
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
$qry = $conn->query("SELECT * FROM cv_entries WHERE c_num = '{$_GET['id']}' ");
$row = $qry->fetch_assoc();
$cv_id = $row['c_num'];
// $check_num = $row['check_num'];
$po_no = $row['po_no'];
$supp_code = $row['supplier_id'];
$cvdate = $row['check_date'];
$desc = $row['description'];
$check_name = $row['check_name'];
$v_num = $row['v_num'];
$supp_name = "Not found in any source"; 
$status1 =  $row['c_status'];
$status2 =  $row['c_status2'];
$qry_supp = $conn->query("SELECT * FROM supplier_list WHERE id = '$supp_code'; ");
$row_supp = $qry_supp->fetch_assoc();
if (!empty($row_supp)) {
    $supp_name = $row_supp['name'];
} else {
    $qry_supp = $conn->query("SELECT * FROM users WHERE user_code = '$supp_code'; ");
    $row_supp = $qry_supp->fetch_assoc();
    if (!empty($row_supp)) {
        $supp_name = $row_supp['firstname'] . ' ' . $row_supp['lastname'];
    } else {
        $qry_supp = $conn->query("SELECT * FROM t_agents WHERE c_code = '$supp_code'; ");
        $row_supp = $qry_supp->fetch_assoc();
        if (!empty($row_supp)) {
            $supp_name = $row_supp['c_first_name'] . ' ' . $row_supp['c_last_name'];
        }else{
            $qry_supp = $conn->query("SELECT * FROM property_clients WHERE client_id = '$supp_code'; ");
            $row_supp = $qry_supp->fetch_assoc();
            if (!empty($row_supp)) {
                $supp_name = $row_supp['first_name'] . ' ' . $row_supp['last_name'];
            }
        }
    }
}
?>

<style>
.phase {

-webkit-appearance: none;
-moz-appearance: none;
appearance: none;
border:none;
background-size: 15px; 
background-position: right center;
background-repeat: no-repeat;
width:auto;

}


.phase:focus {

border-color: #007BFF; 
}
</style>
    <table class="report-container" style="margin-top:-10px;width:100%;">
        <thead class="report-header">
            <tr>
                <th class="report-header-cell">
                    <div class="header-info">
                        <img src="images/Header.jpg" class="img-thumbnail" style="margin-left:120px;height:110px;width:750px;border:none;margin-bottom:-5px;z-index:-1;position:relative;margin-bottom:-35px;" alt="">
                        <h6 style="margin-top:-25px;margin-left:335px;font-weight:normal;">Grand Royale Subdivision, Bulihan, Malolos City, Bulacan, Philippines</h6>

                        <h3 style="margin-top:50px;margin-left:435px;font-weight:bold;font-family: 'Armata', sans-serif;">CHECK VOUCHER</h3>

                        <div class="container" style="margin-top:15px;">
                        <table class="table table-bordered">
                            <tr>
                                <td style="width:10%;font-weight:bold; padding: 4px 10px;">CV No:</td>
                                <td style="width:50%; padding: 4px 10px;"><?php echo $cv_id; ?></td>
                                <td style="font-weight:bold; padding: 4px 10px;">VS No:</td>
                                <td style="padding: 4px 10px;"><?php echo $v_num; ?></td>
                            </tr>
                            <tr>
                                <td style="width:15%;font-weight:bold; padding: 4px 10px;">PO No:</td>
                                <td style="padding: 4px 10px;"><?php echo $po_no; ?></td>
                                <td style="font-weight:bold; padding: 4px 10px;">Date:</td>
                                <td style="padding: 4px 10px;"><?php echo date('Y-m-d'); ?></td>
                                
                            </tr>
                            <tr>
                                <td style="width:15%;font-weight:bold; padding: 4px 10px;">Paid To:</td>
                                <td style="padding: 4px 10px;"><?php echo $supp_name; ?></td>
                                <td style="width:15%;font-weight:bold; padding: 4px 10px;">
                                    <?php
                                    if (ctype_alpha(substr($supp_code, 0, 1))) {
                                        echo "Employee Code:";
                                    } elseif (preg_match('/^\d{5,}$/', $supp_code)) {
                                        echo "Agent Code:";
                                    } else {
                                        echo "Supplier Code:";
                                    }
                                    ?>
                                </td>
                                <td style="padding: 4px 10px;"><?php echo $supp_code; ?></td>
                            </tr>
                            <tr>
                                <td style="width:15%;font-weight:bold; padding: 4px 10px;">Check Name:</td>
                                <td style="padding: 4px 10px;"><?php echo $check_name; ?></td>
                                <td style="width:15%;font-weight:bold; padding: 4px 10px;">Due Date:</td>
                                <td style="padding: 4px 10px;"><?php echo date('Y-m-d', strtotime($cvdate)); ?></td>
                                <!-- <td style="padding: 4px 10px;font-weight:bold;">Check #:</td>
                                <td style="padding: 4px 10px;"><?php echo $check_num; ?></td> -->
                            </tr>
                        </table>
                            <hr>
                            <h6 style="font-weight:bold;margin-left:10px;">Particulars:</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <td><?php echo $desc; ?></td>
                                </tr>
                            </table>
                        <table id="account_list" class="table table-striped table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="10%">
                            <col width="50%">
                            <!-- <col width="30%"> -->
                            <col width="15%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">Item No.</th>
                                <th class="text-center">Account Code</th>
                                <th class="text-center">Account Name</th>
                                <!-- <th class="text-center">Location</th> -->
                                <!-- <th class="text-center">Group</th> -->
                                <th class="text-center">Debit</th>
                                <th class="text-center">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $counter = 1;
                            $jitems = $conn->query("SELECT DISTINCT a.name, gl.gtype, gl.amount, vi.phase, vi.block, vi.lot, gl.account
                            FROM tbl_gl_trans gl
                            LEFT JOIN vs_items vi ON gl.vs_num = vi.journal_id
                            LEFT JOIN account_list a ON gl.account = a.code
                            WHERE gl.cv_num = '{$_GET['id']}' AND gl.doc_type='CV'
                            GROUP BY gl.account
                            ORDER BY gl.gtype;
                            ");
                            while($row = $jitems->fetch_assoc()):
                                ?>
                                <tr>  
                                    <td class="" style="padding: 4px 10px;">
                                        <span class="account_code"><?= $counter; ?></span>
                                    </td>
                                    <td class="" style="padding: 4px 10px;">
                                        <span class="account_code"><?= $row['account'] ?></span>
                                    </td>
                                    <td class="" style="padding: 4px 10px;">
                                        <span class="account"><?= $row['name'] ?></span>
                                    </td>
                                    <!-- <td class="">
                                        <div class="loc-cont">
                                            <select name="phase[]" id="phase[]" class="phase">
                                            <?php 
                                                $meta = array();
                                                $cat = $conn->query("SELECT * FROM t_projects ORDER BY c_acronym ASC ");
                                                while($row1 = $cat->fetch_assoc()):
                                            ?>
                                                <?php
                                                    echo "Meta Phase: " . $row['phase'] . ", Row1 c_code: " . $row1['c_code'];
                                                ?>
                                                <option value="<?php echo $row1['c_code'] ?>" <?php echo isset($row['phase']) && $row['phase'] == $row1['c_code'] ? 'selected' : '' ?>><?php echo $row1['c_acronym'] ?></option>
                                            <?php
                                                endwhile;
                                            ?>
                                            </select>
                                            <?php echo $row['block'] ?>/<?php echo $row['lot'] ?>
                                            <script>
                                                $(document).ready(function () {
                                                    $('.lot, .block, .phase').on('input', function () {
                                                        var currentRow = $(this).closest('td');
    
                                                        var enteredPhase = currentRow.find('.phase').val();
                                                        var enteredBlock = currentRow.find('.block').val();
                                                        var enteredLot = currentRow.find('.lot').val();
    
                                                        console.log("AJAX Data:", {
                                                            phase: enteredPhase,
                                                            block: enteredBlock,
                                                            lot: enteredLot
                                                        });
    
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'journals/check_loc.php',
                                                            data: JSON.stringify({
                                                                phase: enteredPhase,
                                                                block: enteredBlock,
                                                                lot: enteredLot
                                                            }),
                                                            contentType: 'application/json', 
                                                            success: function (response) {
                                                                console.log("AJAX Response:", response);
                                                                var lotExistsMsg = currentRow.find('.lotExistsMsg');
                                                                    lotExistsMsg.html(response);
                                                            }
                                                        });
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </td> -->
                                    <td class="debit_amount text-right" style="padding: 4px 10px;"><?= $row['gtype'] == 1 ? number_format($row['amount'], 2) : '' ?></td>
                                    <td class="credit_amount text-right" style="padding: 4px 10px;">
                                        <?= $row['gtype'] == 2 ? number_format(abs($row['amount']), 2) : '' ?>
                                    </td>
                                </tr>
                                <?php $counter++;
                                endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">TOTAL</th>
                                <th class="text-right total_debit">0.00</th>
                                <th class="text-right total_credit">0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                    <table class="table table-bordered" style="text-align:center;">
                        <tr>
                            <i>Approved by:</i>
                        </tr>
                        <tr>
                           
                            <?php
                            $qry = $conn->query("SELECT * FROM users WHERE (position ='AVP-TREASURY' AND division='MNGR')");
                            $statusRow = $qry->fetch_assoc();
                            if ($statusRow) {
                                $lastname = $statusRow['lastname'];
                                $firstname = $statusRow['firstname'];
                                ?>
                                <td><?php echo $firstname . ' ' . $lastname; ?></td>
                            <?php
                            } else {
                                ?>
                                <td>User not found</td>
                            <?php
                            }
                            ?>
                            
                            
                            <?php
                            $qry = $conn->query("SELECT * FROM users WHERE position = 'CHIEF FINANCE OFFICER'");
                            $statusRow = $qry->fetch_assoc();
                            if ($statusRow) {
                                $lastname = $statusRow['lastname'];
                                $firstname = $statusRow['firstname'];
                                ?>
                                <td><?php echo $firstname . ' ' . $lastname; ?></td>
                            <?php
                            } else {
                                ?>
                                <td>User not found</td>
                            <?php
                            }
                            ?>
                        </tr>
                        <tr>
                            <?php
                                $qry = $conn->query("SELECT c_status FROM cv_entries WHERE c_num = '" . $cv_id . "' ");
                                
                                $statusRow1 = $qry->fetch_assoc();
                                if ($statusRow1) {
                                    $status1 = $statusRow1['c_status'];
                                    $statusText = '';
                                    if ($status1 == 1) {
                                        $statusText = '<strong>Approved</strong>';
                                    } elseif ($status1 == 2) {
                                        $statusText = '<strong>Disapproved</strong>';
                                    } else {
                                        $statusText = '<strong>Pending</strong>';
                                    }
                                    ?>
                                    <td><?php echo $statusText; ?></td>
                                    <?php
                                } else {
                                    ?>
                                    <?php
                                }
                                ?>
                                <?php
                                $qry = $conn->query("SELECT c_status2 FROM cv_entries WHERE c_num = '" . $cv_id . "' ");
                                
                                $statusRow1 = $qry->fetch_assoc();
                                if ($statusRow1) {
                                    $status2 = $statusRow1['c_status2'];
                                    $statusText = '';
                                    if ($status2 == 1) {
                                        $statusText = '<strong>Approved</strong>';
                                    } elseif ($status2 == 2) {
                                        $statusText = '<strong>Disapproved</strong>';
                                    } else {
                                        $statusText = '<strong>Pending</strong>';
                                    }
                                    ?>
                                    <td><?php echo $statusText; ?></td>
                                    <?php
                                } else {
                                    ?>
                                    <?php
                                }
                                ?>
                            </tr>
                        </table>
                        </div>
                    </div>
                </th>
            </tr>
        </thead>
    </table>

</body>
<script>
function formatAmount(amount) {
    amount = parseFloat(amount);
    if (Math.floor(amount) === amount) {
        return amount.toLocaleString('en-US', { style: 'decimal' }) + ".00";
    } else {
        return amount.toLocaleString('en-US', { style: 'decimal' });
    }
}

function cal_tb() {
    var debit = 0;
    var credit = 0;

    $('#account_list tbody tr').each(function () {
        if ($(this).find('.debit_amount').text() != "") {
            debit += parseFloat(($(this).find('.debit_amount').text().replace(/,/gi, ''))) || 0;
        }
        if ($(this).find('.credit_amount').text() != "") {
            credit += parseFloat(($(this).find('.credit_amount').text().replace(/,/gi, ''))) || 0;
        }
    });
    $('#account_list').find('.total_debit').text(formatAmount(debit));
    $('#account_list').find('.total_credit').text(formatAmount(credit));
}
cal_tb();
</script>
<script type="text/javascript">
	function PrintPage() {
		window.print();
	}
	    document.loaded = function(){
	}
	window.addEventListener('DOMContentLoaded', (event) => {
   		PrintPage()
		setTimeout(function(){ window.close() },750)
	});
</script>
</html>

