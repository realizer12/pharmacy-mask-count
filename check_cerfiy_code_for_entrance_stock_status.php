<?php
session_start();

//msyql 연결
$conn = mysqli_connect("localhost","root","sssddd456852","practice");


$inserted_code=$_POST['code'];

$str=explode('show_mask.',$inserted_code);

//위 코드에서 분리된 uid
$uid=$str[1];

if($uid==null){//코드가 잘못되어서 uid부분이  아예 값이 안나오면  -1을 반환
   echo "-1";

}else{//코드 형식은 맞아서 uid를 잡았을때 계속  확인 진행

  $sql_for_check_code="SELECT password FROM pharmacyinfo WHERE pharmacy_uid='$uid'";
  $result_for_check_code=mysqli_query($conn,$sql_for_check_code);

  if($result_for_check_code){//쿼리 성공적으로 던짐

    $row_for_check_result=mysqli_fetch_array($result_for_check_code);
    
    if($row_for_check_result[0]==$inserted_code){
       
         echo "1";

         //성공했으므로  세션 
        
         $_SESSION['show_mask_uid'] = $uid;
        
    }else{

        echo "-3";
    }

  }else{

     echo "-2";//해당 sql 쿼리 과정중  문제 발생
  }

  
}

//mysql 닫음
mysqli_close($conn);
?>