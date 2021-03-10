<?php
$sub_menu = "200200";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from kmp_MEMBER_HONOR ";

$g5['title'] = '명예도선사리스트';

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'H_USER_ID' :
//            $sql_search .= " ({$sfl} like '%{$stx}%') ";
//            break;
        case 'H_USER_NAME' :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "H_RETIRE_DATE ASC, IDX DESC";
    //$sod = "desc";
}

$sql_order = " order by {$sst}  ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search}  {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

//// 탈퇴회원수
//$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
//$row = sql_fetch($sql);
//$leave_count = $row['cnt'];
//
//// 차단회원수
//$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
//$row = sql_fetch($sql);
//$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';


include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
//alert($sql);
$result = sql_query($sql);

$colspan = 16;
?>

    <div class="local_ov01 local_ov">
        <?php echo $listall ?>
        <span class="btn_ov01"><span class="ov_txt">총 명예도선사수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
    </div>

    <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

        <label for="sfl" class="sound_only">검색대상</label>
        <select name="sfl" id="sfl">
            <option value="H_USER_ID"<?php echo get_selected($sfl, "H_USER_ID"); ?>>회원아이디</option>
            <option value="H_USER_NAME"<?php echo get_selected($sfl, "H_USER_NAME"); ?>>이름</option>
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
                    <th scope="col" id="mb_list_id" colspan="">소속도선사회</th>
                    <th scope="col" id="mb_list_name">이름</th>
                    <th scope="col" id="mb_list_auth">생년</th>
                    <th scope="col" id="mb_list_auth">퇴직년</th>
                    <th scope="col" id="mb_list_mobile">직책</th>
                    <th scope="col" rowspan="" colspan="2" id="mb_list_mng">관리</th>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i=0; $row=sql_fetch_array($result); $i++) {
                    $mb_id = $row['mb_id'];
                    $bg = 'bg'.($i%2);
                    ?>

                    <tr class="<?php echo $bg; ?>">
                        <td headers="mb_list_chk" class="td_chk" rowspan="">
                            <input type="hidden" name="IDX_[<?php echo $i ?>]" value="<?php echo $row['IDX'] ?>" id="IDX_<?php echo $i ?>">
                            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?></label>
                            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        </td>
                        <td headers="mb_list_id" colspan="" class="td_name sv_use">
                            <?php echo get_doseongu_name($row['H_USER_GROUP_KEY']) ?>
                        </td>
                        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['H_USER_NAME']); ?></td>
                        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['H_USER_BIRTH']); ?></td>
                        <td headers="mb_list_name" class="td_mbname" ><?php echo get_text($row['H_RETIRE_DATE']); ?></td>
                        <td headers="mb_list_name" class="td_mbname">
                            <select id="H_POSITION" name="H_POSITION">
                                <option value="" <?=get_selected($row['H_POSITION'],'')?>>선택</option>
                                <option value="1" <?=get_selected($row['H_POSITION'],1)?>>감사</option>
                                <option value="2" <?=get_selected($row['H_POSITION'],2)?>>부회장</option>
                                <option value="3" <?=get_selected($row['H_POSITION'],3)?>>회장</option>
                                <option value="4" <?=get_selected($row['H_POSITION'],4)?>>고문</option>
                            </select>
                        </td>
                        <td headers="mb_list_mng" rowspan="" class="td_mng td_mng_s"><button type="button" class="btn btn_03" onclick="open_popup(<?=$row['IDX']?>,600,500,'honor_member_modi.php?w=u','honor_member_modi','honor_member_id')">수정</button></td>
                    </tr>
                    <?php
                }
                if ($i == 0)
                    echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                ?>
                </tbody>
            </table>
        </div>

        <div class="btn_fixed_top">
            <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
            <?php if($is_admin =='super'){ ?>
                <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
            <?php } ?>
            <?php if ($is_admin == 'super') { ?>
                <button id="member_add" class="btn btn_01" type="button" onclick="open_popup('',500,500,'honor_member_modi.php?w=i','honor_member_modi','honor_member_id')">명예도선사등록</button>
            <?php } ?>
        </div>
    </form>
    <form name="honor_submit" id="honor_submit" method="POST" action="">
        <input type="hidden" name="honor_member_id" id="honor_member_id" value="">
    </form>


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

    <script>
        function fmemberlist_submit(f)
        {
            if (!is_checked("chk[]")) {
                alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            if(document.pressed == "선택삭제") {
                if(!confirm("선택한 회원을 정말 삭제하시겠습니까?")) {
                    return false;
                }
            }

            return true;
        }

        function open_popup(id,width,height,action,target,field){
            $("#"+field).val(id);
            let popupX = (window.screen.width / 2) - (width / 2);
            // 만들 팝업창 좌우 크기의 1/2 만큼 보정값으로 빼주었음
            //console.log($("#honor_member_id").val());
            let popupY= (window.screen.height /2) - (height / 2);
            // 만들 팝업창 상하 크기의 1/2 만큼 보정값으로 빼주었음
            let pop = $("#honor_submit");
            pop.attr("action", action);
            pop.attr("target", target); //window.open 두번째 인자와 값이 같아야 한다.

            window.open(action, target, 'status=no, height='+height+', width='+width+', ' +
                'left='+ popupX + ', top='+ popupY + ', screenX='+ popupX + ', screenY= '+ popupY);
            pop.submit();
        }
    </script>

<?php
include_once ('./admin.tail.php');
