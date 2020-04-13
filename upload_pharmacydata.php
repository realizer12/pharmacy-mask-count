<?php

$conn = mysqli_connect("localhost","root","sssddd456852","practice");


$read_pharmacyfile=json_decode(file_get_contents('seprate.json'),true);




for($i=0; $i<sizeof($read_pharmacyfile); $i++){


   $uid=$read_pharmacyfile[$i]['market_uid'];//각 약국 uid
   $name=$read_pharmacyfile[$i]['pharmacy_name'];//각  약국이름 .
    
    
   $sql="INSERT INTO pharmacyinfo (pharmacy_uid,pharmacy_name,password,big_mask_count,small_mask_count) VALUES ('$uid','$name','-1','0','0')";
   $result= mysqli_query($conn,$sql);

   if($result){
     
     echo "성공";
     echo "</br>";

   }else{

     echo "실패";
     echo "</br>";

   }
}



mysqli_close($conn);

?>