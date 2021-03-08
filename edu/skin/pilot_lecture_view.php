<?php
include_once('./_common.php');

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$edu_type = $_POST['edu_type'];
$edu_idx = $_POST['edu_idx'];
$apply_idx = $_POST['apply_idx'];
$lecture_idx = $_POST['lecture_idx'];
$now_date = date("Y-m-d");

if($edu_type == "" || $edu_idx == "" || $apply_idx == "" || $lecture_idx == ""){
    alert($lang['fatal_err'],"content.php?co_id=pilot_edu_list");
    exit;
}

//신청자인지 판단
$row_cnt = sql_fetch(" select count(*) as apply_cnt from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' and apply_idx = '{$apply_idx}' and apply_cancel = 'N' ");
$apply_count = $row_cnt['apply_cnt'];

if($apply_count == 0){
    alert($lang['fatal_err'],"content.php?co_id=pilot_edu_list");
    exit;
}

//교육 불러옴
$row_edu = sql_fetch(" select * from kmp_pilot_edu_list where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' and edu_del_type = 'N' ");

//강의 불러옴
$row = sql_fetch(" select * from kmp_pilot_lecture_list where lecture_idx='{$lecture_idx}' and edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' and lecture_del_type = 'N' ");
$youtube_id = youtube_url($row['lecture_youtube']);

//이미 동영상 시청을 끝냈는지 파악
$view_cnt = sql_fetch("select count(*) as view_cnt from kmp_pilot_lecture_complet where lecture_idx='{$lecture_idx}' and edu_idx = '{$edu_idx}' and apply_idx = '{$apply_idx}' and mb_id = '{$member['mb_id']}' ");
$view_count = $view_cnt['view_cnt'];

$ajaxpage_view_save = G5_URL."/edu_process/ajax_lecture_view_save.php";
?>

<div id="player"></div>
    <div id="total_play_time"></div>
    <div id="lecture_complet"></div>
    <script>
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

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

        function onPlayerReady(event) {
            //player.seekTo(see_time,true); //보던 시간 대로 이동 시키기
            event.target.playVideo();
            total_playTime = Math.ceil(player.getDuration());   //.동영상 총 시간
            document.getElementById('total_play_time').innerHTML = "총 플레이 타임 : <span style='font-size:20px;color:blue;'>"+convert(total_playTime)+"</span> 초";
        }

        var done = false;
        var tmp_time = 0;   //일시 정지시 시간을 간직 하는 임시 변수
        var tmp_flag = 0;   //처음 로딩시인지 일시 정지 후 다시 시작인지 체크 변수
        var reset = 0;  //reset
        function onPlayerStateChange(event) {
            if(event.data == 1){    //동영상 재생
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
                tmp_flag = 1;   //일시 정지 후 다시 시작인지 체크 변수
                see_time = tmp_time;
                printClock(see_time,tmp_flag); //일시 정지 했던 시간 부터 다시 흐르게...
            }

            if(event.data == 0){    //동영상 재생 완료
                setTimeout(stopVideo, 0);   //동영상이 완료 되면 처음 화면으로 되돌린다
                clearTimeout(reset);
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
            if(tmp_flag == 0){
                if(tmp_time != 0) {
                    reset = setTimeout("printClock(tmp_time,tmp_flag);", 1000);    // 1초마다 printClock() 함수 호출
                }
            }
            //영상 본시간
            var total_see = 0;
            total_see = total_playTime - tmp_time;

<?php
//이미 동영상을 시청 했거나 교육 기간이 지났을 경우 저장 하지 않는다
if($view_count == 0){
    if($row_edu['edu_cal_end'] != ""){  //종료일이 미정이 아닐때
        if($now_date <= $row_edu['edu_cal_end']){
            //동영상 총 시간과 영상 본시간이 같으면 디비에 저장
?>
            view_save(total_playTime, total_see);
<?php
        }
    }else{
        //동영상 총 시간과 영상 본시간이 같으면 디비에 저장
?>
        view_save(total_playTime, total_see);
<?php
    }
}
?>
            console.log("total_see===> "+total_see);
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
    function view_info_save(lecture_idx,apply_idx,edu_idx,edu_type){

        var ajaxUrl = "<?=$ajaxpage_view_save?>";
            $.ajax({
                type		: "POST",
                dataType    : "text",
                url			: ajaxUrl,
                data		: {
                    "lecture_idx"   : lecture_idx,
                    "apply_idx"     : apply_idx,
                    "edu_idx"       : edu_idx,
                    "edu_type"      : edu_type
                },
                success: function(data){
                    if(trim(data) == "no_member"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "fail"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "OK"){
                        document.getElementById('lecture_complet').innerHTML = "<?=$row['lecture_subject']?> <?=$lang['lecture_complete']?>";
                        //location.reload();
                    }
                },
                error: function () {
                        console.log('error');
                }
            });
    }
</script>

<script>
    function view_save(total_playTime, total_see){
        if(total_playTime == total_see){
                //clearTimeout(reset);
                //alert("저장!!!!");
                view_info_save('<?=$lecture_idx?>','<?=$apply_idx?>','<?=$edu_idx?>','<?=$edu_type?>');   //디비에 저장 하는 함수 호출
        }
    }
</script>

<?php
if($view_count == 1){
    echo "
        <script>
            document.getElementById('lecture_complet').innerHTML = '{$row['lecture_subject']} {$lang['lecture_complete']}';
        </script>
    ";
    exit;
}else{
    if($row_edu['edu_cal_end'] != ""){
        if($now_date > $row_edu['edu_cal_end']){
            echo "
            <script>
                document.getElementById('lecture_complet').innerHTML = '{$lang['lecture_date_end']}';
            </script>
            ";
            exit;
        }
    }
}
?>