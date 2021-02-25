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
echo $sql;
$result = sql_query($sql);
$now_data_cnt = sql_num_rows($result);  //현재 저장된 데이터 갯수
$process_cnt = "";
for ($g=0; $g < $now_data_cnt; $g++) {
    $process_cnt .= "{$g},";
}
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
<input type='hidden' name='process_cnt' id='process_cnt' value="">
<input type='hidden' name='del_type' id='del_type' value="">

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
    var m = $("#html_total").val(); //DB 저장 된 갯수 빼고 증가
    console.log("aaa=====> "+m);
    var html = "";
    var process_cnt = "<?=$process_cnt?>";
    $(document).ready(function(){

        $("#add_lecture").click(function(){
//alert(m);
            //html = "<div class='tbl_frm01 tbl_wrap' id='add"+m+"'><input type='hidden' name='lecture_idx"+m+"' id='lecture_idx"+m+"' value='<?=$row[lecture_idx]?>'>";
            html = "<div class='tbl_frm01 tbl_wrap' id='add"+m+"'>";
            html += "    <table><tr>";
            html += "    <th scope='row'><label for='lecture_subject'>강의제목<?=$sound_only ?></label></th>";
            html += "    <td  colspan='3'>";
            html += "       <input type='text' name='lecture_subject"+m+"' value='<?=$row[lecture_subject]?>' id='lecture_subject"+m+"' required class='frm_input required' size='80' maxlength='250'>";
            html += "    </td>";
            html += "    <th scope='row' rowspan='3' style='width:10px;'><input type='checkbox' id='chk"+m+"' name='chk' value='"+m+"'></th>";
            html += "        </tr><tr>";
            html += "        <th scope='row'><label for='lecture_name'>강사명<strong class='sound_only'>필수</strong></label></th>";
            html += "        <td><input type='text' id='lecture_name"+m+"' name='lecture_name"+m+"' value='<?=$row[lecture_name] ?>' required class='required frm_input' size='15' maxlength='50'></td>";
            html += "        <th scope='row'><label for='lecture_time'>강의시간<strong class='sound_only'>필수</strong></label></th>";
            html += "        <td><input type='text' id='lecture_time"+m+"' name='lecture_time"+m+"' value='<?=$row[lecture_time] ?>' required class='required frm_input' size='3' maxlength='3' onkeypress='inNumber();'></td>";
            html += "</tr>";
            html += "        <tr><th scope='row'><label for='lecture_youtube'>유튜브주소<strong class='sound_only'>필수</strong></label></th>";
            html += "        <td colspan='3'><input type='text' id='lecture_youtube"+m+"' name='lecture_youtube"+m+"' value='<?php echo $row[lecture_youtube] ?>' required class='required frm_input' size='80' maxlength='250'></td>";
            html += "        </tr></table><br></div>";

            process_cnt += m + ",";
//alert("addprocess_cnt===> "+process_cnt);
console.log("m====> "+m);
            m++;
            $("#add_div").append(html)
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
                    process_cnt = process_cnt.replace(str+",", "");
//alert("process_cnt   ====> " +process_cnt);
                    $("#add"+str).remove();
                });
            }else{
                return false;
            }
        }
        $("#del_type").val("del");
        ajax_process(); //호출
    }

    function ajax_save_lecture(){
        var chk_len = $("input[name='chk']").length;

        if(chk_len == 0){
            alert("강의를 추가 하세요.");
            return false;
        }else{
            process_cnt_tmp = process_cnt.substr(0,process_cnt.length-1);
            var process_cnt_cut_array = process_cnt_tmp.split(",");

            for(k = 0; k < process_cnt_cut_array.length; k++){
                if($("#lecture_subject"+process_cnt_cut_array[k]).val() == ""){
                    alert("강의제목을 입력 하세요.");
                    $("#lecture_subject"+process_cnt_cut_array[k]).focus();
                    return false;
                }

                if($("#lecture_name"+process_cnt_cut_array[k]).val() == ""){
                    alert("강사명을 입력 하세요.");
                    $("#lecture_name"+process_cnt_cut_array[k]).focus();
                    return false;
                }

                if($("#lecture_time"+process_cnt_cut_array[k]).val() == ""){
                    alert("강의시간을 입력 하세요.");
                    $("#lecture_time"+process_cnt_cut_array[k]).focus();
                    return false;
                }

                if($("#lecture_youtube"+process_cnt_cut_array[k]).val() == ""){
                    alert("유튜브주소를 입력 하세요.");
                    $("#lecture_youtube"+process_cnt_cut_array[k]).focus();
                    return false;
                }
            }

            $("#process_cnt").val(process_cnt_cut_array);
            $("#del_type").val("inup");
            ajax_process(); //호출
        }
    }

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