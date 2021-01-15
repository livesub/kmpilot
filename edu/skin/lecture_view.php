<?php
include_once('./_common.php');

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$idx = $_GET['idx'];
$table = $_GET['type'];
$lecture_type_table = $g5[$table."_table"];

$now_date = date("Y-m-d");

//먼저 이 강의를 신청 했는지 체크
$row_chk = sql_fetch(" select * from {$g5['pilot_lecture_apply_table']} where lecture_idx='{$idx}' and mb_id='{$member['mb_id']}' and lecture_type_table='{$_GET['type']}' ");

if(count($row_chk) == 0){
    alert($lang['fatal_err'], G5_URL);
    exit;
}

//강의를 불러옴
$row = sql_fetch_array(sql_query(" select * from {$lecture_type_table} where idx='{$idx}' "));

//강의 날짜 비교
if($now_date < $row['startdatetime']){
    alert($row['subject'].$lang['lecture_no_start']);
    goto_url(G5_BBS_URL."/content.php?co_id=pilot_license_renewal_list");
    exit;
}

if($now_date > $row['enddatetime']){
    alert($row['subject'].$lang['lecture_no_end']);
    goto_url(G5_BBS_URL."/content.php?co_id=pilot_license_renewal_list");
    exit;
}

$ajaxpage_view_save = G5_URL."/edu_process/lecture_view_save_ajax.php";

$youtube_id = youtube_url($row['youtube']);
?>

<div id="player"></div>
    <div id="total_play_time"></div>
    <div id="time_remaining"></div>
    <div id="lecture_complet"></div>


    <script>
        // 2. This code loads the IFrame Player API code asynchronously.
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // 3. This function creates an <iframe> (and YouTube player)
        //    after the API code downloads.

        //https://youtu.be/YOZavfOgRu4
        var player;
        function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
                height: '360',
                width: '640',
                videoId: '<?=$youtube_id?>',
                playerVars : {
                    'controls': 1, //플레이어 컨드롤러 표시여부
                    'rel': 0, //연관동영상 표시여부 0 이면 표시 않함 - 2018년 10 월에 rel = 0 지원을 종료

                    'playsinline': 1, //ios환경에서 전체화면으로 재생하지 않게하는 옵션
                    'autoplay': 1, //자동재생 여부(모바일에서 작동하지 않습니다. mute설정을 하면 작동합니다.) - ** 익스,파폭에선 자동 재생, 크롬 자동 재생 안됨
                    'loop': 0,

                    //이 매개변수를 통해 YouTube 로고를 표시하지 않는 YouTube 플레이어를 사용할 수 있습니다.
                    //매개변수 갑ㅅ을 1로 설정하면 YouTube 로고가 컨트롤바에 표시되지 않습니다.
                    //하지만 사용자가 마우스 포인터를 플레이어 위에 올려놓으면 작은 YouTube 텍스트 라벨이 일시중지된 동영상의 오른쪽 상단에 표시됩니다
                    'modestbranding' : 1,

                    //'mute' : false, //true 로 하면 자동 재생 되지만 소리안나고  false 로 하면 자동 재생 안되지만 소리는 난다.
                    'disablekb' : 1 //키보드 방향키 막기
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        var total_playTime = 0; //총플레이 시간
        var value = 0;
        var see_time = 0;  //보던 시간 대 값

        // 4. The API will call this function when the video player is ready.
        function onPlayerReady(event) {
            //player.seekTo(see_time,true); //보던 시간 대로 이동 시키기
            event.target.playVideo();
            total_playTime = Math.ceil(player.getDuration());   //.동영상 총 시간
            document.getElementById('total_play_time').innerHTML = "총 플레이 타임 : <span style='font-size:20px;color:blue;'>"+convert(total_playTime)+"</span> 초";

<?php
    //이미 본 강의 이면 표시 해줌
    if($row_chk['lecture_completion_status'] == 'Y'){
?>
            document.getElementById('lecture_complet').innerHTML = "<?=$row['subject']?> <?=$lang['lecture_complete']?>";
<?php
    }
?>
            //console.log("total_playTime===> "+total_playTime);
        }

        // 5. The API calls this function when the player's state changes.
        //    The function indicates that when playing a video (state=1),
        //    the player should play for six seconds and then stop.
        var done = false;

        //var startdate = 0;
        //var pause_sum = 0;
        //var start_movie = 0; //동영상 재생 시작시간
        var tmp_time = 0;   //일시 정지시 시간을 간직 하는 임시 변수
        var tmp_flag = 0;   //처음 로딩시인지 일시 정지 후 다시 시작인지 체크 변수
        var reset = 0;  //reset
        function onPlayerStateChange(event) {
            //console.log("StateChange===> "+event.data);

            if(event.data == 1){    //동영상 재생
                //console.log("*** play_start ***");
                //startdate = new Date();
                //start_movie = new Date();

                //보던 시간대가 있으면 보던때 부터 카운트 처음 보면 동영상 전체 시간을 넣음
                if(tmp_flag == 0){  //일시 정지후 다시 보는 것인지 파악 => 일시 정지 아님
                    if(see_time == 0) see_time = total_playTime;
                    else see_time = total_playTime - see_time;
                }else{  //일시 정지후 다시봄
                    see_time = tmp_time;
                }

                tmp_flag = 0;
                printClock(see_time,tmp_flag);
            }

            if(event.data == 2){    //동영상 일시정지
                //var enddate = new Date();
                //var vtime = enddate.getTime() - startdate.getTime();
                //var vsec = Math.round(vtime/1000);
                //console.log("sec===> "+vsec);
                //pause_sum = pause_sum + vsec;
                //console.log("pause_sum===> "+pause_sum);
                //var movie_ing = Math.round(player.getCurrentTime()); //동영상이 멈춘 시간 값
                //console.log("movie_ing===> "+movie_ing);
                tmp_flag = 1;   //일시 정지 후 다시 시작인지 체크 변수
                see_time = tmp_time;
                //console.log("see_time===> "+see_time);
                printClock(see_time,tmp_flag); //일시 정지 했던 시간 부터 다시 흐르게...
                //일시 정지가 일어 나면 TABLE 에 내용 저장 하는 로직
                //alert("see_time====> "+see_time);
            }

            if(event.data == 0){    //동영상 재생 완료
                setTimeout(stopVideo, 0);   //동영상이 완료 되면 처음 화면으로 되돌린다
                clearTimeout(reset);
//alert("끝났어 저장 콜??");
                //var end_movie = new Date();  //동영상 끝까지 본 시간
                //var tot_movie = Math.round((end_movie.getTime() - start_movie.getTime())/1000); //총 동영상 재생시간
                //console.log("tot_movie===> "+tot_movie);
                see_time = 0;
                done = true;
            }
        }

        function stopVideo() {
            player.stopVideo();
        }

        function printClock(value,tmp_flag){
            value = value - 1;
            tmp_time = value;   //초값이 사라짐 임시 변수에 저장
            //console.log("tmp_time===> "+tmp_time);

            if(tmp_flag == 0){
                if(tmp_time != 0) {
                    reset = setTimeout("printClock(tmp_time,tmp_flag);", 1000);    // 1초마다 printClock() 함수 호출
                }
            }

            if(tmp_time >= 20){ //20초 경과후 빨간색 표현
                document.getElementById('time_remaining').innerHTML = "남은 시간 : <span style='font-size:20px;color:blue;'>"+convert(tmp_time)+"</span> 초";
            }else{
                document.getElementById('time_remaining').innerHTML = "남은 시간 : <span style='font-size:20px;color:red;'>"+convert(tmp_time)+"</span> 초";
            }
            //document.getElementById('time_remaining').innerHTML = "남은 시간 : <span style='font-size:20px;color:blue;'>"+tmp_time+"</span> 초";
            //영상 본시간
            var total_see = 0;
            total_see = total_playTime - tmp_time;

<?php
    if($row_chk['lecture_completion_status'] == 'N'){
        //강좌 보기를 완료 했을시 다시 저장 하지 않음
?>
            //동영상 총 시간과 영상 본시간이 같으면 디비에 저장
            if(total_playTime == total_see){
                //clearTimeout(reset);
                //alert("저장!!!!");
                view_info_save('<?=$idx?>','<?=$table?>');   //디비에 저장 하는 함수 호출
            }
            console.log("total_see===> "+total_see);
<?php
    }
?>


        }

        function convert(seconds) {
        var hour = parseInt(seconds/3600);
        var min = parseInt((seconds % 3600) / 60);
        var sec = seconds % 60;

        if(hour < 10) hour = "0" + hour;
        if(min < 10) min = "0" + min;
        if(sec < 10) sec = "0" + sec;

        var convert = hour+":"+min+":" + sec;
        return convert;
    }
</script>

<script>
    function view_info_save(idx,type){

        var ajaxUrl = "<?=$ajaxpage_view_save?>";
            $.ajax({
                type		: "POST",
                dataType    : "text",
                url			: ajaxUrl,
                data		: {
                    "idx"   : idx,
                    "type"   : type,
                },
                success: function(data){
alert(data);
return;
                    if(trim(data) == "no_member"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "fail"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "OK"){
                        document.getElementById('lecture_complet').innerHTML = "<?=$row['subject']?> <?=$lang['lecture_complete']?>";
                        //location.reload();
                    }
                },
                error: function () {
                        console.log('error');
                }
            });
    }
</script>