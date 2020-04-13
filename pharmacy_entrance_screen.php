<?php session_start();?>

<html>

<head>
     
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">  
   <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="crossorigin="anonymous"></script>
   <style>
   @font-face { font-family: 'CookieRun-Regular'; src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2001@1.1/CookieRun-Regular.woff') format('woff'); font-weight: normal; font-style: normal; }

/* 입력 바 */
.search {
  width: 100%;
  min-width: 400px;
  margin-top: 63px;
  position: relative;
  
  text-align: center;
 
  z-index: 200;
  
}

.searchTerm {
  width: 80%;
  min-width: 300px;
  border: 3px solid rgb(5, 5, 5);
  border-right: none;
  padding: 5px;
  height: 100px;
  font-family: inherit;
  border-radius: 5px 0 0 5px;
  outline: none;
  color: #9DBFAF;
}

.searchTerm:focus{
  color: rgb(3, 3, 3);
}

.searchButton {
  width: 70px;
  height: 115px;
  border: 1px solid rgb(2, 1, 1);
  background: rgb(2, 1, 1);
  text-align: center;
  color: rgb(255, 255, 255);
  border-radius: 0 5px 5px 0;
  cursor: pointer;
  margin-left: -12px;
  font-size: 20px;
}
    </style>
<title>입장 코드 화면</title>

</head>
<body style="text-align: center; padding-top: 10%;">
<?php
    session_start();
        if(isset($_SESSION['show_mask_uid'])){
          //로그인 창을  실행하였을때  세션에  관리자 로그인 아이디 기록이 남아있따면,  바로  로그인후 페이지로 넘어가도록 진행하였다.
          echo '<script>location.href="pharmacy_stock_status.php";</script>';
          exit();

      }  ?>


<script>

$(document).ready(function(){

    //input에서 공간이  빌때 없애줌.
   // 공백 사용 못 하게
   function eliminate_empty(obj)  {             
            var str_space = /\s/;               // 공백 체크
            if(str_space.exec(obj.value)) 
            {     // 공백 체크
               
                obj.focus();
                obj.value = obj.value.replace(' ',''); // 공백제거
                return false;
            }
        }


     //클릭버튼 이벤트   
    $('#searchButton').click(function(){

    
    var written_code=$('#searchTerm').val();

        $.ajax({
          url: "check_cerfiy_code_for_entrance_stock_status.php",
          type: "post",
          dataType:"text",
          data: {"code":written_code},

        success : function(data, status, xhr) {
            
           if(data=="-1"){

             alert("인증 실패  -1");
             $('#searchTerm').val('');

           }else if(data=="-2"){
             
            alert("인증 실패  -2");
            $('#searchTerm').val('');

           }else if(data=="-3"){

            alert("인증 실패  -3");
            $('#searchTerm').val('');

           }else if(data=="1"){

            alert("인증 성공");
            window.location.href='pharmacy_stock_status.php';
           
           }
           
           

        },error: function(jqXHR, textStatus, errorThrown) { 

            alert('데이터 못가지고옴'+errorThrown);
        }

       });
       
    });//클릭버튼 이벤트 끝

});//$(document).ready(function() 끝


</script>    

<div id="title_of_screen" style=" width: 100%; height : 30px; " >
    
<p style="font-family: 'CookieRun-Regular';font-size: 4em;">입장 코드를 입력해주세요!!</p>   
<p style="font-family: 'CookieRun-Regular';font-size: 2em;">코드 입력시 빈칸이있는지 확인해주세요! (빈칸x)</p>   
<div class="search">
    <input type="text" onkeyup="eliminate_empty(this);"  onchange="eliminate_empty(this);" id="searchTerm" class="searchTerm" value="" style="text-align: center; font-family: 'CookieRun-Regular'; font-size: 40px;"  placeholder="가지고 있는 입장 코드를  넣어주세요!">
    <button id="searchButton" type="submit" class="searchButton">
     <i style="width: 200px;">클릭</i>
   </button>
 </div>

</div>




</body>

</html>