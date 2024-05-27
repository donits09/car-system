<?php require_once('../config.php'); ?>
<script src="../build/js/jquery.min.js" type="text/javascript"></script>
<script src="../build/js/num-to-words.js" type="text/javascript"></script>
<?php
//--->get app url > start

if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $ssl = 'https';
}
else {
  $ssl = 'http';
}
 
$app_url = ($ssl  )
          . "://".$_SERVER['HTTP_HOST']
          //. $_SERVER["SERVER_NAME"]
          . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
          . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

//--->get app url > end

header("Access-Control-Allow-Origin: *");

?>

<!DOCTYPE html>
<head>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
	<script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
</head>
<!DOCTYPE html>
<html lang="en">
<?php include "../inc/header.php";?>
  <?php

    $query = "SELECT * from `t_csr` where c_csr_no = '{$_GET['id']}' ";

    $result = mysqli_query($conn, $query);

    // mysqli select query
    if($result) {
        while ($row = mysqli_fetch_assoc($result)) {
        
        $c_csr_no = $row['c_csr_no']; 

        $c_reservation = $row['c_reservation'];
        $c_lot_lid = $row['c_lot_lid'];
       
	}
}
/* close connection */
$conn->close();
?>
<head>
    <link rel="stylesheet" href="css/print_ra.css">
</head>
<style>
.container-content{
    border:none!important;
}
.card-body{
    border:none!important;
    text-align:justify;
    font-size:10.5px;
    line-height:13.5px;
}
.watermark_sample{
    background-image: url("images/4.png");
    width:400px;
    background-repeat:no-repeat;
    position:absolute;
    height:350px;
    margin-top:900px;
    margin-left:350px;
    opacity:0.1;
}
.whole_content{
    visibility:hidden;
}
.form-control{
    border:none;
    background-color:transparent;
    width:auto;
    float:left;
}
.hiddentxt{
    width:65px;
    max-width:65px;
    height:auto;
    font-size:10.5px!important;
    border:none;
    text-decoration: underline;
    text-align:center;
    margin-top:0px;
}
.hiddentxt1{
    width:20px;
    height:auto;
    font-size:10.5px!important;
    border:none;
    text-decoration: underline;
    text-align:center;
    margin-top:0px;
}
#numtowords{
    width:300px;
    height:auto;
    font-size:10.5px!important;
    border:none;
    text-decoration: underline;
    text-align:center;
    text-transform:uppercase;
    margin-top:0px;
}
</style>

<body onload="printAgreement()">
<div class="whole_content">
<div class="text-center" style="padding:20px;">
	<input type="button" id="rep" value="Print" class="btn btn-info btn_print">
</div>
<div class="container_content" id="container_content">
<img src="images/Header.jpg" class="img-thumbnail" style="height:95px;width:650px;border:none;margin-left:20px;" alt="">
<h5 class="text-center" style="position:absolute;margin-top:-55px;margin-left:275px;"><b>RESERVATION AGREEMENT</b></h5>
<div style="clear:both"></div>
    <br>
    <div class="card-body" style="margin-top:-20px;">
        <div class="watermark_sample"></div>
        <input type="hidden" value="<?php echo $c_lot_lid; ?>" id="lid">
        <?php
            $l_lid=$c_lot_lid;
            $l_phase = intval(substr($l_lid, 0,3));
            $l_block = intval(substr($l_lid, 3,3));
            $l_lot = intval(substr($l_lid, 6,8));
        ?>

        <input type="hidden" value="<?php echo $l_phase; ?>" id="txtPhase">
    I hereby offer to purchase from Asian Land Strategies Corporation (“ALSC”, “Seller”) the following property (“Property”) and request that the property be reserved for my
purchase. Project Name and Phase <input type="text" class="hiddentxt" id="final_phase"> Block <input type="text" class="hiddentxt1" value="<?php echo $l_block; ?>"> Lot <input type="text" class="hiddentxt1" value="<?php echo $l_lot; ?>">. The property is to be paid by me in the manner I chose as indicated in the
attachments and understand that the purchase price is valid only for the payment scheme and manner of payment which has been selected herein. I request that the
Property be reserved, and for this purpose I deposit the amount of Pesos: <input type="text" id="numtowords"> (Php<input type="text" class="hiddentxt" value="<?php echo $c_reservation; ?>" id="res_amount">) my reservation money for the
Property. Should I decide to change the selected payment manner, such change will be effective only upon the approval of the Seller, and will also result in a change of
Purchase Price and amendment of necessary documents.<br><br>
I understand and agree that my reservation for the property is subject to the following Terms and Conditions:<br>

1.&#8195;&nbsp;That &nbsp;the &nbsp;Reservation for &nbsp;the property &nbsp;specified above is good &nbsp;only for a &nbsp;period of forty-five &nbsp;(45) calendar days from the &nbsp;payment of the reservation money <br>&#8195;&#8195;and I understand that Seller reserves the right to approve or deny my offer for reservation.<br>
2.&#8195;&nbsp;Reservation &nbsp;Money/Check shall &nbsp;not produce the effect of &nbsp;payment &nbsp;until proceeds &nbsp;thereof have &nbsp;been actually received by &nbsp;ALSC and issued with a &nbsp;Collection <br>&#8195;&#8195;Receipt /
Sales Invoice for the application.<br>
3.&#8195;&nbsp;I undertake &nbsp;to submit &nbsp;to the &nbsp;Seller a &nbsp;completely &nbsp;filled out &nbsp;Reservation &nbsp;Application &nbsp;with all the &nbsp;information, &nbsp;requirements &nbsp;and &nbsp;documentation &nbsp;required in <br>&#8195;&#8195;connection
with the &nbsp;sale and that I &nbsp;understand that this RA &nbsp;shall be deemed &nbsp;cancelled in case I &nbsp;fail to submit &nbsp;all requirements, &nbsp;documents &nbsp;and &nbsp;information 
<br>&#8195;&#8195;within &nbsp;fifteen &nbsp;(15) days &nbsp;from the date of &nbsp;reservation. I also &nbsp;understand that &nbsp;an additional &nbsp;house account shall be treated as &nbsp;a separate account and shall be<br>&#8195;&#8195;subject to the terms and &nbsp;conditions
and &nbsp;documentation distinct &nbsp;from the lot account. I undertake &nbsp;to submit to full credit assessment which may be  <br>&#8195;&#8195;conducted by the Seller in connection to the purchase of the Property.<br>
4.&#8195;&nbsp;It is further &nbsp;agreed &nbsp;that my &nbsp;Reservation is &nbsp;valid and &nbsp;binding &nbsp;only when &nbsp;approved by&nbsp; the &nbsp;Seller and my &nbsp;payments &nbsp;will &nbsp;be forfeited &nbsp;in favor of the Seller, if <br>&#8195;&#8195;my application is not approved.<br>
&#8195;&#8195;A.&nbsp;&#8195;If my purchase &nbsp;of &nbsp;the Property &nbsp;is approved &nbsp;by the Seller, I &nbsp;agree to &nbsp;comply with all &nbsp;the conditions &nbsp;for &nbsp;purchase &nbsp;which &nbsp;are or &nbsp;may be &nbsp;prescribed &nbsp;by 
<br>&#8195;&#8195;&#8195;&#8195;the Seller &nbsp;for &nbsp;the &nbsp;purchase &nbsp;of &nbsp;the &nbsp;Property, &nbsp;including &nbsp;but &nbsp;not &nbsp;limited to (i) &nbsp;obtaining a &nbsp;Credit &nbsp;Life &nbsp;Insurance &nbsp;plus &nbsp;Fire &nbsp;Insurance &nbsp;from a reputable 
<br>&#8195;&#8195;&#8195;&#8195;insurance provider &nbsp;acceptable to the &nbsp;Seller &nbsp;in &nbsp;an &nbsp;amount &nbsp;sufficient &nbsp;to &nbsp;cover &nbsp;the &nbsp;value of the &nbsp;Property &nbsp;or, &nbsp;subject &nbsp;to &nbsp;the &nbsp;acceptance &nbsp;of &nbsp;the &nbsp;Seller, 
<br>&#8195;&#8195;&#8195;&#8195;assigning to the &nbsp;Seller, as &nbsp;beneficiary to the &nbsp;extent of &nbsp;the unpaid &nbsp;balance of &nbsp;the &nbsp;Purchase &nbsp;Price of the &nbsp;Property, my existing life &nbsp;insurance policy, and 
<br>&#8195;&#8195;&#8195;&#8195;(ii) submitting post dated checks covering the installment payment due under the agreed payment scheme. I understand that a penalty will be charged to 
<br>&#8195;&#8195;&#8195;&#8195;me upon my failure to fund said checks.
<br>&#8195;&#8195;B.&nbsp;&#8195;If the purchase of&nbsp; the Property is disapproved, I understand that I may still purchase the Property only under any of the following payment schemes which 
<br>&#8195;&#8195;&#8195;&#8195;may be prescribed by the Seller: (i) cash scheme, where I agree to pay the Purchase Price, taxes and other costs in full; or (ii) deferred cash payment scheme 
&#8195;&#8195;&#8195;&#8195;(refer to Reservation Application).<br>
5.&#8195;&nbsp;Should I decide to cancel the &nbsp;reservation, or if &nbsp;unable to settle the &nbsp;amount due on the date stipulated, it is understood that the reservation shall lapse and the
<br>&#8195;&#8195;reservation &nbsp;money &nbsp;shall be &nbsp;forfeited. I agree &nbsp;that any &nbsp;failure by me to make the &nbsp;necessary &nbsp;payments &nbsp;forty-five &nbsp;(45) days from &nbsp;the date of &nbsp;payment of the 
<br>&#8195;&#8195;reservation &nbsp;shall &nbsp;cause the full &nbsp;forfeiture in &nbsp;favor of the &nbsp;Seller and that the &nbsp;latter shall have the right to &nbsp;automatically cancel &nbsp;the reservation without further 
<br>&#8195;&#8195;notice as liquidated damages. It is understood that in any event of cancellation of this Reservation Agreement, the Seller shall be free to dispose of the Property 
<br>&#8195;&#8195;as if this Reservation Agreement had not been executed.<br>


6.&#8195;&nbsp;In the event that I fail to pay any amounts due under my &nbsp;payment scheme in &nbsp;relation to my purchase &nbsp;of the property, or fail &nbsp;to comply &nbsp;with my undertakings 
<br>&#8195;&#8195;herein, or fail to execute the relevant Contract to Sell and/or Deed of &nbsp;Absolute Sale for the &nbsp;property, or comply with &nbsp;the terms of my purchase, then the Seller 
<br>&#8195;&#8195;shall have the option to cancel the sale and refund all payments less: (i) The Reservation Fee, which shall be forfeited in favor of the Seller as liquidated damage; 
<br>&#8195;&#8195;(ii) &nbsp;Broker’s commission; (iii) Any unpaid &nbsp;charges and dues on the &nbsp;Property ; (iv) &nbsp;Taxes and expenses &nbsp;paid by the &nbsp;Seller to the &nbsp;government or &nbsp;third parties in 
<br>&#8195;&#8195;connection herewith; (v) Construction bond for my  &nbsp;construction works,  &nbsp;if applicable;  &nbsp;(vi) any amount determined by the  &nbsp;Seller to be  &nbsp;necessary to &nbsp;restore the 
<br>&#8195;&#8195;Property to the same physical condition it was found at  &nbsp;the time of acceptance of the Property. I understand that remittance to the Bureau of Internal &nbsp;Revenue 
<br>&#8195;&#8195;of the applicable creditable withholding tax (CWT) on  &nbsp;my payments is required under applicable rules and regulations. Should a delay in the remittance &nbsp;of the 
<br>&#8195;&#8195;CWT arise by the reason of the information I provide herein, including &nbsp;information on  &nbsp;whether I am engaged in business,  &nbsp;I undertake to pay and &nbsp; not to hold 
<br>&#8195;&#8195;the Seller liable for any penalty and/or surcharge, costs, and &nbsp;expenses which may be incurred in connection with such delay.<br>

7.&#8195;&nbsp;It is agreed that this &nbsp;Reservation is &nbsp;transferable only to &nbsp;immediate family &nbsp;members, and &nbsp;any third party transfer made by me shall be void and shall cause 
for <br>&#8195;&#8195;the cancellation of the Reservation and forfeiture of my reservation money and other payment.<br>
8.&#8195;&nbsp;That I certify that I have personally inspected the Property subject of this reservation and I have found the same to be satisfactory.
<br>9.&#8195;&nbsp;&nbsp;In the event the subject property is unavailable for sale to me due to a prior sale commitment, the same having been offered to me by mistake or 
<br>&#8195;&#8195;&nbsp;inadvertence, I agree to have the subject property exchanged with a property of equal value, or to the cancellation of the reservation agreement, subject to the 
<br>&#8195;&#8195;&nbsp;reimbursement of all my payments previously made by me by reason of this reservation.<br>
10.&#8195;Any provision to the contrary withstanding, I hereby agree and acknowledge that the Seller has the right to cancel and rescind this reservation for any cause
<br>&#8195;&#8195;&nbsp;whatsoever at any time before issuance of my Contract to Sell by giving written notice of its intention and refunding to me all payments made by virtue 
<br>&#8195;&#8195;&nbsp;thereof.
<br>11.&#8195;I hereby undertake to execute the Contract to Sell with the seller upon payment of the down payment, and the Deed of Absolute Sale with the Seller upon full 
<br>&#8195;&#8195;&nbsp;payment of the Purchase Price and all amounts due on the purchase of the property. The contracts will be in the form and under the terms prescribed by the 
<br>&#8195;&#8195;&nbsp;Seller. I confirm that upon full payment, the Seller shall have the right to execute a Deed of Absolute Sale in my favor. I understand that non delivery of the 
<br>&#8195;&#8195;&nbsp;copy of Contract to Sell shall not delay the commencement of the payment of the monthly installment due.<br>
12.&#8195;I agree that all taxes, fees and expenses which are imposed or incurred in connection with the sale of the Property, execution of documents with the Registry of 
<br>&#8195;&#8195;&nbsp;Deeds, and the transfer in my favor of the certificates of title covering the property, and any increase in the rates prevailing of the date of this reservation of all 
<br>&#8195;&#8195;&nbsp;taxes, fees and expenses shall be for my account.<br>
13.&#8195;I agree that the purchase of the property is subject to the covenants and restrictions as specified in the project’s Deed of Restrictions, and that I undertake to 
<br>&#8195;&#8195;&nbsp;faithfully comply with. That compliance herein is an essential consideration of the sale by the Seller of the Property to me and all other agreements executed in 
<br>&#8195;&#8195;&nbsp;connection herewith.<br>
14.&#8195;I warrant that the information provided are true and correct as of the date hereof and agree to inform the Seller of any changes in my personal data such as 
<br>&#8195;&#8195;&nbsp;name, &nbsp;address and contact details. &nbsp;I also warrant that the  &nbsp;funds used for  &nbsp;the  &nbsp;purchase of  &nbsp;the property is  &nbsp;obtained through  &nbsp;legitimate means and do not 
<br>&#8195;&#8195;&nbsp;constitute all or &nbsp;part of proceeds of any unlawful activity under  &nbsp;applicable  &nbsp;laws. I hereby  &nbsp;hold Seller free and  &nbsp;harmless from any  &nbsp;incident, claim,  &nbsp;action or 
<br>&#8195;&#8195;&nbsp;liability &nbsp;arising from the breach of my warranty herein, and hereby authorize the Seller to disclose to any government body or agency any information 
<br>&#8195;&#8195;&nbsp;pertaining to this sale &nbsp;and purchase transaction if so warranted and required under existing laws.<br>
15.&#8195;To facilitate continuous &nbsp;vital and &nbsp;crucial communication between the&nbsp; Seller, I agree &nbsp;to join the Viber &nbsp;Contact and Channel of Seller, &nbsp;whose main &nbsp;number is 
<br>&#8195;&#8195;&nbsp;0917-523-7373. I commit not to leave the said Viber Channel to assure that I always receive and send any communication required under the herein Contract. I 
<br>&#8195;&#8195;&nbsp;agree to update my contact information yearly.<br>
16.&#8195;This Reservation Agreement constitutes the complete understanding between parties with respect to the subject matter and &nbsp;supersedes any &nbsp;prior expression 
<br>&#8195;&#8195;&nbsp;of intent, &nbsp;representation or &nbsp;warranty with &nbsp;respect to this &nbsp;transaction. ALSC is not &nbsp;and shall &nbsp;not be &nbsp;bound by &nbsp;stipulations, representations, agreements, or 
<br>&#8195;&#8195;&nbsp;promises, oral or otherwise not contained in this agreement. That any representation to me by the agent who handled this sale not embodied herein shall not 
<br>&#8195;&#8195;&nbsp;be binding on the Seller unless reduced into writing and confirmed by the President of ALSC and this contract shall not be considered as changed, modified, 
<br>&#8195;&#8195;&nbsp;altered or in any way &nbsp;amended by &nbsp;acts of &nbsp;tolerance of ALSC, &nbsp;unless such changes, &nbsp;modification or amendments are made in writing and signed by the 
<br>&#8195;&#8195;&nbsp;aforementioned officer. Only duly authorized
officers of the company are allowed to make commitments for and in behalf of ALSC.<br>
17.&#8195;I understand and agree that this Reservation &nbsp;Agreement only gives me the &nbsp;right to purchase the property and that no other right, title or ownership is vested 
<br>&#8195;&#8195;&nbsp;upon me by the execution of the Reservation Agreement. The Seller retains title and ownership of the property until my full payment of all amounts due to the 
<br>&#8195;&#8195;&nbsp;Seller by reason of purchase of the Property.<br><br><br>

&#8195;&#8195;&#8195;&#8195;&#8195;I conform to the foregoing and certify that all information provided herein are true and correct.<br><br><br>
&#8195;&#8195;&#8195;&#8195;&#8195;_______________________________________________________&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;______________________________________________________<br>
&#8195;&#8195;&#8195;&#8195;&#8195;Client’s Signature over Printed Name/Date Purchase&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;Client’s Signature over Printed Name/Date Purchase<br><br>
<br>&#8195;&#8195;&#8195;&#8195;&#8195;Witnessed by:<br><br>
&#8195;&#8195;&#8195;&#8195;&#8195;______________________________________________________<br>
&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;Authorized Signature over Printed Name/Date
    </div>
</div>
</div>
</body>
</html>


<script type="text/javascript">
function per() {
    var words="";
    var totalamount = document.getElementById('res_amount').value;
    
    words = toWords(totalamount);
    $("#numtowords").val(words + "Pesos Only");
}

    ///////////////////////////BILLING ADDRESS///////////////////////////////////
function printAgreement(){
    getPhase();
    per();
        var element = document.getElementById('container_content'); 

        var opt = 
        {
            margin:       [0,10,0,5],
            filename:    'RA<?php echo $c_csr_no; ?>_AGREEMENT'+'.pdf',
            
            image:        { type: 'jpeg', quality: 2 },
            html2canvas:  { dpi: 300, letterRendering: true, width: 780, height: 1500, scale:2},
            jsPDF:        { unit: 'mm', format: 'legal', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();

        window.setTimeout(function(){
        window.history.back();
        }, 500);
}

function getPhase(){
    var phase=document.getElementById("txtPhase").value;

    if(phase==152){
        document.getElementById('final_phase').value="CBP";
    }else if(phase==161){
        document.getElementById('final_phase').value="CBP-1A";
    }else if(phase==180){
        document.getElementById('final_phase').value="CBP-1B";
    }else if(phase==157){
        document.getElementById('final_phase').value="CBP-2";
    }else if(phase==166){
        document.getElementById('final_phase').value="CBP-2A";
    }else if(phase==170){
        document.getElementById('final_phase').value="CBP-2B";
    }else if(phase==168){
        document.getElementById('final_phase').value="CBP-3";
    }else if(phase==182){
        document.getElementById('final_phase').value="CBP-3A";
    }else if(phase==186){
        document.getElementById('final_phase').value="CBP-3B";
    }else if(phase==189){
        document.getElementById('final_phase').value="CBP-3C";
    }else if(phase==169){
        document.getElementById('final_phase').value="CBP-4";
    }else if(phase==172){
        document.getElementById('final_phase').value="CBP-5";
    }else if(phase==183){
        document.getElementById('final_phase').value="CBP-5A";
    }else if(phase==187){
        document.getElementById('final_phase').value="CBP-5B";
    }else if(phase==102){
        document.getElementById('final_phase').value="CR";
    }else if(phase==191){
        document.getElementById('final_phase').value="CR-AH";
    }else if(phase==104){
        document.getElementById('final_phase').value="DCH-1";
    }else if(phase==114){
        document.getElementById('final_phase').value="DCH-1A";
    }else if(phase==107){
        document.getElementById('final_phase').value="DCH-2A";
    }else if(phase==109){
        document.getElementById('final_phase').value="DCH-2B";
    }else if(phase==137){
        document.getElementById('final_phase').value="DCH-2C";
    }else if(phase==158){
        document.getElementById('final_phase').value="DCH-2D";
    }else if(phase==112){
        document.getElementById('final_phase').value="DCH-3";
    }else if(phase==116){
        document.getElementById('final_phase').value="DCH-4";
    }else if(phase==117){
        document.getElementById('final_phase').value="DCH-5";
    }else if(phase==138){
        document.getElementById('final_phase').value="DCH-5A";
    }else if(phase==145){
        document.getElementById('final_phase').value="DCH-5B";
    }else if(phase==147){
        document.getElementById('final_phase').value="DCH-5C";
    }else if(phase==162){
        document.getElementById('final_phase').value="DCH-5D";
    }else if(phase==185){
        document.getElementById('final_phase').value="DCH-5E";
    }else if(phase==192){
        document.getElementById('final_phase').value="DCH-AH";
    }else if(phase==106){
        document.getElementById('final_phase').value="GIE";
    }else if(phase==103){
        document.getElementById('final_phase').value="GR-1";
    }else if(phase==128){
        document.getElementById('final_phase').value="GR-10";
    }else if(phase==110){
        document.getElementById('final_phase').value="GR-1A";
    }else if(phase==133){
        document.getElementById('final_phase').value="GR-1B";
    }else if(phase==134){
        document.getElementById('final_phase').value="GR-1C";
    }else if(phase==153){
        document.getElementById('final_phase').value="GR-1D";
    }else if(phase==154){
        document.getElementById('final_phase').value="GR-1E";
    }else if(phase==160){
        document.getElementById('final_phase').value="GR-1F";
    }else if(phase==105){
        document.getElementById('final_phase').value="GR-2";
    }else if(phase==108){
        document.getElementById('final_phase').value="GR-2A";
    }else if(phase==111){
        document.getElementById('final_phase').value="GR-3";
    }else if(phase==139){
        document.getElementById('final_phase').value="GR-3A";
    }else if(phase==165){
        document.getElementById('final_phase').value="GR-3B";
    }else if(phase==113){
        document.getElementById('final_phase').value="GR-4";
    }else if(phase==136){
        document.getElementById('final_phase').value="GR-4A";
    }else if(phase==115){
        document.getElementById('final_phase').value="GR-5";
    }else if(phase==118){
        document.getElementById('final_phase').value="GR-5A";
    }else if(phase==142){
        document.getElementById('final_phase').value="GR-5B";
    }else if(phase==144){
        document.getElementById('final_phase').value="GR-5C";
    }else if(phase==151){
        document.getElementById('final_phase').value="GR-5D";
    }else if(phase==119){
        document.getElementById('final_phase').value="GR-6";
    }else if(phase==143){
        document.getElementById('final_phase').value="GR-6A";
    }else if(phase==148){
        document.getElementById('final_phase').value="GR-6B";
    }else if(phase==173){
        document.getElementById('final_phase').value="GR-6C";
    }else if(phase==184){
        document.getElementById('final_phase').value="GR-6D";
    }else if(phase==179){
        document.getElementById('final_phase').value="GR-6E";
    }else if(phase==120){
        document.getElementById('final_phase').value="GR-7";
    }else if(phase==130){
        document.getElementById('final_phase').value="GR-7A";
    }else if(phase==132){
        document.getElementById('final_phase').value="GR-7B";
    }else if(phase==135){
        document.getElementById('final_phase').value="GR-7C";
    }else if(phase==140){
        document.getElementById('final_phase').value="GR-7D";
    }else if(phase==141){
        document.getElementById('final_phase').value="GR-7E";
    }else if(phase==146){
        document.getElementById('final_phase').value="GR-7F";
    }else if(phase==155){
        document.getElementById('final_phase').value="GR-7G";
    }else if(phase==159){
        document.getElementById('final_phase').value="GR-7H";
    }else if(phase==171){
        document.getElementById('final_phase').value="GR-7I";
    }else if(phase==188){
        document.getElementById('final_phase').value="GR-7J";
    }else if(phase==121){
        document.getElementById('final_phase').value="GR-8";
    }else if(phase==124){
        document.getElementById('final_phase').value="GR-8A";
    }else if(phase==125){
        document.getElementById('final_phase').value="GR-8B";
    }else if(phase==126){
        document.getElementById('final_phase').value="GR-8C";
    }else if(phase==129){
        document.getElementById('final_phase').value="GR-8D";
    }else if(phase==131){
        document.getElementById('final_phase').value="GR-8E";
    }else if(phase==178){
        document.getElementById('final_phase').value="GR-8F";
    }else if(phase==122){
        document.getElementById('final_phase').value="GR-9";
    }else if(phase==127){
        document.getElementById('final_phase').value="GR-9A";
    }else if(phase==175){
        document.getElementById('final_phase').value="GR-9B";
    }else if(phase==193){
        document.getElementById('final_phase').value="GR-AH";
    }else if(phase==194){
        document.getElementById('final_phase').value="MEAD-AH";
    }else if(phase==123){
        document.getElementById('final_phase').value="MEADOWS";
    }else if(phase==164){
        document.getElementById('final_phase').value="MEADOWS-2";
    }else if(phase==101){
        document.getElementById('final_phase').value="RE";
    }else if(phase==150){
        document.getElementById('final_phase').value="RE-2";
    }else if(phase==190){
        document.getElementById('final_phase').value="RE-AH";
    }else if(phase==149){
        document.getElementById('final_phase').value="WGR";
    }else if(phase==167){
        document.getElementById('final_phase').value="WGR-1A";
    }else if(phase==177){
        document.getElementById('final_phase').value="WGR-1B";
    }else if(phase==156){
        document.getElementById('final_phase').value="WGR-2";
    }else if(phase==176){
        document.getElementById('final_phase').value="WGR-2A";
    }else if(phase==181){
        document.getElementById('final_phase').value="WGR-2B";
    }else if(phase==163){
        document.getElementById('final_phase').value="WGR-3";
    }else if(phase==174){
        document.getElementById('final_phase').value="WGR-4";
    }
}
</script>
