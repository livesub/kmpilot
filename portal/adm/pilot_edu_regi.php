<?php

include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$edu_onoff_type = $_GET['edu_onoff_type'];    //온라인,오프라인  등록 페이지 같이 쓰기 위해 파라메터로 구분 함
if($edu_onoff_type == "off")
{
    $g5['title'] = '오프라인 교육';
    $sub_menu = "400100";
    $title_change = "교육기간";
    $return_page = "pilot_off_edu_list.php";
}else{
    $g5['title'] = '온라인 교육';
    $sub_menu = "400200";
    $title_change = "수강기간";
    $return_page = "pilot_on_edu_list.php";
}

if ($w == '')
{
    $g5['title'] .= ' 등록';
}else{
    $g5['title'] .= ' 수정';
    $edu_type = $_GET['edu_type'];
    $edu_idx = $_GET['edu_idx'];
    //$sql = "select * from kmp_pilot_edu_list where edu_type = '$edu_type' and edu_idx = '$edu_idx' ";
    $sql = "select * from kmp_pilot_edu_list where edu_idx = '$edu_idx' ";
    $result = sql_query($sql);
    $row=sql_fetch_array($result);
}

include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
?>

<form name="fedu" id="fedu" onsubmit="return fedu_submit();" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?=$w?>">
<input type="hidden" name="edu_onoff_type" value="<?=$edu_onoff_type?>">
<input type="hidden" name="edu_idx" value="<?=$edu_idx?>">
<input type="hidden" name="edu_way" value="<?=$edu_onoff_type?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
<?php
if($edu_onoff_type == "off"){
?>
    <tr>
        <th scope="row"><label for="edu_type">교육종류<?php echo $sound_only ?></label></th>
        <td colspan="3">
            <select name="edu_type" id="edu_type">
                <option value="CR" <?php if($row['edu_type'] == "CR" || $row['edu_type'] == "") echo "selected";?>>면허갱신교육</option>
                <option value="CE" <?php if($row['edu_type'] == "CE") echo "selected";?>>보수교육</option>
                <option value="" <?php if($row['edu_type'] == "etc") echo "selected";?>>직접작성</option>
            </select>
        </td>
    </tr>
<?php
}else if($edu_onoff_type == "on"){
?>
    <tr>
        <th scope="row"><label for="edu_type">교육종류<?php echo $sound_only ?></label></th>
        <td colspan="3">
            <select name="edu_type" id="edu_type">
                <option value="CC" <?php if($row['edu_type'] == "CC" || $row['edu_type'] == "") echo "selected";?>>필수도선사교육</option>
                <option value="" <?php if($row['edu_type'] == "etc") echo "selected";?>>직접작성</option>
            </select>
        </td>
    </tr>
<?php
}
?>
    <tr>
        <th scope="row"><label for="edu_name_kr">교육명(한글)<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="edu_name_kr" id="edu_name_kr" class="frm_input" size="70" maxlength="250" value="<?=$row['edu_name_kr']?>" placeholder="내용을 입력해 주세요">
        </td>

        <th scope="row"><label for="edu_name_en">교육명(영문)<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="edu_name_en" id="edu_name_en" class="frm_input" size="70" maxlength="250" value="<?=$row['edu_name_en']?>" placeholder="내용을 입력해 주세요">
        </td>
    </tr>

    <tr>
<!-- 먼저 교육 방법을 선택하고 들어 오는 형태라면 등록 메뉴에서 교육방법(온/오프) 별도 지정 기능 삭제(02_17 문서)
        <th scope="row"><label for="edu_way">교육방법<?php echo $sound_only ?></label></th>
        <td>
            <select name="edu_way" id="edu_way">
                <option value="off" <?php if($row['edu_way'] == "off" || ($row['edu_way'] == "" && $edu_onoff_type == "off")) echo "selected";?>>오프라인</option>
                <option value="on" <?php if($row['edu_way'] == "on" || ($row['edu_way'] == "" && $edu_onoff_type == "on")) echo "selected";?>>온라인</option>
            </select>
        </td>
-->
        <th scope="row"><label for="edu_place">교육장소<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="edu_place" id="edu_place" class="frm_input" size="50" maxlength="250" value="<?=$row['edu_place']?>" placeholder="내용을 입력해 주세요">
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="edu_time">교육시간<?php echo $sound_only ?></label></th>
        <td colspan="3">
            <input type="text" name="edu_time" id="edu_time" class="frm_input" size="50" maxlength="50" value="<?=$row['edu_time']?>" placeholder="내용을 입력해 주세요">
        </td>
    </tr>


    <tr>
        <th scope="row"><label for="edu_cal"><?=$title_change?><?php echo $sound_only ?></label></th>
        <td colspan="3">
            <input type="text" name="edu_cal_start" id="edu_cal_start" class="frm_input" size="15" maxlength="10" value="<?=$row['edu_cal_start']?>" placeholder="내용을 입력해 주세요" readonly> 부터
            <input type="text" name="edu_cal_end" id="edu_cal_end" class="frm_input" size="15" maxlength="10" value="<?=$row['edu_cal_end']?>" placeholder="내용을 입력해 주세요" readonly> 까지
            <input type="checkbox" name="edu_cal_type" id="edu_cal_type" value="0" <?php if($row['edu_cal_type'] == "0") echo "checked";?> onclick="edu_cal_type_chk();"> 종료일 미정( ※ 종료일 미정 일시 수료증서 발급에 문제가 발생 합니다. )
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="edu_receipt">접수기간<?php echo $sound_only ?></label></th>
        <td colspan="3">
            <input type="text" name="edu_receipt_start" id="edu_receipt_start" class="frm_input" size="15" maxlength="10" value="<?=$row['edu_receipt_start']?>" placeholder="내용을 입력해 주세요" readonly> 부터
            <input type="text" name="edu_receipt_end" id="edu_receipt_end" class="frm_input" size="15" maxlength="10" value="<?=$row['edu_receipt_end']?>" placeholder="내용을 입력해 주세요" readonly> 까지
            <input type="checkbox" name="edu_receipt_type" id="edu_receipt_type" value="0" <?php if($row['edu_receipt_type'] == "0") echo "checked";?> onclick="edu_receipt_type_chk();"> 종료일 미정
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="edu_receipt_status">접수현황<?php echo $sound_only ?></label></th>
        <td>
            <select name="edu_receipt_status" id="edu_receipt_status">
                <option value="I" <?php if($row['edu_receipt_status'] == "I") echo "selected";?>>접수중</option>
                <option value="C" <?php if($row['edu_receipt_status'] == "C") echo "selected";?>>접수마감</option>
                <option value="P" <?php if($row['edu_receipt_status'] == "P") echo "selected";?>>준비중</option>
            </select>
        </td>

        <th scope="row"><label for="edu_person">교육인원<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="edu_person" id="edu_person" class="frm_input" size="5" maxlength="3" value="<?=$row['edu_person']?>" placeholder="정원" onkeypress="inNumber();">
        </td>
    </tr>

   </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./<?=$return_page?>?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>

</form>


<script>
    $(function() {
        $( "#edu_cal_start, #edu_cal_end, #edu_receipt_start, #edu_receipt_end").datepicker({
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

<script>
<?php
if($w == "u")
{
?>
    if($("input:checkbox[name=edu_cal_type]").is(":checked") == true) {
        $("#edu_cal_end").val("");
        $("#edu_cal_end").attr("disabled",true);
    }
    if($("input:checkbox[name=edu_receipt_type]").is(":checked") == true) {
        $("#edu_receipt_end").val("");
        $("#edu_receipt_end").attr("disabled",true);
    }
<?php
}
?>
    function edu_cal_type_chk(){
        if($("input:checkbox[name=edu_cal_type]").is(":checked") == true) {
            $("#edu_cal_end").val("");
            $("#edu_cal_end").attr("disabled",true);
        }else{
            $("#edu_cal_end").attr("disabled",false);
        }
    }

    function edu_receipt_type_chk(){
        if($("input:checkbox[name=edu_receipt_type]").is(":checked") == true) {
            $("#edu_receipt_end").val("");
            $("#edu_receipt_end").attr("disabled",true);
        }else{
            $("#edu_receipt_end").attr("disabled",false);
        }
    }
</script>

<script>
    function fedu_submit(){
        if($("#edu_name_kr").val() == ""){
            alert("교육명(한글)을 입력 하세요.");
            $("#edu_name_kr").focus();
            return false;
        }

        if($("#edu_name_en").val() == ""){
            alert("교육명(영문)을 입력 하세요.");
            $("#edu_name_en").focus();
            return false;
        }

        if($("#edu_place").val() == ""){
            alert("교육장소를 입력 하세요.");
            $("#edu_place").focus();
            return false;
        }

        if($("#edu_time").val() == ""){
            alert("교육시간을 입력 하세요.");
            $("#edu_time").focus();
            return false;
        }

        if($("#edu_receipt_status").val() != "P"){
            //접수현황이 준비중일떼 날짜 입력 안 할수 있게
            if($("#edu_cal_start").val() == ""){
                alert("교육일정 시작 날짜를 입력 하세요.");
                $("#edu_cal_start").focus();
                return false;
            }

            if($("input:checkbox[name=edu_cal_type]").is(":checked") == false) {
                //종료일 미정 체크시 날짜 입력 안하게
                if($("#edu_cal_end").val() == ""){
                    alert("교육일정 종료 날짜를 입력 하세요.");
                    $("#edu_cal_end").focus();
                    return false;
                }
            }

            if($("#edu_receipt_start").val() == ""){
                alert("접수기간 시작 날짜를 입력 하세요.");
                $("#edu_receipt_start").focus();
                return false;
            }

            if($("input:checkbox[name=edu_receipt_type]").is(":checked") == false) {
                //종료일 미정 체크시 날짜 입력 안하게
                if($("#edu_receipt_end").val() == ""){
                    alert("접수기간 종료 날짜를 입력 하세요.");
                    $("#edu_receipt_end").focus();
                    return false;
                }
            }
        }
        if($("#edu_person").val() == ""){
            alert("교육인원을 숫자로 입력 하세요.");
            $("#edu_person").focus();
            return false;
        }

        var ajaxUrl = "ajax_edu_regi.php";
        var queryString = $("form[name=fedu]").serialize() ;

        $.ajax({
            type		: "POST",
            dataType    : 'text',
            url			: ajaxUrl,
            data        : queryString,
            success: function(data){
                if (trim(data) == "ok") {
                    alert("<?=$g5['title']?> 되었습니다.");
                }else{
                    alert("문제가 발생 하였습니다. 개발자에게 문의 하세요.");
                    return false;
                }
            },
            error: function () {
                    console.log('error');
            }
        });
    }
</script>

<?php
include_once ('./admin.tail.php');