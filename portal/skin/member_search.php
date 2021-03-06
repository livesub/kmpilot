<?php

$g5['title'] = "회원 검색";

if(!$is_member) {
    echo "<script>
        alert('회원만 이용가능한 페이지 입니다. 로그인을 해주세요');
        reLogin();
    </script>";
}

$row_member = '';

if($is_member) {
    $sql_common = " from {$g5['member_table']} ";
    $sql_del_mem = " and mb_memo = '' and {$g5['member_table']}.mb_id != 'yongsanzip' ";

    if ($member['mb_level'] == 10) {
        $sql_del_mem = null;
    }

    $sql_search = " where (1) ";
    if ($stx) {
        $sql_search .= " and ( ";
        switch ($sfl) {
            case 'mb_level' :
                $sql_search .= " ({$sfl} = '{$stx}') ";
                break;
            case 'mb_hp' :
                $sql_search .= " ({$sfl} like '%{$stx}') ";
                break;
            default :
                $sql_search .= " ({$sfl} like '{$stx}%') ";
                break;
        }
        $sql_search .= " ) ";
    }

    if ($is_admin != 'super')
        $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

    if (!$sst) {
        $sst = "mb_datetime";
        $sod = "desc";
    }
    $mb_doseongu = $_REQUEST['do'];
    $mb_group = $_REQUEST['gr'];

    if (isset($mb_doseongu) && $mb_doseongu != "" && $mb_doseongu != '0') {
        $sql_doseongu = " and mb_doseongu = $mb_doseongu";
    } else {
        $sql_doseongu = "";
    }

    if (isset($mb_group) && $mb_group != "") {
        $sql_join = " left join {$g5['group_member_table']} on {$g5['member_table']}.mb_id = {$g5['group_member_table']}.mb_id";
        $sql_group_sel = " and gr_id = {$mb_group} ";
        $sql_owner = " {$g5['member_table']} ";
    } else {
        $sql_join = "";
        $sql_owner = "";
        $sql_group_sel = "";
    }

    $sql_order = " order by {$sst} {$sod} ";

    $sql = " select count(*) as cnt {$sql_common} {$sql_join} {$sql_search} {$sql_group_sel} {$sql_del_mem} {$sql_doseongu} {$sql_order} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $rows = $config['cf_page_rows'];
    $total_page = ceil($total_count / $rows);  // 전체 페이지 계산
    if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $rows; // 시작 열을 구함

    $listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '/?co_id=member_search" class="ov_listall">전체목록</a>';
//alert('서버 네임'.$_SERVER['SCRIPT_NAME']);
    $g5['title'] = '회원검색';

    $sql = " select * {$sql_common} {$sql_join} {$sql_search} {$sql_group_sel} {$sql_del_mem} {$sql_doseongu} {$sql_order} limit {$from_record}, {$rows} ";
//alert('쿼리문을 달라'.$sql);
    $result = sql_query($sql);
//alert('조건문 확인 :'.$sql);
    $colspan = 16;
}
?>

    <div class="local_ov01 local_ov">
        <h1>회원 검색</h1>
        <?php echo $listall ?>
<!--        <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num"> --><?php //echo number_format($total_count) ?><!--명 </span></span>-->
    </div>
<?php if($is_member) {?>
    <form id="fsearch" name="fsearch" class="local_sch01 local_sch"  method="get">
        <input type="hidden" id="co_id" name="co_id" value="member_search">
        <br>
        <label for="do">도선구별</label>
        <?php echo get_doseongu_select("do", 0,12, $mb_doseongu)?>
        <br>
        <label for="gr">그룹별</label>
        <?php echo get_group_select("gr", $mb_group, '', 'no')?>
        <br>
        <label for="sfl" class="sound_only">필터별</label>
        <select name="sfl" id="sfl">
            <option value=""<?php echo get_selected($sfl, ""); ?>>선택해주세요</option>
            <option value="mb_name"<?php echo get_selected($sfl, "mb_name"); ?>>이름</option>
            <option value="mb_hp"<?php echo get_selected($sfl, "mb_hp"); ?>>휴대폰번호</option>
        </select>
        <label for="stx" class="sound_only">검색어</label>
        <input type="text" name="stx" value="<?php echo $stx ?>" id="stx">
        <input type="submit" value="검색">
    </form>
    <form name="fmemberlist" id="fmemberlist" action="" onsubmit="" method="post">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="token" value="">

        <div class="tbl_head01 tbl_wrap">
            <table>
                <caption><?php echo $g5['title']; ?> 목록</caption>
                <thead>
                <tr>
                    <th scope="col" id="mb_list_doseongu" colspan="">도선구</a></th>
                    <th scope="col" id="mb_list_group" colspan="">그룹</a></th>
                    <th scope="col" id="mb_list_name">이름</a></th>
                    <th scope="col" id="mb_list_mobile">연락처</th>
                    <th scope="col" id="mb_list_email">이메일</th>
                </tr>
                <tr>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i=0; $row_member=sql_fetch_array($result); $i++) {
                    // 접근가능한 그룹수
                    $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row_member['mb_id']}' ";
                    $row_member2 = sql_fetch($sql2);

                    $group = '';
                    if ($row_member2['cnt'])
                        $group = '<a href="./boardgroupmember_form.php?mb_id='.$row_member['mb_id'].'">'.$row_member2['cnt'].'</a>';

                    if ($is_admin == 'group') {
                        $s_mod = '';
                    } else {
                        $s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row_member['mb_id'].'" class="btn btn_03">수정</a>';
                    }
                    $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row_member['mb_id'].'" class="btn btn_02">그룹</a>';

                    $leave_date = $row_member['mb_leave_date'] ? $row_member['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
                    $intercept_date = $row_member['mb_intercept_date'] ? $row_member['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);
                    //$mb_id = $row_member['mb_id'];

                    $address = $row_member['mb_zip1'] ? print_address($row_member['mb_addr1'], $row_member['mb_addr2'], $row_member['mb_addr3'], $row_member['mb_addr_jibeon']) : '';

                    $bg = 'bg'.($i%2);
                    ?>

                    <tr class="<?php echo $bg; ?>">
                        <td headers="mb_list_id" colspan="" class="td_name sv_use">
                            <?php echo get_doseongu_name($row_member['mb_doseongu']) ?>
                        </td>
                        <?php $user_group = get_group_name($row_member['gr_id']);?>
                        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($user_group); ?></td>
                        <td headers="mb_list_name" class="td_mbname"> <a href="#" onclick="open_popup('<?=$row_member['mb_id']?>',500,500)"><?php echo $row_member['mb_name']; ?></a></td>
                        <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row_member['mb_hp']); ?></td>
                        <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row_member['mb_email']); ?></td>
<!--                        <td headers="mb_list_mng" rowspan="" class="td_mng td_mng_s">--><?php //echo $s_mod ?><!--</td>-->
<!--                        <td headers="mb_list_mng" rowspan="" class="td_mng td_mng_s">--><?php //echo $s_grp ?><!--</td>-->
                    </tr>
                    <?php
                }
                if ($i == 0 || !$is_member)
                    echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                ?>
                </tbody>
            </table>
        </div>
    </form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?co_id=member_search'.$qstr.'&amp;do='.$mb_doseongu.'&amp;gr='.$mb_group.'&amp;sfl='.$sfl.'&amp;stx='.$stx.'&amp;page='.$page); ?>

<?php
}
?>

<form name="pop_submit" id="pop_submit" method="POST" action="">
    <input type="hidden" name="member_id" id="member_id" value="">
</form>

<script>
    function open_popup(id,width,height){
        $("#member_id").val(id);
        let popupX = (window.screen.width / 2) - (width / 2);
        // 만들 팝업창 좌우 크기의 1/2 만큼 보정값으로 빼주었음
        //console.log($("#member_id").val());
        let popupY= (window.screen.height /2) - (height / 2);
        // 만들 팝업창 상하 크기의 1/2 만큼 보정값으로 빼주었음
        let pop = $("#pop_submit");
        pop.attr("action", "member_info.php");
        pop.attr("target", "member_info"); //window.open 두번째 인자와 값이 같아야 한다.


        window.open('member_info.php', 'member_info', 'status=no, height='+height+', width='+width+', ' +
            'left='+ popupX + ', top='+ popupY + ', screenX='+ popupX + ', screenY= '+ popupY);
        $("#pop_submit").submit();
    }
</script>
