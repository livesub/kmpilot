<?php
$sub_menu = "200300";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');


$gr_id = $_POST['gr_id'];
$ma_id = $_POST['ma_id'];

$edu_idx_cut = explode("edu_",$gr_id);
$edu_idx = trim($edu_idx_cut[1]);

//교육명 찾기
$edu_row = sql_fetch(" select * from kmp_pilot_edu_list where edu_idx = '{$edu_idx}' and edu_del_type = 'N' ");

$sql2 = " select mb_id,lecture_completion_status from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and apply_cancel = 'N' order by apply_idx asc ";
$result2 = sql_query($sql2);
$group_member = sql_num_rows($result2);

if (!$group_member)
    alert('선택하신 \"'.$edu_row['edu_name_kr'].'\" 신청자가 한명도 없습니다.');

$g5['title'] = '"'.$edu_row['edu_name_kr'].'"'." 신청 회원 메일발송";
include_once('./admin.head.php');

?>

<form name="fmailselectlist" id="fmailselectlist" onsubmit="return edu_mem_email(this);" method="post">
<input type="hidden" name="token" value="">
<input type="hidden" name="ma_id" value="<?php echo $ma_id ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk"  style="width:50px;">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">번호</th>
        <th scope="col">회원아이디</th>
        <th scope="col" style="width:150px;">이름</th>
        <th scope="col">E-mail</th>
    </tr>
    </thead>
    <tbody>
<?php
    $i=0;

    while ($row=sql_fetch_array($result2)) {
        $st_ment = "";
        $i++;
        $bg = 'bg'.($i%2);
        if($row['lecture_completion_status'] == "Y"){
            $st_ment = "(수료)";
        }else{
            $st_ment = "(미수료)";
        }
        //회원 정보 찾기
        $mem_row = sql_fetch(" select mb_id, mb_name, mb_email, mb_datetime from {$g5['member_table']} where mb_id = '{$row[mb_id]}' ");
?>
    <tr class="<?php echo $bg; ?>">
        <td><input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row['mb_id'] ?>"></td>
        <td class="td_num"><?=$i ?></td>
        <td class="td_mbid"><?=$mem_row['mb_id'] ?></td>
        <td class="td_mbname"><?=get_text($mem_row['mb_name']);?><?=$st_ment?></td>
        <td><?=$mem_row['mb_email'] ?></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
    <textarea name="ma_list" style="display:none"><?php echo $ma_list?></textarea>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" name="act_button" value="선택 메일보내기" onclick="document.pressed=this.value" class="btn_submit">
    <a href="./mail_select_form.php?ma_id=<?php echo $ma_id ?>">뒤로</a>
</div>

</form>

<script>
function edu_mem_email(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    $("#fmailselectlist").attr("action", "./mail_select_update.php");
    $("#fmailselectlist").submit();
}
</script>



<?php
include_once('./admin.tail.php');
