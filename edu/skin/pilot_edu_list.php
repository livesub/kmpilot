<?php
include_once('./_common.php');

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
    exit;
}

//년도 검색
$year_ch = $_GET['year_ch'];
$default_year = 2021;
$now_year = date("Y");
if($year_ch == "") $select_y = $now_year;
else $select_y = $year_ch;

$sql = " select count(*) as cnt from kmp_pilot_edu_list where edu_del_type = 'N' and edu_onoff_type = 'off' and (edu_cal_start like '%{$select_y}%' or edu_cal_start = '') ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql_list = " select * from kmp_pilot_edu_list where edu_del_type = 'N' and edu_onoff_type = 'off' and (edu_cal_start like '%{$select_y}%' or edu_cal_start = '')  order by edu_idx asc ";
$result = sql_query($sql_list);
$now_date = date("Y-m-d");

$ajaxpage_apply = G5_URL."/edu_process/ajax_lecture_apply.php";
?>

<table>
    <tr>
        <td><b><?=$lang['edu_title']?></b></td>
        <td> <?=$lang['edu_year_search']?>
            <select name="year_ch" id="year_ch" onchange = "year_change();">
<?php
    for($k = $default_year; $k <= $now_year; $k++){
?>
                <option value="<?=$k?>" <?php if($k == $select_y) echo "selected"?>><?=$k?></option>
<?php
    }
?>
            </select>
        </td>
    </tr>
</table>
<br><br>


<!-- ------------------ 오프라인 교육 부분 ------------------------------- -->
<table>
    <tr>
        <td><?=$lang['edu_subtitle1']?></td>
    </tr>
</table>


<table border=1>
    <tr>
        <td><?=$lang['edu_list_num']?></td>
        <td><?=$lang['edu_list_name']?></td>
        <td><?=$lang['edu_list_type']?></td>
        <td><?=$lang['edu_list_time']?></td>
        <td><?=$lang['edu_list_calendar']?></td>
        <td><?=$lang['edu_list_place']?></td>
        <td><?=$lang['edu_list_receipt_time']?></td>
        <td><?=$lang['edu_list_status']?></td>
        <td></td>
    </tr>
<?php
    $virtual_num = 1;
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $apply_button = "";
        $cancel_button = "";
        $edu_way = edu_way($row['edu_way'],$lang);
        $edu_cal_end = "";
        $edu_caln = "";
        $edu_receipt_end = "";
        $row_status = "";

        //현재 신청 인원 구하기
        $row_cnt = sql_fetch(" select count(*) as apply_cnt from kmp_pilot_edu_apply where edu_idx = '{$row['edu_idx']}' and edu_type = '{$row['edu_type']}' and apply_cancel = 'N' ");
        $apply_count = $row_cnt['apply_cnt'];

        //신청 여부
        $row_status_result = sql_query(" select apply_idx, lecture_completion_status from kmp_pilot_edu_apply where edu_idx = '{$row['edu_idx']}' and edu_type = '{$row['edu_type']}' and mb_id='{$member['mb_id']}' and apply_cancel = 'N' ");
        $apply_status = sql_num_rows($row_status_result);
        $row_status = sql_fetch_array($row_status_result);

        if($row['edu_receipt_status'] == "P"){
            //준비중(교육 일정, 접수기간 미정으로 만든다.)
            $edu_cal = $lang['edu_list_undefined'];
            $edu_receipt = $lang['edu_list_undefined'];
        }else{
            if($row['edu_cal_end'] != "") $edu_cal_end = " ~ {$row['edu_cal_end']}";
            $edu_cal = $row['edu_cal_start'].$edu_cal_end;

            if($row['edu_receipt_type'] != "0") $edu_receipt_end = " ~ {$row['edu_receipt_end']}";
            $edu_receipt = $row['edu_receipt_start'].$edu_receipt_end;
        }

        //준비중 처리
        if($row['edu_receipt_status'] == "P"){
            //신청 버튼 처리 하기
            if($apply_status != 0){

                $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_pre]\");return false;'>";
                if($row_status['lecture_completion_status'] == "Y"){
                    //이수한 상태(모든 동영상을 다 본 상태)
                    $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                }else{
                    //$cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_.$lang_type]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                    $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_kr]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                }
            }else{
                $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_pre]\");return false;'>";
                if($row_status['lecture_completion_status'] == "Y"){
                    //이수한 상태(모든 동영상을 다 본 상태)
                    $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                }else{
                    $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_pre]\");return false;'>";
                }
            }
            $edu_receipt_status = edu_receipt_status($row['edu_receipt_status'],$lang);
        }else{
            //접수현황 프로세서(마감 조건은 1.인원이 모두 찼거나 2. 기간이 지났거나 3. 관리자가 변경했거나 준비중일때는 "준비중입니다" 접수마감은 "접수가 마감되었습니다.")
            if($row['edu_receipt_type'] != "0"){
                //접수기간 종료일 미정 아닐시

                if($apply_count >= $row['edu_person'] || $now_date > $row['edu_receipt_end'] || $row['edu_receipt_status'] == "C"){

                    //신청 인원과 정원이 같을때(1.인원이 모두 찼거나 2. 기간이 지났거나 3. 관리자가 변경했거나) ** 접수마감 **
                    $edu_receipt_status = edu_receipt_status("C",$lang);

                    //신청 버튼 처리 하기
                    if($apply_status != 0){
                        $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_ok]\");return false;'>";
                        if($row_status['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            //$cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_.$lang_type]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_kr]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                        }
                    }else{
                        $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                        if($row_status['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                        }
                    }
                }else{
                    //접수 마감 조건이 아닐시
                    //신청 버튼 처리 하기
                    $edu_receipt_status = edu_receipt_status($row['edu_receipt_status'],$lang);
                    if($apply_status != 0){
                        $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_ok]\");return false;'>";
                        if($row_status['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            //$cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_.$lang_type]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_kr]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                        }
                    }else{
                        //$apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='apply_chk(\"{$row[edu_name_.$lang_type]}\",\"{$row[edu_type]}\",{$row[edu_idx]})'>";
                        $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='apply_chk(\"{$row[edu_name_kr]}\",\"{$row[edu_type]}\",{$row[edu_idx]})'>";
                        if($row_status['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_no]\");return false;'>";
                        }
                    }
                }
            }else{
                //접수기간 종료일 미정 일시
                if($apply_count >= $row['edu_person'] || $row['edu_receipt_status'] == "C"){
                    //신청 인원과 정원이 같을때(1.인원이 모두 찼거나,3. 관리자가 변경했거나)
                    $edu_receipt_status = edu_receipt_status("C",$lang);
                    if($apply_status != 0){
                        $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_ok]\");return false;'>";
                        if($row_status['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            //$cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_.$lang_type]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_kr]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                        }
                    }else{
                        $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                        if($row_status['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                        }
                    }
                }else{
                    $edu_receipt_status = edu_receipt_status($row['edu_receipt_status'],$lang);
                    if($apply_status != 0){
                        $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_ok]\");return false;'>";
                        if($row_status['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            //$cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_.$lang_type]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row[edu_name_kr]}\",\"{$row[edu_type]}\",{$row[edu_idx]}, {$row_status[apply_idx]})'>";
                        }
                    }else{
                        //$apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='apply_chk(\"{$row[edu_name_.$lang_type]}\",\"{$row[edu_type]}\",{$row[edu_idx]})'>";
                        $apply_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='apply_chk(\"{$row[edu_name_kr]}\",\"{$row[edu_type]}\",{$row[edu_idx]})'>";
                        if($row_status['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            $cancel_button = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_no]\");return false;'>";
                        }
                    }
                }
            }
        }

        //관리자 접수 현황 자동 업뎃 하기
        admin_apply_status_auto($row['edu_receipt_status'],$row['edu_receipt_type'],$apply_count,$row['edu_person'],$row['edu_receipt_end'],$row['edu_idx'],$row['edu_onoff_type'],$row['edu_type']);
?>
    <tr>
        <td><?=$virtual_num?></td>
        <!-- <td><?=$row['edu_name_'.$lang_type]?></td> -->
        <td><?=$row['edu_name_kr']?></td>
        <td><?=$edu_way?></td>
        <td><?=$row['edu_time']?></td>
        <td><?=$edu_cal?></td>
        <td><?=$row['edu_place']?></td>
        <td><?=$edu_receipt?></td>
        <td>
            <?=$edu_receipt_status?><br>
            <?=$apply_count?> / <?=$row['edu_person']?>
        </td>
        <td><?=$apply_button?>            <?=$cancel_button?></td>
    </tr>
<?php
        $virtual_num++;
    }
?>
<?php if ($total_count == 0) { echo '<tr><td colspan="9" class="empty_table">'.$lang['edu_apply_list'].'</td></tr>'; } ?>
</table>





<!-- ------------------ 온라인 교육 부분 ------------------------------- -->
<br><br>

<?php
$sql_on_cnt = " select count(*) as cnt from kmp_pilot_edu_list where edu_del_type = 'N' and edu_onoff_type = 'on' and edu_cal_start like '%{$select_y}%' ";
$row_on_cnt = sql_fetch($sql_on_cnt);
$total_count_on = $row_on_cnt['cnt'];

$sql_list_on = " select * from kmp_pilot_edu_list where edu_del_type = 'N' and edu_onoff_type = 'on' and edu_cal_start like '%{$select_y}%' order by edu_idx asc ";
$result_on = sql_query($sql_list_on);
?>
<table>
    <tr>
        <td><?=$lang['edu_subtitle2']?></td>
    </tr>
</table>


<table border=1>
    <tr>
        <td><?=$lang['edu_list_num']?></td>
        <td><?=$lang['edu_list_name']?></td>
        <td><?=$lang['edu_list_type']?></td>
        <td><?=$lang['edu_list_time']?></td>
        <td><?=$lang['edu_list_class_time']?></td>
        <td><?=$lang['edu_list_place']?></td>
        <td><?=$lang['edu_list_receipt_time']?></td>
        <td><?=$lang['edu_list_status']?></td>
        <td></td>
    </tr>
<?php
    $virtual_num_on = 1;
    for ($i=0; $row_on=sql_fetch_array($result_on); $i++) {
        $apply_button_on = "";
        $cancel_button_on = "";
        $lecture_button_on = "";
        $edu_cal_end_on = "";
        $edu_cal_on = "";
        $edu_receipt_end_on = "";
        $row_status_on = "";
        $edu_way_on = edu_way($row_on['edu_way'],$lang);

        //현재 신청 인원 구하기
        $row_cnt_on = sql_fetch(" select count(*) as apply_cnt from kmp_pilot_edu_apply where edu_idx = '{$row_on['edu_idx']}' and edu_type = '{$row_on['edu_type']}' and apply_cancel = 'N' ");
        $apply_count_on = $row_cnt_on['apply_cnt'];

        //신청 여부
        $row_status_result_on = sql_query(" select apply_idx, lecture_completion_status from kmp_pilot_edu_apply where edu_idx = '{$row_on['edu_idx']}' and edu_type = '{$row_on['edu_type']}' and mb_id='{$member['mb_id']}' and apply_cancel = 'N' ");
        $apply_status_on = sql_num_rows($row_status_result_on);
        $row_status_on = sql_fetch_array($row_status_result_on);

        if($row_on['edu_receipt_status'] == "P"){
            //준비중(교육 일정, 접수기간 미정으로 만든다.)
            $edu_cal_on = $lang['edu_list_undefined'];
            $edu_receipt_on = $lang['edu_list_undefined'];
        }else{
            if($row_on['edu_cal_end'] != "") $edu_cal_end_on = " ~ {$row_on['edu_cal_end']}";
            $edu_cal_on = $row_on['edu_cal_start'].$edu_cal_end_on;

            if($row_on['edu_receipt_type'] != "0") $edu_receipt_end_on = " ~ {$row_on['edu_receipt_end']}";
            $edu_receipt_on = $row_on['edu_receipt_start'].$edu_receipt_end_on;
        }

        //준비중 처리
        if($row_on['edu_receipt_status'] == "P"){
            //신청 버튼 처리 하기
            if($apply_status_on != 0){
                $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_pre]\");return false;'>";
                if($row_status_on['lecture_completion_status'] == "Y"){
                    //이수한 상태(모든 동영상을 다 본 상태)
                    $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                }else{
                    //$cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_.$lang_type]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                    $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_kr]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                }
                $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='alert(\"$lang[edu_apply_pre]\");return false;'>";
            }else{
                $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_pre]\");return false;'>";
                if($row_status_on['lecture_completion_status'] == "Y"){
                    //이수한 상태(모든 동영상을 다 본 상태)
                    $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                }else{
                    $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_pre]\");return false;'>";
                }
                $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='alert(\"$lang[edu_apply_pre]\");return false;'>";
            }
            $edu_receipt_status_on = edu_receipt_status($row_on['edu_receipt_status'],$lang);
        }else{
            //접수현황 프로세서(마감 조건은 1.인원이 모두 찼거나 2. 기간이 지났거나 3. 관리자가 변경했거나 준비중일때는 "준비중입니다" 접수마감은 "접수가 마감되었습니다.")
            if($row_on['edu_receipt_type'] != "0"){
                //접수기간 종료일 미정 아닐시
                if($apply_count_on >= $row_on['edu_person'] || $now_date > $row_on['edu_receipt_end'] || $row_on['edu_receipt_status'] == "C"){
                    //신청 인원과 정원이 같을때(1.인원이 모두 찼거나 2. 기간이 지났거나 3. 관리자가 변경했거나) ** 접수마감 **
                    $edu_receipt_status_on = edu_receipt_status("C",$lang);
                    //신청 버튼 처리 하기
                    if($apply_status_on != 0){
                        $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_ok]\");return false;'>";
                        if($row_status_on['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            //$cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_.$lang_type]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_kr]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                        }
                        $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='lecture_move(\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                    }else{
                        $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                        if($row_status_on['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                        }
                        $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                    }
                }else{
                    //접수 마감 조건이 아닐시
                    //신청 버튼 처리 하기
                    $edu_receipt_status_on = edu_receipt_status($row_on['edu_receipt_status'],$lang);
                    if($apply_status_on != 0){
                        $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_ok]\");return false;'>";
                        if($row_status_on['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            //$cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_.$lang_type]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_kr]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                        }
                        $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='lecture_move(\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                    }else{
                        //$apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='apply_chk(\"{$row_on[edu_name_.$lang_type]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]})'>";
                        $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='apply_chk(\"{$row_on[edu_name_kr]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]})'>";
                        if($row_status_on['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_no]\");return false;'>";
                        }
                        $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='alert(\"$lang[edu_apply_no]\");return false;'>";
                    }
                }
            }else{
                //접수기간 종료일 미정 일시
                if($apply_count_on >= $row_on['edu_person'] || $row_on['edu_receipt_status'] == "C"){
                    //신청 인원과 정원이 같을때(1.인원이 모두 찼거나,3. 관리자가 변경했거나)
                    $edu_receipt_status_on = edu_receipt_status("C",$lang);
                    if($apply_status_on != 0){
                        $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_ok]\");return false;'>";
                        if($row_status_on['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            //$cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_.$lang_type]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_kr]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                        }
                        $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='lecture_move(\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                    }else{
                        $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                        if($row_status_on['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                        }
                        $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='alert(\"$lang[edu_apply_close]\");return false;'>";
                    }
                }else{
                    $edu_receipt_status_on = edu_receipt_status($row_on['edu_receipt_status'],$lang);
                    if($apply_status_on != 0){
                        $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='alert(\"$lang[edu_apply_ok]\");return false;'>";
                        if($row_status_on['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            //$cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_.$lang_type]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='cancel_chk(\"{$row_on[edu_name_kr]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                        }
                        $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='lecture_move(\"{$row_on[edu_type]}\",{$row_on[edu_idx]}, {$row_status_on[apply_idx]})'>";
                    }else{
                        //$apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='apply_chk(\"{$row_on[edu_name_.$lang_type]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]})'>";
                        $apply_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_apply]."' onclick='apply_chk(\"{$row_on[edu_name_kr]}\",\"{$row_on[edu_type]}\",{$row_on[edu_idx]})'>";
                        if($row_status_on['lecture_completion_status'] == "Y"){
                            //이수한 상태(모든 동영상을 다 본 상태)
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_status]\");return false;'>";
                        }else{
                            $cancel_button_on = "<input type='button' class='btn btn_02' value='".$lang[lecture_cancel]."' onclick='alert(\"$lang[edu_apply_no]\");return false;'>";
                        }
                        $lecture_button_on = "<input type='button' class='btn btn_02' value='".$lang[edu_lecture_ing]."' onclick='alert(\"$lang[edu_apply_no]\");return false;'>";
                    }
                }
            }
        }

        //관리자 접수 현황 자동 업뎃 하기
        admin_apply_status_auto($row_on['edu_receipt_status'],$row_on['edu_receipt_type'],$apply_count_on,$row_on['edu_person'],$row_on['edu_receipt_end'],$row_on['edu_idx'],$row_on['edu_onoff_type'],$row_on['edu_type']);
?>
    <tr>
        <td><?=$virtual_num_on?></td>
        <!-- <td><?=$row_on['edu_name_'.$lang_type]?></td> -->
        <td><?=$row_on['edu_name_kr']?></td>
        <td><?=$edu_way_on?></td>
        <td><?=$row_on['edu_time']?></td>
        <td><?=$edu_cal_on?></td>
        <td><?=$row_on['edu_place']?></td>
        <td><?=$edu_receipt_on?></td>
        <td>
            <?=$edu_receipt_status_on?><br>
            <?=$apply_count_on?> / <?=$row_on['edu_person']?>
        </td>
        <td><?=$apply_button_on?>            <?=$cancel_button_on?>         <?=$lecture_button_on?></td>
    </tr>
<?php
        $virtual_num_on++;
    }
?>
<?php if ($total_count_on == 0) { echo '<tr><td colspan="9" class="empty_table">'.$lang['edu_apply_list'].'</td></tr>'; } ?>
</table>

<script>
    function year_change(){
        location.href = "content.php?co_id=pilot_edu_list&year_ch="+$("#year_ch option:selected").val();
    }
</script>

<script>
    function apply_chk(edu_name,edu_type,edu_idx){
        var subject_tmp = edu_name + " <?=$lang['apply_confirm']?>";
        if(confirm(subject_tmp)){
            var ajaxUrl = "<?=$ajaxpage_apply?>";
            $.ajax({
                type		: "POST",
                dataType    : "text",
                url			: ajaxUrl,
                data		: {
                    "edu_idx"       : edu_idx,
                    "edu_type"      : edu_type,
                    "button_type"    : "apply"
                },
                success: function(data){
                    if(trim(data) == "no_member"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "full"){
                        alert("<?=$lang['edu_apply_full']?>");
                        location.reload();
                    }

                    if(trim(data) == "apply_ok_status"){
                        alert("<?=$lang['edu_apply_ok']?>");
                        location.reload();
                    }

                    if(trim(data) == "fail"){
                        alert("<?=$lang['fatal_err']?>");
                        location.reload();
                    }

                    if(trim(data) == "OK"){
                        alert("<?=$lang['apply_ok']?>");
                        location.reload();
                    }
                },
                error: function () {
                        console.log('error');
                }
            });
        }else{
            return false;
        }
    }
</script>

<script>
    function cancel_chk(edu_name,edu_type,edu_idx,apply_idx){
        var subject_tmp = edu_name + " <?=$lang['cancel_confirm']?>";
        if(confirm(subject_tmp)){
            var ajaxUrl = "<?=$ajaxpage_apply?>";
            $.ajax({
                type		: "POST",
                dataType    : "text",
                url			: ajaxUrl,
                data		: {
                    "edu_idx"       : edu_idx,
                    "edu_type"      : edu_type,
                    "button_type"   : "del",
                    "apply_idx"     : apply_idx
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
                        alert("<?=$lang['cancel_ok']?>");
                        location.reload();
                    }
                },
                error: function () {
                        console.log('error');
                }
            });
        }else{
            return false;
        }
    }
</script>

<script>
    function lecture_move(edu_type,edu_idx,apply_idx){
        location.href = "content.php?co_id=pilot_lecture_list&edu_type="+edu_type+"&edu_idx="+edu_idx+"&apply_idx="+apply_idx;
    }
</script>