
$(document).ready(function() {

	let dt = new Date().toISOString().slice(0, 10);

	$("#action_add_comment").click(function(e) {
		e.preventDefault();
	    actionAddComment();
	});

	$("#action_add_reply").click(function(e) {
		e.preventDefault();
	    actionAddReply();
	});

	$(".birth_day").on("change", function() {
		// Get the entered date value
		var enteredDate = $(this).val();
		// Check if the entered date is in YYYY-MM-DD format
		var datePattern = /^\d{4}-\d{2}-\d{2}$/;
		if (datePattern.test(enteredDate)) {
			$(this).parent().removeClass("has-error");
			
			// Split the entered date into year, month, and day
			var parts = enteredDate.split("-");
			var year = parseInt(parts[0], 10);
			var month = parseInt(parts[1], 10);
			var day = parseInt(parts[2], 10);

			// Calculate the age
			var currentDate = new Date();
			var age = currentDate.getFullYear() - year;

			// If the birthday hasn't occurred yet this year, subtract 1 from age
			if (
				currentDate.getMonth() + 1 < month ||
				(currentDate.getMonth() + 1 === month && currentDate.getDate() < day)
			) {
				age--;
			}

			// Update the age input field
			$("#customer_age").val(age);
		} else {
			// If the entered date is not in the correct format, you can handle the error here.
			// For example, you can display a message to the user or clear the age field.
			
			$(this).parent().addClass("has-error");
			alert_toast("Please enter a valid date in YYYY-MM-DD format.",'error');
			$("#customer_age").val("");
			$("#birth_day").val("");
		}
	});



	// delete reservation
	$(document).on('click', ".delete-reservation", function(e) {
		e.preventDefault();
		var raId = 'action=delete_reservation&delete='+ $(this).attr('data-ra-id')+'&csr_no=' + $(this).attr('data-csr-no');
		var ra = $(this);
		
		$('#delete_ra').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete', function() {
			deleteReservation(raId);
			$(ra).closest('tr').remove();
		});
		
		});


	//agent
	$(document).on('click', ".item-select", function(e) {

		e.preventDefault;

		var agent = $(this);

		$('#insert').modal({ backdrop: 'static', keyboard: false }).one('click', '#selected', function(e) {


		 var itemText = $('#insert').find("option:selected").text();
		 var itemValue = $('#insert').find("option:selected").val();
		
		 //alert(itemValue);
		 
		var data = itemValue.split("-");
		 
		var code = data[0];
		var pos = data[1];


		 $(agent).closest('tr').find('.agent-name').val(itemText);
		 $(agent).closest('tr').find('.agent-code').val(code);
		 $(agent).closest('tr').find('.agent-pos').val(pos);



		 //updateTotals('.calculate');
		 //calculateTotal();

		});

		return false;

	});

	$(document).on('click', ".model-select", function(e) {

		e.preventDefault;

		var model = $(this);

		$('#insert_model').modal({ backdrop: 'static', keyboard: false }).one('click', '#selected_model', function(e) {


		 var itemText = $('#insert_model').find("option:selected").text();


		 $('#house_model').val(itemText);
	


		 //updateTotals('.calculate');
		 //calculateTotal();

		});

		return false;

	});

	
	// create customer
	$("#action_create_customer").click(function(e) {
		e.preventDefault();
	    actionCreateCustomer();
	});

   	
   	$(document).on('click', ".select-customer", function(e) {

		e.preventDefault;

		var customer = $(this);

		$('#insert_customer').modal({ backdrop: 'static', keyboard: false }).one('click', '.customer-select', function(e) {

			//old version
			var customer_last_name = $(this).attr('data-customer-lname');
			var customer_first_name = $(this).attr('data-customer-fname');
			var customer_middle_name = $(this).attr('data-customer-mname');
			var customer_suffix_name = $(this).attr('data-customer-sname');
	
			var customer_email = $(this).attr('data-customer-email');
			var customer_phone = $(this).attr('data-customer-phone');
	
			var customer_address_1 = $(this).attr('data-customer-address-1');
			var customer_zip_code = $(this).attr('data-customer-zip-code');
	
			var customer_address_abroad = $(this).attr('data-customer-address-abroad');
	
			var customer_viber = $(this).attr('data-customer-viber');
			var customer_birthday = $(this).attr('data-customer-birthday');
			var customer_age = $(this).attr('data-customer-age');
			var customer_gender = $(this).attr('data-customer-gender');
			var customer_civil = $(this).attr('data-customer-civil');
			var customer_ctzn = $(this).attr('data-customer-ctzn');

			//new version
			
		/* 	$('.buyer-last').val(customer_last_name); */
	
			$(customer).closest('tr').find('.buyer-last').val(customer_last_name);
			$(customer).closest('tr').find('.buyer-first').val(customer_first_name);
			$(customer).closest('tr').find('.buyer-middle').val(customer_middle_name);
			$(customer).closest('tr').find('.buyer-suffix').val(customer_suffix_name);
			$(customer).closest('tr').find('.buyer-address').val(customer_address_1);
			$(customer).closest('tr').find('.buyer-zipcode').val(customer_zip_code);
			$(customer).closest('tr').find('.buyer-add-abroad').val(customer_address_abroad);
			$(customer).closest('tr').find('.buyer-viber').val(customer_viber);
			$(customer).closest('tr').find('.buyer-bday').val(customer_birthday);
			$(customer).closest('tr').find('.buyer-age').val(customer_age);
			$(customer).closest('tr').find('.buyer-contact').val(customer_phone);
			$(customer).closest('tr').find('.buyer-email').val(customer_email);
			$(customer).closest('tr').find('.buyer-gender').val(customer_gender);
			$(customer).closest('tr').find('.buyer-civl').val(customer_civil);
			$(customer).closest('tr').find('.buyer-ctzn').val(customer_ctzn);

	
	
	
			$('#insert_customer').modal('hide');
	
		});





		return false;

   	});

		
	//select lot

	$(document).on('click', ".select-lot", function(e) {

		e.preventDefault;

		var lot = $(this);
		

		$('#insert_lot').modal({ backdrop: 'static', keyboard: false });
	

		return false;

	});

	//select lot

	
	$(document).on('click', ".lot-select", function(e) {

		let prod_lid = $(this).attr('data-lot-lid');
		let prod_h_lid = $(this).attr('data-house-lid');
		var prod_site =  $(this).attr('data-lot-site');
		var prod_block = $(this).attr('data-lot-block');
		var prod_lot = $(this).attr('data-lot-lot');

		var prod_lot_area = $(this).attr('data-lot-lot-area');
		var prod_price_sqm = $(this).attr('data-lot-per-sqm');

		var prod_floor_area = $(this).attr('data-floor-area');
		var prod_h_price_sqm = $(this).attr('data-house-price');
		var prod_house_model = $(this).attr('data-house-model');

		var lot_status = $(this).attr('data-lot-status');

		//alert(lot_status);
	
		$('#l_lid').val(prod_lid);
		$('#l_house_lid').val(prod_h_lid);
		$('#l_site').val(prod_site);
		
		//$('#prod_code').val(prod_site);
		$('#l_block').val(prod_block);
		$('#l_lot').val(prod_lot);

		$('#lot_area').val(prod_lot_area);
		$('#price_per_sqm').val(prod_price_sqm);
		subtotal = parseInt(prod_lot_area) * parseFloat(prod_price_sqm);
		$('#amount').val(subtotal.toFixed(2))
		var lot_discount = 0
		var lot_disc_amount = 0
		$('#lot_disc').val(lot_discount.toFixed(2));
		$('#lot_disc_amt').val(lot_disc_amount.toFixed(2));
		$('#lcp').val(subtotal.toFixed(2));

		
		if(prod_floor_area == "" & prod_h_price_sqm == ""){
			var prod_floor_area = 0
			var prod_h_price_sqm = 0
		}
		$('#house_model').val(prod_house_model);
		$('#floor_area').val(prod_floor_area);
		$('#h_price_per_sqm').val(prod_h_price_sqm);
		subtotal2 = parseInt(prod_floor_area) * parseFloat(prod_h_price_sqm);
		$('#hcp').val(subtotal2.toFixed(2))



		$('#insert_lot').modal('hide');

        compute_net_tcp();
		compute_lot();
		

	});


	// $(document).on('click', ".select-ra", function(e) {

	// 	e.preventDefault;

	//  	var ra = $(this); 

	// 	$('#insert_ra').modal({ backdrop: 'static', keyboard: false });

	// 	return false;

	// });

	// $(document).on('click', ".ra-select", function(e) {

	// 	var ra_no =  $(this).attr('data-ra-no');
	// 	var lot_lid =  $(this).attr('data-ra-lot-lid');
	// 	var csr_no =  $(this).attr('data-csr-no');
	// 	var ra_site = $(this).attr('data-ra-site');
	// 	var ra_block = $(this).attr('data-ra-block');
	// 	var ra_lot = $(this).attr('data-ra-lot');
	// 	var fullname = $(this).attr('data-ra-fname');

	// 	var reserve_or_no = $(this).attr('data-or-no');
	// 	var reserve_date = $(this).attr('data-reserve-date');
	// 	/* var reserve_amt = $(this).attr('data-amt-paid'); */
	// 	var reserve_amt = $(this).attr('data-res-remaining');
	// 	var total_res = $(this).attr('data-ra-res');


	// 	$('#ra_no').val(ra_no);
	// 	$('#lot_lid').val(lot_lid);
	// 	$('#csr_no').val(csr_no);
	// 	$('#reserve_site').val(ra_site);
	// 	$('#reserve_block').val(ra_block);
	// 	$('#reserve_lot').val(ra_lot);
	// 	$('#fullname').val(fullname);
	// 	$('#or_no').val(reserve_or_no);
	// 	$('#pay_date').val(reserve_date);
	// 	$('#amount_paid').val(reserve_amt);
	// 	$('#total_res').val(total_res);

		


	// 	$('#insert_ra').modal('hide');

	// });
// remove commission row
$('#comm_table').on('click', ".delete-row", function(e) {
	e.preventDefault();
		$(this).closest('tr').remove();
	//calculateTotal();
});


$('#buyer_table').on('click', ".delete-buyer-row", function(e) {
	e.preventDefault();
		$(this).closest('tr').remove();
	//calculateTotal();
});


// add new agent row on ra
var cloned = $('#comm_table tr:last').clone();
cloned.find('input').val('');
$(".add-row").click(function(e) {
	e.preventDefault();
	cloned.clone().appendTo('#comm_table'); 
});

// add new agent row on ra
var cloned = $('#payment_table tr:last').clone();
cloned.find('input').val('');
$(".add-pay-row").click(function(e) {
	e.preventDefault();
	cloned.clone().appendTo('#payment_table'); 
});
$(".add-buyer-row").click(function(e) {
    e.preventDefault();
    var clonedRow = $('#buyer_table tr:last').clone();
    clonedRow.find('input').val('');
    clonedRow.find('.buyer-bday').on('change', function() {
        calculateAgeAndSetError($(this), $(this).closest('tr').find("#age"));
    });

    $('#buyer_table tbody').append(clonedRow);
});
$('#comm_table').on('input', '.calculate', function () {
	//alert(this);
	updateTotals(this);  
});

function updateTotals(elem) {
	net_tcp = $('.total-tcp').val()
	var tr = $(elem).closest('tr'),
		name = $('[name="agent_name[]"]', tr).val(),
		pos = $('[name="agent_position[]"]', tr).val(),
		code = $('[name="agent_code[]"]', tr).val(),
		rate= $('[name="agent_rate[]"]', tr).val(),
		subtotal = (parseFloat(rate) / 100) * parseFloat(net_tcp);

	$('.comm-amt', tr).val(subtotal.toFixed(2));
	
}

	// create csr
	$("#action_create_csr").click(function(e) {
		e.preventDefault();
	    actionCreateCSR();


	});


	// save reservation
	$("#action_save_reservation").click(function(e) {
		e.preventDefault();
	    actionSaveRes();
	});

	// update csr
	//$("#action_update_csr").click(function(e) {
	$(document).on('click', "#action_update_csr", function(e) {
		e.preventDefault();
		updateCSR();
	});

	$(document).on('click', "#action_update_reservation", function(e) {
		e.preventDefault();
		updateRes();
	});



	/* $('#birth_date').datetimepicker({
		showClose: false,
		format : "YYYY-MM-DD"
	});

	$('#hire_date').datetimepicker({
		showClose: false,
		format : "YYYY-MM-DD"
	});
	
	$('#dos').datetimepicker({
		showClose: false,
		format: "YYYY-MM-DD"
	});

	$('#reserve_date').datetimepicker({
		showClose: false,
		format: "YYYY-MM-DD"
	}); */
	
	
	$(document).on('blur', ".date-of-sale", function(e) {

		e.preventDefault();
  		var dos = $('.date-of-sale').val();
		
		dos = new Date(dos);

		dos.setMonth(dos.getMonth()+1);
	
		var dos =  dos.toISOString().slice(0, 10);


		$('#first_dp_date').val(dos);
		$('#full_down_date').val(dos);
		//$('#start_date').val(dos);

		compute_no_payment();

		
	});

	$(document).on('keyup', ".flrelev-price", function(e) {
		e.preventDefault();
		compute_addcost();
		

	});	

	$(document).on('keyup', ".aircon-outlets", function(e) {
		e.preventDefault();
		compute_ac();
		

	});	

	$(document).on('keyup', ".aircon-outlet-price", function(e) {
		e.preventDefault();
		compute_ac();
	

	});	

	$(document).on('keyup', ".ac-grill", function(e) {
		e.preventDefault();
		compute_grill();
		
	});	


	$(document).on('keyup', ".ac-grill-price", function(e) {
		e.preventDefault();
		compute_grill();
		

	});	

	$(document).on('keyup', ".conv-outlet", function(e) {
		e.preventDefault();
		compute_conv();
		

	});	

	$(document).on('keyup', ".conv-outlet-price", function(e) {
		e.preventDefault();
		compute_conv();
	

	});	

	
	$(document).on('keyup', ".service-area", function(e) {
		e.preventDefault();
		compute_service();


	});	

	$(document).on('keyup', ".service-area-price", function(e) {
		e.preventDefault();
		compute_service();
	

	});	

	$(document).on('keyup', ".others", function(e) {
		e.preventDefault();
		compute_others();
		

	});	
	
	$(document).on('keyup', ".others-price", function(e) {
		e.preventDefault();
		compute_others();
	

	});	


	$(document).on('blur', ".first-dp-date", function(e) {
		e.preventDefault();
		auto_terms();

	});	

	$(document).on('blur', ".full-down-date", function(e) {
		e.preventDefault();
		auto_terms();

	});	


	$(document).on('keyup', ".lot-disc", function(e) {
		e.preventDefault();
		compute_lot();
		
		
	});

    $(document).on('change', ".l-lcp", function(e) {
		e.preventDefault();
		compute_net_tcp();



	});

	$(document).on('keyup', ".tcp-disc", function(e) {
		e.preventDefault();
		compute_net_tcp();
		//compute_house();
		
	});


	$(document).on('keyup', ".house-disc", function(e) {
		e.preventDefault();
		//compute_lot();
		compute_house();
		
	});

	$(document).on('keyup', ".vat-percent", function(e) {
		e.preventDefault();
		compute_net_tcp();
		//compute_house();
		
	});

	$(document).on('keyup', ".down-percent",  function(e) {
		e.preventDefault();
		compute_net_dp();
		compute_no_payment();
		compute_rate();
		compute_monthly_payments();
		
		
	});

	
	// $(document).on('keyup', ".terms-count",  function(e) {
	// 	e.preventDefault();
	// 	compute_net_dp();
	// 	compute_no_payment();
	// 	compute_rate();
	// 	compute_monthly_payments();
		
		
	// });

	$(document).on('keyup', ".reservation-fee", function(e) {
		e.preventDefault();
		compute_net_dp();
		compute_no_payment();
		compute_monthly_payments();
		
		//compute_house();
		
	});

	
	$(document).on('keyup', ".interest-rate", function(e) {
		e.preventDefault();
		compute_net_dp();
		compute_no_payment();
		compute_monthly_payments();
		
		//compute_house();
		
	});

	$(document).on('keyup', ".no-payment", function(e) {
		e.preventDefault();
		compute_no_payment();
		//compute_house();
		
	});


	$(document).on('keyup', ".terms-count", function(e) {
		e.preventDefault();
		compute_rate();
		compute_monthly_payments();
		

	});

	$(document).on('change', ".payment-type1", function(e) {
		e.preventDefault();
		payment_type1_changed();


	});



	$(document).on('change', ".payment-type2", function(e) {
		e.preventDefault();
		payment_type2_changed();
	});
	$(document).on('keyup', ".prod-lot-price", function(e) {
		e.preventDefault();
		compute_lcp();
	});

	$(document).on('change', ".acc-interest", function(e) {
		e.preventDefault();
		compute_adj_prin();
	});

  $(document).on('change', ".acc-surcharge2", function(e) {
		e.preventDefault();
		compute_adj_prin();
	});

	function calculateAgeAndSetError(birthdateInput, ageInput) {
		var enteredDate = birthdateInput.val();
		
		var datePattern = /^\d{4}-\d{2}-\d{2}$/;
		if (datePattern.test(enteredDate)) {
			birthdateInput.parent().removeClass("has-error");

			var parts = enteredDate.split("-");
			var year = parseInt(parts[0], 10);
			var month = parseInt(parts[1], 10);
			var day = parseInt(parts[2], 10);

			var currentDate = new Date();
			var age = currentDate.getFullYear() - year;
	
			if (
				currentDate.getMonth() + 1 < month ||
				(currentDate.getMonth() + 1 === month && currentDate.getDate() < day)
			) {
				age--;
			}

			ageInput.val(age);
		} else {
			birthdateInput.parent().addClass("has-error");
			birthdateInput.val("");
			ageInput.val("");
			alert_toast("Please enter a valid date in YYYY-MM-DD format.", 'error');
		}
	}
	

	$(".buyer-bday").on("change", function() {
		calculateAgeAndSetError($(this), $("#age"));
	});

	
  function compute_adj_prin(){
    var balance = $('.amt-to-be-financed').val();
    var acc_sur = $('.acc-surcharge2').val();
    var acc_int = $('.acc-interest').val();

    total =  parseFloat(balance) +  parseFloat(acc_sur) +  parseFloat(acc_int);

    $("#adj_prin_bal").val(total);
	compute_monthly_payments();
  
  }

	function payment_type1_changed(){

			var l_payment_type1 = $('.payment-type1').val();
			$('#payment_type2').removeAttr('disabled');
			$('#loan_text').text("Amount to be financed :");
			$('#first_dp').show();
			$('#first_dp_date').show();
			$('#down_frm').show();
			$('#monthly_frm').show();
			$('#no_pay_text').show();
			$('#no_payment').show();
			$('#mo_down_text').show();
			$('#monthly_down').show();
			$('#down_text').show();
			$('#factor_text').show();
			$('#fixed_factor').show();
			$('#start_text').text("Start Date :");	
			$('#ma_text').text("Monthly Amortization ");
			//alert(l_payment_type1);
			if (l_payment_type1 == "Spot Cash"){
				$('#payment_type2').prop('disabled', true);
				/* $('#payment_type2').val('Spot Cash'); */
				$('#down_frm').hide();
				$('#monthly_frm').hide();
				$('#down_text').hide();
				$('#p1').hide();
				document.getElementById('p2').style.width='100%';
				document.getElementById('p2').style.marginLeft='0%';

				$("#down_percent").val(0);
				$("#net_dp").val(0);
				$("#terms").val(0);
				$("#first_dp_date").val("");
				$("#full_down_date").val("");
				$("#interest_rate").val(0);
				$("#fixed_factor").val(0);
				$("#monthly_amortization").val(0);
				

				$('#loan_text').text("Amount :");
				$('#start_text').text("Pay Date :");	
				$('#ma_text').text("Spot Cash Payment ");


			} else if(l_payment_type1 == "Full DownPayment"){
				
				$('#no_pay_text').hide();
				$('#no_payment').val(0);
				$('#no_payment').hide();
				$('#mo_down_text').hide();
				$('#first_dp').hide();
				$('#first_dp_date').hide();
				$('#monthly_down').val(0);
				$('#monthly_down').hide();
				$('#p1').show();
				document.getElementById('p2').style.width='49%';
				document.getElementById('p2').style.marginLeft='2%';
				compute_net_dp();
				compute_no_payment();
				compute_rate();
				compute_monthly_payments();
				
				
			} else if(l_payment_type1 == "No DownPayment"){
				$('#down_text').hide();
				l_a = $('.net-tcp').val();
				l_b = $('.reservation-fee').val();
				$('#down_frm').hide();
				/* $('#no_payment').val('1'); */
				$('#mo_down_text').hide();
				l_sdate = $('.first-dp-date').val();
				$('#p1').hide();
				document.getElementById('p2').style.width='100%';
				document.getElementById('p2').style.marginLeft='0%';
				
				$('#start_date').val(l_sdate);

				var l_c = parseFloat(l_a) - parseFloat(l_b);
				$('#amt_to_be_financed').val(l_c.toFixed(2))
				$("#down_percent").val(0);
				$("#net_dp").val(0);
				$("#no_payment").val(0);
				$("#monthly_down").val(0);
				$("#first_dp_date").val("");
				$("#full_down_date").val("");
				compute_net_dp();
				compute_no_payment();
				compute_rate();
				compute_monthly_payments();
				
			}else{
				$('#p1').show();
				document.getElementById('p2').style.width='49%';
				document.getElementById('p2').style.marginLeft='2%';
				compute_net_dp();
				compute_no_payment();
				compute_rate();
				compute_monthly_payments();
			}
	}

	function payment_type2_changed(){
		var l_payment_type2 = $('.payment-type2').val();
		$('#loan_text').text("Amount to be financed :");
		$('#interest_rate').show();
		$('#fixed_factor').show();
		$('#monthly_frm').show();
		$('#rate_text').show()
		$('#factor_text').show()
		$('#ma_text').text("Monthly Amortization ");
		if (l_payment_type2 == "Deferred Cash Payment"){
			$('#ma_text').text("Deferred Cash Payment ");
			$('#loan_text').text("Deferred Amount:");
			$("#interest_rate").val(0);
			$("#fixed_factor").val(0);
			$('#rate_text').hide()
			$('#factor_text').hide()
			$('#interest_rate').hide();
			$('#fixed_factor').hide();
		}

		compute_monthly_payments();

	}
	function compute_lcp(){
		
		var l_sqm = $('.prod-lot-area').val();
		var l_area = $('.prod-lot-price').val();
		var l_total = parseFloat(l_sqm) * parseFloat(l_area);
		$('#prod_lcp').val(l_total.toFixed(2));

	}

	function compute_ac(){

		var	q = $('.aircon-outlets').val();
		var p = $('.aircon-outlet-price').val();
		var l_total_amt = q * p;
		$('#ac_outlet_subtotal').val(l_total_amt.toFixed(2));
		compute_addcost();
	}

	function compute_grill(){

		var	q = $('.ac-grill').val();
		var p = $('.ac-grill-price').val();
		var l_total_amt = q * p;
		$('#ac_grill_subtotal').val(l_total_amt.toFixed(2));
		compute_addcost();
	}

	function compute_conv(){

		var	q = $('.conv-outlet').val();
		var p = $('.conv-outlet-price').val();
		var l_total_amt = q * p;
		$('#conv_outlet_subtotal').val(l_total_amt.toFixed(2));
		compute_addcost();
	}

	function compute_service(){

		var	q = $('.service-area').val();
		var p = $('.service-area-price').val();
		var l_total_amt = q * p;
		$('#service_subtotal').val(l_total_amt.toFixed(2));
		compute_addcost();
	}

	function compute_others(){

		var	q = $('.others').val();
		var p = $('.others-price').val();
		var l_total_amt = q * p;
		
		$('#others_subtotal').val(l_total_amt.toFixed(2));
		compute_addcost();
	}

	function compute_addcost(){
		var	f = $('.flrelev-price').val();
		var	a = $('.ac-outlet-subtotal').val();
		var	b = $('.ac-grill-subtotal').val();
		var	c = $('.conv-outlet-subtotal').val();
		var	d = $('.service-subtotal').val();
		var e = $('.others-subtotal').val();
		/* let total = a + b; */
		var total =  parseFloat(f) + parseFloat(a) + parseFloat(b) + parseFloat(c) + parseFloat(d) + parseFloat(e);
		$('#add_cost_total').val(total.toFixed(2));
	}

	function compute_lot(){

		var l_sqm = $('.price-sqm').val();
		var l_area = $('.lot-area').val();

		var l_discount_percentage = $('.lot-disc').val();
		var l_total_amt = l_sqm*l_area;
		$('#amount').val(l_total_amt.toFixed(2));
	 
		var l_discount_amt = l_total_amt*(l_discount_percentage*0.01);      
		var l_lcp = l_total_amt-l_discount_amt;

		$('#lot_disc_amt').val(l_discount_amt.toFixed(2));
		$('#lcp').val(l_lcp.toFixed(2));
		
		compute_net_tcp();

	}

	function compute_house(){

		var l_h_sqm = $('.h-price-sqm').val();
		var l_f_area = $('.floor-area').val();

		var l_h_disc_percent = $('.house-disc').val();
		var l_h_total_amt = l_h_sqm*l_f_area;
		
		let l_h_discount_amt = l_h_total_amt*(l_h_disc_percent*0.01);      
		let l_hcp = parseFloat(l_h_total_amt)-parseFloat(l_h_discount_amt);

		$('#house_disc_amt').val(l_h_discount_amt.toFixed(2));
		$('#hcp').val(l_hcp.toFixed(2));
		
		compute_lot();

	}
	
	function compute_net_tcp(){
		//var vat_percentage = document.getElementById('vat_amt').value;
		var vat_percentage = $('#vat_percent').val();
		//alert(vat_percentage);
		var tcp_l_lcp = $('.l-lcp').val();		
		var tcp_h_hcp = $('.house-hcp').val();
		var tcp_discount = $('.tcp-disc').val();
		var tcp_sum = parseFloat(tcp_l_lcp) + parseFloat(tcp_h_hcp);
	
		var tcp_disc_amt = parseFloat(tcp_sum) * (parseFloat(tcp_discount) * 0.01) ;
		$('#tcp_disc_amt').val(tcp_disc_amt.toFixed(2));
	
		var tcp_total = tcp_sum - tcp_disc_amt;

		$('#total_tcp').val(tcp_total.toFixed(2));
		var vat_tcp = $('#total_tcp').val();
	
		l_vat_amt = $('.vat-percent').val();
		var vat_net_tcp = ( 1 + (0.01*vat_percentage))*vat_tcp;
		var vat_total = vat_net_tcp - vat_tcp;

		//document.getElementById('vat_amt_computed').value = vat_total.toFixed(2);
		$('#vat_amt_computed').val(vat_total.toFixed(2));
		//$('#vat_amt').val(l_vat_amt.toFixed(2));
		l_total_ntcp = parseFloat(vat_net_tcp);
		
		//l_total_ntcp = parseFloat(tcp_total) + parseFloat(l_vat_amt);
		$('#net_tcp').val(l_total_ntcp.toFixed(2));
		$('#net_tcp1').val(l_total_ntcp.toFixed(2));
		$('#amt_to_be_financed').val(l_total_ntcp.toFixed(2));

		compute_net_dp();
	
	}
	function compute_net_dp(){
		var l_net_tcp = $('.net-tcp-1').val();
		var l_down_per = $('.down-percent').val();
		var l_reservation = $('.reservation-fee').val();
		//alert(l_down_per);		
		if (l_down_per == 0){
			
		/* 	let l_net_dp = 0.00;
			let no_payment = 0.00
			$('#net_dp').val(l_net_dp.toFixed(2));
			$('#no_payment').val(l_no_payment); */
			var l_amt_2b_finance = parseFloat(l_net_tcp) - parseFloat(l_reservation);
			$('#amt_to_be_financed').val(l_amt_2b_finance.toFixed(2));

		}else{
			//alert(l_down_per);
			var l_down = parseFloat(l_down_per) * .01;
			
			var l_net_dp = (parseFloat(l_net_tcp) * parseFloat(l_down)) - parseFloat(l_reservation);
			$('#net_dp').val(l_net_dp.toFixed(2));
			//alert(l_net_dp);
			if (l_net_dp < 0)
				{
				l_net_dp = 0;
				$('#net_dp').val(l_net_dp.toFixed(2));
				var l_net_dp = $('.net-dp').val();
				}
			var l_amt_2b_finance = parseFloat(l_net_tcp) - parseFloat(l_net_dp) - parseFloat(l_reservation);
			$('#amt_to_be_financed').val(l_amt_2b_finance.toFixed(2));

		}
		
	}

	function compute_no_payment(){
		var l_no_pay = $('.no-payment').val();
		var l_net_dp = $('.net-dp').val();

		var l_mo_down = parseFloat(l_net_dp) / parseFloat(l_no_pay);
		l_mo_down = isFinite(l_mo_down) ? l_mo_down : 0.0;
		//alert(l_mo_down);
		$("#monthly_down").val(l_mo_down.toFixed(2));		
		auto_terms();
	}

	function auto_terms(){
		var l_no_pay = $('.no-payment').val();
		var l_start_date = $('.first-dp-date').val() || new Date().toISOString().split('T')[0];;
				
		fd_dte = new Date(l_start_date);
		if(l_no_pay == 0 || l_no_pay == 1){
			l_no_pay = 1
		}
	
		fd_dte.setMonth(fd_dte.getMonth()+ parseFloat(l_no_pay - 1));
		
		var fd_dte = fd_dte.toISOString().slice(0, 10);
 
		$('#full_down_date').val(fd_dte);


		var l_fd_dte = $('.full-down-date').val();

		start_dte = new Date(l_fd_dte);

		start_dte.setMonth(start_dte.getMonth()+ 1);
		
		var start_dte = start_dte.toISOString().slice(0, 10);

		$('#start_date').val(start_dte);
		//alert(end_dte);
		

	}

	function compute_rate(){
		var l_down = $('.down-percent').val();
		var l_terms = parseInt($('.terms-count').val());
		if (l_down == 20){
			if(l_terms>0 && l_terms<=60){
				l_rate = 15.0
				$('#interest_rate').val(l_rate);

			}else if(l_terms >60 && l_terms <= 120 ){
				l_rate = 17.0
				$('#interest_rate').val(l_rate);
			}
			else if(l_terms > 120 ){
				l_rate = 19.0
				$('#interest_rate').val(l_rate);
			}
		}else if(l_down == 30){
			if(l_terms>0 && l_terms<=60){
				l_rate = 14.0
				$('#interest_rate').val(l_rate);

			}else if(l_terms >60 && l_terms <= 120 ){
				l_rate = 16.0
				$('#interest_rate').val(l_rate);
			}
			else if(l_terms > 120 ){
				l_rate = 18.0
				$('#interest_rate').val(l_rate);
			}

		}else{
			$('#interest_rate').val(0.0);
		}

	}
	function compute_monthly_payments() {
		var l_rate = $('.interest-rate').val();
		var l_x = '1200';
		// if (l_rate == 0){
		// 	l_rate_value = 0;
		// }else{
		// 	var l_rate_value = parseFloat(l_rate)/ parseFloat(l_x);
		// }
		if (l_rate == 0) {
			var l_rate_value = 0;
		} else {
			var l_rate_value = parseFloat(l_rate) / parseFloat(l_x);
			if (isNaN(l_rate_value)) {
			l_rate_value = 0;
			}
		}
		
		var l_payment_type1 = $('.payment-type1').val();
		var l_payment_type2 = $('.payment-type2').val();
		
		if (l_payment_type1 == "Spot Cash") {
			var l_net_tcp = $('.net-tcp').val();
			var l_rsv_fee = $('.reservation-fee').val();
			var l_amt_2b_finance = parseFloat(l_net_tcp) - parseFloat(l_rsv_fee);
		
			$("#fixed_factor").val(0);
			$("#monthly_amortization").val(0);
			$('#amt_to_be_financed').val(l_amt_2b_finance.toFixed(2));
		} else if (l_payment_type2 == "Deferred Cash Payment") {
			var l_loan_amt = $('.amt-to-be-financed').val();
			var l_terms = $('.terms-count').val();
			var l_amt_2b_finance = parseFloat(l_loan_amt) / parseFloat(l_terms);
			l_amt_2b_finance = isFinite(l_amt_2b_finance) ? l_amt_2b_finance : 0;
		
			$("#fixed_factor").val(isNaN(l_rate_value) ? 0 : l_rate_value.toFixed(8));
			$("#monthly_amortization").val(l_amt_2b_finance.toFixed(2));
		} else if (l_payment_type2 == "Monthly Amortization") {
			compute_no_payment();
			compute_net_dp();
			var l_terms = $('.terms-count').val();
			var l_factor = parseFloat(l_rate_value) / (1 - Math.pow(1 + parseFloat(l_rate_value), -parseFloat(l_terms)));
		
			if (l_factor == 0) {
			return;
			}
		
			var l_loan_amt = $('.amt-to-be-financed').val();
			var monthly_ma = parseFloat(l_loan_amt) * parseFloat(l_factor);
			monthly_ma = isFinite(monthly_ma) ? monthly_ma : 0;
		
			$("#fixed_factor").val(isNaN(l_factor) || !isFinite(l_factor) ? 0 : l_factor.toFixed(8));

			//$("#fixed_factor").val(isNaN(l_factor) ? 0 : l_factor.toFixed(8));
			$("#monthly_amortization").val(monthly_ma.toFixed(2));
		}
	}
	  
	
   	function validateForm() {
	    // error handling
	    var errorCounter = 0;

	    $(".required").each(function(i, obj) {

	        if($(this).val() === ''){
	            $(this).parent().addClass("has-error");
	            errorCounter++;
	        } else{ 
	            $(this).parent().removeClass("has-error"); 
	        }


	    });

	    return errorCounter;
	}
   

	function actionAddComment() {
		
		var errorCounter = validateForm();

		if (errorCounter > 0) {
		    $("#response").removeClass("alert-success").addClass("alert-warning").fadeIn();
		    $("#response .message").html("<strong>Error</strong>: It appear's you have forgotten to complete something!");
		    $("html, body").animate({ scrollTop: $('#response').offset().top }, 1000);
		} else {

			var $btn = $("#action_add_comment").button("loading");
			
			$(".required").parent().removeClass("has-error");

			$.ajax({

				url: 'response.php',
				type: 'POST',
				data: $("#add_comment").serialize(),
				dataType: 'json',
				success: function(data){
					$("#response .message").html("<strong>" + data.status + "</strong>: " + data.message);
					$("#response").removeClass("alert-warning").addClass("alert-success").fadeIn();
					$("html, body").animate({ scrollTop: $('#response').offset().top }, 1000);
					$btn.button("reset");
					setInterval('location.reload()', 2000);
					
				},
				error: function(data){
					$("#response .message").html("<strong>" + data.status + "</strong>: " + data.message);
					$("#response").removeClass("alert-success").addClass("alert-warning").fadeIn();
					$("html, body").animate({ scrollTop: $('#response').offset().top }, 1000);
					$btn.button("reset");
					setInterval('location.reload()', 2000);
				} 

			});
		}

	

	}


	function actionAddReply() {
		
		var errorCounter = validateForm();

		if (errorCounter > 0) {
		    $("#response").removeClass("alert-success").addClass("alert-warning").fadeIn();
		    $("#response .message").html("<strong>Error</strong>: It appear's you have forgotten to complete something!");
		    $("html, body").animate({ scrollTop: $('#response').offset().top }, 1000);
		} else {

			var $btn = $("#action_add_reply").button("loading");
			
			$(".required").parent().removeClass("has-error");

			$.ajax({

				url: 'response.php',
				type: 'POST',
				data: $("#add_reply").serialize(),
				dataType: 'json',
				success: function(data){
					$("#response .message").html("<strong>" + data.status + "</strong>: " + data.message);
					$("#response").removeClass("alert-warning").addClass("alert-success").fadeIn();
					$("html, body").animate({ scrollTop: $('#response').offset().top }, 1000);
					$btn.button("reset");
					setInterval('location.reload()', 2000);
					
				},
				error: function(data){
					$("#response .message").html("<strong>" + data.status + "</strong>: " + data.message);
					$("#response").removeClass("alert-success").addClass("alert-warning").fadeIn();
					$("html, body").animate({ scrollTop: $('#response').offset().top }, 1000);
					$btn.button("reset");
					setInterval('location.reload()', 2000);
				} 

			});
		}

	

	}

	$(document).on('click', "#compute-pmt", function(e) {
		e.preventDefault();
		alert("done");
		var interest_rate = $('.int-rate').val();
		var terms = $('.term-rate').val();
		var l_amt_financed = $('.loan-amt').val();
		$i = (interest_rate /100)/12;
		$n = terms;
		$fv  = 0;
		$pv =  l_amt_financed;
		$type = 0;

		$PMT = (($pv - $fv) * $i )/ (1 - pow((1 + $i), (-$n))); 
		/* pmt($interest_rate,$terms,$pv,$fv,$type); */


		alert($PMT);

	});

});


function pmt(rate_per_period, number_of_payments, present_value, future_value, type){
	future_value = typeof future_value !== 'undefined' ? future_value : 0;
	type = typeof type !== 'undefined' ? type : 0;

	if(rate_per_period != 0.0){
		// Interest rate exists
		var q = Math.pow(1 + rate_per_period, number_of_payments);
		return -(rate_per_period * (future_value + (q * present_value))) / ((-1 + q) * (1 + rate_per_period * (type)));

	} else if(number_of_payments != 0.0){
		// No interest rate, but number of payments exists
		return -(future_value + present_value) / number_of_payments;
	}

	return 0;

	
}
function pmt(rate_per_period, number_of_payments, present_value, future_value, type){
	var loanAmount = Number(this.inputValArray[0]),
	interest = Number(this.inputValArray[1]),
	numOfMonths = Number(this.inputValArray[2]),
	rate = interest / 1200,
	income_req = 0,
	monthlyPayment = 0;

	// Use toFixed method to round rate. The toFixed method converts a number to a string,
	// so we use Number() to convert it back.
	rate = Number(rate.toFixed(7));

	// Substitute the proper variables into our equation to get the monthlyPayment value.
	monthlyPayment = (rate + rate / (Math.pow(rate + 1, numOfMonths) - 1)) * loanAmount;

	income_req =  monthlyPayment / 0.4;
	// Round the monthlyPayment to the second decimal place. We can leave it as a string.
	monthlyPayment = monthlyPayment.toFixed(2);

	income_req = income_req.toFixed(2);
}
//** //////////////////////////////////////////////////////////////////*/

function redirectToClientList(){
	window.location.href = "?page=customer-list";
}

function redirectToProjectList(){
	window.location.href = "?page=project-list";
}

function redirectToHouseList(){
	window.location.href = "?page=house-list";
}

function redirectToLotList(){
	window.location.href = "?page=lot-list";
}

function redirectToCSRList(){
	window.location.href = "?page=csr-create";
}


function redirectToAgentList(){
	window.location.href = "?page=agent-list";
}

function redirectToUserList(){
	window.location.href = "?page=user-list";
}

function redirectToRaList(){
	window.location.href = "?page=reservation-list";
}





