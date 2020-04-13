<?php

//mysql 연결 
$conn = mysqli_connect("localhost","root","sssddd456852","practice");

//약국 uid
$pharmacy_uid=$_POST['pharmacy_uid'];

$get_mask_count_sql="SELECT big_mask_count,small_mask_count,update_datetime FROM pharmacyinfo WHERE pharmacy_uid='$pharmacy_uid'";

$result=mysqli_query($conn,$get_mask_count_sql);

$row_for_get_result=mysqli_fetch_array($result);


$get_data_result=array("big"=> $row_for_get_result[0],"small"=>$row_for_get_result[1],"update"=>$row_for_get_result[2]);//대형  소형  업데이트 시간이 담길 어레이


  //위에서 룸 데이터  jsonarray화 해서 보냄.
  echo json_encode($get_data_result);

mysqli_close($conn);
?>