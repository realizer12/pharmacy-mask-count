<?php session_start();?>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">  
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="crossorigin="anonymous"></script>
<title>약국 재고 현황 입력폼</title>
<style>
 @font-face { font-family: 'CookieRun-Regular'; src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2001@1.1/CookieRun-Regular.woff') format('woff'); font-weight: normal; font-style: normal; }

 .search {
  width: 100%;
  min-width: 400px;
  
}

.searchTerm {
  width: 50%;
  min-width: 50px;
  border: 3px solid rgb(5, 5, 5);
  
  text-align: center;
  font-size: 30px;
  height: 80px;
  
  font-family: 'CookieRun-Regular';
  border-radius: 5px 0 0 5px;
  
  color: white;
}

.searchTerm:focus{
  color: rgb(3, 3, 3);
}

.searchButton {
  width: 100px;
  height: 50px;
  font-family: 'CookieRun-Regular';
  background: rgb(2, 1, 1);
  margin-left: 40px;
  color: rgb(255, 255, 255);
  
  cursor: pointer;
  font-size: 15px;
}

.plus_btn{
   margin-top: 10px;
   font-family: 'CookieRun-Regular';
   width: 70px;
   height: 70px;
   text-align: center;
   font-size: 20px;
   color: white;
   font-size: 30px;
   background-color: black;

}
.minus_btn{
    margin-top: 10px;
    font-family: 'CookieRun-Regular';
    text-align: center;
    width: 70px;
    height: 70px;
    font-size: 30px;
    color: white;
    background-color: black;
}
#back_to_code_input_screen{
    
    font-family: 'CookieRun-Regular';
    text-align: center;
    width: 220px;
    height: 50px;
    font-size: 15px;
    color: white;
    margin-top: 20px;
    background-color: black;

}

</style>
</head>

<body style="text-align: center;">
<?php
  
   
   //mysql 연결 
   $conn = mysqli_connect("localhost","root","sssddd456852","practice");

      if(!isset($_SESSION['show_mask_uid'])){

        echo '<script>alert("코드입력후 이용가능합니다.!");</script>';
        //로그인이 안되어 게시판 페이리ㅗ 들어가지 못하는 경우  접속을 시작한 부분으로 다시 돌아간다.
        echo "<script>window.location.href='pharmacy_entrance_screen.php';</script>";
        exit();
        
    }else{

      //세션에 저장된  uid받음. 
      $pharmacy_uid=$_SESSION['show_mask_uid'];

      $get_mask_count_sql="SELECT pharmacy_name,big_mask_count,small_mask_count FROM pharmacyinfo WHERE pharmacy_uid='$pharmacy_uid'";
      
      $result=mysqli_query($conn,$get_mask_count_sql);
      $row_for_get_result=mysqli_fetch_array($result);
      
      $market_name=$row_for_get_result[0];//약국 이름
      $big_count=$row_for_get_result[1];//현재 큰 마스크 개수
      $small_count=$row_for_get_result[2];//현재 작은 마스크 개수  


      mysqli_close($conn);

    }

?>

<script>
     
     var pharmacyuid='<?php echo "$pharmacy_uid" ?>';//약국 uid

     $(document).ready(function(){

     $('#marketname').html('<?php echo "$market_name";?>');
     $('#countTerm_for_big').val('<?php echo "$big_count";?>');
     $('#countTerm_for_small').val('<?php echo "$small_count";?>'); 


     //맨처음 페이지 시작시 상태  보여줌
     check_big_mask_update_status();
     check_small_mask_update_status();


     $('#plusbtn_for_big').click(function(){
      
        var presentvalue=$('#countTerm_for_big').val();
        var plusvalue=Number( presentvalue )+1;
        
        
        if(plusvalue<0){

            alert('재고가  0보다 작을수 없습니다!');
       
        }else{
        
        $('#countTerm_for_big').val(plusvalue); 
        check_big_mask_update_status();
       }
      

     });


     $('#minusbtn_for_big').click(function(){
      
      var presentvalue=$('#countTerm_for_big').val();
      var minusvalue=Number( presentvalue )-1;
      
      if(minusvalue<0){
          
            alert('재고가  0보다 작을수 없습니다!');

      }else{

        $('#countTerm_for_big').val(minusvalue); 
        check_big_mask_update_status();
      }
      
   });


   $('#plusbtn_for_small').click(function(){
      
      var presentvalue=$('#countTerm_for_small').val();
      var plusvalue=Number( presentvalue )+1;
      
      
      if(plusvalue<0){

          alert('재고가  0보다 작을수 없습니다!');
     
      }else{
      
      $('#countTerm_for_small').val(plusvalue); 
      check_small_mask_update_status();
     }
   

   });


   $('#minusbtn_for_small').click(function(){

   
    var presentvalue=$('#countTerm_for_small').val();
    var minusvalue=Number( presentvalue )-1;
    
    if(minusvalue<0){
        
          alert('재고가  0보다 작을수 없습니다!');

    }else{

      $('#countTerm_for_small').val(minusvalue); 
      check_small_mask_update_status();
    }
    
     });
 
   

   //큰마스크 재고 입력  업데이트 버튼
   //빅=1 스몰=0  
   $('#update_big_mask_count').click(function(){
   
    
     $.ajax({
          url: "update_mask_count.php",
          type: "post",
          dataType:"text",
          data: {"uid":pharmacyuid,"updated_big":$('#countTerm_for_big').val(),"bigorsmall":0},

        success : function(data, status, xhr) {
              
          if(data != -1){//성공
          $('#countTerm_for_big').val(data);
          
          //업데이트 여부 체크
          check_big_mask_update_status();
     
          }else if(data==-1){//실패
            
             alert('업데이트중 문제발생 -1');

          }

        },error: function(jqXHR, textStatus, errorThrown) { 

            alert('업데이트 실패함'+errorThrown);
        }

       });
   });//큰마스크 재고 업데이트 버튼


   //작은 마스크 재고 입력  업데이트 버튼
   $('#update_small_mask_count').click(function(){
      
    $.ajax({
          url: "update_mask_count.php",
          type: "post",
          dataType:"text",
          data: {"uid":pharmacyuid,"updated_small":$('#countTerm_for_small').val(),"bigorsmall":1},

        success : function(data, status, xhr) {
            
          if(data != -1){//성공
       
            $('#countTerm_for_small').val(data);
       
            //업데이트 여부 체크
            check_small_mask_update_status();
          
          }else if(data==-1){//실패
            
             alert('업데이트중 문제발생 -1');

          }

        },error: function(jqXHR, textStatus, errorThrown) { 

            alert('업데이트 실패함'+errorThrown);
        }

       });

   }); //작은 마스크 재고 업데이트 버튼

  

   $('#countTerm_for_big').keyup(function(){
    
    var words=$('#countTerm_for_big').val();

      if(words !=''){//무언가 적혀있을떄
       
        $.ajax({
          url: "check_update_stock_status.php",
          type: "post",
          dataType:"text",
          data: {"uid":pharmacyuid,"updated_big":words,"bigorsmall":0},

        success : function(data, status, xhr) {
          
          if(data==0){//틀릴때 -> 즉  업데이트 안된 개수일때
            $('#countTerm_for_big').css("background-color","red");

          }else if(data==1){//같을때 -> 즉  업데이트 된 개수일때
            $('#countTerm_for_big').css("background-color","green");

            
          }else if(data==3){//조회하는 쿼리에서 에러

            $('#countTerm_for_big').css("background-color","orange");
          }
          

        },error: function(jqXHR, textStatus, errorThrown) { 
          alert('업데이트 여부 확인중 에러남');
           
        }

       });//ajax끝
      }

   });

  
  
 

   $('#countTerm_for_small').keyup(function(){
    
    var words=$('#countTerm_for_small').val();

      if(words !=''){//무언가 적혀있을떄
       
        $.ajax({
          url: "check_update_stock_status.php",
          type: "post",
          dataType:"text",
          data: {"uid":pharmacyuid,"updated_small":words,"bigorsmall":1},

        success : function(data, status, xhr) {
          
          if(data==0){//틀릴때 -> 즉  업데이트 안된 개수일때
            $('#countTerm_for_small').css("background-color","red");

          }else if(data==1){//같을때 -> 즉  업데이트 된 개수일때
            $('#countTerm_for_small').css("background-color","green");

            
          }else if(data==3){//조회하는 쿼리에서 에러

            $('#countTerm_for_small').css("background-color","orange");
          }
          

        },error: function(jqXHR, textStatus, errorThrown) { 
          alert('업데이트 여부 확인중 에러남');
           
        }

       });//ajax끝

      }
   });
  

   function check_big_mask_update_status(){

    var words=$('#countTerm_for_big').val();

     if(words !=''){//무언가 적혀있을떄
 
    $.ajax({
      url: "check_update_stock_status.php",
    type: "post",
    dataType:"text",
    data: {"uid":pharmacyuid,"updated_big":words,"bigorsmall":0},

     success : function(data, status, xhr) {
    
      if(data==0){//틀릴때 -> 즉  업데이트 안된 개수일때
        $('#countTerm_for_big').css("background-color","red");

      }else if(data==1){//같을때 -> 즉  업데이트 된 개수일때
        $('#countTerm_for_big').css("background-color","green");

      
       }else if(data==3){//조회하는 쿼리에서 에러

       $('#countTerm_for_big').css("background-color","orange");
      }
    

  },error: function(jqXHR, textStatus, errorThrown) { 
    alert('업데이트 여부 확인중 에러남');
     
  }

 });//ajax끝

}



   }//check_big-mask_update_status()끝



  //키 감지가 아닌  버튼 클릭이나,  업데이트 버튼  새로 시작할때  진행되는  update체크 -소형
  function check_small_mask_update_status(){
  
    var words=$('#countTerm_for_small').val();

      if(words !=''){//무언가 적혀있을떄
       
        $.ajax({
          url: "check_update_stock_status.php",
          type: "post",
          dataType:"text",
          data: {"uid":pharmacyuid,"updated_small":words,"bigorsmall":1},

        success : function(data, status, xhr) {
          
          if(data==0){//틀릴때 -> 즉  업데이트 안된 개수일때
            $('#countTerm_for_small').css("background-color","red");

          }else if(data==1){//같을때 -> 즉  업데이트 된 개수일때
            $('#countTerm_for_small').css("background-color","green");

            
          }else if(data==3){//조회하는 쿼리에서 에러

            $('#countTerm_for_small').css("background-color","orange");
          }
          

        },error: function(jqXHR, textStatus, errorThrown) { 
          alert('업데이트 여부 확인중 에러남');
           
        }

       });//ajax끝

      }
  
    }//check_small_mask_update_status()끝

   
    


   });//jquery 끝

</script>
    
    <div style="font-size: 3em; margin-top: 10px; margin-bottom: 70px; font-family: 'CookieRun-Regular'">
        <div style="margin-top: 50px; font-size: 20px; color:red;">
         읽어 주세요!! 
      </br>초록색이면  해당 수량으로  업데이트된  상태이며,  빨간색일 경우 업데이트 버튼을 눌러야지 해당 수량으로 업데이트 됩니다 </br>
         </div>
         <p id="marketname"style="margin-right: 30px; margin-top:20px">ㅇㅇㅇ 약국</p> 
     </div>
    
    <div style="font-size: 2em; margin-bottom: 100px;  font-family: 'CookieRun-Regular'">
        <p style="margin-bottom: 3px;">대형 마스크 재고 입력</p>
        <div class="search">
            <input type="text" id="countTerm_for_big" class="searchTerm" value="" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"  placeholder="0">
           <div id="btn_cover" >
            <button class="plus_btn" id="plusbtn_for_big">+</button> 
            <button class="minus_btn" id="minusbtn_for_big">-</button>  
            <button id="update_big_mask_count" type="submit" class="searchButton">
            업데이트
           </button>
        </div>
         </div>
    
    </div>

    <div style="font-size: 2em;  font-family: 'CookieRun-Regular'">
        <p style="margin-bottom: 3px;">소형 마스크 재고 입력</p>
        <div class="search">
            <input type="text" id="countTerm_for_small" class="searchTerm" value=""  onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="0">
           <div id="btn_cover" >
            <button class="plus_btn" id="plusbtn_for_small">+</button> 
            <button class="minus_btn" id="minusbtn_for_small">-</button>  
            <button id="update_small_mask_count" type="submit" class="searchButton">
            업데이트
           </button>
        </div>
         </div>

         <div style="margin-top: 50px; font-size: 20px;">
          위  대형 마스크 와  소형 마스크 밑에   + , - 버튼을 누르면  1씩 숫자가 바뀌며</br>
          숫자  입력창을  누르면  원하는 수량을  쓰실수 있습니다.!!</br>
          현재 남은 마스크 수량을 쓰고  업데이트 버튼을 눌러주세요!</br></br></br>

         </div>
    
    </div>



     
</body>


</html>