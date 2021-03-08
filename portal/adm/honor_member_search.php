<?php
include_once ("./_common.php");

$sql_sel_member = " select * from {$g5['member_table']} ";
$sql_common = " and mb_leave_date = '' and mb_intercept_date = '' 
                       and mb_memo = '' and mb_id != 'yongsazip' and mb_id != 'root' and mb_level != 9 abd mb_level != 10 ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
        case 'mb_name' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
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
    $sst = "mb_no";
    $sod = "asc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_search} {$sql_common} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$sql = " select * {$sql_search} {$sql_common} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
$colspan = 16;
?>
<h1>사용자찾기</h1>
<h2>전체사용자</h2>
<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
    <label for="sfl" class="sound_only">검색대상</label>
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
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
            <tr>
                <th scope="col" id="mb_list_chk" rowspan="2" >
                    <label for="chkall" class="sound_only">회원 전체</label>
                    <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
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
                // 접근가능한 그룹수
                $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
                $row2 = sql_fetch($sql2);
                $group = '';
                if ($row2['cnt'])
                    $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

                if ($is_admin == 'group') {
                    $s_mod = '';
                } else {
                    $s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" class="btn btn_03">수정</a>';
                }
                $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'" class="btn btn_02">그룹</a>';

                $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
                $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

                $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

                $mb_id = $row['mb_id'];
                $leave_msg = '';
                $intercept_msg = '';
                $intercept_title = '';
                if ($row['mb_leave_date']) {
                    $mb_id = $mb_id;
                    $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
                }
                else if ($row['mb_intercept_date']) {
                    $mb_id = $mb_id;
                    $intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
                    $intercept_title = '차단해제';
                }
                if ($intercept_title == '')
                    $intercept_title = '차단하기';

                $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

                $bg = 'bg'.($i%2);

                switch($row['mb_certify']) {
                    case 'hp':
                        $mb_certify_case = '휴대폰';
                        $mb_certify_val = 'hp';
                        break;
                    case 'ipin':
                        $mb_certify_case = '아이핀';
                        $mb_certify_val = '';
                        break;
                    case 'admin':
                        $mb_certify_case = '관리자';
                        $mb_certify_val = 'admin';
                        break;
                    default:
                        $mb_certify_case = '&nbsp;';
                        $mb_certify_val = 'admin';
                        break;
                }
                ?>

                <tr class="<?php echo $bg; ?>">
                    <td headers="mb_list_chk" class="td_chk" rowspan="">
                        <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
                        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                    </td>
                    <td headers="mb_list_id" colspan="" class="td_name sv_use">
                        <?php echo $mb_id ?>
                        <?php
                        //소셜계정이 있다면
                        if(function_exists('social_login_link_account')){
                            if( $my_social_accounts = social_login_link_account($row['mb_id'], false, 'get_data') ){

                                echo '<div class="member_social_provider sns-wrap-over sns-wrap-32">';
                                foreach( (array) $my_social_accounts as $account){     //반복문
                                    if( empty($account) || empty($account['provider']) ) continue;

                                    $provider = strtolower($account['provider']);
                                    $provider_name = social_get_provider_service_name($provider);

                                    echo '<span class="sns-icon sns-'.$provider.'" title="'.$provider_name.'">';
                                    echo '<span class="ico"></span>';
                                    echo '<span class="txt">'.$provider_name.'</span>';
                                    echo '</span>';
                                }
                                echo '</div>';
                            }
                        }
                        ?>
                    </td>
                    <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
                    <!--        <td headers="mb_list_cert"  rowspan="2" class="td_mbcert">-->
                    <!---->
                    <!--        </td>-->
                    <!--        <td headers="mb_list_mailc">--><?php //echo preg_match('/[1-9]/', $row['mb_email_certify'])?'<span class="txt_true">Yes</span>':'<span class="txt_false">No</span>'; ?><!--</td>-->
                    <!--        <td headers="mb_list_open">-->
                    <!--            <label for="mb_open_--><?php //echo $i; ?><!--" class="sound_only">정보공개</label>-->
                    <!--            <input type="checkbox" name="mb_open[--><?php //echo $i; ?><!--]" --><?php //echo $row['mb_open']?'checked':''; ?><!-- value="1" id="mb_open_--><?php //echo $i; ?><!--">-->
                    <!--        </td>-->
                    <!--        <td headers="mb_list_mailr">-->
                    <!--            <label for="mb_mailling_--><?php //echo $i; ?><!--" class="sound_only">메일수신</label>-->
                    <!--            <input type="checkbox" name="mb_mailling[--><?php //echo $i; ?><!--]" --><?php //echo $row['mb_mailling']?'checked':''; ?><!-- value="1" id="mb_mailling_--><?php //echo $i; ?><!--">-->
                    <!--        </td>-->
                    <td headers="mb_list_auth" class="td_mbstat">
                        <?php
                        if ($leave_msg || $intercept_msg) echo $leave_msg.' '.$intercept_msg;
                        else echo "정상";
                        ?>
                    </td>
                    <td headers="mb_list_deny">
                        <?php if(empty($row['mb_leave_date'])){ ?>
                            <input type="checkbox" name="mb_intercept_date[<?php echo $i; ?>]" <?php echo $row['mb_intercept_date']?'checked':''; ?> value="<?php echo $intercept_date ?>" id="mb_intercept_date_<?php echo $i ?>" title="<?php echo $intercept_title ?>">
                            <label for="mb_intercept_date_<?php echo $i; ?>" class="sound_only">접근차단</label>
                        <?php } ?>
                    </td>
                    <?php
                    if($member['mb_level'] == 10 || $member['mb_level'] == 9){
                        ?>
                        <td headers="mb_list_memo" class="td_memo"><?php  echo get_text($row['mb_memo'])?></td>
                        <?php
                    }
                    ?>
                    <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_hp']); ?></td>
                    <td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_tel']); ?></td>
                    <td headers="mb_list_lastcall" class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
                    <!--        <td headers="mb_list_auth" class="td_mbstat">-->
                    <!--            --><?php //echo get_member_level_select("mb_level[$i]", 1, $member['mb_level'], $row['mb_level']) ?>
                    <!--        </td>-->
                    <!--        <td headers="mb_list_grp" class="td_numsmall">--><?php //echo $group ?><!--</td>-->
                    <td headers="mb_list_mng" rowspan="" class="td_mng td_mng_s"><?php echo $s_mod ?></td>
                    <td headers="mb_list_mng" rowspan="" class="td_mng td_mng_s"><?php echo $s_grp ?></td>
                </tr>
                <!--    <tr class="--><?php //echo $bg; ?><!--">-->

                <!--        <td headers="mb_list_nick" class="td_name sv_use"><div>--><?php //echo $mb_nick ?><!--</div></td>-->

                <!--        <td headers="mb_list_sms">-->
                <!--            <label for="mb_sms_--><?php //echo $i; ?><!--" class="sound_only">SMS수신</label>-->
                <!--            <input type="checkbox" name="mb_sms[--><?php //echo $i; ?><!--]" --><?php //echo $row['mb_sms']?'checked':''; ?><!-- value="1" id="mb_sms_--><?php //echo $i; ?><!--">-->
                <!--        </td>-->
                <!--        <td headers="mb_list_adultc">-->
                <!--            <label for="mb_adult_--><?php //echo $i; ?><!--" class="sound_only">성인인증</label>-->
                <!--            <input type="checkbox" name="mb_adult[--><?php //echo $i; ?><!--]" --><?php //echo $row['mb_adult']?'checked':''; ?><!-- value="1" id="mb_adult_--><?php //echo $i; ?><!--">-->
                <!--        </td>-->



                <!--        <td headers="mb_list_join" class="td_date">--><?php //echo substr($row['mb_datetime'],2,8); ?><!--</td>-->
                <!--        <td headers="mb_list_point" class="td_num"><a href="point_list.php?sfl=mb_id&amp;stx=--><?php //echo $row['mb_id'] ?><!--">--><?php //echo number_format($row['mb_point']) ?><!--</a></td>-->

                <!--    </tr>-->

                <?php
            }
            if ($i == 0)
                echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
            ?>
            </tbody>
        </table>
    </div>
</form>