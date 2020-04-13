<?php

$conn = mysqli_connect("localhost","root","sssddd456852","practice");



for($i=1; $i<346; $i++){

  $ss=GenerateString(5)."show_mask.$i";
  $sql="UPDATE pharmacyinfo SET password='$ss' WHERE pharmacy_uid='$i'";
  
  $result=mysqli_query($conn,$sql);

  if($result){

    echo"성공";
  }else{

    echo "실패";

  }
    
}






// $str=explode('show_me_mask',$ss);
// echo "</br>";
// echo $str[0]."첫벗째   ".$str[1]."두번째";


function GenerateString($length)  
{  
    $characters  = "0123456789";  
    $characters .= "abcdefghijklmnopqrstuvwxyz";  
    $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
    $characters .= "_";  
      
    $string_generated = "";  
      
    $nmr_loops = $length;  
    while ($nmr_loops--)  
    {  
        $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];  
    }  
      
    return $string_generated;  
}  



?>