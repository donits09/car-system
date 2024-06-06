<?php
require_once('../../config.php');

if(isset($_GET['id'])){

    $sql = "SELECT * FROM t_agents WHERE c_code = ?";
    $acc = $_GET['id'];

    $qry = odbc_prepare($conn, $sql);
    if (!$qry) {
        die("Preparation of the statement failed: " . odbc_errormsg($conn));
    }
    
    if (!odbc_execute($qry, array($acc))) {
        die("Execution of the statement failed: " . odbc_errormsg($conn));
    }
    while ($res = odbc_fetch_array($qry)) {
        $c_code = $res['c_code'];
        $c_last_name = $res['c_last_name'];
        $c_first_name = $res['c_first_name'];
        $c_middle_initial = $res['c_middle_initial'];
        $c_nick_name = $res['c_nick_name'];
        $c_sex = $res['c_sex'];
        $c_birthdate = $res['c_birthdate'];
        $c_birth_place = $res['c_birth_place'];
        $c_address_ln1 = $res['c_address_ln1'];
        $c_address_ln2 = $res['c_address_ln2'];
        $c_tel_no = $res['c_tel_no'];
        $c_civil_status = $res['c_civil_status'];
        $c_sss_no = $res['c_sss_no'];
        $c_tin = $res['c_tin'];
        $c_status = $res['c_status'];
        $c_recruited_by = $res['c_recruited_by'];
        $c_hire_date = $res['c_hire_date'];
        $c_position = $res['c_position'];
        $c_network = $res['c_network'];
        $c_division = $res['c_division'];
        

        
    }
}

?>
<h2>Agent Information Form</h2>

<form action="" >

    <!-- Primary Details Section -->
    <div class="form-section">
        <h3>Primary Details</h3>
        <label for="c_code">Code:</label>
        <input type="text" id="c_code" name="c_code" value="<?php echo isset($c_code) ? $c_code : ''; ?>"><br><br>

        <label for="c_last_name">Last Name:</label>
        <input type="text" id="c_last_name" name="c_last_name" value="<?php echo isset($c_last_name) ? $c_last_name : ''; ?>"><br><br>

        <label for="c_first_name">First Name:</label>
        <input type="text" id="c_first_name" name="c_first_name" value="<?php echo isset($c_first_name) ? $c_first_name : ''; ?>"><br><br>

        <label for="c_middle_initial">Middle Initial:</label>
        <input type="text" id="c_middle_initial" name="c_middle_initial" value="<?php echo isset($c_middle_initial) ? $c_middle_initial : ''; ?>"><br><br>

        <label for="c_nick_name">Nick Name:</label>
        <input type="text" id="c_nick_name" name="c_nick_name" value="<?php echo isset($c_nick_name) ? $c_nick_name : ''; ?>"><br><br>

        <label for="c_sex">Sex:</label>
        <input type="text" id="c_sex" name="c_sex" value="<?php echo isset($c_sex) ? $c_sex : ''; ?>"><br><br>

        <label for="c_birthdate">Birthdate:</label>
        <input type="date" id="c_birthdate" name="c_birthdate" value="<?php echo isset($c_birthdate) ? $c_birthdate : ''; ?>"><br><br>

        <label for="c_birth_place">Birth Place:</label>
        <input type="text" id="c_birth_place" name="c_birth_place" value="<?php echo isset($c_birth_place) ? $c_birth_place : ''; ?>"><br><br>
    </div>

    <!-- Contact Info Section -->
    <div class="form-section">
        <h3>Contact Info</h3>
        <label for="c_address_ln1">Address Line 1:</label>
        <input type="text" id="c_address_ln1" name="c_address_ln1" value="<?php echo isset($c_address_ln1) ? $c_address_ln1 : ''; ?>"><br><br>

        <label for="c_address_ln2">Address Line 2:</label>
        <input type="text" id="c_address_ln2" name="c_address_ln2" value="<?php echo isset($c_address_ln2) ? $c_address_ln2 : ''; ?>"><br><br>

        <label for="c_tel_no">Telephone Number:</label>
        <input type="text" id="c_tel_no" name="c_tel_no" value="<?php echo isset($c_tel_no) ? $c_tel_no : ''; ?>"><br><br>
    </div>

    <!-- Other Details Section -->
    <div class="form-section">
        <h3>Other Details</h3>
        <label for="c_civil_status">Civil Status:</label>
        <input type="text" id="c_civil_status" name="c_civil_status" value="<?php echo isset($c_civil_status) ? $c_civil_status : ''; ?>"><br><br>

        <label for="c_sss_no">SSS Number:</label>
        <input type="text" id="c_sss_no" name="c_sss_no" value="<?php echo isset($c_sss_no) ? $c_sss_no : ''; ?>"><br><br>

        <label for="c_tin">TIN:</label>
        <input type="text" id="c_tin" name="c_tin" value="<?php echo isset($c_tin) ? $c_tin : ''; ?>"><br><br>

        <label for="c_status">Status:</label>
        <input type="text" id="c_status" name="c_status" value="<?php echo isset($c_status) ? $c_status : ''; ?>"><br><br>

        <label for="c_recruited_by">Recruited By:</label>
        <input type="text" id="c_recruited_by" name="c_recruited_by" value="<?php echo isset($c_recruited_by) ? $c_recruited_by : ''; ?>"><br><br>

        <label for="c_hire_date">Hire Date:</label>
        <input type="date" id="c_hire_date" name="c_hire_date" value="<?php echo isset($c_hire_date) ? $c_hire_date : ''; ?>"><br><br>

        <label for="c_position">Position:</label>
        <input type="text" id="c_position" name="c_position" value="<?php echo isset($c_position) ? $c_position : ''; ?>"><br><br>

        <label for="c_network">Network:</label>
        <input type="text" id="c_network" name="c_network" value="<?php echo isset($c_network) ? $c_network : ''; ?>"><br><br>

        <label for="c_division">Division:</label>
        <input type="text" id="c_division" name="c_division" value="<?php echo isset($c_division) ? $c_division : ''; ?>"><br><br>
    </div>

</form>