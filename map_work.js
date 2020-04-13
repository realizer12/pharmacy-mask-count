     
    $(document).ready(function() {
        
        //맨처음엔  검색창 리셋
        $('#searchTerm').val('');


        //마커들  담길  어레이
        var markers = [];

        //약국 정보 들어갈 infowindow 어레이
        var infoWindows=[];

        //윈도우
        var $window = $(window);

        var json;//전체 약국  정보를 받을떄 사용. 
        

        // // //네이버 맵 -> 사이트 시작시 -> 오산 시청보이도록 함.
        var map = new naver.maps.Map('map', {
            center: new naver.maps.LatLng(37.15028,127.07750),
            zoom: 17,
            mapTypeId: naver.maps.MapTypeId.NORMAL
        });
          
        //1-1
        resize();


      $('#searchButton').click(function(){

          var search_keyword= $('#searchTerm').val();
          search_location(search_keyword);
         
      });   
      

      //검색창 클릭하고 엔터시 ->  실행
      $('#searchTerm').keypress(function(e){
        if (e.which == 13){
        var search_keyword= $('#searchTerm').val();
        search_location(search_keyword);
        
        }
        
      });

   

  
     
        //infowindow->  클릭시  안사라지는 이슈 해결 위해 c변수 값을 이용해
        //카운트혛식으로 지움. 
        var c=0;
        $('#map').click(function(){

            c++;
            if(c>=2){
               
                infoWindow.close();
                
                c=0;
            }

            
        });

        $('#searchTerm').click(function(){

            $('#searchTerm').focus();
              

        });


     function search_location(search_keyword){
             
            // 장소 검색 객체를 생성합니다
             var ps = new kakao.maps.services.Places(); 

           // 키워드로 장소를 검색합니다
           ps.keywordSearch(search_keyword, placesSearchCB); 
   
           // 키워드 검색 완료 시 호출되는 콜백함수 입니다
           function placesSearchCB (data, status, pagination) {
           
             if (status === kakao.maps.services.Status.OK) {

                //검색 성공시에 모바일에서 방해되는  커서 focus 기능  blur처리
                $('#searchTerm').blur();  


                // var  mapOptions  = {
                //     useStyleMap: true,
                //     center: new naver.maps.LatLng(data[0].y, data[0].x),
                //     zoom: 17,
                //     mapTypeId: naver.maps.MapTypeId.NORMAL
                // };
        
                //  //맵  새로 지정함.
                // map = new naver.maps.Map('map', mapOptions);  
                
                var new_location= new naver.maps.LatLng(data[0].y, data[0].x);
         
                map.setCenter(new_location);


                //set_marker(data[0].y, data[0].x,map)
                for(var i=0; i<json.length; i++){

                    make_marker(json[i]);
                    //make_marker(json[i].latitude,json[i].longitude);
                }

                //클릭시 약국 정보를 보여줌. 
                show_pharmacy_info();

                naver.maps.Event.addListener(map, 'zoom_changed', function() {
                    updateMarkers(map, markers);
                });
        
                naver.maps.Event.addListener(map, 'dragend', function() {
                    updateMarkers(map, markers);
        
                });

              
            } else{
                
                $('#searchTerm').val('');   
            }
          }

     }


     //클릭시 약국 정보를  보여줌.
     function show_pharmacy_info(){

        

        for (var i=0, ii=markers.length; i<ii; i++) {
            naver.maps.Event.addListener(markers[i], 'click', getClickHandler(markers[i],infoWindows[i]));

           
        }
       
        
     }

     
     

        //서버에서  new_pharmacy.json에서  해당  약국  값들을 가지고옴.
        $.getJSON('seprate.json', function(data) {
            
            // var myJSON = JSON.stringify(data);
            json=data;
            $(data).each(function(){

                //이 경우의 this는 mData라는 객체를 의미한다. 					
            
                 
                make_marker(this);
               
                

            });

            //클릭시 약국 정보를 보여줌. 
            show_pharmacy_info();
            
        });
           
        
    

        naver.maps.Event.addListener(map, 'zoom_changed', function() {
            updateMarkers(map, markers);
        });

        naver.maps.Event.addListener(map, 'dragend', function() {
            updateMarkers(map, markers);

        });
        
        function updateMarkers(map, markers) {
        
            var mapBounds = map.getBounds();
            var marker, position;
        
            for (var i = 0; i < markers.length; i++) {
        
                marker = markers[i]
                position = marker.getPosition();
        
                if (mapBounds.hasLatLng(position)) {
                    showMarker(map, marker);
                } else {
                    hideMarker(map, marker);
                }


            }
        }
        
        function showMarker(map, marker) {
        
            if (marker.getMap()) return;
            marker.setMap(map);
        }
        
        function hideMarker(map, marker) {
        
            if (!marker.getMap()) return;
            marker.setMap(null);
        }
           

      
        //반응형에  적용되게 ->  사이즈 리사이징  1-1
        function resize(){

            $window.on('resize', function() {
          
          
                map.setSize(getMapSize());
            
            });
             
            map.setSize(getMapSize());

        }

    
        //맵사이즈 리사이징을 위해 윈도우  넓이 및   height를  받아옴
        function getMapSize() {
         
            var size = new naver.maps.Size($window.width() , $window.height());
 
              return size;
            };
 
         

       //내 위치 보기   함수 (1파라미터=위도,  2파라미터 경도)
       function my_location(latitude,longitude){
       
    
        // var  mapOptions  = {
        //     useStyleMap: true,
        //     center: new naver.maps.LatLng(latitude, longitude),
        //     zoom: 17,
        //     mapTypeId: naver.maps.MapTypeId.NORMAL
        // };

         //맵  새로 지정함.
         //map = new naver.maps.Map('map', mapOptions);  
          
         var new_location= new naver.maps.LatLng(latitude, longitude);
         
         map.setCenter(new_location);

         
         //내위치보기 이므로,  내가 검색했던 기록이 있으면 지워줌.
         $('#searchTerm').val('');
         
         

        for(var i=0; i<json.length; i++){
            make_marker(json[i]);
           // make_marker(json[i].latitude,json[i].longitude);
        }
        
        //클릭시 약국 정보를 보여줌. 
        show_pharmacy_info();

        naver.maps.Event.addListener(map, 'zoom_changed', function() {
            updateMarkers(map, markers);
        });

        naver.maps.Event.addListener(map, 'dragend', function() {
            updateMarkers(map, markers);

        });

       }//my_location()끝


       
       //내 주변 약국 보기  버튼 클릭시 이벤트 
      $('#show_my_location_btn').click(function(){
         
        
          //위치 정보를 얻기
          navigator.geolocation.getCurrentPosition (function(pos) {

          var latitude = pos.coords.latitude;//위도
          var longitude = pos.coords.longitude;//경도

          my_location(latitude,longitude);


         });

       });//내주변 약국 보기 버튼 클릭끝


    
       var  infoWindow;
     
       //마커 만들기
     function make_marker(data){

        var marker =new naver.maps.Marker({
        
        position: new naver.maps.LatLng(data.latitude, data.longitude),
        
        map: map,

        icon: {
            url: './show_mask_count_album/markeimg_for_pharmacy.png',
            size: new naver.maps.Size(25, 34),
            origin: new naver.maps.Point(0, 0),
            anchor: new naver.maps.Point(11, 35)
         }

        });
          

         markers.push(marker);
             
         infoWindows.push(data); 
        
    }//make_marker() 끝     
    
    
    function data1(data){

        

        $.ajax({
          url: "get_mask_status_from_server.php",
          type: "post",
          dataType:"json",
          data: {"pharmacy_uid":data.market_uid},

        success : function(data, status, xhr) {
            
            $big_mask_count=data.big;
            $small_mask_count=data.small;
            $update_date=data.update;

            if($update_date==null){

                $update_date="업데이트가 아직 안되었습니다";
            }
           // alert(JSON.stringify(data.big));

            $("#ddd").append("<font style='font-size:14x; color:red; font-weight: bold;'>마스크 재고 현황</font></br></br>대형 마스크: <font style='font-size:14x; color:red; font-weight: bold;'>"+$big_mask_count+"</font>개</br></br>소형 마스크: <font style='font-size:14x; color:red; font-weight: bold;'>"+$small_mask_count+"</font>개</br></br>업데이트 된 시간: <font style='color:red';>"+$update_date+"</font>");
           

        },error: function(jqXHR, textStatus, errorThrown) { 

            alert('데이터 못가지고옴'+errorThrown);
         }
        });

       
        var contentString = [

            '<div id="ddd" class="iw_inner" style=" background-color: rgb(255, 255, 255);  width:300px; height:280px; font-size:13px; border:2px solid rgb(5, 5, 5); padding:2px; text-align:left; font-family: CookieRun-Regular;">',
            '약국 이름: '+data.pharmacy_name+'</br></br>',
            '약국 위치: '+data.address+'</br></br>',
            '약국 연락처: <a href=tel:'+data.phone_number+'>'+data.phone_number+'</a></br></br>',
            '</div>'
        ].join('');

      
        return contentString;
   
    }
   
 
    
    




// 해당 마커의 인덱스를 seq라는 클로저 변수로 저장하는 이벤트 핸들러를 반환합니다.
function getClickHandler(marker,data) {
    
    
   return function(e) {
       
  
    infoWindow = new naver.maps.InfoWindow({
        content: data1(data)
    });

       
        if (infoWindow.getMap()) {

            infoWindow.close();
           

        } else {

            infoWindow.open(map, marker);
           
        }
        
       //검색 성공시에 모바일에서 방해되는  커서 focus 기능  blur처리
       $('#searchTerm').blur();
    
    }

    
}






    });//$(document).ready(function()) 끝





