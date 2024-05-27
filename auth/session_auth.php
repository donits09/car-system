<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$link = isset($_GET['link']) ? $_GET['link'] : '';
// $department = $_settings->userdata('department');
// $user_type = $_settings->userdata('user_type');
// if(isset($_SESSION['userdata'])){
//     if($user_type =='IT Admin'){
//         if(strpos($link, 'login.php') === false){
//             redirect('admin/index.php');
//         }
//     } 
//     else{
//         if($_SESSION['userdata']['position'] =='Agent'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('agent_user/index.php');
//             }
//         } 
//         if($_SESSION['userdata']['position'] =='PURCHASING ASSISTANT'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/po/index.php');
//             }
//         } 
//         if($_SESSION['userdata']['position'] == 'FINANCE MANAGER/SR EA TO THE BOARD/INTERNAL AUDITOR'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/finance_manager/index.php');
//             }
//         } 
//         if($_SESSION['userdata']['position'] == 'CHIEF FINANCE OFFICER'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('mancomm/cfo/index.php');
//             }
//         } 
//         if($_SESSION['userdata']['position'] == 'SALES AND MARKETING OPERATIONS SUPERVISOR'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/sales_manager/index.php');
//             }
//         }  
//         if($_SESSION['userdata']['position'] == 'CREDIT ASSESSMENT AND LOANS SUPERVISOR'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/ca/index.php');
//             }
//         }     
//         if($_SESSION['userdata']['section'] == 'Treasury'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/cashier/index.php');
//             }
//         }
//         if($_SESSION['userdata']['position'] == 'CHIEF OF OPERATION'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('mancomm/coo/index.php');
//             }
//         }  
//         if($_SESSION['userdata']['position'] == 'ASSISTANT ACCOUNTING MANAGER'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/accounting/index.php');
//             }
//         }  
//         if($_SESSION['userdata']['position'] =='PURCHASING OFFICER'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/po_m/index.php');
//             }
//         }  
//         if($_SESSION['userdata']['position'] =='EA TO THE MANCOM' || $_SESSION['userdata']['position'] == 'EXECUTIVE ASSISTANT TO THE COO'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/ea/index.php');
//             }
//         }  
//         if($_SESSION['userdata']['position'] == 'ACCOUNTING ASSISTANT' && $department == 'Accounting'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/accounting_emp/index.php');
//             }
//         }  
//         if($_SESSION['userdata']['department'] == 'Billing'){
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/billing/index.php');
//             }
//         }  
//         else{
//             if(strpos($link, 'login.php') === false){
//                 redirect('employee/reg_emp/index.php');
//             }
//         }
//     }

// }
// ?>

