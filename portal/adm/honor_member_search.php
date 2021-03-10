<?php
include_once ("./_common.php");

if($is_admin != 'super'){
    exit;
}

$sql_sel_member = " select * from {$g5['member_table']} ";
$sql_common = " and mb_leave_date = '' and mb_intercept_date = '' and mb_memo = '' and mb_id != 'yongsazip' and mb_id != 'root' and mb_level != 9 and mb_level != 10 ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
        case 'mb_name' :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

//if ($is_admin != 'super')
//    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_no";
    $sod = "asc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql_page = " select count(*) as cnt from {$g5['member_table']} {$sql_search} {$sql_common} {$sql_order} ";
$row_page = sql_fetch($sql_page);
$total_count = $row_page['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$sql = " {$sql_sel_member} {$sql_search} {$sql_common} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);
$colspan = 5;
?>
<script>console.log(<?=$total_count?>, <?=$total_page?>)</script>
<h1>사용자찾기 <button onclick="self.close();">닫기</button></h1>
<h2>전체사용자</h2>
<?php echo $listall?>
<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
    <select name="sfl" id="sfl">
        <option value="mb_id"<?php echo get_selected($sfl, "mb_id"); ?>>회원아이디</option>
        <option value="mb_name"<?php echo get_selected($sfl, "mb_name"); ?>>이름</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
    <input type="submit" class="btn_submit" value="검색">
</form>
<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <thead>
            <tr>
                <th scope="col" id="mb_list_chk" rowspan="2" >

                </th>
                <th scope="col" id="mb_list_auth">소속도선구</th>
                <th scope="col" id="mb_list_id" colspan="">아이디</th>
                <th scope="col" id="mb_list_name">이름</th>
                <th scope="col" id="mb_list_auth">이메일</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for ($i=0; $row=sql_fetch_array($result); $i++) {
                $bg = 'bg'.($i%2);

                ?>

                <tr class="<?php echo $bg; ?>">
                    <td headers="mb_list_chk" class="td_chk" rowspan="">
                        <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
                        <input type="radio" name="chk[]" value="" id="chk_<?php echo $i ?>" onclick="popup_return('<?=$row['mb_name']?>','<?=$row['mb_id']?>')">
                    </td>
                    <td headers="mb_list_id" colspan="" class="td_name sv_use">
                        <?php echo get_doseongu_name($row['mb_doseongu']) ?>
                    </td>
                    <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_id']); ?></td>
                    <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_name']); ?></td>
                    <td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_email']); ?></td>
                </tr>
                <?php
            }
            if ($i == 0)
                echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
            ?>
            </tbody>
        </table>
    </div>
</form>

<?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
    function popup_return(data,data2){
        //console.log('함수는 들어오나? : '+data2);
        window.opener.data_sel(data,data2);
        window.close();
    }
</script>