<?php
include_once('./_common.php');

//로그인 사용자만 이용 할수 있음
if($is_member <> 1){
    alert($lang['lecture_login'], G5_BBS_URL.'/login.php?url='.urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$sql = " select count(*) as cnt from ".$g5['pilot_lecture_apply_table']." where mb_id = '{$member['mb_id']}' ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * from ".$g5['pilot_lecture_apply_table']." where mb_id = '{$member['mb_id']}' order by idx desc limit {$from_record}, {$rows} ";
$result = sql_query($sql);

?>

<table border=1>
    <tr>
        <td><?=$lang['mypage_title1']?></td>
    </tr>
</table>
<table border=1>
    <tr>
        <td><?=$lang['mypage_lecture_1']?></td>
        <td><?=$lang['mypage_lecture_2']?></td>
        <td><?=$lang['mypage_lecture_3']?></td>
        <td><?=$lang['mypage_lecture_4']?></td>
        <td><?=$lang['mypage_lecture_5']?></td>
    </tr>

<?php
$virtual_num = $total_count - $rows * ($page - 1);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $edu_name = edu_name($row['lecture_type_table'],$lang);
    $row_subject = sql_fetch(" select subject from {$g5[$row[lecture_type_table].'_table']} where idx = '{$row[lecture_idx]}' ");
    $lecture_apply_date = explode(" ",$row['lecture_apply_date']);

    if($row['lecture_completion_status'] == 'Y') $ment = $lang['mypage_complet1'];
    else $ment = $lang['mypage_complet2'];
?>
    <tr>
        <td><?=$virtual_num;?></td>
        <td><?=$edu_name;?></td>
        <td><?=$row_subject['subject'];?></td>
        <td><?=$lecture_apply_date[0];?></td>
        <td><?=$ment;?></td>
    </tr>
<?php
    $virtual_num--;
}
?>
<?php if ($total_count == 0) { echo '<tr><td colspan="5" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>
</table>
