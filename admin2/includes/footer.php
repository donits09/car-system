<script>
    $(document).ready(function(){
      window.uni_modal = function($title = '', $url = '', $size = ""){
        $.ajax({
          url: $url,
          error: function(err){
            console.log(err);
            alert("An error occurred");
          },
          success: function(resp){
            if(resp){
              $('#uni_modal .modal-title').html($title);
              $('#uni_modal .modal-body').html(resp);
              if($size != ''){
                $('#uni_modal .modal-dialog').addClass($size + ' modal-dialog-centered');
              } else {
                $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-lg modal-dialog-centered");
              }
              $('#uni_modal').modal('show');
              
            }
          }
        });
      };

      window._conf = function($msg = '', $func = '', $params = []){
        $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")");
        $('#confirm_modal .modal-body').html($msg);
        $('#confirm_modal').modal('show');
      };

      window.uni_modal_right = function($title = '' , $url='',$size=""){
        start_loader()
        $.ajax({
            url:$url,
            error:err=>{
                console.log()
                alert("An error occured")
            },
            success:function(resp){
                if(resp){
                    $('#uni_modal_right .modal-title').html($title)
                    $('#uni_modal_right .modal-body').html(resp)
                    if($size != ''){
                        $('#uni_modal_right .modal-dialog').addClass($size+'  modal-dialog-centered')
                    }else{
                        $('#uni_modal_right .modal-dialog').removeAttr("class").addClass("modal-dialog modal-lg modal-dialog-centered")
                    }
                    $('#uni_modal_right').modal('show');
                }
            }
        })
    }

   
    });
  </script>

