<?php 


    function is_leap_year($year) {
      return date('L', strtotime("$year-01-01"));
    }


    function auto_date($last_day,$date)
      {
       
        $date_arr = date_parse($date);

        $year = $date_arr['year'];
        $month = $date_arr['month'];
        $day = $date_arr['day'];
        /* $change_date = 0; */
        $conn = mysqli_connect('localhost', 'root', '', 'alscdb');
        if (!$conn) {
            die('Could not connect to database: ' . mysqli_connect_error());
        }
        $l_col = "c_retention,c_change_date,c_restructured,c_date_of_sale";
        $l_sql = sprintf("SELECT %s FROM properties WHERE md5(property_id) = '{$_GET['id']}' ", $l_col);
        $l_qry = $conn->query($l_sql);
        
        while($row=$l_qry->fetch_assoc()):
            $l_retention = $row['c_retention'];
            $change_date = $row['c_change_date'];
            $restructured = $row['c_restructured'];
            $date_of_sale = $row['c_date_of_sale'];
        endwhile;


/*    
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date)); */
        $l_leap = is_leap_year($year);

        if($date_of_sale == 31 && $last_day >= 28 && $change_date != 1){
              $last_day = 31;
        }

        
        if ($month == 1):
            if ($last_day < 28):
                $dt = new DateTime($date);
                $dt->modify('+31 days');
                $l_result = $dt->format('Y-m-d');     
            else:
                if ($l_leap):
                      $l_result = $year .'-02-29';
                else:
                      $l_result = $year .'-02-28';
                   
                endif;

            endif;
        elseif($month == 2):
            if ($last_day > 28):
                if($last_day == 29):
                    $l_result = $year .'-03-29'; 
                elseif($last_day == 30):
                    $l_result = $year .'-03-30'; 
                elseif($last_day == 31):
                    $l_result = $year .'-03-31';
                endif;
            else:

                if($l_leap):
                    $dt = new DateTime($date);
                    $dt->modify('+29 days');
                    $l_result = $dt->format('Y-m-d');
                else:
                    $dt = new DateTime($date);
                    $dt->modify('+28 days');
                    $l_result = $dt->format('Y-m-d');
                endif;
            endif;
        
        elseif($month == 3 or $month == 5 or $month == 7 or $month == 8 or $month == 10 or $month == 12):
            if($month ==7 or $month == 12):
                  if($last_day >= 30):
                      $l_date1 = $year .'-' .$month . '-'. $last_day ; 
                  else:
                      $l_date1 = $date ; 
                  endif;
                  $dt = new DateTime($l_date1);
                  $dt->modify('+31 days');
                  $l_result = $dt->format('Y-m-d');

            else:
                  if ($last_day <= 30):
                      $l_date1 = $date ;            
                  else:
                      $l_date1 = $year .'-'.$month . '-'. '30';         
                  endif;     
                  $dt = new DateTime($l_date1);
                  $dt->modify('+31 days');
                  $l_result = $dt->format('Y-m-d');
            endif;

        elseif($month == 4 or $month == 6 or $month == 9 or $month == 11):
              if ($last_day == 31):
                    $dt = new DateTime($date);
                    $dt->modify('+1 month');
                    $l_result = $dt->format('Y-m-d');
              else:
                    $dt = new DateTime($date);
                    $dt->modify('+30 days');
                    $l_result = $dt->format('Y-m-d');
              endif;
        else:
              
        endif;

        return $l_result;


      }

?>