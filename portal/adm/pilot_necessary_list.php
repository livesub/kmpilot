<?php
$sub_menu = "400300";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');
$sql_common = " from {$g5['pilot_necessary_table']} ";
$sql_order = " order by idx desc";

$sql = " select count(*) as cnt {$sql_common} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;


$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$g5['title'] = '필수 도선사';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
?>

<form name="renewal_from" id="renewal_from" action="./pilot_necessary_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="page" value="<?php echo $page ?>">



<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <?php if ($is_admin == 'super') { ?>
    <a href="./pilot_necessary_regi.php" id="renewal_add" class="btn btn_01">강의등록</a>
    <?php } ?>
</div>


<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" rowspan="2" >
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" rowspan="2">번호</th>
        <th scope="col" rowspan="2">강의명</th>
        <th scope="col" rowspan="2">수정</a></th>
        <th scope="col" colspan="3">리스트 다운로드</th>
    </tr>
    <tr>
        <th scope="col">신청</th>
        <th scope="col">수료</th>
        <th scope="col">미수료</th>
    </tr>

    </thead>
    <tbody>
<?php
    $virtual_num = $total_count - $rows * ($page - 1);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
    <tr>
        <td><input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row['idx'] ?>"></td>
        <td><?=$virtual_num ?></td>
        <td><a href="pilot_lecture_status_list.php?idx=<?=$row['idx'];?>&type=pilot_necessary&sub_menu=<?=$sub_menu?>"><?=$row[subject]?></a></td>
        <td><input type="button" class="btn btn_02" value="수정" onclick="location.href='pilot_necessary_regi.php?idx=<?=$row[idx]?>&w=u' "></td>
        <td><button class="btn btn_02">다운로드</button></td>
        <td><button class="btn btn_02">다운로드</button></td>
        <td><button class="btn btn_02">다운로드</button></td>
    </tr>
<?php
        $virtual_num--;
    }
    if (!$i)
        echo "<tr><td colspan='7' class=\"empty_table\">자료가 없습니다.</td></tr>";
?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function fmemberlist_submit(f)
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