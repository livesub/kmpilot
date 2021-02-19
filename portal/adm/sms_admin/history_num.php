<?php
$sub_menu = '900410';
include_once('./_common.php');

$page_size = 20;
$colspan = 10;

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = "발송회원 보기";

if ($page < 1) $page = 1;

$line = 0;

$idx = $_REQUEST['IDX'];
if($idx != ""){
    $sql_IDX = " and PARENTIDX = $idx ";
}else{
    $sql_IDX ="";
}
$sr = $_REQUEST['sr'];
    switch ($sr){
        case "success": $sql_sr = " and SEND_RESULT = '1' ";break;
        case "fail": $sql_sr = " and SEND_RESULT = '0' "; break;
        case "error": $sql_sr = " and RECEIVE = '0' "; break;
        default : $sql_sr = ""; break;
    }
//alert($idx);
//if( isset($st) && !in_array($st, array('RECV_NAME', 'RPHONE3')) ){
//    $st = '';
//}

if ($st && trim($sv))
    $sql_search = " and $st like '%$sv%' ";
else
    $sql_search = "";

$total_res = sql_fetch("select count(*) as cnt from CMS_SMS_RESULT where IDX != 0 and IS_DEL != '1' {$sql_IDX} {$sql_sr} {$sql_search} ");
$total_count = $total_res['cnt'];

$total_page = (int)($total_count/$page_size) + ($total_count%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );

$vnum = $total_count - (($page-1) * $page_size);

include_once(G5_ADMIN_PATH.'/admin.head.php');
//alert($_SERVER['SCRIPT_NAME']);
//$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME']."?st=$st&amp;sv=$sv&amp;sr=$sr&amp;IDX=$idx&amp;page=";
?>

<form name="search_form" method="get" action="<?=$_SERVER['SCRIPT_NAME'];?>" class="local_sch01 local_sch" >
<label for="sr">전송결과</label>
    <select name="sr" id="sr">
        <option value=""<?php echo get_selected('', $sr); ?>>선택해주세요</option>
        <option value="success"<?php echo get_selected('success', $sr); ?>>성공</option>
        <option value="fail"<?php echo get_selected('fail', $sr); ?>>실패</option>
        <option value="error"<?php echo get_selected('error', $sr); ?>>오류</option>
    </select>
    <br>
<label for="st" class="sound_only">검색대상</label>
<select name="st" id="st">
    <option value="RECV_NAME"<?php echo get_selected('RECV_NAME', $st); ?>>이름</option>
    <option value="RPHONE3"<?php echo get_selected('RPHONE3', $st); ?>>휴대폰번호(뒷자리4개)</option>
</select>
<input type="hidden" value="<?=$idx?>" name="IDX" id="IDX">
<label for="sv" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="sv" value="<?php echo $sv; ?>" id="sv" >
<input type="submit" value="검색" class="btn_submit">
<!--<input type="reset" value="초기화">-->
</form>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">수신자명</th>
        <th scope="col">수신자ID</th>
        <th scope="col">수신번호</th>
        <th scope="col">전송일시</th>
<!--        <th scope="col">오류</th>-->
        <th scope="col">전송</th>
        <th scope="col">메세지</th>
        <th scope="col">관리</th>
     </tr>
     </thead>
     <tbody>
        <?php if (!$total_count) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" class="empty_table" >
                데이터가 없습니다.
            </td>
        </tr>
    <?php
    }
    $qry = sql_query("select * from CMS_SMS_RESULT where IDX != 0 and IS_DEL != '1'{$sql_IDX} {$sql_sr} {$sql_search} order by IDX desc limit $page_start, $page_size");

    while($res = sql_fetch_array($qry)) {
        $bg = 'bg'.($line++%2);

//        $write = sql_fetch("select * from {$g5['sms5_write_table']} where wr_no='{$res['wr_no']}' and wr_renum=0");
//        $group = sql_fetch("select * from {$g5['sms5_book_group_table']} where bg_no='{$res['bg_no']}'");
//        if ($group)
//            $bg_name = $group['bg_name'];
//        else
//            $bg_name = '없음';
//
//        if ($res['mb_id'])
//            $mb_id = '<a href="'.G5_ADMIN_URL.'/member_form.php?w=u&amp;mb_id='.$res['mb_id'].'">'.$res['mb_id'].'</a>';
//        else
//            $mb_id = '비회원';
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_num"><?php echo $vnum--; ?></td>
        <td class="td_mbname"><?php echo $res['RECV_NAME']; ?></td>
        <td class="td_mbid"><?php echo $res['USER_ID']; ?></td>
        <td class="td_numbig"><?php echo $res['RPHONE1']; ?>-<?php echo $res['RPHONE2']; ?>-<?php echo $res['RPHONE3']; ?></td>
        <td class="td_datetime"><?php echo date('Y-m-d H:i',$res['REG_DATE'])?></td>
<!--        <td class="td_boolean">--><?php //echo $res['RECEIVE']?'없음':'있음'?><!--</td>-->
        <td class="td_boolean"><?php echo $res['SEND_RESULT']?'성공':'실패'?></td>
        <td class="td_left"><span title="<?php echo $res['R_MSG']?>"><?php echo $res['R_MSG']?></span></td>
<!--        <td class="td_mng td_mng_s">-->
<!--            <a href="./history_view.php?page=--><?php //echo $page; ?><!--&amp;st=--><?php //echo $st; ?><!--&amp;sv=--><?php //echo $sv; ?><!--&amp;wr_no=--><?php //echo $res['wr_no']; ?><!--" class="btn btn_03">수정</a>-->
<!--        </td>-->
        <td><button onclick="del_num('정말 삭제하시겠습니까?',<?=$res['IDX']?>)">삭제</button></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME']."?st=$st&amp;sv=$sv&amp;sr=$sr&amp;IDX=$idx&amp;page="); ?>

<?php
//alert($_SERVER['SCRIPT_NAME']."?IDX={$idx}");
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
<script>
    function del_num($msg, $value){
        if(confirm($msg)){
           location.href="./history_num_delete.php?IDX="+$value;
        }else{
            return false;
        }
    }
</script>
