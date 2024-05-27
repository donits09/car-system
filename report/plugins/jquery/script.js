///////////////////////////BILLING ADDRESS///////////////////////////////////
function getLocal(){
	var rd1=document.getElementById('rdolocal');
	var rd2=document.getElementById('rdoabroad');
	if(rd1.checked==true){
		rd2.checked=false;
		document.getElementById('c_billing_address').value=document.getElementById("rdolocal").value;
	}
}
function getAbroad(){
	var rd1=document.getElementById('rdolocal');
	var rd2=document.getElementById('rdoabroad');
	if(rd2.checked==true){
		rd1.checked=false;
		document.getElementById('c_billing_address').value=document.getElementById("rdoabroad").value;
	}
}

///////////////////////////EMPLOYMENT STATUS///////////////////////////////////
function Unemployed(){
	var rd0=document.getElementById('rdounemployed');
	var rd1=document.getElementById('rdoemployed');
	var rd2=document.getElementById('rdoselfemployed');
	var rd3=document.getElementById('rdoocw');
	var rd4=document.getElementById('rdoretired');
	var rd5=document.getElementById('rdoothers');
	if(rd0.checked==true) { 
		rd1.checked=false;
		rd2.checked=false;
		rd3.checked=false;
		rd4.checked=false;
		rd5.checked=false;
		document.getElementById("c_employment_status").value = document.getElementById("rdounemployed").value; 
		document.getElementById("c_employment_status_others").readOnly = true;
	}
}
function Employed(){
	var rd0=document.getElementById('rdounemployed');
	var rd1=document.getElementById('rdoemployed');
	var rd2=document.getElementById('rdoselfemployed');
	var rd3=document.getElementById('rdoocw');
	var rd4=document.getElementById('rdoretired');
	var rd5=document.getElementById('rdoothers');
	if(rd1.checked==true) { 
		rd0.checked=false;
		rd2.checked=false;
		rd3.checked=false;
		rd4.checked=false;
		rd5.checked=false;
		document.getElementById("c_employment_status").value = document.getElementById("rdoemployed").value; 
		document.getElementById("c_employment_status_others").readOnly = true;
	}
}
function SelfEmployed(){
	var rd0=document.getElementById('rdounemployed');
	var rd1=document.getElementById('rdoemployed');
	var rd2=document.getElementById('rdoselfemployed');
	var rd3=document.getElementById('rdoocw');
	var rd4=document.getElementById('rdoretired');
	var rd5=document.getElementById('rdoothers');
	if(rd2.checked==true) { 
		rd0.checked=false;
		rd1.checked=false;
		rd3.checked=false;
		rd4.checked=false;
		rd5.checked=false;
		document.getElementById("c_employment_status").value = document.getElementById("rdoselfemployed").value; 
		document.getElementById("c_employment_status_others").readOnly = true;
	}
}
function OCW(){
	var rd0=document.getElementById('rdounemployed');
	var rd1=document.getElementById('rdoemployed');
	var rd2=document.getElementById('rdoselfemployed');
	var rd3=document.getElementById('rdoocw');
	var rd4=document.getElementById('rdoretired');
	var rd5=document.getElementById('rdoothers');
	if(rd3.checked==true) { 
		rd0.checked=false;
		rd1.checked=false;
		rd2.checked=false;
		rd4.checked=false;
		rd5.checked=false;
		document.getElementById("c_employment_status").value = document.getElementById("rdoocw").value; 
		document.getElementById("c_employment_status_others").readOnly = true;
	}
}
function Retired(){
	var rd0=document.getElementById('rdounemployed');
	var rd1=document.getElementById('rdoemployed');
	var rd2=document.getElementById('rdoselfemployed');
	var rd3=document.getElementById('rdoocw');
	var rd4=document.getElementById('rdoretired');
	var rd5=document.getElementById('rdoothers');
	if(rd4.checked==true) { 
		rd0.checked=false;
		rd1.checked=false;
		rd2.checked=false;
		rd3.checked=false;
		rd5.checked=false;
		document.getElementById("c_employment_status").value = document.getElementById("rdoretired").value; 
		document.getElementById("c_employment_status_others").readOnly = true;
	}
}
function Others(){
	var rd0=document.getElementById('rdounemployed');
	var rd1=document.getElementById('rdoemployed');
	var rd2=document.getElementById('rdoselfemployed');
	var rd3=document.getElementById('rdoocw');
	var rd4=document.getElementById('rdoretired');
	var rd5=document.getElementById('rdoothers');
	if(rd5.checked==true) { 
		rd0.checked=false;
		rd1.checked=false;
		rd2.checked=false;
		rd3.checked=false;
		rd4.checked=false;
		document.getElementById("c_employment_status").value = document.getElementById("rdoothers").value; 
		document.getElementById("c_employment_status_others").readOnly = false;
	}
}
function computeAge() {
    var now = new Date();                            
    var currentY = now.getFullYear();               
    var currentM= now.getMonth();                   

    var dobget = document.getElementById('c_birthdate').value; 
    var dob = new Date(dobget);                             
    var prevY = dob.getFullYear();                         
    var prevM = dob.getMonth();                             

    var ageY =currentY - prevY;
    var ageM =Math.abs(currentM- prevM);         

    document.getElementById('c_age').value = ageY;
}
function getAcronym(){
    var acronym =  document.getElementById('c_site');
    var code = model.options[acronym.selectedIndex].value;
    document.getElementById('c_site_txt').value = code;
}
///////////////////////////INVESTMENT TYPE///////////////////////////////////
function lotOnly(){
	var rd0=document.getElementById('rdolotonly');
	var rd1=document.getElementById('rdohouseonly');
	var rd2=document.getElementById('rdopackaged');
	var rd3=document.getElementById('rdoitothers');
	if(rd0.checked==true) { 
		rd1.checked=false;
		rd2.checked=false;
		rd3.checked=false;
		document.getElementById("c_investment_type").value = document.getElementById("rdolotonly").value; 
	}
}
function houseOnly(){
	var rd0=document.getElementById('rdolotonly');
	var rd1=document.getElementById('rdohouseonly');
	var rd2=document.getElementById('rdopackaged');
	var rd3=document.getElementById('rdoitothers');
	if(rd1.checked==true) { 
		rd0.checked=false;
		rd2.checked=false;
		rd3.checked=false;
		document.getElementById("c_investment_type").value = document.getElementById("rdohouseonly").value; 
	}
}
function Packaged(){
	var rd0=document.getElementById('rdolotonly');
	var rd1=document.getElementById('rdohouseonly');
	var rd2=document.getElementById('rdopackaged');
	var rd3=document.getElementById('rdoitothers');
	if(rd2.checked==true) { 
		rd0.checked=false;
		rd1.checked=false;
		rd3.checked=false;
		document.getElementById("c_investment_type").value = document.getElementById("rdopackaged").value; 
	}
}
function ITOthers(){
	var rd0=document.getElementById('rdolotonly');
	var rd1=document.getElementById('rdohouseonly');
	var rd2=document.getElementById('rdopackaged');
	var rd3=document.getElementById('rdoitothers');
	if(rd3.checked==true) { 
		rd0.checked=false;
		rd1.checked=false;
		rd2.checked=false;
		document.getElementById("c_investment_type").value = document.getElementById("rdoitothers").value; 
	}
}
///////////////////////////TITLE///////////////////////////////////
function getAnd(){
	var rdo0 = document.getElementById('rdoand');
	var rdo1 = document.getElementById('rdospouses');
	var rdo2 = document.getElementById('rdomarriedto');
	var rdo3 = document.getElementById('rdominorrep');

	if(rdo0.checked==true){
		rdo1.checked=false;
		rdo2.checked=false;
		rdo3.checked=false;
		document.getElementById("c_conjunction").value = document.getElementById("rdoand").value;
	}
}
function getSpouses(){
	var rdo0 = document.getElementById('rdoand');
	var rdo1 = document.getElementById('rdospouses');
	var rdo2 = document.getElementById('rdomarriedto');
	var rdo3 = document.getElementById('rdominorrep');

	if(rdo1.checked==true){
		rdo0.checked=false;
		rdo2.checked=false;
		rdo3.checked=false;
		document.getElementById("c_conjunction").value = document.getElementById("rdospouses").value;
	}
}
function getMarriedTo(){
	var rdo0 = document.getElementById('rdoand');
	var rdo1 = document.getElementById('rdospouses');
	var rdo2 = document.getElementById('rdomarriedto');
	var rdo3 = document.getElementById('rdominorrep');

	if(rdo2.checked==true){
		rdo0.checked=false;
		rdo1.checked=false;
		rdo3.checked=false;
		document.getElementById("c_conjunction").value = document.getElementById("rdomarriedto").value;
	}
}
function getMinorRep(){
	var rdo0 = document.getElementById('rdoand');
	var rdo1 = document.getElementById('rdospouses');
	var rdo2 = document.getElementById('rdomarriedto');
	var rdo3 = document.getElementById('rdominorrep');

	if(rdo3.checked==true){
		rdo0.checked=false;
		rdo1.checked=false;
		rdo2.checked=false;
		document.getElementById("c_conjunction").value = document.getElementById("rdominorrep").value;
	}
}
function concatName(){
	var lastname = document.getElementById("c_b1_last_name").value;
	var firstname = document.getElementById("c_b1_first_name").value;
	var middlename = document.getElementById("c_b1_middle_name").value;
	
	var res = firstname + ' ' + middlename + ' ' + lastname
	document.getElementById('c_client_name').value = res;
}
///////////////////////////DROPDOWN///////////////////////////////////
function selectPayment(){
    var dp1 = document.getElementById('c_payment_type1').value;
    var dp2 = document.getElementById('c_payment_type2').value;

    if(dp1=="PD" && dp2=="MA"){
        document.getElementById('div_partialdp').style.display="block";
        document.getElementById('div_fulldp').style.display="none";
        document.getElementById('div_sc').style.display="none";
        document.getElementById('div_ma').style.display="block";
        document.getElementById('div_dfc').style.display="none";  
    }else if(dp1=="PD" && dp2=="DFC"){
        document.getElementById('div_fulldp').style.display="none";
        document.getElementById('div_partialdp').style.display="block";
        document.getElementById('div_sc').style.display="none";
        document.getElementById('div_ma').style.display="none";
        document.getElementById('div_dfc').style.display="block";      
    }else if(dp1=="FD" && dp2=="MA"){
        document.getElementById('div_fulldp').style.display="block";
        document.getElementById('div_partialdp').style.display="none";
        document.getElementById('div_sc').style.display="none";
        document.getElementById('div_ma').style.display="block";
        document.getElementById('div_dfc').style.display="none";  
    }else if(dp1=="FD" && dp2=="DFC"){
        document.getElementById('div_fulldp').style.display="block";
        document.getElementById('div_partialdp').style.display="none";
        document.getElementById('div_sc').style.display="none";
        document.getElementById('div_ma').style.display="none";
        document.getElementById('div_dfc').style.display="block";
    }else if(dp1=="ND" && dp2=="MA"){
        document.getElementById('div_partialdp').style.display="none";
        document.getElementById('div_fulldp').style.display="none";
        document.getElementById('div_sc').style.display="none";
        document.getElementById('div_ma').style.display="block";
        document.getElementById('div_dfc').style.display="none";
    }else if(dp1=="ND" && dp2=="DFC"){
        document.getElementById('div_partialdp').style.display="none";
        document.getElementById('div_fulldp').style.display="none";
        document.getElementById('div_sc').style.display="none";
        document.getElementById('div_ma').style.display="none";
        document.getElementById('div_dfc').style.display="block";
    }else if(dp1=="SC"){
        document.getElementById('div_sc').style.display="block";
        document.getElementById('div_partialdp').style.display="none";
        document.getElementById('div_fulldp').style.display="none";
        document.getElementById('div_ma').style.display="none";
        document.getElementById('div_dfc').style.display="none";
        //document.getElementById('c_payment_type2').disabled="true";
    }    
}

///////////////////////////COMPUTATIONS///////////////////////////////////
function getVat(){
	var vat=document.getElementById('rdovat');
	var vatexempt=document.getElementById('rdovatexempt');
	if(vat.checked==true){
		vatexempt.checked=false;
		document.getElementById("c_vat_res").value = document.getElementById("rdovat").value;
	}
}
function getVatExempt(){
	var vat=document.getElementById('rdovat');
	var vatexempt=document.getElementById('rdovatexempt');
	if(vatexempt.checked==true){
		vat.checked=false;
		document.getElementById("c_vat_res").value = document.getElementById("rdovatexempt").value;
	}
}
function getTitleMonthly(){
	var titlemonthly=document.getElementById('rdotitlemonthly');
	var lumpsum=document.getElementById('rdolumpsum');
	if(titlemonthly.checked==true){
		lumpsum.checked=false;
		document.getElementById("c_title_monthly_res").value = document.getElementById("rdotitlemonthly").value;
	}
}
function getLumpsum(){
	var titlemonthly=document.getElementById('rdotitlemonthly');
	var lumpsum=document.getElementById('rdolumpsum');
	if(lumpsum.checked==true){
		titlemonthly.checked=false;
		document.getElementById("c_title_monthly_res").value = document.getElementById("rdolumpsum").value;
	}
}
function lotCalculation(){
	var l_area = document.getElementById("c_lot_area").value;
	var l_price = document.getElementById("c_price_sqm").value;
	var res = l_area * l_price;
		
	document.getElementById('c_lot_price').value = res;
	
	var l_disc_percentage = document.getElementById("c_lot_discount_percentage").value;

	var l_discount_amt = res*(l_disc_percentage*0.01);     
	var l_lcp = res - l_discount_amt; 

	document.getElementById('c_lot_discount_amount').value = l_discount_amt;
	document.getElementById('c_lcp').value = l_lcp;
}
function houseCalculation(){
    var h_price = document.getElementById('c_house_price_sqm').value;
    var h_flr_area = document.getElementById('c_floor_area').value;

	var res = h_price / h_flr_area;

	var h_disc_percentage = document.getElementById('c_house_discount').value;

	var h_discount_amt = h_price*(h_disc_percentage*0.01);     

	document.getElementById('c_house_discount_amt').value = h_discount_amt;
	var h_hcp = h_price - h_discount_amt

	document.getElementById('c_hcp').value = h_hcp;
	getTCP();
}
function getTCP(){
	var hcp = parseFloat(document.getElementById('c_hcp').value); 
	var lcp = parseFloat(document.getElementById('c_lcp').value); 

	var res = hcp + lcp;

	document.getElementById('c_tcp').value = res;
}
function getNTCP(){
	var tcp = document.getElementById('c_tcp').value;
	var tcp_disc = document.getElementById('c_tcp_discount').value;

	var res = tcp*(tcp_disc*0.01);
	var ntcp = tcp - res;

	document.getElementById('c_ntcp').value = ntcp;
}

//////////////////////////////////////////////////////////////////////////PRINT.PHP
function loadBasics(){
	lotCalculation();
	houseCalculation();
	loadEmpStatus();
	loadBillingAddress();
	loadGender();
	loadCivilStatus();	
	
	loadInvestmentType();
}
function loadBillingAddress(){
	var billingaddress=document.getElementById("billing_address").value;
	if(billingaddress=='0'){
		document.getElementById('rdolocal').checked=true;
	}else{
		document.getElementById('rdoabroad').checked=true;
	}
}
function loadGender(){
	var gender=document.getElementById("gender").value;
	if(gender=='0'){
		document.getElementById("female").selected = true;
	}else{
		document.getElementById("male").selected = true;
	}
}
function loadCivilStatus(){
	var civil=document.getElementById("civil_status").value;
	if(civil=='0'){
		document.getElementById("married").selected = true;
	}else if(civil=='1'){
		document.getElementById("separated").selected = true;
	}else if(civil=='2'){
		document.getElementById("single").selected = true;
	}else{
		document.getElementById("widowed").selected = true;
	}
}

function loadInvestmentType(){
	var investment=document.getElementById("investment_type").value;
	if(investment=='0'){
		document.getElementById("rdolotonly").checked=true;
	}else if(investment=='1'){
		document.getElementById("rdohouseonly").checked=true;
	}else if(investment=='2'){
		document.getElementById("rdopackaged").checked=true;
	}else if(investment=='3'){
		document.getElementById("rdoitothers").checked=true;
	}
}

