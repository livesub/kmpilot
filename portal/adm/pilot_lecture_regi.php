<?php
$sub_menu = "400200";
include_once('./_common.php');


$edu_onoff_type = $_GET['edu_onoff_type'];
$edu_idx = $_GET['edu_idx'];
$edu_type = $_GET['edu_type'];
$w = $_GET['w'];

$data = sql_fetch(" SELECT * FROM kmp_pilot_edu_list WHERE edu_idx = {$edu_idx} ");

$g5['title'] = "온라인 교육 - 강의 등록";

include_once('./admin.head.php');

$sql = " select * from kmp_pilot_lecture_list where edu_idx = '{$edu_idx}' and edu_onoff_type = '{$edu_onoff_type}' and edu_type = '{$edu_type}' and lecture_del_type = 'N' order by lecture_idx asc ";
$result = sql_query($sql);
$now_data_cnt = sql_num_rows($result);  //현재 저장된 데이터 갯수
?>

<div class="local_desc01 local_desc">
    <p>
        <font color="blue"><b>교육명 : <?=$data['edu_name_kr']?></b></font>
    </p>
</div>

<div class="btn_fixed_top">
    <a href="./pilot_on_edu_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="button" class="btn btn_01" id="add_lecture" value="강의추가">
    <input type="button" class="btn btn_01" id="add_del" value="강의삭제" onclick="add_lecture_del();">
</div>

<form id="flecture" name="flecture" class="local_sch01 local_sch" method="post">
<input type="hidden" name="html_total" id="html_total" value="<?=$now_data_cnt?>">
<input type='hidden' name='edu_onoff_type' id='edu_onoff_type' value="<?=$edu_onoff_type?>">
<input type='hidden' name='edu_idx' id='edu_idx' value="<?=$edu_idx?>">
<input type='hidden' name='edu_type' id='edu_type' value="<?=$edu_type?>">
<input type='hidden' name='lecture_del_type' id='lecture_del_type' value="">

<div id="add_div">

<?php
for ($r=0; $row=sql_fetch_array($result); $r++) {
?>
    <div class="tbl_frm01 tbl_wrap" id="add<?=$r?>">
        <input type="hidden" name="lecture_idx<?=$r?>" id="lecture_idx<?=$r?>" value="<?=$row[lecture_idx]?>">
        <table>
            <tr>
                <th scope="row"><label for="lecture_subject">강의제목<?=$sound_only ?></label></th>
                <td  colspan="3">
                <input type="text" name="lecture_subject<?=$r?>" value="<?=$row[lecture_subject]?>" id="lecture_subject<?=$r?>" required class="frm_input required" size="80" maxlength="250">
                </td>
                <th scope="row" rowspan="3" style="width:10px;"><input type="checkbox" id="chk<?=$r?>" name="chk" value="<?=$r?>"></th>
            </tr>
            <tr>
                <th scope="row"><label for="lecture_name">강사명<strong class="sound_only">필수</strong></label></th>
                <td><input type="text" id="lecture_name<?=$r?>" name="lecture_name<?=$r?>" value="<?=$row[lecture_name] ?>" required class="required frm_input" size="15" maxlength="50"></td>
                <th scope="row"><label for="lecture_time">강의시간<strong class="sound_only">필수</strong></label></th>
                <td><input type="text" id="lecture_time<?=$r?>" name="lecture_time<?=$r?>" value="<?=$row[lecture_time] ?>" required class="required frm_input" size="3" maxlength="3"  onkeypress="inNumber();"></td>
            </tr>
            <tr>
                <th scope="row"><label for="lecture_youtube">유튜브주소<strong class="sound_only">필수</strong></label></th>
                <td colspan="3"><input type="text" id="lecture_youtube<?=$r?>" name="lecture_youtube<?=$r?>" value="<?php echo $row[lecture_youtube] ?>" required class="required frm_input" size="80" maxlength="250"></td>
            </tr>
        </table><br>
    </div>

<?php
}
?>

</div>

<div align="center">
    <input type="button" class="btn btn_02" id="save_lecture" value="등록 및 수정" onclick="ajax_save_lecture();">
</div>
</form>

<script>
    var i = $("#html_total").val(); //DB 저장 된 갯수 빼고 증가
    var html = "";
    $(document).ready(function(){
        $("#add_lecture").click(function(){
            html = "<div class='tbl_frm01 tbl_wrap' id='add"+i+"'><input type='hidden' name='lecture_idx"+i+"' id='lecture_idx"+i+"' value='<?=$row[lecture_idx]?>'>";
            html += "    <table><tr>";
            html += "    <th scope='row'><label for='lecture_subject'>강의제목<?=$sound_only ?></label></th>";
            html += "    <td  colspan='3'>";
            html += "       <input type='text' name='lecture_subject"+i+"' value='<?=$row[lecture_subject]?>' id='lecture_subject"+i+"' required class='frm_input required' size='80' maxlength='250'>";
            html += "    </td>";
            html += "    <th scope='row' rowspan='3' style='width:10px;'><input type='checkbox' id='chk"+i+"' name='chk' value='"+i+"'></th>";
            html += "        </tr><tr>";
            html += "        <th scope='row'><label for='lecture_name'>강사명<strong class='sound_only'>필수</strong></label></th>";
            html += "        <td><input type='text' id='lecture_name"+i+"' name='lecture_name"+i+"' value='<?=$row[lecture_name] ?>' required class='required frm_input' size='15' maxlength='50'></td>";
            html += "        <th scope='row'><label for='lecture_time'>강의시간<strong class='sound_only'>필수</strong></label></th>";
            html += "        <td><input type='text' id='lecture_time"+i+"' name='lecture_time"+i+"' value='<?=$row[lecture_time] ?>' required class='required frm_input' size='3' maxlength='3' onkeypress='inNumber();'></td>";
            html += "</tr>";
            html += "        <tr><th scope='row'><label for='lecture_youtube'>유튜브주소<strong class='sound_only'>필수</strong></label></th>";
            html += "        <td colspan='3'><input type='text' id='lecture_youtube"+i+"' name='lecture_youtube"+i+"' value='<?php echo $row[lecture_youtube] ?>' required class='required frm_input' size='80' maxlength='250'></td>";
            html += "        </tr></table><br></div>";

            $("#add_div").append(html)
            i++;
        });
    })

    function add_lecture_del(){
        if($("input:checkbox[id^=chk]:checked").length == 0){
            alert("하나 이상 선택 하세요.");
            return false;
        }

        if($("input:checkbox[id^=chk]").is(":checked") == true){
            var lecture_del_type = "";
            if(confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                $("#add_div input:checkbox:checked").each(function (index) {
                    var str = $(this).attr("value");
                    lecture_del_type += $("#lecture_idx"+str).val()+",";
                    $("#lecture_del_type").val(lecture_del_type);
                    $("#add"+str).remove();
                });
            }else{
                return false;
            }
        }
        ajax_process(); //호출
    }
</script>

<script>
    function ajax_save_lecture(){
        var chk_len = $("input[name='chk']").length;
        var html_total = $("#html_total").val(chk_len);
        $("#lecture_del_type").val("");

        if(chk_len == 0){
            alert("강의를 추가 하세요.");
            return false;
        }else{
            for(k = 0; k < chk_len; k++){
                if($("#lecture_subject"+k).val() == ""){
                    alert("강의제목을 입력 하세요.");
                    $("#lecture_subject"+k).focus();
                    return false;
                }

                if($("#lecture_name"+k).val() == ""){
                    alert("강사명을 입력 하세요.");
                    $("#lecture_name"+k).focus();
                    return false;
                }

                if($("#lecture_time"+k).val() == ""){
                    alert("강의시간을 입력 하세요.");
                    $("#lecture_time"+k).focus();
                    return false;
                }

                if($("#lecture_youtube"+k).val() == ""){
                    alert("유튜브주소를 입력 하세요.");
                    $("#lecture_youtube"+k).focus();
                    return false;
                }
            }
            ajax_process(); //호출
        }
    }
</script>

<script>
    function ajax_process(){
        var ajaxUrl = "ajax_lecture_regi.php";
        var queryString = $("form[name=flecture]").serialize() ;

        $.ajax({
            type		: "POST",
            dataType    : 'text',
            url			: ajaxUrl,
            data        : queryString,
            success: function(data){
                if (trim(data) == "ok") {
                    alert("등록 및 수정 되었습니다.");
                    location.reload(true);
                }else if (trim(data) == "no") {
                    alert("문제가 발생 하였습니다. 개발자에게 문의 하세요.");
                    $("#lecture_del_type").val("");
                    location.reload(true);
                }else{
                    alert("삭제 되었습니다.");
                    $("#lecture_del_type").val("");
                }
            },
            error: function () {
                console.log('error');
            }
        });
    }
</script>

<?php
run_event('admin_member_form_after', $mb, $w);

include_once('./admin.tail.php');