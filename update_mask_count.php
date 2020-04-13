<?php
date_default_timezone_set('Asia/Seoul');
// echo "현재 일시 : ". date("Y-m-d H:i:s")."<br/>";

//msyql 연결
$conn = mysqli_connect("localhost","root","sssddd456852","practice");

//약국 uid
$pharmacy_uid=$_POST['uid'];

//약국 대형 마스크 카운트
$updated_bigmaskcount=$_POST['updated_big'];

//약국 소형 마스크 카운트 
$updated_smallmaskcount=$_POST['updated_small'];

//마스크  소형 대형  구분  -> 0이면  대형,  1이면  소형
$bigorsmall=$_POST['bigorsmall'];

$date_time=date("Y-m-d H:i:s");

//대형마스크 업데이트 쿼리문
$sql_for_update_big_mask="UPDATE pharmacyinfo SET big_mask_count='$updated_bigmaskcount',update_datetime='$date_time'  WHERE pharmacy_uid='$pharmacy_uid'";

//소형 마스크 업데이트 쿼리문
$sql_for_update_small_mask="UPDATE pharmacyinfo SET small_mask_count='$updated_smallmaskcount', update_datetime='$date_time' WHERE pharmacy_uid='$pharmacy_uid'";


if($bigorsmall==0){//대형일떄

    //쿼리 보냄
    $result_for_sql_for_big=mysqli_query($conn,$sql_for_update_big_mask);
    
    if($result_for_sql_for_big){//대형 마스크 업데이트 성공시

        echo $updated_bigmaskcount;
        
    }else{//업데이트 실패시

        echo "-1";
    }

    
}else if($bigorsmall==1){//소형일때

   //쿼리 보냄
   $result_for_sql_for_small=mysqli_query($conn,$sql_for_update_small_mask);
    
   if($result_for_sql_for_small){//대형 마스크 업데이트 성공시

       echo $updated_smallmaskcount;
       
   }else{//업데이트 실패시

       echo "-1";
   }

}


//약국


?>

