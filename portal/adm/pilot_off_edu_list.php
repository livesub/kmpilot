<?php
$sub_menu = "400100";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

//sfl=edu_type&stx=s
$search = "";
if($_GET['sfl'] != "" && $_GET['stx'] != ""){
    //검색
    $search = " and {$_GET[sfl]} like '%{$_GET[stx]}%' ";
}

$sql_common = " from kmp_pilot_edu_list ";
$sql_order = " where (edu_type = 'CR' OR edu_type = 'CE') and edu_onoff_type = 'off' and edu_del_type ='N' {$search} order by edu_idx desc";

$sql = " select count(*) as cnt {$sql_common} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$g5['title'] = '오프라인 교육 리스트';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
?>


<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="">교육종류선택</option>
    <option value="edu_type_name" <?php if($_GET['sfl'] == "edu_type_name") echo "selected";?>>교육종류</option>
    <option value="edu_name_kr" <?php if($_GET['sfl'] == "edu_name_kr") echo "selected";?>>교육명(한글)</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">

</form>

<form name="edu_list_from" id="edu_list_from" onsubmit="return edu_list_submit(this);" method="post">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="w" value="d">

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <?php if ($is_admin == 'super') { ?>
    <a href="./pilot_edu_regi.php?edu_onoff_type=off" id="edu_add" class="btn btn_01">교육등록</a>
    <?php } ?>
    <a href="./pilot_mending_regi.php" id="renewal_add" class="btn btn_01">신청자 관리</a>
</div>


<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" rowspan="2" >
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">순번</th>
        <th scope="col">교육종류</th>
        <th scope="col">교육명</a></th>
        <th scope="col">접수현황</th>
        <th scope="col">수정</th>
        <th scope="col" colspan="2">수강신청<br>현황관리</th>
    </tr>

    </thead>
    <tbody>

<?php
    $virtual_num = $total_count - $rows * ($page - 1);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $edu_ment = "";
        $edu_ment = edu_type($row['edu_type']);
        //접수 마감 로직 작업 해야 함(날짜 지나도 접수 마감이며, 인원이 꽉 차도 접수 마감이다)
        $edu_receipt_status_dis = edu_receipt_status($row['edu_receipt_status']);
?>
    <tr>
        <td><input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row['edu_idx'] ?>"></td>
        <td><?=$virtual_num?></td>
        <td><?=$edu_ment?></td>
        <td><?=$row['edu_name_kr']?></td>
        <td><?=$edu_receipt_status_dis?><br>20 / <?=$row['edu_person']?></td>
        <td><input type="button" class="btn btn_02" value="수정하기" onclick="location.href='pilot_edu_regi.php?edu_onoff_type=off&edu_idx=<?=$row[edu_idx]?>&edu_type=<?=$row[edu_type]?>&w=u' "></td>
        <td><button class="btn btn_02">리스트</button></td>
        <td><button class="btn btn_02">관리</button></td>
    </tr>
<?php
        $virtual_num--;
    }
    if (!$i)
        echo "<tr><td colspan='7' class=\"empty_table\">자료가 없습니다.</td></tr>";
?>
    </tbody>
    </table>
</div>
</form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function edu_list_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            var ajaxUrl = "ajax_edu_regi.php";
            var queryString = $("form[name=edu_list_from]").serialize() ;

            $.ajax({
                type		: "POST",
                dataType    : 'text',
                url			: ajaxUrl,
                data        : queryString,
                success: function(data){
                    if(trim(data) == "no_idx"){
                        alert('삭제할 메일목록을 1개이상 선택해 주세요.');
                        return false;
                    }

                    if(trim(data) == "ok"){
                        alert("삭제 되었습니다.");
                    }
                },
                error: function () {
                        console.log('error');
                }
            });
        }else{
            return false;
        }
    }

    return true;
}
</script>




<?php
include_once ('./admin.tail.php');