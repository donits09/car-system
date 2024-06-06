<script>
    $(document).ready(function(){
      window.uni_modal = function($title = '', $url = '', $size = ''){
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
              $('#uni_modal').modal({
                show: true,
                backdrop: 'static',
                keyboard: false,
                focus: true
              });
              console.log("SUCCESS");
            }
          }
        });
      };

      window._conf = function($msg = '', $func = '', $params = []){
        $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")");
        $('#confirm_modal .modal-body').html($msg);
        $('#confirm_modal').modal('show');
      };

   
    });
  </script>

