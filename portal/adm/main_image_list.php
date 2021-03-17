<?php
$sub_menu = "100050";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '메인 페이지 이미지 설정';
include_once ('./admin.head.php');

$row = sql_fetch(" select count(*) as cnt from {$g5['main_image_table']} ");
$total_count = $row['cnt'];

$rows = 10;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$result = sql_query(" select * from {$g5['main_image_table']} order by turn desc limit {$from_record}, {$rows} ");

?>

<div class="local_desc01 local_desc">
    <p>
    출선순서는 번호가 클수록 먼저 출력 됩니다.
    </p>
</div>


<form name="fimagelist" id="fimagelist" action="./main_image_list_update.php" onsubmit="return fimagelist_submit(this);" method="post">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <thead>
    <tr>
        <th scope="col" id="image_list_chk" rowspan="2" >
            <label for="chkall" class="sound_only">이미지 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" id="image_list_id" colspan="">번호</th>
        <th scope="col" id="image_list_name">이미지</th>
        <th scope="col" id="image_list_auth">제목</th>
        <th scope="col" id="image_list_turn">출력순서</th>
        <th scope="col" id="image_list_turn">타입</th>
        <th scope="col" rowspan="" colspan="2" id="image_list_mng">관리</th>
    </tr>

    </thead>
    <tbody>

<?php
$virtual_num = $total_count - $rows * ($page - 1);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $image_path = "";
?>
    <tr class="bg0">
        <td class="td_chk">
            <input type="hidden" name="idx[<?php echo $i ?>]" value="<?php echo $row['idx'] ?>" id="idx_<?php echo $i ?>">
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td><?=$virtual_num?></td>
        <td>
<?php
    if($row['image_name'] != ""){
        $image_path = G5_DATA_URL."/main_image/{$row['image_name']}'";
        echo "<input type='hidden' name='image_name[{$i}]' value='{$row['image_name']}' id='image_name_{$i}'>";
        echo "<img src='{$image_path}' width='60' height='60'>";
    }
?>
        </td>
        <td><?=stripslashes($row['subject']);?></td>
        <td><input type="text" name="turn[]" id="turn[]" value="<?=$row['turn']?>" size="5" maxlength="2" onkeydown="this.value=this.value.replace(/[^0-9]/g,'')" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" onblur="this.value=this.value.replace(/[^0-9]/g,'')" required></td>
        <td>
            <select name="type[]" id="type[]" style="width:70px;">
                <option value="A" <?php if($row['type'] == "" || $row['type'] == "A") echo "selected";?>>협회</option>
                <option value="E" <?php if($row['type'] == "E") echo "selected";?>>교육센터</option>
            </select>
        </td>
        <td>
            <a href="./main_image_form.php?<?=$qstr?>&amp;w=u&amp;idx=<?=$row['idx']?>" class="btn btn_03">수정</a>
        </td>
    </tr>
<?php
    $virtual_num--;
}
?>
    <?php if ($total_count == 0) { echo '<tr><td colspan="7" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
    <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <a href="./main_image_form.php" id="main_image_add" class="btn btn_01">이미지추가</a>
</div>

</form>


<script>
function fimagelist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>



<?php
include_once ('./admin.tail.php');