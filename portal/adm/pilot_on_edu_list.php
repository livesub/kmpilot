<?php
$sub_menu = "400200";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

//sfl=edu_type&stx=s
$search = "";
if($_GET['sfl'] != "" && $_GET['stx'] != ""){
    //검색
    $search = " and {$_GET[sfl]} like '%{$_GET[stx]}%' ";
}

//년도 검색
$year_ch = $_GET['year_ch'];
$default_year = 2021;
$now_year = date("Y");
if($year_ch == "") $select_y = $now_year;
else $select_y = $year_ch;

$sql_common = " from kmp_pilot_edu_list ";
$sql_order = " where (edu_type = 'CC' OR edu_type = 'CN') and edu_onoff_type = 'on' and edu_del_type ='N' {$search} and edu_regi like '%{$select_y}%' order by edu_idx desc";

$sql = " select count(*) as cnt {$sql_common} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$g5['title'] = '온라인 교육 리스트';
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
        <td> 등록 년도 검색
            <select name="year_ch" id="year_ch" onchange = "year_change();">
<?php
    for($k = $default_year; $k <= $now_year; $k++){
?>
                <option value="<?=$k?>" <?php if($k == $select_y) echo "selected"?>><?=$k?></option>
<?php
    }
?>
            </select>
        </td>
</form>


<form name="edu_list_from" id="edu_list_from" onsubmit="return edu_list_submit(this);" method="post">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="w" value="d">

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <?php if ($is_admin == 'super') { ?>
    <a href="./pilot_edu_regi.php?edu_onoff_type=on" id="edu_add" class="btn btn_01">교육등록</a>
    <?php } ?>
    <input type="button" class="btn btn_01" value="전체 신청자 리스트" onclick="apply_manage_list('','','on','all');">
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
        <th scope="col">교육명</th>
        <th scope="col">접수현황</th>
        <th scope="col">등록</th>
        <th scope="col">수정</th>
        <th scope="col" colspan="2">수강신청<br>현황관리</th>
    </tr>

    </thead>
    <tbody>

<?php
    $virtual_num = $total_count - $rows * ($page - 1);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $list_button = "";
        $edu_ment = "";
        $edu_ment = edu_type($row['edu_type']);

        $sql_cnt = " select count(*) as cnt from kmp_pilot_edu_apply where apply_cancel = 'N' and edu_idx = '{$row[edu_idx]}' and edu_type = '{$row[edu_type]}' ";
        $row_cnt = sql_fetch($sql_cnt);
        if($row_cnt['cnt'] == 0){
            $list_button = "<input type='button' class='btn btn_02' value='리스트' onclick='alert(\"신청자가 없습니다.\");return false;'>";
        }else{
            $list_button = "<input type='button' class='btn btn_02' value='리스트' onclick='excel_down(\"{$row[edu_idx]}\",\"{$row[edu_type]}\",\"{$row[edu_onoff_type]}\");'>";
        }

        //접수 마감 로직 작업 해야 함(날짜 지나도 접수 마감이며, 인원이 꽉 차도 접수 마감이다) - 나중에 유지 보수로 해달라 할때 해줌으로 대표님과 협의
        $edu_receipt_status_dis = edu_receipt_status($row['edu_receipt_status']);
?>
    <tr>
        <td><input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row['edu_idx'] ?>"></td>
        <td><?=$virtual_num?></td>
        <td><?=$edu_ment?></td>
        <td><?=$row['edu_name_kr']?></td>
        <td><?=$edu_receipt_status_dis?><br><?=$row_cnt['cnt']?> / <?=$row['edu_person']?></td>
        <td><input type="button" class="btn btn_02" value="강의등록" onclick="location.href='pilot_lecture_regi.php?edu_onoff_type=on&edu_idx=<?=$row[edu_idx]?>&edu_type=<?=$row[edu_type]?>' "></td>
        <td><input type="button" class="btn btn_02" value="수정하기" onclick="location.href='pilot_edu_regi.php?edu_onoff_type=on&edu_idx=<?=$row[edu_idx]?>&edu_type=<?=$row[edu_type]?>&w=u' "></td>
        <td><?=$list_button?></td>
        <td><input type="button" class="btn btn_02" value="관리" onclick="apply_manage_list('<?=$row[edu_idx]?>','<?=$row[edu_type]?>','<?=$row[edu_onoff_type]?>','select');"></td>
    </tr>
<?php
        $virtual_num--;
    }
    if (!$i)
        echo "<tr><td colspan='8' class=\"empty_table\">자료가 없습니다.</td></tr>";
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

<script>
    function year_change(){
        location.href = "pilot_on_edu_list.php?year_ch="+$("#year_ch option:selected").val();
    }
</script>

<form name="form_submit" id="form_submit" method="POST" action="">
    <input type="hidden" name="edu_idx" id="edu_idx" vlaue="">
    <input type="hidden" name="edu_type" id="edu_type" vlaue="">
    <input type="hidden" name="edu_onoff_type" id="edu_onoff_type" vlaue="">
    <input type="hidden" name="choice_type" id="choice_type" vlaue="">
</form>

<script>
    function excel_down(edu_idx,edu_type,edu_onoff_type){
        $("#edu_idx").val(edu_idx);
        $("#edu_type").val(edu_type);
        $("#edu_onoff_type").val(edu_onoff_type);
        $("#form_submit").attr("action", "pilot_apply_excel_down.php");
        $("#form_submit").submit();
    }
</script>

<script>
    function apply_manage_list(edu_idx='',edu_type='',edu_onoff_type,choice_type){
        $("#edu_idx").val(edu_idx);
        $("#edu_type").val(edu_type);
        $("#edu_onoff_type").val(edu_onoff_type);
        $("#choice_type").val(choice_type);
        $("#form_submit").attr("method", "get");
        $("#form_submit").attr("action", "pilot_on_apply_manage_list.php");
        $("#form_submit").submit();
    }
</script>

<?php
include_once ('./admin.tail.php');