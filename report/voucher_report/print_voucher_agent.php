<?php require ('../../config.php'); ?>
<?php include "../../inc/header.php" ?>
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
    $qry = $conn->query("SELECT * FROM vs_entries WHERE v_num = '{$_GET['id']}' ");
    $row = $qry->fetch_assoc();
    $vs_id = $row['v_num'];
    $po_no = $row['po_no'];
    $supp_code = $row['supplier_id'];
    $jdate = $row['journal_date'];
    $due = $row['due_date'];
    $desc = $row['description'];

    $qry_supp = $conn->query("SELECT * FROM supplier_list WHERE id = '$supp_code'; ");
    $row_supp = $qry_supp->fetch_assoc();
    if (!empty($row_supp)) {
        $supp_name = $row_supp['name'];
    } else {
        $qry_supp = $conn->query("SELECT * FROM t_agents WHERE c_code = '$supp_code'; ");
        $row_supp = $qry_supp->fetch_assoc();
        if (!empty($row_supp)) {
            $supp_name = $row_supp['c_first_name'] . ' ' . $row_supp['c_last_name'];
        } 
        else {
            $supp_name = "Not found in any source";
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
                    <img src="../images/Header.jpg" class="img-thumbnail" style="margin-left:120px;height:110px;width:750px;border:none;margin-bottom:-5px;z-index:-1;position:relative;margin-bottom:-35px;" alt="">
                    <h6 style="margin-top:-25px;margin-left:335px;font-weight:normal;">Grand Royale Subdivision, Bulihan, Malolos City, Bulacan, Philippines</h6>

                    <h3 style="margin-top:50px;margin-left:435px;font-weight:bold;font-family: 'Armata', sans-serif;">VOUCHER SETUP</h3>

                    <div class="container" style="margin-top:15px;">
                        <table class="table table-bordered">
                            <tr>
                                <td style="width:10%;font-weight:bold; padding: 4px 10px;">VS No:</td>
                                <td style="width:50%; padding: 4px 10px;"><?php echo $vs_id; ?></td>
                                <td style="font-weight:bold; padding: 4px 10px;">Date:</td>
                                <td style="padding: 4px 10px;"><?php echo $jdate; ?></td>
                            </tr>
                            <tr>
                                <td style="width:15%;font-weight:bold; padding: 4px 10px;">Paid To:</td>
                                <td style="padding: 4px 10px;"><?php echo $supp_name; ?></td>
                                <td style="width:15%;font-weight:bold; padding: 4px 10px;">Due Date:</td>
                                <td style="padding: 4px 10px;"><?php echo $due; ?></td>
                            </tr>
                            <tr> 
                                <td></td>
                                <td></td>
                                <td style="width:15%;font-weight:bold; padding: 4px 10px;">
                                    Agent Code:
                                </td>
                                <td style="padding: 4px 10px;"><?php echo $supp_code; ?></td>
                            </tr>
                        </table>
                        <br>
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
                            $jitems = $conn->query("SELECT DISTINCT gl.preparer as prepp, a.name, gl.gtype, gl.amount, vi.phase, vi.block, vi.lot, gl.account
                            FROM tbl_gl_trans gl
                            LEFT JOIN vs_items vi ON gl.vs_num = vi.journal_id
                            LEFT JOIN account_list a ON gl.account = a.code
                            WHERE gl.vs_num = '{$_GET['id']}' AND gl.doc_type='AP'
                            GROUP BY gl.account
                            ORDER BY gl.gtype;
                            ");
                            $global_prepp = null;
                            while($row = $jitems->fetch_assoc()):
                                $global_prepp = $row['prepp'];
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
                    <br><br>Prepared by:
                    <?php
                        $prep = $conn->query("SELECT * FROM users WHERE user_code = '" . $global_prepp . "'");
                        while($row2 = $prep->fetch_assoc()): ?>
                        
                        <?php echo $row2['firstname']; ?> <?php echo $row2['lastname']; ?>
                        <i>(<?php echo $row2['section']; ?>)</i>
                        <?php endwhile; ?>
                    </div>
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

