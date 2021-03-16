<?php

$g5['title'] = "명예도선사";

    $sql_common = " from kmp_MEMBER_HONOR ";
    
    $sql_search = " where (1) ";
    if ($stx) {
        $sql_search .= " and ( ";
        switch ($sfl) {
            case 'H_USER_NAME' :
                $sql_search .= " ({$sfl} like '%{$stx}%') ";
                break;
            case 'H_USER_ID' :
                $sql_search .= " ({$sfl} = '{$stx}') ";
                break;
            default :
                $sql_search .= " ({$sfl} like '{$stx}%') ";
                break;
        }
        $sql_search .= " ) ";
    }

    
    if (!$sst) {
        $sst = "H_RETIRE_DATE";
        $sod = "asc";
    }
    $mb_doseongu = $_REQUEST['do'];

    if (isset($mb_doseongu) && $mb_doseongu != "" && $mb_doseongu != '0') {
        $sql_doseongu = " and H_USER_GROUP_KEY = $mb_doseongu";
    } else {
        $sql_doseongu = "";
    }

    $sql_order = " order by {$sst} {$sod} ";

    // $sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_doseongu} {$sql_order} ";
    // $row = sql_fetch($sql);
    // $total_count = $row['cnt'];

    // $rows = $config['cf_page_rows'];
    // $total_page = ceil($total_count / $rows);  // 전체 페이지 계산
    // if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
    // $from_record = ($page - 1) * $rows; // 시작 열을 구함

    $listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '/?co_id=honor_member_view" class="ov_listall">전체목록</a>';
//alert('서버 네임'.$_SERVER['SCRIPT_NAME']);
    
    //$sql = " select * {$sql_common} {$sql_search}  {$sql_doseongu} {$sql_order} limit {$from_record}, {$rows} ";
    $sql = " select * {$sql_common} {$sql_search}  {$sql_doseongu} {$sql_order} ";
//alert('쿼리문을 달라'.$sql);
    $result = sql_query($sql);
//alert('조건문 확인 :'.$sql);
    $colspan = 6;

    //포지션이 있는 것들만 가져온다.
    $sql_honor_positon = "select * from kmp_MEMBER_HONOR where H_POSITION != '' order by H_POSITION desc";
    $result_position = sql_query($sql_honor_positon);
?>
    <div style="display : flex;">
        <?php for($i=0; $row_position=sql_fetch_array($result_position); $i++){
        ?>
        <div>
        <?php 
            if($row_position['H_USER_PHOTO']){
                //alert('들어는 오니??');
                $thumb = '';
                //있을 경우 썸네일 생성
                if(file_exists(G5_DATA_PATH.'/honor_member/'.$row_position['H_USER_PHOTO'])){
                    //alert('들어는 왔나?');
                    $img = $row_position['H_USER_PHOTO'];
                    $source_path = G5_DATA_PATH."/honor_member/";
                    $target_path = $source_path."thumb/";
                    //폴더 없을 경우 만들기
                    //없을 경우 추가
                    if (!is_dir($target_path)) {
                        @mkdir($target_path, G5_DIR_PERMISSION);
                        @chmod($target_path, G5_DIR_PERMISSION);
                        }
                        $thumb = thumbnail($img,$source_path,$target_path,300,394,false);
                    }
                }
                ?>
                <img src="<?=G5_DATA_URL?>/honor_member/thumb/<?=$thumb?>" alt="">
                <br>
                <b><?=get_honor_position($row_position['H_POSITION'])?> : <?=$row_position['H_USER_NAME']?></b>
                <br>
                <span> 소속 : <?=get_doseongu_name($row_position['H_USER_GROUP_KEY'])?></span>
                </div>
            <?php }?>
    </div>

    <div class="local_ov01 local_ov">
        <h1>명예도선사 검색</h1>
        <?php echo $listall ?>
<!--        <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num"> --><?php //echo number_format($total_count) ?><!--명 </span></span>-->
    </div>

    <form id="fhonor" name="fhonor" class="local_sch01 local_sch"  method="get">
        <input type="hidden" id="co_id" name="co_id" value="honor_member_view">
        <br>
        <label for="do"></label>
        <?php echo get_doseongu_select("do", 0,12, $mb_doseongu)?>
        <label for="sfl" class="sound_only">필터별</label>
        <select name="sfl" id="sfl">
            <option value=""<?php echo get_selected($sfl, ""); ?>>선택해주세요</option>
            <option value="H_USER_NAME"<?php echo get_selected($sfl, "H_USER_NAME"); ?>>회원명</option>
            <option value="H_USER_ID"<?php echo get_selected($sfl, "H_USER_ID"); ?>>ID</option>
        </select>
        <label for="stx" class="sound_only">검색어</label>
        <input type="text" name="stx" value="<?php echo $stx ?>" id="stx">
        <input type="submit" value="검색">
    </form>
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
                    <th scope="col" id="mb_list_doseongu" colspan="">성명</th>
                    <th scope="col" id="mb_list_group" colspan="">퇴직년</th>
                    <th scope="col" id="mb_list_name">현소속</th>
                    <th scope="col" id="mb_list_doseongu" colspan="">성명</th>
                    <th scope="col" id="mb_list_group" colspan="">퇴직년</th>
                    <th scope="col" id="mb_list_name">현소속</th>
                </tr>
                <tr>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i=0; $row_member=sql_fetch_array($result); $i++) {
                    
                    $bg = 'bg'.($i%2);
                    if($i%2 == 0){
                    ?>
                    <tr class="<?php echo $bg; ?>">
                    <?php }?>
                        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row_member['H_USER_NAME']); ?></td>
                        <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row_member['H_RETIRE_DATE']); ?></td>
                        <td headers="mb_list_id" colspan="" class="td_name sv_use">
                            <?php echo get_doseongu_name($row_member['H_USER_GROUP_KEY']) ?>
                        </td>
                    <?php if($i%3 == 0 && $i != 0){?>    
                    </tr>
                    <?php }?>    
                    <?php
                }
                if ($i == 0)
                    echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                ?>
                </tbody>
            </table>
        </div>

<!-- <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?co_id=member_search'.$qstr.'&amp;do='.$mb_doseongu.'&amp;gr='.$mb_group.'&amp;sfl='.$sfl.'&amp;stx='.$stx.'&amp;page='.$page); ?> -->

