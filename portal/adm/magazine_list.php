<?php
$sub_menu = "300300";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from CMS_MAGAZINE_NEW_TEST ";

$sql_search = " where (1) and S_YEAR != '' and S_MONTH != '' and S_DAY != '' and SECTION != '' and M_TITLE != '' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst || $sst == '') {
    $sst = "IDX";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search}   ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 15;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '도선지관리';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

//번호 출력 관련 변수
$sql_list_num_sel = "select count(*) as cnt2 {$sql_common} where PARENTIDX = 0 and GUBUN = 1";
$row_num_sel = sql_fetch($sql_list_num_sel);
$total_list = $row_num_sel['cnt2'] - ($page -1) * $rows;

$colspan = 16;
?>

  <!--  <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

        <label for="sfl" class="sound_only">검색대상</label>
        <select name="sfl" id="sfl">
            <option value="mb_name"<?php //echo get_selected($sfl, "mb_name"); ?>>제목</option>
            <option value="mb_email"<?php //echo get_selected($sfl, "mb_email"); ?>>E-MAIL</option>
            <option value="mb_tel"<?php //echo get_selected($sfl, "mb_tel"); ?>>전화번호</option>
            <option value="mb_hp"<?php //echo get_selected($sfl, "mb_hp"); ?>>휴대폰번호</option>
            <option value="mb_datetime"<?php //echo get_selected($sfl, "mb_datetime"); ?>>가입일시</option>
        </select>
        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="stx" value="<?php //echo $stx ?>" id="stx" required class="required frm_input">
        <input type="submit" class="btn_submit" value="검색">

    </form> -->
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
                        <label for="chkall" class="sound_only">도선지 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                    <th scope="col" id="mb_list_id" colspan=""><?php echo subject_sort_link('mb_id') ?>번호</a></th>
                    <th scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?>제목</a></th>
                    <th scope="col" id="mb_list_auth">작성일시</th>
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
                            <label for="chk_<?php echo $i; ?>" class="sound_only"></label>
                            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        </td>
                        <td headers="mb_list_IDX" colspan="" class="sv_use">
                            <?php echo get_text($total_list-$i); ?>
                        </td>
                        <td headers="mb_list_name" class="td_mb"><a href="./magazine_form.php?idx=<?=$row['IDX']?>&u=u"><?php echo get_text($row['M_TITLE']); ?></a></td>
                        <?php if($row['REGI_DATE'] != ''){?>
                        <td><?= get_text($row['REGI_DATE'])?></td>
                        <?php }else {?>
                            <td><?=get_text($row['S_YEAR'])?>-<?=get_text($row['S_MONTH'])?>-<?=get_text($row['S_DAY'])?></td>
                        <?php }?>
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
            <?php if($is_admin =='super'){ ?>
                <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
            <?php } ?>
            <?php if ($is_admin == 'super') { ?>
                <a href="./magazine_form.php?u=u" id="member_add" class="btn btn_01">도선지추가</a>
            <?php } ?>

        </div>


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
                if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                    return false;
                }
            }

            return true;
        }
    </script>

<?php
include_once ('./admin.tail.php');
