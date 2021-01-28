<?php
$sub_menu = "900400";
include_once("./_common.php");

$page_size = 20;
$colspan = 11;

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = "문자전송 내역";

$sms_mean = $_REQUEST['SMS_MEAN'];
$fDate = $_REQUEST['fDate'];
$lDate = $_REQUEST['lDate'];
//alert('fDate'.$fDate.'   lDate'.$lDate);
if ($page < 1) $page = 1;

if (isset($sv) && trim($sv) && trim($sms_mean)){
    switch ($sms_mean){
        case "SMS_TYPE" :
        case "SPHONE3" :
        case "SEND_TYPE" : $sql_search = " and $sms_mean = '$sv' "; break;
        case "SMS_MSG" : $sql_search = " and $sms_mean like '%$sv%' "; break;
        default : $sql_search = "";
    }
}else{
    $sql_search = "";
}
if($fDate != ""){
    $a  = true;
}else{$a = false;}

if($lDate != ""){
    $b  = true;
}else{$b = false;}

//alert('첫번째 일자 데이터 '.$a." 두번째 일자 데이터 ".$b);
if((isset($fDate) && $fDate != "") && (isset($lDate) && $lDate != "") ){
    $sql_date_search = " and from_unixtime(REG_DATE, '%Y%m%d') between '".str_replace("-","",$fDate)."' and '".str_replace("-","",$lDate)."'";
}elseif($a && !$b){
    //alert('첫번째 일자 데이터 '.$fDate." 두번째 일자 데이터 ".$lDate);
    $sql_date_search = " and from_unixtime(REG_DATE, '%Y%m%d') = '".str_replace("-","",$fDate)."'";
}elseif(!$a && $b){
    //alert('첫번째 일자 데이터 '.$fDate." 두번째 일자 데이터 ".$lDate);
    $sql_date_search = " and from_unixtime(REG_DATE, '%Y%m%d') = '".str_replace("-","",$lDate)."'";
}elseif(!$a && !$b){
    //alert('첫번째 일자 데이터 '.$fDate." 두번째 일자 데이터 ".$lDate);
    $sql_date_search = "";
}
//alert('방향'.$sql_date_search);
//alert('시간좀 알아보기 1 : '.strtotime($fDate).' 시간 알아보기 2 : '.strtotime($lDate));

$total_res = sql_fetch("select count(*) as cnt from CMS_SMS_DATA where IS_DEL='0' {$sql_search} {$sql_date_search}");
//alert('search 쿼리문 : '.$sql_search.' date 쿼리문'.$sql_date_search);
$total_count = $total_res['cnt'];

$total_page = (int)($total_count/$page_size) + ($total_count%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );

$vnum = $total_count - (($page-1) * $page_size);
$line = 0;
include_once(G5_ADMIN_PATH.'/admin.head.php');
//alert('action 방향'.$_SERVER['SCRIPT_NAME']);
?>

<form name="search_form" id="search_form" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" class="local_sch01 local_sch" method="get">
    <label for="fDate">날짜 선택</label>
    <input type="date" id="fDate" name="fDate" value="<?php echo $fDate?>"> ~ <input type="date" id="lDate" name="lDate" value="<?php echo $lDate?>"><br>
<lable for="SMS_MEAN">검색종류</lable>
    <?php echo get_sms_mean_value("SMS_MEAN", 0, 4, $sms_mean)?>
<!--<label for="st" class="sound_only">검색대상</label>-->
<!--<input type="hidden" name="st" id="st" value="wr_message" >-->
<label for="sv" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="sv" value="<?php echo $sv ?>" id="sv">
<input type="submit" value="검색" class="btn_submit">
</form>
    <div id="tab_container">
        <ul class="tabs">
            <li rel="tab1"><a href="<?=$_SERVER['SCRIPT_NAME']?>"><span>전체</span></a></li>
            <li rel="tab2"><a href="<?=$_SERVER['SCRIPT_NAME']."?&amp;sv=S&amp;SMS_MEAN=SMS_TYPE&amp;page=1"?>"><span class="fs">단문 (SMS)</span></a></li>
            <li rel="tab3"><a href="<?=$_SERVER['SCRIPT_NAME']."?&amp;sv=M&amp;SMS_MEAN=SMS_TYPE&amp;page=1"?>"><span class="fs">장문 (MMS)</span></a></li>
        </ul>
    </div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">발송구분</th>
        <th scope="col">발신구분</th>
        <th scope="col">회신번호</th>
        <th scope="col">메세지</th>
        <th scope="col">전송일시</th>
        <th scope="col">보낸시간</th>
        <th scope="col">발송인원</th>
        <th scope="col">관리</th>
<!--        <th scope="col">총건수</th>-->
<!--        <th scope="col">성공</th>-->
<!--        <th scope="col">실패</th>-->
<!--        <th scope="col">중복</th>-->
<!--        <th scope="col">재전송</th>-->
<!--        <th scope="col">관리</th>-->
     </tr>
     </thead>
     <tbody>
    <?php if (!$total_count) { ?>
    <tr>
        <td colspan="<?php echo $colspan?>" class="empty_table" >
            데이터가 없습니다.
        </td>
    </tr>
    <?php
    }
    $qry = sql_query("select IDX,SEND_TYPE,SMS_TYPE,SPHONE1,SPHONE2,SPHONE3,SMS_MSG,S_COUNT,from_unixtime(REG_DATE, '%Y%m%d'), IS_DEL, REG_DATE from CMS_SMS_DATA where IS_DEL='0' $sql_search $sql_date_search order by IDX desc limit $page_start, $page_size");
    //alert('방향'.$qry);
    while($res = sql_fetch_array($qry)) {
        $bg = 'bg'.($line++%2);
        $tmp_wr_memo = @unserialize($res['wr_memo']);
        $dupli_count = (isset($tmp_wr_memo['total']) && $tmp_wr_memo['total']) ? (int) $tmp_wr_memo['total'] : 0;
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_numsmall"><?php echo $vnum--?></td>
        <td class="td_numsmall"><?php echo $res['SMS_TYPE']--?></td>
        <td class="td_numsmall"><?php echo $res['SEND_TYPE']--?></td>
        <td class="td_tel"><?php echo $res['SPHONE1']?>-<?php echo $res['SPHONE2']?>-<?php echo $res['SPHONE3']?></td>
        <td class="td_left"><span title="<?php echo $res['SMS_MSG']?>"><?php echo $res['SMS_MSG']?></span></td>
        <td class="td_datetime"><?=date('Y-m-d', $res['REG_DATE'])?></td>
        <td class="td_datetime"><?=date('H:i', $res['REG_DATE'])?></td>
        <td class="td_num"><?=$res['S_COUNT']?></td>
        <td>발송회원보기/삭제</td>
<!--        <td class="td_boolean">--><?php //echo $res['wr_booking']!='0000-00-00 00:00:00'?"<span title='{$res['wr_booking']}'>예약</span>":'';?><!--</td>-->
<!--        <td class="td_num">--><?php //echo number_format($res['wr_total'])?><!--</td>-->
<!--        <td class="td_num">--><?php //echo number_format($res['wr_success'])?><!--</td>-->
<!--        <td class="td_num">--><?php //echo number_format($res['wr_failure'])?><!--</td>-->
<!--        <td class="td_num">--><?php //echo $dupli_count;?><!--</td>-->
<!--        <td class="td_num">--><?php //echo number_format($res['wr_re_total'])?><!--</td>-->
<!--        <td class="td_mng td_mng_s">-->
<!--            <a href="./history_view.php?page=--><?php //echo $page;?><!--&amp;st=--><?php //echo $st;?><!--&amp;sv=--><?php //echo $sv;?><!--&amp;wr_no=--><?php //echo $res['wr_no'];?><!--" class="btn btn_03">수정</a>-->
<!--            <a href="./history_del.php?page=--><?php //echo $page;?><!--&amp;st=--><?php //echo $st;?><!--&amp;sv=--><?php //echo $sv;?><!--&amp;wr_no=--><?php //echo $res['wr_no'];?><!--">삭제</a> -->
<!--        </td>-->
    </tr>
    <?php } ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME']."?&amp;sv=$sv&amp;SMS_MEAN=$sms_mean&amp;fDate=$fDate&amp;lDate=$lDate&amp;page="); ?>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
