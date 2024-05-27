<?php require_once('../config.php'); ?>
<script src="../build/js/jquery.min.js" type="text/javascript"></script>
<script src="../build/js/num-to-words.js" type="text/javascript"></script>
<!DOCTYPE html>
<html lang="en">
<style>
    @media print {
        @page {
            margin: 0mm;
            orientation: landscape;
            height:139.7mm;
            width:215.9mm;
        }
    }
    body {
        font-family: 'Armata', sans-serif;
        font-size: 13px;
    }
    input[type=checkbox]
    {
        accent-color: black;
        mix-blend-mode: multiply;
        background-color: transparent;
    }
    .main_receipt_cont{
        width:auto;
        height:auto;
        /* margin-top:0px;
        margin-left:0px; */
    }
    /* img{
        visibility: hidden;
    } */
</style>

<?php $qry1 = $conn->query("SELECT * FROM or_logs LEFT JOIN property_clients ON or_logs.property_id = property_clients.property_id where or_id = '{$_GET['id']}'");
    $row1 = $qry1->fetch_assoc();
    $client_id = md5($row1['property_id']);
    $property_id = $row1['property_id'];
    $p_last_name = $row1['last_name'];
    $p_first_name = $row1['first_name'];
    $p_middle_name = $row1['middle_name'];
    $p_address = $row1['address'];
    $p_zipcode = $row1['zip_code'];
    $acr = substr($property_id, 2, 3);
    $blk = substr($property_id, 5, 3);
    $lot = substr($property_id, 8, 2); ?>
    <?php $qry2 = $conn->query("SELECT * from t_projects where c_code = '$acr'");
    $row2 = $qry2->fetch_assoc();
    $name = $row2['c_acronym']; 
    $or_id = $row1['or_id'];
    $total_prin = $row1['principal'];
    $total_surcharge = $row1['surcharge'];
    $total_rebate = $row1['rebate'];
    $total_amt_due = $row1['amount_due'];
    $total_amt_pd= $row1['amount_paid'];
    $total_interest= $row1['interest'];
    $or_no = $row1['or_no'];
    $particulars = $row1['particulars'];
    $mop = $row1['mode_of_payment'];
    $c_date = $row1['check_date'];
    $c_branch = $row1['branch'];
    $check_no = $row1['check_number'];
    $preparer = $row1['user'];
?>

<body onload="getMoP()">
<div class="main_receipt_cont">
    <div class="img_container">
        <img src="../images/ALSC_OR.jpg" style="width:215.9mm;height:139.7mm;border:solid 1px black;"><br>
        <div class="b_name" style="text-transform:uppercase;font-weight:bold;position:absolute;margin-top:-406px;margin-left:155px;">
            <?php echo $p_last_name; ?>, <?php echo $p_first_name; ?> <?php echo $p_middle_name; ?>
        </div>
        <div class="b_address" style="position:absolute;margin-top:-386px;margin-left:155px;">
            <?php echo $p_address; ?>, <?php echo $p_zipcode; ?>
        </div>
        <div class="b_date" style="position:absolute;margin-top:-406px;margin-left:645px;">
            <?php echo date("F j, Y"); ?>
        </div>
        <div class="b_loc" style="position:absolute;margin-top:-386px;margin-left:645px;">
            <?php echo $name; ?>
            <?php echo $blk; ?> /
            <?php echo $lot; ?>
        </div>
        <div class="b_prop_id" style="position:absolute;margin-top:-367px;margin-left:645px;">
            <?php echo $property_id; ?>
        </div>
        <div class="or_no_box" style="background-color:white;font-weight:bold;text-align:center;font-size:23px;position:absolute;width:150px;margin-top:-460px;margin-left:630px;">
            NO. <?php echo $or_no; ?>
        </div>
        <div class="b_particulars" style="position:absolute;margin-top:-320px;margin-left:30px;">
            <textarea class="b_particulars" name="b_particulars" style="height:120px;width:450px;border:none;resize:none;overflow:none;"><?php echo $particulars; ?></textarea>
        </div>


        <div class="b_prin" style="position:absolute;margin-top:-312px;margin-left:645px;text-align:right;width:140px;padding-right:5px;">
            <?php echo number_format($total_prin, 2) ?>
        </div>
        <div class="b_int" style="position:absolute;margin-top:-294px;margin-left:645px;text-align:right;width:140px;padding-right:5px;">
            <?php echo number_format($total_interest, 2) ?>
        </div>
        <div class="b_surcharge" style="position:absolute;margin-top:-275px;margin-left:645px;text-align:right;width:140px;padding-right:5px;">
            <?php echo number_format($total_surcharge, 2) ?>
        </div>

        <div class="b_due" style="position:absolute;margin-top:-242px;margin-left:645px;text-align:right;width:140px;padding-right:5px;">
            <?php echo number_format($total_amt_pd, 2) ?>
        </div>
        <div class="b_rebate" style="position:absolute;margin-top:-207px;margin-left:645px;text-align:right;width:140px;padding-right:5px;">
            <?php echo number_format($total_rebate, 2) ?>
        </div>
        <div class="b_payment" style="position:absolute;margin-top:-190px;margin-left:645px;text-align:right;width:140px;padding-right:5px;">
            <?php echo number_format($total_amt_pd, 2) ?>
            <input type="hidden" id="res_amount" name="res_amount" value="<?php echo number_format($total_amt_pd) ?>">
        </div>
        <div class="check_date" style="position:absolute;margin-top:-163px;margin-left:249px;text-align:right;padding-right:5px;font-size:10px;">
            <?php echo $c_date; ?>
        </div>
        <div class="bank_branch" style="position:absolute;margin-top:-163px;margin-left:395px;text-align:right;padding-right:5px;font-size:10px;">
            <?php echo $c_branch; ?>
        </div>
        <div class="check_no" style="position:absolute;margin-top:-163px;margin-left:92px;text-align:center;font-size:10px;width:auto;">
            <?php echo $check_no; ?>
        </div>
        <div class="preparer" style="position:absolute;margin-top:-64px;margin-left:645px;text-align:center;font-size:10px;width:auto;font-size:10px;font-weight:bold;">
            <?php echo $preparer; ?>
        </div>

        <div class="col-md-12">
            <div style="float:left;margin-right:2px;margin-top:3px;">
                <input id="cash" class="option-input checkbox" type="checkbox" name="chkOption1" style="position:absolute;margin-top:-182px;margin-left:32px;"/>
            </div>
            <div style="float:left;margin-right:2px;margin-top:3px;">
                <input id="check" class="option-input checkbox" type="checkbox" name="chkOption1" style="position:absolute;margin-top:-167px;margin-left:30px;"/>
            </div>
            <div style="float:left;margin-right:2px;margin-top:3px;">
                <input id="bank" class="option-input checkbox" type="checkbox" name="chkOption1" style="position:absolute;margin-top:-152px;margin-left:28px;"/>
            </div>
        </div>
        <input type="hidden" id="mop" name="mop" value="<?php echo $mop; ?>">
        <textarea id="numtowords" style="margin-left:25px;background-color:transparent;height:45px;margin-top:-95px;position:absolute;width:490px;border:none;"></textarea>
</div>
<script>
    per();
    function getMoP(){
    var rel = document.getElementById('mop').value;
    if(rel==-1){
        document.getElementById('cash').checked=true;
    }else if(rel==0){
        document.getElementById('check').checked=true;
    }else if(rel==1){
        document.getElementById('bank').checked=true;
    }else{
        document.getElementById('cash').checked=false;
        document.getElementById('check').checked=false;
        document.getElementById('bank').checked=false;
    }
}
function per() {
    var words="";
    var totalamount = document.getElementById('res_amount').value;
    
    words = toWords(totalamount);
    $("#numtowords").val(words + "Pesos Only");
}
function PrintPage() {
    window.print();
}
document.loaded = function(){
}
window.addEventListener('DOMContentLoaded', (event) => {
    PrintPage()
    setTimeout(function(){ 
        
    },750)
});
</script>
<!-- <img src="../images/ALSC_OR.jpg" style="width:215.9mm;height:139.7mm;border:solid 1px; black;"> -->
</div>
</body>
</html>