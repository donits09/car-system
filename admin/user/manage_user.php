<?php

if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `users` where user_code = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=stripslashes($v);
        }
    }
}else{
	$division="";
}
?>
<style>
    span.select2-selection.select2-selection--single {
        border-radius: 0;
        padding: 0.25rem 0.5rem;
        padding-top: 0.25rem;
        padding-right: 0.5rem;
        padding-bottom: 0.25rem;
        padding-left: 0.5rem;
        height: auto;
    }
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
    .lblPass{
        color:red;
        font-style:italic;
        float:right;
        margin-right:235px;
        font-weight:bold;
        font-size:10px;
        margin-top:5px;
    }
</style>
<body>
<div class="card card-outline rounded-0 card-maroon">
    <div class="card-body">
        <form action="" id="user-form">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo isset($user_id) ? $user_id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="lastname" class="control-label">Last Name:</label>
                        <input type="text" name="lastname" id="lastname" class="form-control rounded-0" value="<?php echo isset($lastname) ? $lastname :"" ?>" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="firstname" class="control-label">First Name:</label>
                        <input type="text" name="firstname" id="firstname" class="form-control rounded-0" value="<?php echo isset($firstname) ? $firstname :"" ?>" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="middle_name" class="control-label">Middle Name:</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control rounded-0" value="<?php echo isset($middle_name) ? $middle_name :"" ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="email" class="control-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control rounded-0" value="<?php echo isset($email) ? $email :"" ?>" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="phone" class="control-label">Contact #:</label>
                        <input type="text" name="phone" id="phone" class="form-control rounded-0" value="<?php echo isset($phone) ? $phone :"" ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="gender" class="control-label">Gender:</label>
                        <select name="gender" id="gender" class="form-control rounded-0" required>
                            <option value="F" <?php echo isset($gender) && $gender =="" ? "selected": "F" ?> >F</option>
                            <option value="M" <?php echo isset($gender) && $gender =="" ? "selected": "M" ?>>M</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="birthdate" class="control-label">Birthdate:</label>
                        <input type="date" name="birthdate" id="birthdate" class="form-control rounded-0" value="<?php echo isset($birthdate) ? $birthdate :"" ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="control-label">Address:</label>
                    <input type="text" name="address" id="address" class="form-control rounded-0" value="<?php echo isset($address) ? $address :"" ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="position" class="control-label">Position:</label>
                        <input type="text" name="position" id="position" class="form-control rounded-0" value="<?php echo isset($position) ? $position :"" ?>" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="division" class="control-label">Division:</label>
                        <select name="division" id="division" class="form-control rounded-0" onchange="updateType()" required>
                            <option value="" disabled selected>Select an Item</option>
                            <option value="MNGR" <?php echo ($division === "MNGR") ? "selected" : ""; ?>>Manager</option>
                            <option value="SPVR" <?php echo ($division === "SPVR") ? "selected" : ""; ?>>Supervisor</option>
                            <option value="RANK" <?php echo ($division === "RANK") ? "selected" : ""; ?>>Rank & File</option>
                        </select>
                        <input type="hidden" name="type" id="type" class="form-control rounded-0" value="<?php echo isset($type) ? $type : "" ?>" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="department">Department:</label>
                        <select name="department" id="department" class="custom-select rounded-0 select2" required>
                            <option value="" selected>Select an Item</option>
                            <?php 
                            $dept_qry = $conn->query("SELECT * FROM `department_list` order by `department` asc");
                            while($row = $dept_qry->fetch_assoc()):
                                $deptValue = $row['department']; 
                            ?>
                            <option 
                                value="<?php echo $deptValue ?>" 
                                <?php echo isset($department) && $department == $deptValue ? 'selected' : '' ?> 
                                ><?php echo $deptValue ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <input type="hidden" name="user_code" id="user_code" class="form-control rounded-0" value="<?php echo isset($user_code) ? $user_code : ""; ?>" required>
                        <label for="usercode" class="control-label">User Code:</label>
                        <input type="text" name="username" id="username" class="form-control rounded-0" value="<?php echo isset($username) ? $username : "" ?>" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="password" class="control-label">Password:</label><div class="lblPass">(If you don't want to change your password, leave it blank.)</div>
                        <?php
                            function decryptPassword($encryptedPassword, $encryptionKey) {
                                return openssl_decrypt($encryptedPassword, 'aes-256-cbc', $encryptionKey, 0, substr($encryptionKey, 0, 16));
                            }
                            if(isset($_GET['id']) && !empty($_GET['id'])) {
                                $decryptedPassword = isset($password) ? decryptPassword($password, $password) : "";
                            } else {
                            $decryptedPassword = isset($password) ? $password : "";
                            }
                            ?>
                        <input type="text" name="password" id="password" class="form-control rounded-0" value="<?php echo $decryptedPassword ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="" class="control-label">Avatar: </label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <img src="" alt="" id="cimg" class="img-fluid img-thumbnail">
                    <!-- <span><img src="" width="30" height="30"></span> -->
                </div>
            </div>
        </form>
        <div class="card-footer">
                <table style="width:100%;">
                    <tr>
                        <td>
                            <button class="btn btn-flat btn-default bg-maroon" form="user-form" style="width:100%;margin-right:5px;font-size:14px;"><i class='fa fa-save'></i>&nbsp;&nbsp;Save</button>
                        </td>
                        <td>
                            <a class="btn btn-flat btn-default" href="?page=user/list" style="width:100%;margin-left:5px;font-size:14px;"><i class='fa fa-times-circle'></i>&nbsp;&nbsp;Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
    </div>  
</div>
<script>
    <?php if(isset($_GET['id']) && !empty($_GET['id'])): ?>
        document.addEventListener("DOMContentLoaded", function() {
            var customFilePath = ".<?php echo $avatar; ?>"; 
            document.getElementById("cimg").src = customFilePath;
        });
        function displayImg(input, _this) {
            $('#cimg').attr('src', customFilePath);
        }
    <?php endif; ?>


	function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function updateType() {
        var division = document.getElementById("division").value;
        var typeInput = document.getElementById("type");

        switch (division) {
            case "MNGR":
                typeInput.value = "3";
                break;
            case "SPVR":
                typeInput.value = "4";
                break;
            default:
                typeInput.value = "5";
        }
    }
    $(function(){
        function displayAvatar() {
        var avatarUrl = "<?php echo isset($avatar) ? '.' . $avatar : "" ?>"; 
        console.log("Avatar URL:", avatarUrl); 
        if (avatarUrl.trim() !== '') {
            $('#cimg').attr('src', avatarUrl);
        }
    }

        displayAvatar();
    $('#user-form').submit(function(e){
        e.preventDefault();
        var _this = $(this)
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=save_users",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("An error occured", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    // location.reload();
                    location.replace('?page=user/list');
                } else if (resp.status == 'failed' && !!resp.msg) {
                    var el = $('<div>');
                    el.addClass("alert alert-danger err-msg").text(resp.msg);
                    _this.prepend(el);
                    el.show('slow');
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                } else {
                    alert_toast("An error occured", 'error');
                    console.log(resp);
                }
                end_loader();
            }
        });
    });
});
$(document).ready(function(){
    $('#username').on('input', function() {
        $('#user_code').val($(this).val());
    });
});
</script>