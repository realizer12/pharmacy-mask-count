<?php


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

//대형마스크 업데이트 쿼리문
$sql_for_update_big_mask="SELECT big_mask_count FROM pharmacyinfo WHERE pharmacy_uid='$pharmacy_uid'";

//소형 마스크 업데이트 쿼리문
$sql_for_update_small_mask="SELECT small_mask_count FROM pharmacyinfo WHERE pharmacy_uid='$pharmacy_uid'";


if($bigorsmall==0){//대형일때

 $result_for_check_bigmask=mysqli_query($conn,$sql_for_update_big_mask);
 
 if($result_for_check_bigmask){

  $row_for_check_bigmask=mysqli_fetch_array($result_for_check_bigmask);

  if($row_for_check_bigmask[0]==$updated_bigmaskcount){

    echo "1";//같을때  1
  }else{

    echo "0";//틀릴때  0
  }

 }else{


     echo "3";//대형 마스크  개수 조회 쿼리 에러   
 }




}else if($bigorsmall==1){//소형일때

    $result_for_check_smallmask=mysqli_query($conn,$sql_for_update_small_mask);
 
    if($result_for_check_smallmask){
   
     $row_for_check_samllmask=mysqli_fetch_array($result_for_check_smallmask);
   
     if($row_for_check_samllmask[0]==$updated_smallmaskcount){
   
       echo "1";//같을때  1
     }else{
   
       echo "0";//틀릴때  0
     }
   
    }else{
   
   
        echo "3";//소형 마스크  개수 조회 쿼리 에러   
    }
   

}



//mysql 닫음
mysqli_close($conn);

?>