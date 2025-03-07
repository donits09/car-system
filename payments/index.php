<?php
session_start();

require_once('../inc/check_session.php');
check_user_group(1);

include('../config.php');
include('../inc/navbar.php');
include('../inc/header.php');
$current_date = date('Y-m-d');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/car_reports.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">
    <style>
        .form-control.tbl-input{
            background-color: transparent;
            border: none;
            text-align: center;
            cursor: default;
        }
        *{
            font-size:12px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
    </style>
</head>

<body>

<div class="container mt-5" style="max-width: 1600px; ">
    <div class="sub-container">
            <a id="create_or" name="create_or" class="btn btn-flat btn-primary create-or" href="javascript:void(0)">
                <span class="fa fa-add"></span> Create New Payment
            </a>
            
        </div>
    <div class="card mt-3" >
       
            <div class="main_header" style="padding:10px;">
                <div id="header">ASIAN LAND STRATEGIES CORPORATION</div>
                <div id="subheader">Daily Cash & Deposit Record</div>

            </div>
            <hr>
           
            <div class="table-container">
                <table class="table table-bordered table-striped" id="data-table">
                    <thead>
                        <tr>
                            <th>NO </th>
                            <th>ACCOUNT NO</th>
                            <th>SALES INVOICE NO</th>
                            <th>PARTICULAR</th>
                            <th>LAST NAME</th>
                            <th>FIRST NAME</th>
                            <th>LOCATION</th>
                            <th>CASH / ONLINE</th>
                            <th>CHECK</th>
                            <th>BANK</th>
                            <th>TOTAL</th>
                            <th>TRANSACTION DATE</th>
                            <th>PAY DATE</th>
                            <th>ENCODER</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="summary-type-body">
                        <?php
                        $car_list_query = "SELECT DISTINCT DATE(c_tran_date) AS c_tran_date_without_time FROM t_car_payment ORDER BY c_tran_date_without_time DESC;";
                        $stmt = odbc_prepare($conn, $car_list_query);

                        if ($stmt && odbc_execute($stmt)) {
                            while ($row_date = odbc_fetch_array($stmt)):
                                $trandate = $row_date['c_tran_date_without_time'];

                               
                        ?>
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>

                                    <td> </td>
                                   
                                    <td align="center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item lock_data" href="javascript:void(0)" >
                                                        <span class="fa fa-lock text-primary"></span> Lock
                                                    </a>
                                                    <a class="dropdown-item unlock_data" href="javascript:void(0)" >
                                                        <span class="fa fa-lock-open text-primary"></span> Unlock
                                                    </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            endwhile;
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No data available or error executing query.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

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
                <button type="button" class="btn btn-primary" id="confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <table style="width:100%;">
          <tr>
            <td>
              <button type="button" class="btn btn-flat btn-default bg-maroon" id='submit' onclick="$('#uni_modal form').submit()" style="width:100%; margin-right:5px;font-size:14px;"><i class="fa fa-save" aria-hidden="true"></i>&nbsp;&nbsp;Save</button>
            </td>
            <td>
              <button type="button" class="btn btn-flat btn-default" data-dismiss="modal" style="width:100%; margin-left:5px;font-size:14px;"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Cancel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
            </td>
          </tr>
        </table>
      </div>
      </div>
    </div>
  </div>

<script src="../dist/js/table.js"></script>

<script>
 $(document).ready(function(){
		$('.table').dataTable();
	})
  
    $('.create-or').click(function(){
      
        uni_modal("<i class='fa fa-edit'></i>&nbsp;&nbsp;Payment Window",'../payments/create.php',"")
    })

    $('.edit-lot').click(function(){
        uni_modal("<i class='fa fa-lot'></i>&nbsp;&nbsp;Lot Inventory",'inventory/manage_lot.php?id='+$(this).attr('data-lot-id'),"mid-large")
    })

    $('.view-lot').click(function(){
        uni_modal("<i class='fa fa-lot'></i>&nbsp;&nbsp;Lot Inventory",'inventory/view_lot.php?id='+$(this).attr('data-lot-id'),"mid-large")
    })

    $('.delete-lot').click(function(){
        _conf("Are you sure you want to delete this lot information?","delete_lot",[$(this).attr('data-lot-id')])
    }) 


$(document).ready(function(){
    window.uni_modal = function($title = '' , $url='',$size=""){
        start_loader()
        $.ajax({
            url:$url,
            error:err=>{
                console.log()
                alert("An error occured")
            },
            success:function(resp){
                if(resp){
                    $('#uni_modal .modal-title').html($title)
                    $('#uni_modal .modal-body').html(resp)
                  
                    $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-lg modal-dialog-centered")
                    
                    $('#uni_modal').modal({
                      show:true,
                      backdrop:'static',
                      keyboard:false,
                      focus:true
                    })
                    end_loader()
                }
            }
        })
    }


    window._conf = function($msg='',$func='',$params = []){
       $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
       $('#confirm_modal .modal-body').html($msg)
       $('#confirm_modal').modal('show')
    }

});


</script>

<?php include('../inc/footer.php'); ?>
