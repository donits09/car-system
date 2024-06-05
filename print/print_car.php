
<?php 
session_start();
require_once('../config.php');
function format_num($number){
    $decimals = 2; 
    return number_format($number, $decimals);
}
?>
<style>
    .control-label{
        font-weight:bold;
        font-size:14px;
    }
    .form-control{
        font-size:14px;
        margin:5px;
        text-align: right;
    }
    #car_copy{
        height:250px;
        width:670px;
        border:none;
    }
</style>


<div class="container-fluid">
<form action="" id="pay-form">
<img src="<?php echo base_url ?>images/car.jpg" class="img-thumbnail" id="car_copy" alt="Car Scanned Copy">

        <input type="hidden" name="id" value="<?php echo isset($account_no) ? $account_no : '' ?>">
        <div class="fieldset-container">

                <table style="width:100%;font-size:14px;">
                    <tr>
                        <td><b>Account No:</b></td>
                        <td><?= isset($account_no) ? $account_no : '' ?></td>

                        <td><b>Location:</b></td>
                        <td><?= isset($c_location) ? $c_location : '' ?></td>
                    </tr>
                    <tr>
                        <td><b>Full Name:</b></td>
                        <td><?= isset($full_name) ? $full_name : '' ?></td>
                    </tr>
                </table>  
        </div>
        <input type="hidden" name="acc_no" id="acc_no" class="form-control form-control-border"  value ="<?php echo isset($account_no) ? $account_no : '' ?>">

        <input type="hidden" name="lname" id="lname" class="form-control form-control-border" value ="<?php echo isset($last_name) ? $last_name : '' ?>" readonly required>

        <input type="hidden" name="fname" id="fname" class="form-control form-control-border" value ="<?php echo isset($first_name) ? $first_name : '' ?>"readonly required>

        <input type="hidden" name="mname" id="mname" class="form-control form-control-border" value ="<?php echo isset($middle_name) ? $middle_name : '' ?>"readonly required>
    
        <input type="hidden" name="address" id="address" class="form-control form-control-border" value ="<?php echo isset($add) ? $add : '' ?>">
    
 
        <div class="fieldset-container">
            <fieldset class="fieldset">
                <legend style="text-align:center;font-weight:bold;font-size:16px;">STL (Streetlight) Details</legend>
                <table style="width:100%;">
                    <tr>
                        <td><label for="stl_date" class="control-label">STL Due Date: <br><span style="color: red;"><?php echo $l_stl_status; ?></span> </label></td>
                        <td><input type="date" name="stl_date" id="stl_date" class="form-control" value ="<?php echo isset($street_due) ? $street_due : date('Y-m-d'); ?>"readonly required></td>
                    </tr>
                    <tr>
                        <td><label for="stl_last_bal" class="control-label">STL Prev. Bal: </label></td>
                        <td><input type="text" name="stl_last_bal" id="stl_last_bal" class="form-control" value ="<?php echo isset($stl_prev) ? format_num($stl_prev) : '0.00' ?>"readonly required></td>
                    </tr>
                    <tr>
                        <td><label for="stl_cur" class="control-label">STL Curr. Due: </label></td>
                        <td><input type="text" name="stl_cur" id="stl_cur" class="form-control" value ="<?php echo isset($l_stl_cur) ? format_num($l_stl_cur) : '0.00' ?>"readonly required></td>
                    </tr>
                    <tr>
                        <td><label for="stl_sur" class="control-label">STL Curr. Sur: </label></td>
                        <td><input type="text" name="stl_sur" id="stl_sur" class="form-control" value ="<?php echo isset($l_stl_sur) ? format_num($l_stl_sur) : '0.00' ?>"readonly required></td>
                    </tr>
                    <tr>
                        <td><label for="stl_balance" class="control-label">STL Total Due: </label></td>
                        <td><input type="text" name="stl_balance" id="stl_balance" class="form-control" value ="<?php echo isset($stl_bal) ? format_num($stl_bal) : '0.00' ?>"readonly required></td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="fieldset">
                <legend style="text-align:center;font-weight:bold;font-size:16px;">GCF (Grass-Cutting) Details</legend>
            
                <table style="width:100%;">
                    <tr>
                        <td><label for="main_date" class="control-label">GCF Due Date: <br><span style="color: red;"><?php echo $l_gcf_status; ?></span></label></td>
                        <td><input type="date" name="main_date" id="main_date" class="form-control form-control-border" value ="<?php echo isset($mainte_due) ? $mainte_due : date('Y-m-d'); ?>"readonly required></td>
                    </tr>
                    <tr>
                        <td><label for="main_last_bal" class="control-label">GCF Prev. Bal: </label></td>
                        <td><input type="text" name="main_last_bal" id="main_last_bal" class="form-control form-control-border" value ="<?php echo isset($mainte_prev) ? format_num($mainte_prev) : '0.00' ?>"readonly required></td>
                    </tr>
                    <tr>
                        <td><label for="main_cur" class="control-label">GCF Curr. Due: </label></td>
                        <td><input type="text" name="main_cur" id="main_cur" class="form-control form-control-border" value ="<?php echo isset($l_mtf_cur) ? format_num($l_mtf_cur) : '0.00' ?>"readonly required></td>
                    </tr>
                    <tr>
                        <td><label for="main_sur" class="control-label">GCF Curr. Sur: </label></td>
                        <td><input type="text" name="main_sur" id="main_sur" class="form-control form-control-border" value ="<?php echo isset($l_mtf_sur) ? format_num($l_mtf_sur) : '0.00' ?>"readonly required></td>
                    </tr>
                    <tr>
                        <td><label for="main_balance" class="control-label">GCF Total Due: </label></td>
                        <td><input type="text" name="main_balance" id="main_balance" class="form-control form-control-border" value ="<?php echo isset($mainte_bal) ? format_num($mainte_bal) : '0.00' ?>"readonly required></td>
                    </tr>
                </table>
            </fieldset>
        </div>
       
        <div class="fieldset-container">
            <fieldset class="fieldset">
                <table style="width:100%;">
                    <tr>
                        <td><label for="stl_amount_pay" class="control-label"><b>Payment for Streetlight Amount: </b></label></td>
                        <td><input type="number" name="stl_amount_pay" id="stl_amount_pay" class="form-control form-control-border stl_amount_pay" value ="" required></td>
                    </tr>
                    <tr>
                        <td><label for="stl_discount" class="control-label"><b>STL Discount:</b></label></td>
                        <td><input type="number" name="stl_discount" id="stl_discount" class="form-control form-control-border stl_discount" value ="" required></td>
                    </tr>
                    <tr>
                        <td><label for="stl_amount_paid" class="control-label"><b>STL Amount Paid: </b></label></td>
                        <td><input type="number" name="stl_amount_paid" id="stl_amount_paid" class="form-control form-control-border" value ="0" readonly required></td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="fieldset">
                <table style="width:100%;">
                    <tr>
                        <td><label for="main_amount_paid" class="control-label"><b>Payment for Grass-Cutting Amount: </b></label></td>
                        <td><input type="number" name="main_amount_pay" id="main_amount_pay" class="form-control form-control-border main_amount_pay" value ="" required></td>
                    </tr>
                    <tr>
                        <td><label for="main_discount" class="control-label"><b>GCF Discount:</b></label></td>
                        <td><input type="number" name="main_discount" id="main_discount" class="form-control form-control-border main_discount" value ="" required></td>
                    </tr>
                    <tr>
                        <td><label for="main_amount_paid" class="control-label"><b>GCF Amount Paid: </b></label></td>
                        <td><input type="number" name="main_amount_paid" id="main_amount_paid" class="form-control form-control-border" value ="0" readonly required></td>
                    </tr>

                </table>
            </fieldset>

        </div>
        <input type="hidden" name="usr" id="usr" class="form-control form-control-border" value ="<?php echo $usr; ?>">
        <div class="fieldset-container">     
            <table style="width:100%;">
                <tr>
                    <td><label for="total_amount_paid" class="control-label"><b>Total Amount Paid: </b></label></td>
                    <td><input type="text" name="total_amount_paid" id="total_amount_paid" class="form-control form-control-border" value ="0" readonly required></td>
                </tr>
            </table>
        </div>

        <div class="fieldset-container">     
            <table style="width:100%;">
                <tr>
                    <td class="col-md-2">
                        <div class="form-group">
                            <label for="mode_payment" class="control-label"><b>Mode of Payment:</b></label>
                            <select name="mode_payment" id="mode_payment" class="form-control form-control-border" style="text-align: center;" required>
                                <option value="1">Cash</option>
                                <option value="2">Check</option>
                                <option value="3">Gcash/Online</option>
                            </select>
                        </div>
                    </td>
                    <td class="col-md-2">
                        <div class="form-group">
                            <label for="pay_date" class="control-label"><b>Pay Date: </b></label>
                            <input type="date" name="pay_date" id="pay_date" class="form-control form-control-border pay-date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </td>
                    <td class="col-md-2">
                        <div class="form-group">
                            <label for="payment_or" class="control-label"><b>CAR #: </b></label>
                            <input type="text" name="payment_or" id="payment_or" class="form-control form-control-border required" value="" minlength="6" maxlength="6">
                        </div>
                    </td>
                </tr>
            </table>
            </div>
            <div class="fieldset-container" id="check_details" style="display:none;">    
            <table style="width:100%;">
                <tr>
                    <td class="col-md-2">
                        <div class="form-group">
                            <label for="check_date" class="control-label"><b>Check Date: </b></label>
                            <input type="date" name="check_date" id="check_date" class="form-control form-control-border">
                        </div>
                    </td>
                    <td class="col-md-2">
                        <div class="form-group">
                            <label for="branch" class="control-label"><b>Branch: </b></label>
                            <select name="branch" id="branch" class="form-control form-control-border custom" style="text-align: center;">
                                <option value="" selected>--SELECT BANK--</option>
                                <option value="BPI">BPI</option>
                                <option value="BDO">BDO</option>
                                <option value="CBS">CBS</option>
                                <option value="SBC">SBC</option>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="fieldset-container" id="ref_no_details" style="display:none;">   
            <table style="width:100%;"> 
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="ref_no" class="control-label"><b>Reference No: </b></label>
                            <input type="text" name="ref_no" id="ref_no" class="form-control form-control-border">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" id="printDataButton" class="btn btn-primary">
                    <i class="fa fa-print"></i> Preview
                </button>
            </div>
        </div>
        
    </form>
</div>
<style>
    input[type="number"] {
        text-align: right;
    }
</style>
<script>
    function convertToWords(number) {
        var ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
    var teens = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
    var tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

    function convertGroup(num) {
        var result = "";
        if (num >= 100) {
            result += ones[Math.floor(num / 100)] + " Hundred ";
            num %= 100;
        }
        if (num >= 10 && num <= 19) {
            result += teens[num - 10];
        } else if (num >= 20) {
            result += tens[Math.floor(num / 10)];
            if (num % 10 > 0) {
                result += " " + ones[num % 10];
            }
        } else if (num > 0) {
            result += ones[num];
        }
        return result;
    }

    var result = "";
    if (number >= 1000000) {
        result += convertGroup(Math.floor(number / 1000000)) + " Million ";
        number %= 1000000;
    }
    if (number >= 1000) {
        result += convertGroup(Math.floor(number / 1000)) + " Thousand ";
        number %= 1000;
    }
    if (number >= 1) {
        result += convertGroup(Math.floor(number));
    }

    var decimalPart = number % 1;
    if (decimalPart > 0) {
        result += " and " + (decimalPart * 100).toFixed(0) + "/100";
    }

    return result.trim();
    }

</script>
<script>
    
    $(function(){
        $('#uni_modal_payment #pay-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_payment",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert("An error occured2",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        setTimeout(()=>{
                            printInputData()
                            end_loader();
                            location.reload();
                            /*  location.replace('./?page=admin/index.php&id='+resp.id_encrypt) */
                        },200)

                        /* alert(resp.msg);
                        location.reload(); */
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body,.modal').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>
