<?php
$g5['title'] = "도선지";

$sql_common = " from CMS_MAGAZINE_NEW_TEST ";
$sql_search = " where PARENTIDX = 0  and GUBUN = 1 ";

$sfl = $_GET['sfl'];
if($sfl == 'SCGCODE' || $sfl == 'C_TITLE'){
    $sql_search = " where PARENTIDX != 0  and GUBUN = 1 and SCGCODE != '' ";
}

if ($stx && $sfl) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'M_TITLE' || "M_AUTHOR" || "SECTION":
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
/*        case 'M_AUTHOR' :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break; */
        case "SCGCODE" || "C_TITLE" :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;

        default :
            $sql_search .= "";
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

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'?co_id=doseonge_list" class="ov_listall">전체목록</a>';

$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
//도선지 리스트를 뽑는 쿼리문
$sql_sel_list = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows}";
//alert('쿼리문 확인'. $sql_sel_list);
$result_list = sql_query($sql_sel_list);
?>
    <button><?=$listall?></button>
    <form id="fsearch" name="fsearch" class="local_sch01 local_sch"  method="get">
        <input type="hidden" id="co_id" name="co_id" value="doseonge_list">
        <label for="sfl" class="sound_only">검색</label>
        <select name="sfl" id="sfl">
            <option value=""<?php echo get_selected($sfl, ""); ?> disabled>선택해주세요</option>
            <option value="M_TITLE"<?php echo get_selected($sfl, "M_TITLE"); ?>>제목</option>
            <option value="M_AUTHOR"<?php echo get_selected($sfl, "M_AUTHOR"); ?>>저자</option>
            <option value="SECTION"<?php echo get_selected($sfl, "SECTION"); ?>>연호</option>
            <option value="mb_hp"<?php echo get_selected($sfl, "mb_hp"); ?>>발간일</option>
            <option value="SCGCODE"<?php echo get_selected($sfl, "SCGCODE"); ?>>분류</option>
            <option value="C_TITLE"<?php echo get_selected($sfl, "C_TITLE"); ?>>소제목</option>
        </select>
        <label for="stx" class="sound_only">검색어</label>
        <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" placeholder="내용을 입력해 주세요" required>
        <input type="submit" value="검색">
    </form>
<br>
<br>
<?php if($sfl == 'SCGCODE' || $sfl == 'C_TITLE'){?>
    <div>
<?php }else{?>
    <div style="display: flex; justify-content: space-between;">
<?php }?>
        <?php
        for($i=0; $row=sql_fetch_array($result_list); $i++){
            if($sfl == 'SCGCODE' || $sfl == 'C_TITLE'){
                $sql_title_sel = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$row['PARENTIDX']}";
                $result_title_sel = sql_fetch($sql_title_sel);
                $sql_title = '';
                $sql_title = $result_title_sel['M_TITLE'];
            ?>
                <div>
                    <b><?=$sql_title?></b>
                    <b><?=$row['SCGCODE']?></b>
                    <p><?=$row['C_TITLE']?> - p.<?=$row['C_PAGE']?></p>
                </div>
        <?php
                }else{
                ?>
                <div>
                    <img src="<?=G5_DATA_URL?>/magazine_test/<?=$row['M_IMG']?>" width="150" height="150" alt="">
                    <br>
                    <a href="<?=G5_BBS_URL?>/content.php?co_id=doseonge_info&amp;idx=<?=$row['IDX']?>"><b><?=$row['M_TITLE']?></b></a>
                    <p>저자 : <?=$row['M_AUTHOR']?></p>
                    <p>연호 : <?=$row['S_YEAR']?> <?=$row['SECTION'] ?></p>
                    <p>발간 : <?=$row['S_YEAR']?>-<?=$row['S_MONTH']?>-<?=$row['S_DAY']?></p>
                </div>
                <?php
            }
            }
        if($i == 0){
            echo "검색 결과가 없습니다.";
        }
        ?>
    </div>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;co_id=doseonge_list'.'&amp;page='.$page); ?>