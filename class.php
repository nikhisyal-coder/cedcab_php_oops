<?php 
require_once('config.php');
 class User{
    public $username,$phone,$name,$userpassword,$email, $userpassword2,$origin,$drop,$laugage,$final,$fare;
    
    function entry($username,$phone,$name,$userpassword,$email, $userpassword2,$conn){

        $count=0;
        if ($userpassword != $userpassword2) {
        echo "<center><h3 style='color:white; font-size:1.2em;'>password mismatch</h3></center>";
        $count++;
               }


        if($username=='admin'){
            echo "<center><h3 style='color:white; font-size:1.2em;'>username invalid</h3></center>";
            $count++;
                 }
        if($username!='admin'){
            $sql="SELECT * FROM `users` WHERE `username`='".$username."'";
            $result=$conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<center><h3 style='color:white; font-size:1.2em;'>Username Already Exist</h3></center>"; 
            $count++;
            }
        }         
                 
                 
        if ($count==0) {

           $sql="INSERT INTO users (name, password, contact, date, username, boolean_admin, boolean_status,email) 
            VALUES ('".$name."', '".md5($userpassword)."','".$phone."', current_timestamp(),'".$username."', 1, 0,'".$email."')"; 
           
    
        if ($conn->query($sql)===true) {
          
                        echo "<center><h3 style='color:white; font-size:1.2em;'>New record created successfully</h3></center>";
                        header("Location: login.php");
                    } 
                    
                    else {
                        $conn->error;
                        $errors[]=array("input"=>"form","msg"=>"New record not created.");
                    }
            
                }
            }  
            function admit($username,$userpassword,$conn){
                $count=0;
                if ($count==0) {
        
                     $sql1="SELECT * from users WHERE username='".$username."'
                     AND password='".md5($userpassword)."'";
                     $result=$conn->query($sql1);
                    if ($result->num_rows > 0) {
                        while ($row= $result->fetch_assoc()) {
                            $_SESSION['userdata']=array("username"=>$row['username'],
                            "user_id"=>$row['user_id']);
                            $_SESSION['name']=$row['name'];
                            $_SESSION['phone']=$row['contact'];
                            if ($row['boolean_admin']==0 && $_SESSION['userdata']['username']=='admin') {
                                header("Location: admin.php?id=13");
                            }
                           
                            if ($row['boolean_admin']==1) {
                                if($row['boolean_status']==0){
                                  echo "<center><h3 style='color:white; font-size:1.2em;'><b>plzz wait for admin approval your account is not verified yet</b></h3></center>";
                                }
                                else{
                                    // header("Location:cab\index2.php");
                                    header("Location:cab/index2.php");

                                }
                               
                            }     
                        }
            
                    }
                    else {
                      $count++;
                      echo "<center><h3 style='color:white; font-size:1.2em;'>Invalid Login credentials</h3></center>";
                    }
                    
            }
            $conn->close();
        } 
       function invoice($m,$conn){
        
           $m=$m/100;
           $sql="SELECT * from ride WHERE ride_id='".$m."'";
           echo '<h2><center>Invoice page</h2></h2>';
         
           $f=' <a class="navbar-brand ml-5" href="user.php?id=8" style="font-size: 35px;color:rgba(218, 22, 74, 1); font-weight:40;">ce<span>dca</span>b</a>';
           echo $f;
           $a='<table>';
            $result=$conn->query($sql);
          
            if ($result->num_rows > 0) {
               
                while ($row= $result->fetch_assoc()) {

                    $a.='<tr><th>Ride_id</th><td>'.$row['ride_id'].'</td></tr>';
                    $a.='<tr><th>Ride_date</th><td>'.$row['ride_date'].'</td></tr>';
                    $a.='<tr><th>Name</th><td>'.$_SESSION['userdata']['username'].'</td></tr>';
                    $a.='<tr><th>Pickup</th><td>'.$row['pickup'].'</td></tr>';
                    $a.='<tr><th>Drop</th><td>'.$row['drop'].'</td></tr>';
                    $a.='<tr><th>Total-Distance</th><td>'.$row['total_distance'].'</td></tr>';
                    $a.='<tr><th>CAB-TYPE</th><td>'.$row['cab_type'].'</td></tr>';
                    $a.='<tr><th>Total-fare</th><td>'.$row['total_fare'].'</td></tr>';
                    $a.='<tr><th>laugage</th><td>'.$row['laugage'].'</td></tr>';
                    $a.='<tr><th>Action</th><td><a href="#" class="inv" onclick="javascript:window.print()">Print</a></td><td></tr>';
 
                }
                $a.='</table>';
                echo $a;
            }


       }
   

        function ride($conn){
            $abc='';
            $name=$_SESSION['userdata']['username'];
            $sql1="SELECT * FROM users WHERE `username`='".$name."'";
            
            $result=$conn->query($sql1);
            
            if ($result->num_rows > 0) {
               
                while ($row= $result->fetch_assoc()) {

                  $abc=$row['user_id'];
                }
            }
          
         if($_SESSION['cabname']=='CedMicro'){

            $_SESSION['laugage']=0;
         }
              
              $sql="INSERT INTO `ride` ( `ride_date`, `pickup`, `drop`, `total_distance`, `laugage`, `total_fare`, `status`, `customer_id`,`cab_type`) 
              VALUES ( current_timestamp(),'".$_SESSION['origin']."', '".$_SESSION['drop']."', '".$_SESSION['final']."', '".$_SESSION['laugage']."','".$_SESSION['fare']."', 'pending', '".$abc."','".$_SESSION['cabname']."')";
              $result=$conn->query($sql);
               echo ($result);
              
              
             
           
            

        }
      function userpanel($a,$m,$conn){
           if($m==1){
            $abc='';
            $name=$_SESSION['userdata']['username'];
            $sql1="SELECT * FROM users WHERE `username`='".$name."'";
            
            $result=$conn->query($sql1);
            
            if ($result->num_rows > 0) {
               
                while ($row= $result->fetch_assoc()) {

                  $abc=$row['user_id'];
                }
            }
            $sql="SELECT * from ride WHERE customer_id='".$abc."'";
          
            $result=$conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<center><h2>Welcome<h2><h3>Ride-Approval Page</h3></center><center>";
              
                while ($row= $result->fetch_assoc()) {
                    $a.='<td>'.$row['ride_id'].'</td>';
                    $a.='<td>'.$row['ride_date'].'</td>';
                    $a.='<td>'.$row['pickup'].'</td>';
                    $a.='<td>'.$row['drop'].'</td>';
                    $a.='<td>'.$row['total_distance'].'</td>';
                    $a.='<td>'.$row['total_fare'].'</td>';
                    $a.='<td>'.$row['laugage'].'</td>';
                    $a.='<td>'.$row['status'].'</td>';
                    $a.='<td>'.$row['cab_type'].'</td></tr>';
                }
                $a.='</table>';
                echo $a;
            }

        }
        if($m==2){
            $abc='';
            $name=$_SESSION['userdata']['username'];
            $sql1="SELECT * FROM users WHERE `username`='".$name."'";
            
            $result=$conn->query($sql1);
            
            if ($result->num_rows > 0) {
               
                while ($row= $result->fetch_assoc()) {

                  $abc=$row['user_id'];
                }
            }
            $sql="SELECT * from ride WHERE customer_id='".$abc."' AND `status`='active'";
            $result=$conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<center><h2>Welcome<h2><h3>Ride-Completed-Page</h3></center><center>";
                while ($row= $result->fetch_assoc()) {
                    $a.='<td>'.$row['ride_id'].'</td>';
                    $a.='<td>'.$row['ride_date'].'</td>';
                    $a.='<td>'.$row['pickup'].'</td>';
                    $a.='<td>'.$row['drop'].'</td>';
                    $a.='<td>'.$row['total_distance'].'</td>';
                    $a.='<td>'.$row['total_fare'].'</td>';
                    $a.='<td>'.$row['laugage'].'</td>';
                    $a.='<td>'.$row['status'].'</td>';
                    $a.='<td>'.$row['cab_type'].'</td>';
                    $a.='<td><a href="user2.php?id='.$row['ride_id'].'00" class="a11">Invoice</a></td></tr>';
                 
                }
                $a.='</table>';
                echo $a;
            }

        }

if($m==3){
    $abc='';
    $name=$_SESSION['userdata']['username'];
    $sql1="SELECT * FROM users WHERE `username`='".$name."'";
    
    $result=$conn->query($sql1);
    
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {

          $abc=$row['user_id'];
        }
    }
    $sql="SELECT * from ride  WHERE customer_id='".$abc."' AND `status`='pending'  ORDER BY ride_date DESC";
    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<center><h2>Welcome<h2><h3>Ride-Pending-Page</h3></center><center>";
     
        while ($row= $result->fetch_assoc()) {
            $a.='<td>'.$row['ride_id'].'</td>';
            $a.='<td>'.$row['ride_date'].'</td>';
            $a.='<td>'.$row['pickup'].'</td>';
            $a.='<td>'.$row['drop'].'</td>';
            $a.='<td>'.$row['total_distance'].'</td>';
            $a.='<td>'.$row['total_fare'].'</td>';
            $a.='<td>'.$row['laugage'].'</td>';
            $a.='<td>'.$row['status'].'</td>';
            $a.='<td>'.$row['cab_type'].'</td>';
            $a.='<td><a href="cancel.php?id='.$row['ride_id'].'" class="a11">Cancel-Ride</a></td></tr>';
        }
        $a.='</table>';
        echo $a;
    }
}

           }



   function form($a,$m,$conn){
       if($m==4){
        $abc='';
            $name=$_SESSION['userdata']['username'];
            $sql1="SELECT * FROM users WHERE `username`='".$name."'";
            
            $result=$conn->query($sql1);
            
            if ($result->num_rows > 0) {
               echo '<center><h2>Personal-Details</h2></center>';
              
                while ($row= $result->fetch_assoc()) {
                    $a.='<td>'.$row['user_id'].'</td>';
                    $a.='<td>'.$row['name'].'</td>';
                    $a.='<td>'.$row['contact'].'</td>';
                    $a.='<td>'.$row['date'].'</td>';
                    $a.='<td>'.$row['username'].'</td>';
                    $a.='<td>'.$row['email'].'</td>';
                    $a.='<td><a href="user.php?id=5" class="a11">Update-Details</a></td>';
                  $a.='<td><a href="user.php?id=6" class="a11">Update-Password</a></td></tr>';
                }
                $a.='</table>';
                echo $a;
            }
        }
        if($m==111){
            $abc='';
                $name=$_SESSION['userdata']['username'];
                $sql1="SELECT * FROM users WHERE `username`='".$name."'";
                
                $result=$conn->query($sql1);
                
                if ($result->num_rows > 0) {
                   echo '<center><h2>Personal-Details</h2></center>';
                  
                    while ($row= $result->fetch_assoc()) {
                        $a.='<td>'.$row['user_id'].'</td>';
                        $a.='<td>'.$row['name'].'</td>';
                        $a.='<td>'.$row['contact'].'</td>';
                        $a.='<td>'.$row['date'].'</td>';
                        $a.='<td>'.$row['username'].'</td>';
                        $a.='<td>'.$row['email'].'</td>';
                        $a.='<td><a href="admin.php?id=11" class="a11" >Update-Password</a></td></tr>';
                    }
                    $a.='</table>';
                    echo $a;
                }
            }
       


if($m==5){
    
    
    echo $a;
    if(isset($_POST['submit'])){
        $phone=isset($_POST['phone'])?$_POST['phone']:'';
        $name=isset($_POST['name'])?$_POST['name']:'';
        $username=isset($_POST['username'])?$_POST['username']:'';
    
        if($_SESSION['userdata']['username']==$username){
            
        $sql="UPDATE users
         SET  `contact`='".$phone."',`name`='".$name."' Where `username`='".$username."'";
         
         $result=$conn->query($sql);
         echo "details updated";
        }
        else{
            echo "<h2>details not updated</h2>";    
        }
}        
}  
if($m==6 || $m==11){
    $count=0;
    echo $a;
    if(isset($_POST['submit'])){
       
        $name=isset($_POST['pass'])?$_POST['pass']:'';
        $oldpas=isset($_POST['pass'])?$_POST['opass']:'';
        $username=isset($_POST['username'])?$_POST['username']:'';
        $sql="SELECT * from `users` WHERE `password`='".md5($oldpas)."'";
        if($name==$oldpas){
            echo "<h2>Old password and New password should not be same</h2>";
            $count++;
        }
    
       
        $result=$conn->query($sql);
      
        if ($result->num_rows == 0) {
            echo "<h2>Old password mismatch<h2>";
            
            $count++;
        }
        if($_SESSION['userdata']['username']==$username && $count==0){
            
            $sql="UPDATE users
             SET  `password`='".md5($name)."' Where `username`='".$username."'";
             
            if($result=$conn->query($sql)){
                session_destroy();
                echo "<script>alert('Your Password has been Changed');
                window.location.href='login.php';</script>";
            }

            }
}
}



    }
    function fetch($conn){
    
        $name=$_SESSION['userdata']['username'];
        $sql1="SELECT * FROM users WHERE `username`='".$name."'";
        $result=$conn->query($sql1);
        if ($result->num_rows > 0) {
       
            while ($row= $result->fetch_assoc()) {
    
              $abc=$row['user_id'];
            }
        }
        $sql="SELECT SUM(total_fare) As Total from ride WHERE customer_id='".$abc."' AND `status`='active'"; 
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo '<h2><center>₹</center></h2><center>'.$row['Total'];
    }
    function fetch2($conn){
        $name=$_SESSION['userdata']['username'];
        $sql1="SELECT * FROM users WHERE `username`='".$name."'";
        $result=$conn->query($sql1);
        if ($result->num_rows > 0) {
       
            while ($row= $result->fetch_assoc()) {
    
              $abc=$row['user_id'];
            }
        }
        $sql="SELECT COUNT(total_fare) As Total from ride WHERE customer_id='".$abc."' AND `status`='active'"; 
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo'<center>'. $row['Total'];
    }
    function fetch3($conn){
        $name=$_SESSION['userdata']['username'];
        $sql1="SELECT * FROM users WHERE `username`='".$name."'";
        $result=$conn->query($sql1);
        if ($result->num_rows > 0) {
       
            while ($row= $result->fetch_assoc()) {
    
              $abc=$row['user_id'];
            }
        }
        $sql="SELECT COUNT(total_fare) As Total from ride WHERE customer_id='".$abc."' AND `status`='pending'"; 
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo '<center>'. $row['Total'];
}
function filterrr2($a,$m,$filter,$filter2,$conn){

    $abc='';
    $name=$_SESSION['userdata']['username'];
    $sql1="SELECT * FROM users WHERE `username`='".$name."'";
    
    $result=$conn->query($sql1);
    
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {

          $abc=$row['user_id'];
        }
    }
  if($m==1){
  $sql="SELECT *
 FROM `ride`
  WHERE (ride_date BETWEEN '".$filter."' AND '".$filter2."' )";
}
if($m==2){
$sql="SELECT *
FROM `ride`
WHERE (ride_date BETWEEN '".$filter."' AND '".$filter2."' ) AND `status`='active'";
}
if($m==3){
$sql="SELECT *
FROM `ride`
WHERE (ride_date BETWEEN '".$filter."' AND '".$filter2."' ) AND `status`='pending'";
}
  $result=$conn->query($sql);
  if ($result->num_rows > 0) {
           
    while ($row= $result->fetch_assoc()) {
        $a.='<td>'.$row['ride_id'].'</td>';
        $a.='<td>'.$row['ride_date'].'</td>';
        $a.='<td>'.$row['pickup'].'</td>';
        $a.='<td>'.$row['drop'].'</td>';
        $a.='<td>'.$row['total_distance'].'</td>';
        $a.='<td>'.$row['total_fare'].'</td>';
        $a.='<td>'.$row['cab_type'].'</td>';
        $a.='<td>'.$row['laugage'].'</td></tr>';
    }
    $a.='</table>';
    echo $a;
}

}
function filterrr3($a,$m,$filter,$filter2,$conn){

    $abc='';
    $name=$_SESSION['userdata']['username'];
    $sql1="SELECT * FROM users WHERE `username`='".$name."'";
    
    $result=$conn->query($sql1);
    
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {

          $abc=$row['user_id'];
        }
    }
  if($m==4){
  $sql="SELECT *
 FROM `ride`
  WHERE (ride_date BETWEEN '".$filter."' AND '".$filter2."' )";
}
if($m==5){
$sql="SELECT *
FROM `ride`
WHERE (ride_date BETWEEN '".$filter."' AND '".$filter2."' ) AND `status`='active'";
}
if($m==6){
$sql="SELECT *
FROM `ride`
WHERE (ride_date BETWEEN '".$filter."' AND '".$filter2."' ) AND `status`='pending'";
}
if($m==7){
    $sql="SELECT *
    FROM `ride`
    WHERE (ride_date BETWEEN '".$filter."' AND '".$filter2."' ) AND `status`='cancelled'";
    }
  $result=$conn->query($sql);
  if ($result->num_rows > 0) {
           
    while ($row= $result->fetch_assoc()) {
        $a.='<td>'.$row['ride_id'].'</td>';
        $a.='<td>'.$row['ride_date'].'</td>';
        $a.='<td>'.$row['pickup'].'</td>';
        $a.='<td>'.$row['drop'].'</td>';
        $a.='<td>'.$row['total_distance'].'</td>';
        $a.='<td>'.$row['total_fare'].'</td>';
        $a.='<td>'.$row['cab_type'].'</td>';
        $a.='<td>'.$row['laugage'].'</td></tr>';
    }
    $a.='</table>';
    echo $a;
}

}
function filterrr($a,$m,$filter,$conn){
    $abc='';
    $name=$_SESSION['userdata']['username'];
    $sql1="SELECT * FROM users WHERE `username`='".$name."'";
    
    $result=$conn->query($sql1);
    
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {

          $abc=$row['user_id'];
        }
    }
    if($filter=='mini' ){
        if($m==1){
            echo "<center><h2>Your ride in cedmini</h2></center>";
         $sql="SELECT * FROM ride  WHERE `customer_id`='".$abc."' AND `cab_type`='CedMini'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";   
        }
        if($m==2){
            echo "<center><h2>Your Active ride in cedmini</h2></center>";
         $sql="SELECT * FROM ride WHERE `status`='active' AND `customer_id`='".$abc."' AND `cab_type`='CedMini'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";
        }
        if($m==3){
            echo "<center><h2>Your pending ride in cedmini</h2></center>";
         $sql="SELECT * FROM ride WHERE `status`='pending' AND `customer_id`='".$abc."' AND `cab_type`='CedMini'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";
        }

        $result=$conn->query($sql);
        if ($result->num_rows > 0) {
           
            while ($row= $result->fetch_assoc()) {
                $a.='<td>'.$row['ride_id'].'</td>';
                $a.='<td>'.$row['ride_date'].'</td>';
                $a.='<td>'.$row['pickup'].'</td>';
                $a.='<td>'.$row['drop'].'</td>';
                $a.='<td>'.$row['total_distance'].'</td>';
                $a.='<td>'.$row['total_fare'].'</td>';
                $a.='<td>'.$row['cab_type'].'</td>';
                $a.='<td>'.$row['laugage'].'</td></tr>';
            }
            $a.='</table>';
            echo $a;
        }
       }
    if($filter=='minii' ){
            if($m==4){
                echo "<center><h2>Your ride in cedmini</h2></center>";
             $sql="SELECT * FROM ride  WHERE  `cab_type`='CedMini'
             ORDER BY total_fare DESC"; 
            
            }
            if($m==5){
                echo "<center><h2>Your active ride in cedmini</h2></center>";
             $sql="SELECT * FROM ride WHERE `status`='active' AND `cab_type`='CedMini'
             ORDER BY total_fare DESC ";
            
            }
            if($m==6){
                echo "<center><h2>Your pending ride in cedmini</h2></center>";
             $sql="SELECT * FROM ride WHERE `status`='pending' AND  `cab_type`='CedMini'
             ORDER BY total_fare DESC 
             LIMIT 0, 7";
            }
            if($m==7){
                echo "<center><h2>Your cancelled ride in cedmini</h2></center>";
                $sql="SELECT * FROM ride WHERE `status`='cancelled' AND `cab_type`='CedMini'
                ORDER BY total_fare DESC 
                LIMIT 0, 7";
            }
               $result=$conn->query($sql);
            
               if ($result->num_rows > 0) {
                  
                   while ($row= $result->fetch_assoc()) {
                       $a.='<td>'.$row['ride_id'].'</td>';
                       $a.='<td>'.$row['ride_date'].'</td>';
                       $a.='<td>'.$row['pickup'].'</td>';
                       $a.='<td>'.$row['drop'].'</td>';
                       $a.='<td>'.$row['total_distance'].'</td>';
                       $a.='<td>'.$row['total_fare'].'</td>';
                       $a.='<td>'.$row['cab_type'].'</td>';
                       $a.='<td>'.$row['laugage'].'</td></tr>';
                   }
                   $a.='</table>';
                   echo $a;
               }
              }
        if($filter=='microo' ){
            if($m==4){
                echo "<center><h2>Your ride in cedmicro</h2></center>";
             $sql="SELECT * FROM ride  WHERE   `cab_type`='CedMicro'
             ORDER BY total_fare DESC 
             LIMIT 0, 7";   
            }
            if($m==5){
                echo "<center><h2>Your active ride in cedmicro</h2></center>";
             $sql="SELECT * FROM ride WHERE `status`='active'  AND `cab_type`='CedMicro'
             ORDER BY total_fare DESC 
             LIMIT 0, 7";
            }
            if($m==6){
                echo "<center><h2>Your pending ride in cedmicro</h2></center>";
             $sql="SELECT * FROM ride WHERE `status`='pending'  AND `cab_type`='CedMicro'
             ORDER BY total_fare DESC 
             LIMIT 0, 7";
            }
            if($m==7){
                echo "<center><h2>Your cancelled ride in cedmicro</h2></center>";
                $sql="SELECT * FROM ride WHERE `status`='cancelled' AND `cab_type`='CedMicro'
                ORDER BY total_fare DESC 
                LIMIT 0, 7";
               }
               $result=$conn->query($sql);
               if ($result->num_rows > 0) {
                  
                   while ($row= $result->fetch_assoc()) {
                       $a.='<td>'.$row['ride_id'].'</td>';
                       $a.='<td>'.$row['ride_date'].'</td>';
                       $a.='<td>'.$row['pickup'].'</td>';
                       $a.='<td>'.$row['drop'].'</td>';
                       $a.='<td>'.$row['total_distance'].'</td>';
                       $a.='<td>'.$row['total_fare'].'</td>';
                       $a.='<td>'.$row['cab_type'].'</td>';
                       $a.='<td>'.$row['laugage'].'</td></tr>';
                   }
                   $a.='</table>';
                   echo $a;
               }
              }
        if($filter=='suvv' ){
            if($m==4){
                echo "<center><h2>Your ride in cedsuv</h2></center>";
             $sql="SELECT * FROM ride  WHERE  `cab_type`='Cedsuv'
             ORDER BY total_fare DESC ";
            
            }
            if($m==5){
                echo "<center><h2>Your active ride in cedsuv</h2></center>";
             $sql="SELECT * FROM ride WHERE `status`='active'  AND `cab_type`='Cedsuv'
               ORDER BY total_fare DESC ";
            }
            if($m==6){
                echo "<center><h2>Your pending ride in cedsuv</h2></center>";
             $sql="SELECT * FROM ride WHERE `status`='pending' AND `cab_type`='Cedsuv'
             ORDER BY total_fare DESC ";
            }
            if($m==7){
                echo "<center><h2>Your cancelled ride in cedsuv</h2></center>";
                $sql="SELECT * FROM ride WHERE `status`='cancelled'  AND `cab_type`='Cedsuv'
                  ORDER BY total_fare DESC ";
               }
               $result=$conn->query($sql);
               if ($result->num_rows > 0) {
                  
                   while ($row= $result->fetch_assoc()) {
                       $a.='<td>'.$row['ride_id'].'</td>';
                       $a.='<td>'.$row['ride_date'].'</td>';
                       $a.='<td>'.$row['pickup'].'</td>';
                       $a.='<td>'.$row['drop'].'</td>';
                       $a.='<td>'.$row['total_distance'].'</td>';
                       $a.='<td>'.$row['total_fare'].'</td>';
                       $a.='<td>'.$row['cab_type'].'</td>';
                       $a.='<td>'.$row['laugage'].'</td></tr>';
                   }
                   $a.='</table>';
                   echo $a;
               }
              }
        if($filter=='royall' ){
            if($m==4){
                echo "<center><h2>Your ride in cedroyal</h2></center>";
             $sql="SELECT * FROM ride  WHERE  `cab_type`='Cedroyal'
                ORDER BY total_fare DESC ";  
            }
            if($m==5){
                echo "<center><h2>Your active ride in cedroyal</h2></center>";
             $sql="SELECT * FROM ride WHERE `status`='active' AND `cab_type`='Cedroyal'
          ORDER BY total_fare DESC ";
            }
            if($m==6){
                echo "<center><h2>Your pending ride in cedroyal</h2></center>";
             $sql="SELECT * FROM ride WHERE `status`='pending'  AND `cab_type`='Cedroyal'
               ORDER BY total_fare DESC ";
            }
            if($m==7){
                echo "<center><h2>Your cancelled ride in cedroyal</h2></center>";
                $sql="SELECT * FROM ride WHERE `status`='cancelled'  AND `cab_type`='Cedroyal'
                   ORDER BY total_fare DESC ";
               }
               $result=$conn->query($sql);
               if ($result->num_rows > 0) {
                  
                   while ($row= $result->fetch_assoc()) {
                       $a.='<td>'.$row['ride_id'].'</td>';
                       $a.='<td>'.$row['ride_date'].'</td>';
                       $a.='<td>'.$row['pickup'].'</td>';
                       $a.='<td>'.$row['drop'].'</td>';
                       $a.='<td>'.$row['total_distance'].'</td>';
                       $a.='<td>'.$row['total_fare'].'</td>';
                       $a.='<td>'.$row['cab_type'].'</td>';
                       $a.='<td>'.$row['laugage'].'</td></tr>';
                   }
                   $a.='</table>';
                   echo $a;
               }
              }
     
    if($filter=='micro' ){
        if($m==1){
            echo "<center><h2>Your ride in cedmicro</h2></center>";
         $sql="SELECT * FROM ride  WHERE `customer_id`='".$abc."' AND `cab_type`='CedMicro'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";   
        }
        if($m==2){
            echo "<center><h2>Your active ride in cedmicro</h2></center>";
         $sql="SELECT * FROM ride WHERE `status`='active' AND `customer_id`='".$abc."' AND `cab_type`='CedMicro'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";
        }
        if($m==3){
            echo "<center><h2>Your pending ride in cedmicro</h2></center>";
         $sql="SELECT * FROM ride WHERE `status`='pending' AND `customer_id`='".$abc."' AND `cab_type`='CedMicro'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";
        }
    
     $result=$conn->query($sql);
     if ($result->num_rows > 0) {
        
         while ($row= $result->fetch_assoc()) {
             $a.='<td>'.$row['ride_id'].'</td>';
             $a.='<td>'.$row['ride_date'].'</td>';
             $a.='<td>'.$row['pickup'].'</td>';
             $a.='<td>'.$row['drop'].'</td>';
             $a.='<td>'.$row['total_distance'].'</td>';
             $a.='<td>'.$row['total_fare'].'</td>';
             $a.='<td>'.$row['cab_type'].'</td>';
             $a.='<td>'.$row['laugage'].'</td></tr>';
         }
         $a.='</table>';
         echo $a;
     }
    }

    if($filter=='suv' ){
        if($m==1){
            echo "<center><h2>Your ride in cedsuv</h2></center>";
         $sql="SELECT * FROM ride  WHERE `customer_id`='".$abc."' AND `cab_type`='Cedsuv'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";   
        }
        if($m==2){
            echo "<center><h2>Your active ride in cedsuv</h2></center>";
         $sql="SELECT * FROM ride WHERE `status`='active' AND `customer_id`='".$abc."' AND `cab_type`='Cedsuv'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";
        }
        if($m==3){
            echo "<center><h2>Your pending ride in cedsuv</h2></center>";
         $sql="SELECT * FROM ride WHERE `status`='pending' AND `customer_id`='".$abc."' AND `cab_type`='Cedsuv'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";
        }
      
     $result=$conn->query($sql);
     if ($result->num_rows > 0) {
        
         while ($row= $result->fetch_assoc()) {
             $a.='<td>'.$row['ride_id'].'</td>';
             $a.='<td>'.$row['ride_date'].'</td>';
             $a.='<td>'.$row['pickup'].'</td>';
             $a.='<td>'.$row['drop'].'</td>';
             $a.='<td>'.$row['total_distance'].'</td>';
             $a.='<td>'.$row['total_fare'].'</td>';
             $a.='<td>'.$row['cab_type'].'</td>';
             $a.='<td>'.$row['laugage'].'</td></tr>';
         }
         $a.='</table>';
         echo $a;
     }
    }
    if($filter=='royal' ){
        if($m==1){
            echo "<center><h2>Your ride in cedroyal</h2></center>";
         $sql="SELECT * FROM ride  WHERE `customer_id`='".$abc."' AND `cab_type`='Cedroyal'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";   
        }
        if($m==2){
            echo "<center><h2>Your active ride in cedroyal</h2></center>";
         $sql="SELECT * FROM ride WHERE `status`='active' AND `customer_id`='".$abc."' AND `cab_type`='Cedroyal'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";
        }
        if($m==3){
            echo "<center><h2>Your pending ride in cedroyal</h2></center>";
         $sql="SELECT * FROM ride WHERE `status`='pending' AND `customer_id`='".$abc."' AND `cab_type`='Cedroyal'
         ORDER BY total_fare DESC 
         LIMIT 0, 7";
        }
     
     $result=$conn->query($sql);
     if ($result->num_rows > 0) {
        
         while ($row= $result->fetch_assoc()) {
             $a.='<td>'.$row['ride_id'].'</td>';
             $a.='<td>'.$row['ride_date'].'</td>';
             $a.='<td>'.$row['pickup'].'</td>';
             $a.='<td>'.$row['drop'].'</td>';
             $a.='<td>'.$row['total_distance'].'</td>';
             $a.='<td>'.$row['total_fare'].'</td>';
             $a.='<td>'.$row['cab_type'].'</td>';
             $a.='<td>'.$row['laugage'].'</td></tr>';
         }
         $a.='</table>';
         echo $a;
     }
    }
   if($filter==7 ){
       if($m==1){
        echo "<center><h2>Your total ride in last 7 days</h2></center>";
        $sql="SELECT * FROM ride  WHERE `customer_id`='".$abc."'
        ORDER BY total_fare DESC 
        LIMIT 0, 7";   
       }
       if($m==2){
        echo "<center><h2>Your total active-ride in last 7 days</h2></center>";
        $sql="SELECT * FROM ride WHERE `status`='active' AND `customer_id`='".$abc."'
        ORDER BY total_fare DESC 
        LIMIT 0, 7";
       }
       if($m==3){
        echo "<center><h2>Your total pending-ride in last 7 days</h2></center>";
        $sql="SELECT * FROM ride WHERE `status`='pending' AND `customer_id`='".$abc."'
        ORDER BY total_fare DESC 
        LIMIT 0, 7";
       }
    
   
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {
            $a.='<td>'.$row['ride_id'].'</td>';
            $a.='<td>'.$row['ride_date'].'</td>';
            $a.='<td>'.$row['pickup'].'</td>';
            $a.='<td>'.$row['drop'].'</td>';
            $a.='<td>'.$row['total_distance'].'</td>';
            $a.='<td>'.$row['total_fare'].'</td>';
            $a.='<td>'.$row['cab_type'].'</td>';
            $a.='<td>'.$row['laugage'].'</td></tr>';
        }
        $a.='</table>';
        echo $a;
    }
   }
   if($filter==30){
    if($m==1){
        echo "<center><h2>Your  ride in last 30 days</h2></center>";
        $sql="SELECT * FROM ride  WHERE `customer_id`='".$abc."'
        ORDER BY total_fare DESC 
        LIMIT 0, 30";   
       }
       if($m==2){
        echo "<center><h2>Your  active-ride in last 30 days</h2></center>";
        $sql="SELECT * FROM ride WHERE `status`='active' AND `customer_id`='".$abc."'
        ORDER BY total_fare DESC 
        LIMIT 0, 30";
       }
       if($m==3){
        echo "<center><h2>Your  pending-ride in last 30 days</h2></center>";
        $sql="SELECT * FROM ride WHERE `status`='pending' AND `customer_id`='".$abc."'
        ORDER BY total_fare DESC 
        LIMIT 0, 30";
       }
     
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {
            $a.='<td>'.$row['ride_id'].'</td>';
            $a.='<td>'.$row['ride_date'].'</td>';
            $a.='<td>'.$row['pickup'].'</td>';
            $a.='<td>'.$row['drop'].'</td>';
            $a.='<td>'.$row['total_distance'].'</td>';
            $a.='<td>'.$row['total_fare'].'</td>';
            $a.='<td>'.$row['cab_type'].'</td>';
            $a.='<td>'.$row['laugage'].'</td></tr>';
        }
        $a.='</table>';
        echo $a;
    }
   }
   if($filter==1){
    if($m==1){
        echo "<center><h2>Your total fare</h2></center>";
        $sql="SELECT * FROM ride  WHERE `customer_id`='".$abc."'
        ORDER BY total_fare ASC ";   
       }
       if($m==2){
        echo "<center><h2>Your total fare</h2></center>";
        $sql="SELECT * FROM ride WHERE `status`='active' AND `customer_id`='".$abc."'
        ORDER BY total_fare ASC ";
       
       }
       if($m==3){
        echo "<center><h2>Your total fare</h2></center>";
        $sql="SELECT * FROM ride WHERE `status`='pending' AND `customer_id`='".$abc."'
        ORDER BY total_fare ASC ";
       }
     
  
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {
            $a.='<td>'.$row['ride_id'].'</td>';
            $a.='<td>'.$row['ride_date'].'</td>';
            $a.='<td>'.$row['pickup'].'</td>';
            $a.='<td>'.$row['drop'].'</td>';
            $a.='<td>'.$row['total_distance'].'</td>';
            $a.='<td>'.$row['total_fare'].'</td>';
            $a.='<td>'.$row['cab_type'].'</td>';
            $a.='<td>'.$row['laugage'].'</td></tr>';
        }
        $a.='</table>';
        echo $a;
    }

   }
   if($filter=='name'){
       if($m==1){
        $sql="SELECT * FROM users WHERE `boolean_admin`='1'
        ORDER BY `name` DESC 
        LIMIT 0, 7";
       }
       if($m==2){
        $sql="SELECT * FROM users WHERE `boolean_admin`='1' AND `boolean_status`='1'
        ORDER BY `name` DESC 
        LIMIT 0, 7";
       }
       if($m==3){
        $sql="SELECT * FROM users WHERE `boolean_admin`='1' AND `boolean_status`='0'
        ORDER BY `name` DESC 
        LIMIT 0, 7";
       }
       
 
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {
            $a.='<td>'.$row['user_id'].'</td>';
            $a.='<td>'.$row['name'].'</td>';
            $a.='<td>'.$row['contact'].'</td>';
            $a.='<td>'.$row['date'].'</td>';
            $a.='<td>'.$row['username'].'</td></tr>';
        }
        $a.='</table>';
        echo $a;
    }

   }
   if($filter=='dist'){
    if($m==4){
     $sql="SELECT * FROM ride
     ORDER BY total_distance ASC ";  
    }
    
    if($m==5){
     $sql="SELECT * FROM ride WHERE `status`='active'
     ORDER BY total_distance ASC ";
    }
    if($m==6){
     $sql="SELECT * FROM ride WHERE `status`='pending'
     ORDER BY total_distance ASC ";
    }
    if($m==7){
     $sql="SELECT * FROM ride WHERE `status`='cancelled'
     ORDER BY total_distance ASC ";
    }
  
 $result=$conn->query($sql);
 if ($result->num_rows > 0) {
    
     while ($row= $result->fetch_assoc()) {
         $a.='<td>'.$row['ride_id'].'</td>';
         $a.='<td>'.$row['ride_date'].'</td>';
         $a.='<td>'.$row['pickup'].'</td>';
         $a.='<td>'.$row['drop'].'</td>';
         $a.='<td>'.$row['total_distance'].'</td>';
         $a.='<td>'.$row['total_fare'].'</td>';
         $a.='<td>'.$row['laugage'].'</td>';
         $a.='<td>'.$row['cab_type'].'</td></tr>';
     }
     $a.='</table>';
     echo $a;
 }

}
if($filter=='distance'){
    if($m==1){
     $sql="SELECT * FROM ride
     ORDER BY total_distance ASC ";  
    }
    
    if($m==2){
     $sql="SELECT * FROM ride WHERE `status`='active'
     ORDER BY total_distance ASC ";
    }
    if($m==3){
     $sql="SELECT * FROM ride WHERE `status`='pending'
     ORDER BY total_distance ASC ";
    }
   
  
 $result=$conn->query($sql);
 if ($result->num_rows > 0) {
    
     while ($row= $result->fetch_assoc()) {
         $a.='<td>'.$row['ride_id'].'</td>';
         $a.='<td>'.$row['ride_date'].'</td>';
         $a.='<td>'.$row['pickup'].'</td>';
         $a.='<td>'.$row['drop'].'</td>';
         $a.='<td>'.$row['total_distance'].'</td>';
         $a.='<td>'.$row['total_fare'].'</td>';
         $a.='<td>'.$row['cab_type'].'</td>';
         $a.='<td>'.$row['laugage'].'</td></tr>';
     }
     $a.='</table>';
     echo $a;
 }

}
   if($filter=='fare'){
       if($m==4){
        $sql="SELECT * FROM ride
        ORDER BY total_fare ASC ";  
       }
       if($m==5){
        $sql="SELECT * FROM ride WHERE `status`='active'
        ORDER BY total_fare ASC ";
       }
       if($m==6){
        $sql="SELECT * FROM ride WHERE `status`='pending'
        ORDER BY total_fare ASC ";
       }
       if($m==7){
        $sql="SELECT * FROM ride WHERE `status`='cancelled'
        ORDER BY total_fare ASC ";
       }
    
 
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {
            $a.='<td>'.$row['ride_id'].'</td>';
            $a.='<td>'.$row['ride_date'].'</td>';
            $a.='<td>'.$row['pickup'].'</td>';
            $a.='<td>'.$row['drop'].'</td>';
            $a.='<td>'.$row['total_distance'].'</td>';
            $a.='<td>'.$row['total_fare'].'</td>';
            $a.='<td>'.$row['laugage'].'</td>';
            $a.='<td>'.$row['cab_type'].'</td></tr>';
        }
        $a.='</table>';
        echo $a;
    }

   }
   if($filter=='date'){
    if($m==4){
        $sql="SELECT * FROM ride
        ORDER BY total_fare DESC ";  
       }
       if($m==5){
        $sql="SELECT * FROM ride WHERE `status`='active'
        ORDER BY total_fare DESC ";
       }
       if($m==6){
        $sql="SELECT * FROM ride WHERE `status`='pending'
        ORDER BY total_fare DESC ";
       }
       if($m==7){
        $sql="SELECT * FROM ride WHERE `status`='cancelled'
        ORDER BY total_fare DESC ";
       }
     
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
       
        while ($row= $result->fetch_assoc()) {
            $a.='<td>'.$row['ride_id'].'</td>';
            $a.='<td>'.$row['ride_date'].'</td>';
            $a.='<td>'.$row['pickup'].'</td>';
            $a.='<td>'.$row['drop'].'</td>';
            $a.='<td>'.$row['total_distance'].'</td>';
            $a.='<td>'.$row['total_fare'].'</td>';
            $a.='<td>'.$row['laugage'].'</td>';
            $a.='<td>'.$row['cab_type'].'</td></tr>';
        }
        $a.='</table>';
        echo $a;
    }

   }

}
function ride_user_cancel($m,$conn){
  
$sql="SELECT * from `ride` WHERE `ride_id`='".$m."'";
$result=$conn->query($sql);
if ($result->num_rows > 0){
    $sql1="UPDATE `ride` SET `status`= 'cancelled'  WHERE `ride_id`='".$m."'";
    $result=$conn->query($sql1);
   echo"<script>alert('You cancelled that ride!!');</script>";
    
}

header('location:user.php?id=3');
}


 }
class admin{
    
    function ride_status2($myyy,$conn){
        $sql="SELECT * from ride WHERE ride_id='".$myyy."'";
        $result=$conn->query($sql);
        if ($result->num_rows > 0) {
          $row= $result->fetch_assoc();
          if($row['status']=='pending'){
          $sql1="UPDATE `ride` SET `status`= 'cancelled'  WHERE `ride_id`='".$myyy."'";
          $result=$conn->query($sql1);
          header("Location:admin.php?id=4");
        }
        header("Location:admin.php?id=4");
    }

    }
    function ride_status($myyy,$conn){
        $sql="SELECT * from ride WHERE ride_id='".$myyy."'";
        
    
        $result=$conn->query($sql);
      
    if ($result->num_rows > 0) {

        $row= $result->fetch_assoc();

        if($row['status']=='pending'){
          
        $sql1="UPDATE `ride` SET `status`= 'active'  WHERE `ride_id`='".$myyy."'";
    
        $result=$conn->query($sql1);
        header("Location:admin.php?id=5");
        
  }
    else{
        $sql1="UPDATE ride SET `status`= 'pending' WHERE `ride_id`='".$myyy."' ";
        $result=$conn->query($sql1);
        header("Location:admin.php?id=4");
}
}
}

function ride( $a,$m,$conn){
    if($m==1){
     
        echo "<center><h2>User</h2></center><center>";
        $sql1="SELECT * from users where `boolean_admin`='1'";
        $result=$conn->query($sql1);
        if ($result->num_rows > 0) {
        while ($row= $result->fetch_assoc()) {
        $a.='<td>'.$row['name'].'</td>';
        $a.='<td>'.$row['username'].'</td>';
        $a.='<td>'.$row['user_id'].'</td>';
        $a.='<td>'.$row['contact'].'</td>';
        $a.=($row['boolean_status']==1)?('<td>Approved-User</td>'):('<td>Non-Approved-User</td>');
        $a.='<td><a href="update4.php?id='.$row['user_id'].'" class="a11" >Approved/Disapproved-User</a></td></tr>';
     
    }
    $a.='</table>';
    echo $a;
    }

    }
if($m==2){
   
    echo "<center><h2>Approved </h2><h3>User</h3></center><center>";
    $sql1="SELECT * from users Where `boolean_status`='1' AND  `boolean_admin`='1'";
    $result=$conn->query($sql1);
    if ($result->num_rows > 0) {
    while ($row= $result->fetch_assoc()) {
    $a.='<td>'.$row['name'].'</td>';
    $a.='<td>'.$row['username'].'</td>';
    $a.='<td>'.$row['user_id'].'</td>';
    $a.='<td>'.$row['contact'].'</td>';
    $a.=($row['boolean_status']==1)?('<td>Approved-User</td></tr>'):('<td>Non-Approved-User</td></tr>');

}
$a.='</table>';
echo $a;
}
}
if($m==3){
  
    echo "<center><h2>Pending </h2><h3>User</h3></center><center>";
    $sql1="SELECT * from users Where `boolean_status`='0' ";
    $result=$conn->query($sql1);
    if ($result->num_rows > 0) {
    while ($row= $result->fetch_assoc()) {
    $a.='<td>'.$row['name'].'</td>';
    $a.='<td>'.$row['username'].'</td>';
    $a.='<td>'.$row['user_id'].'</td>';
    $a.='<td>'.$row['contact'].'</td>';
    $a.=($row['boolean_status']==1)?('<td>Approved-User</td></tr>'):('<td>Non-Approved-User</td></tr>');
 
}
$a.='</table>';
echo $a;
}
}

}

function loct($uid,$conn){
    $sql="SELECT * from `location` WHERE `id`='".$uid."'";
    echo $sql;
    $result=$conn->query($sql);
    print_r($result);
    if ($result->num_rows > 0) {
        while ($row= $result->fetch_assoc()) {
            if($row['is_avb']==0){
                $sql1="UPDATE `location` SET `is_avb`='1' WHERE `id`='".$uid."'";  
                $result=$conn->query($sql1);
                header("Location:admin.php?id=15");
            }
            elseif($row['is_avb']==1){
                $sql1="UPDATE `location` SET `is_avb`='0' WHERE `id`='".$uid."'";  
                $result=$conn->query($sql1);
                header("Location:admin.php?id=9");
            }
        }
    }
 $sql="UPDATE `location` SET `is_avb`='1' WHERE `id`='".$uid."'";
 }
function pending($m,$conn){
    $sql1="SELECT * from users Where `user_id`='".$m."' ";
    $result=$conn->query($sql1);
    if ($result->num_rows > 0) {
        while ($row= $result->fetch_assoc()) {
            if($row['boolean_status']==1){
                $sql2="UPDATE `users` SET `boolean_status`='0' WHERE  `user_id`='".$m."' ";
                $result=$conn->query($sql2);
                header("Location:admin.php?id=1");
            }
            else{
                $sql2="UPDATE `users` SET `boolean_status`='1' WHERE  `user_id`='".$m."' ";
                $result=$conn->query($sql2);
                header("Location:admin.php?id=1");
            }
      
        }
}
}
function ride2($a,$m,$conn){
    if($m==4){
        echo "<center><h2>User</h2><h3>Ride</h3></center><center>";
        $sql1="SELECT * from ride";
        $result=$conn->query($sql1);
        if ($result->num_rows > 0) {
        while ($row= $result->fetch_assoc()) {
        $a.='<td>'.$row['ride_id'].'</td>';
        $a.='<td>'.$row['ride_date'].'</td>';
        $a.='<td>'.$row['pickup'].'</td>';
        $a.='<td>'.$row['drop'].'</td>';
        $a.='<td>'.$row['total_distance'].'</td>';
        $a.='<td>'.$row['total_fare'].'</td>';
        $a.='<td>'.$row['laugage'].'</td>';
        $a.='<td>'.$row['status'].'</td>';
        $a.='<td>'.$row['cab_type'].'</td>';
        $a.='<td><a href="update2.php?id='.$row['ride_id'].'" class="a11">Approve-Ride</a></td>';
        $a.='<td><a href="update3.php?id='.$row['ride_id'].'" class="a11">Cancel-Ride</a></td></tr>';
    }
    $a.='</table>';
    echo $a;
}
    }
if($m==5){
    echo "<center><h2>Approved</h2><h3>Ride</h3></center><center>";
   $sql1="SELECT * from ride Where `status`='active'";
   $result=$conn->query($sql1);
  if ($result->num_rows > 0) {
    while ($row= $result->fetch_assoc()) {
    $a.='<td>'.$row['ride_id'].'</td>';
    $a.='<td>'.$row['ride_date'].'</td>';
    $a.='<td>'.$row['pickup'].'</td>';
    $a.='<td>'.$row['drop'].'</td>';
    $a.='<td>'.$row['total_distance'].'</td>';
    $a.='<td>'.$row['total_fare'].'</td>';
    $a.='<td>'.$row['laugage'].'</td>';
    $a.='<td>'.$row['status'].'</td>';
    $a.='<td>'.$row['cab_type'].'</td></tr>';
    
}
$a.='</table>';
echo $a;
}
}
if($m==6){
    echo "<center><h2>Pending </h2><h3>Ride</h3></center><center>";
     $sql1="SELECT * from ride Where `status`='pending'";
    $result=$conn->query($sql1);
  if ($result->num_rows > 0) {
    while ($row= $result->fetch_assoc()) {
    $a.='<td>'.$row['ride_id'].'</td>';
    $a.='<td>'.$row['ride_date'].'</td>';
    $a.='<td>'.$row['pickup'].'</td>';
    $a.='<td>'.$row['drop'].'</td>';
    $a.='<td>'.$row['total_distance'].'</td>';
    $a.='<td>'.$row['total_fare'].'</td>';
    $a.='<td>'.$row['laugage'].'</td>';
    $a.='<td>'.$row['status'].'</td>';
    $a.='<td>'.$row['cab_type'].'</td>';

    $a.='<td><a href="update2.php?id='.$row['ride_id'].'" class="a11">Approve-Ride</a></td>';
    $a.='<td><a href="update3.php?id='.$row['ride_id'].'" class="a11">Cancel-Ride</a></td></tr>';
}
$a.='</table>';
echo $a;


}
}

if($m==7){
    echo "<center><h2>Cancel</h2><h3>Ride</h3></center><center>";
   $sql1="SELECT * from ride Where `status`='cancelled'";
    $result=$conn->query($sql1);
   if ($result->num_rows > 0) {
    while ($row= $result->fetch_assoc()) {
    $a.='<td>'.$row['ride_id'].'</td>';
    $a.='<td>'.$row['ride_date'].'</td>';
    $a.='<td>'.$row['pickup'].'</td>';
    $a.='<td>'.$row['drop'].'</td>';
    $a.='<td>'.$row['total_distance'].'</td>';
    $a.='<td>'.$row['total_fare'].'</td>';
    $a.='<td>'.$row['laugage'].'</td>';
    $a.='<td>'.$row['status'].'</td>';
    $a.='<td>'.$row['cab_type'].'</td></tr>';

}
$a.='</table>';
echo $a;
}
}

}
function ride3($a,$m,$conn){
    if($m==8 || $m==9){
        echo "<center><h2>Location</h2></center><center>";
       $sql1="SELECT * from `location` WHERE `is_avb`='1' ";
        $result=$conn->query($sql1);
        if ($result->num_rows > 0) {
            while ($row= $result->fetch_assoc()) {
                $a.='<td>'.$row['location_name'].'</td>';
                $a.='<td>'.$row['distance'].'</td>';
                $a.=($row['is_avb']==1)?('<td>Working-Location</td>'):('<td>Non-Working-Location</td>');
                $a.='<td><a href="update6.php?id='.$row['id'].'" class="b1">Disapprove</a></td></tr>';
        
        }
        $a.='</table>';
        echo $a;
        }

    }
    if($m==15){
        echo "<center><h2>Location</h2></center><center>";
       $sql1="SELECT * from `location` WHERE `is_avb`='0' ";
        $result=$conn->query($sql1);
        if ($result->num_rows > 0) {
            while ($row= $result->fetch_assoc()) {
                $a.='<td>'.$row['location_name'].'</td>';
                $a.='<td>'.$row['distance'].'</td>';
                $a.=($row['is_avb']==1)?('<td>Working-Location</td>'):('<td>Non-Working-Location</td>');
                $a.='<td><a href="update6.php?id='.$row['id'].'" class="b1">Approved</a></td></tr>';
       }
        $a.='</table>';
        echo $a;
        }

    }
    if($m==10){
     echo $a;
    if(isset($_POST['submit'])){
    $a= $_POST['distance'];
    $b=$_POST['location'];
   $sql="INSERT INTO `location` (`id`, `location_name`, `distance`, `is_avb`) VALUES (NULL, '".$b."', '".$a."', '0');";
    $result=$conn->query($sql);

    } 
}
}
function adm_Pass($conn){
       $count=0;
    //    echo $a;
    if(isset($_POST['submit'])){
        $a= $_POST['username'];
        $b=$_POST['opass']; 
        $c=$_POST['pass'];
        $sql1="SELECT * FROM users WHERE `boolean_admin`='0' AND `password`='".md5($b)."' ";
      
                $result=$conn->query($sql1);
                if ($result->num_rows == 0) {
                    echo "<h2>Old Password Mismatch</h2>";
                    $count++;
                }
                if($b==$c){
                                echo "<h2>Old password and New password should not be same</h2>";
                                $count++;  
                            }
               if($count==0){
                            $sql="UPDATE  `users`  SET  `password`= '".md5($c)."' WHERE  `username`='".$a."' AND   `password` = '".md5($b)."' "; 
                            $result=$conn->query($sql);
                           
        
                    return 1;
                          
                            }
}
}


function adm($a,$conn){
    if($a=='a'){
        $sql="SELECT COUNT(user_id) As Total from users";
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo '='.$row['Total'];
    }
    if($a=='b'){
        $sql="SELECT SUM(total_fare) As Total from ride";
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo '₹='.$row['Total'];
    }
    if($a=='c'){
        $sql="SELECT COUNT(user_id) As Total from users WHERE `boolean_status`='1' AND `boolean_admin`='1'";
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo '='. $row['Total'];
    }
    if($a=='d'){
        $sql="SELECT COUNT(user_id) As Total from users WHERE `boolean_status`='0' AND `boolean_admin`='1'";
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo '='.$row['Total'];
    }
    if($a=='e'){
        $sql="SELECT COUNT(total_fare) As Total from ride WHERE `status`='pending'";
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo '='. $row['Total'];
    }
    if($a=='f'){
        $sql="SELECT COUNT(total_fare) As Total from ride WHERE `status`='cancelled'";
        $result=$conn->query($sql);
        $row= $result->fetch_assoc();
         echo '='.$row['Total'];
    }
}
}
class location{
    function pickup($conn){
           $sql="SELECT * from `location` WHERE `is_avb`='1'";
              $result=$conn->query($sql);
              if ($result->num_rows > 0) {
              while ($row= $result->fetch_assoc()) {
              $a='<option value="'.$row['location_name'].'">'.$row['location_name'].'</option>';
              echo $a;
              }
            }
 }
    function array($conn){
        $cabby2=array();
        $sql="SELECT * FROM `location` WHERE `is_avb`='1'";
        $result=$conn->query($sql);
          if ($result->num_rows > 0) {
          while ($row= $result->fetch_assoc()) { 
              $cabby2[$row['location_name']]=$row['distance'];
          }
         return ( $cabby2);
        }
        }
    }
?>