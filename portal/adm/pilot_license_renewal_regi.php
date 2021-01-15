<?php
$sub_menu = "400100";
include_once('./_common.php');

$idx = $_GET['idx'];
$w = $_GET['w'];

if ($w == '')
{
    $sound_only = '<strong class="sound_only">필수</strong>';
    $html_title = '등록';
}else if ($w == 'u'){
    $sound_only = '<strong class="sound_only">필수</strong>';
    $html_title = '수정';

    $sql = " SELECT * FROM {$g5['pilot_license_renewal_table']} WHERE idx = {$idx} ";
    $result = sql_query($sql);
    $row=sql_fetch_array($result);
}else{
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$g5['title'] .= '도선사면허갱신 강의 '.$html_title;

include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
?>



<script>
    //script구문 내부에 해당 메소드를 입력합니다.
    $(function() {
        $( "#startdatetime,#enddatetime" ).datepicker({
            showButtonPanel: true,
            currentText: '오늘 날짜',
            closeText: '닫기',
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
            dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
        });
    });
</script>

<form name="renewal_from" id="renewal_from" action="./pilot_license_renewal_save.php" onsubmit="return renewal_submit();" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="idx" value="<?php echo $idx ?>">

<div class="btn_fixed_top">
    <a href="./pilot_license_renewal_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?=$title?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="subject">강의 제목<?php echo $sound_only ?></label></th>
        <td  colspan="3">
            <input type="text" name="subject" value="<?php echo $row['subject'] ?>" id="subject" required class="frm_input required" size="80"  maxlength="250">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_name">수강 기간<strong class="sound_only">필수</strong></label></th>
        <td>
            <input type="text" id="startdatetime" name="startdatetime" value="<?php echo $row['startdatetime'] ?>" required class="required frm_input" size="15"  maxlength="20" readonly> 부터
            <input type="text" id="enddatetime" name="enddatetime" value="<?php echo $row['enddatetime'] ?>" required class="required frm_input" size="15"  maxlength="20" readonly> 까지
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="mb_name">유튜브 공유 주소<strong class="sound_only">필수</strong></label></th>
        <td>
            <input type="text" id="youtube" name="youtube" value="<?php echo $row['youtube'] ?>" required class="required frm_input" size="80"  maxlength="250">
        </td>
    </tr>
    </tbody>
    </table>
</div>
</form>


<script>
function renewal_submit(f)
{
    if($("#subject").val() == ""){
        alert("강의 제목을 입력 하세요.");
        $("#subject").focus();
        return false;
    }

    if($("#startdatetime").val() == ""){
        alert("수강 기간 시작 날짜를 입력 하세요.");
        $("#startdatetime").focus();
        return false;
    }

    if($("#enddatetime").val() == ""){
        alert("수강 기간 끝 날짜를 입력 하세요.");
        $("#enddatetime").focus();
        return false;
    }

    if($("#youtube").val() == ""){
        alert("유튜브 주소를 입력 하세요.");
        $("#youtube").focus();
        return false;
    }

    return true;
}
</script>



<?php
run_event('admin_member_form_after', $mb, $w);

include_once('./admin.tail.php');