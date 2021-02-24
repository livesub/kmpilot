<?php
include_once('./_common.php');
auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_PATH.'/head.sub.php');

$edu_idx = $_GET['edu_idx'];
$edu_type = $_GET['edu_type'];
$choice_type = $_GET['choice_type'];
$edu_onoff_type = $_GET['edu_onoff_type'];

$search = "";
if($_GET['sfl'] != "" && $_GET['stx'] != ""){
    //검색
    $search = " and {$_GET[sfl]} like '%{$_GET[stx]}%' ";
}

if($edu_idx == "" || $edu_type == "" || $choice_type == "" || $edu_onoff_type == ""){
    echo "<script>alert('잘못된 경로 입니다.');win_close();</script>";
    exit;
}

if($edu_onoff_type == "on"){
    $title_ment = "온라인 수강자 등록";
}else{
    $title_ment = "오프라인 수강자 등록";
}


//신청된 사람 찾기(신청자는 제외 하고 나오게)
$sql = " select mb_id,mb_name from kmp_pilot_edu_apply where edu_idx = '{$edu_idx}' and edu_type = '{$edu_type}' and apply_cancel = 'N' ";
$result = sql_query($sql);
$num_rows = sql_num_rows($result);

$j = 1;
$mk_sql = "";
if($num_rows != 0){
    $add_and_s = " and (";
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $add_where .= " mb_id != '{$row['mb_id']}' ";
        if($num_rows > $j){
            $add_where .= " and ";
            $j++;
        }
    }
    $add_and_e = " )";
    $mk_sql = $add_and_s.$add_where.$add_and_e;
}


?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?=$title_ment?> | 한국도선사협회</title>
<?php
if (defined('G5_IS_ADMIN')) {
    if(!defined('_THEME_PREVIEW_'))
        echo '<link rel="stylesheet" href="'.run_replace('head_css_url', G5_ADMIN_URL.'/css/admin.css?ver='.G5_CSS_VER, G5_URL).'">'.PHP_EOL;
} else {
    echo '<link rel="stylesheet" href="'.run_replace('head_css_url', G5_CSS_URL.'/'.(G5_IS_MOBILE ?'mobile':'default').'.css?ver='.G5_CSS_VER, G5_URL).'">'.PHP_EOL;
}
?>
<!--[if lte IE 8]>
<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
<![endif]-->
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "<?php echo G5_URL ?>";
<?php
//관리자 페이지에서 제어 할때 때문에 제어
if(strpos($PHP_SELF,"adm")){
?>
    var g5_bbs_url   = "<?php echo G5_ADMIN_BBS_URL ?>";
<?php
}else{
?>
    var g5_bbs_url   = "<?php echo G5_BBS_URL ?>";
<?php
}
?>

var g5_is_member = "<?php echo isset($is_member)?$is_member:''; ?>";
var g5_is_admin  = "<?php echo isset($is_admin)?$is_admin:''; ?>";
var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
var g5_bo_table  = "<?php echo isset($bo_table)?$bo_table:''; ?>";
var g5_sca       = "<?php echo isset($sca)?$sca:''; ?>";
var g5_editor    = "<?php echo ($config['cf_editor'] && $board['bo_use_dhtml_editor'])?$config['cf_editor']:''; ?>";
var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
<?php if(defined('G5_IS_ADMIN')) { ?>
var g5_admin_url = "<?php echo G5_ADMIN_URL; ?>";
<?php } ?>
</script>
<script src="<?=G5_JS_URL?>/jquery-1.12.4.min.js"></script>
<script src="<?=G5_JS_URL?>/jquery-migrate-1.4.1.min.js"></script>
<script src="<?=G5_JS_URL?>/jquery.menu.js?ver='.G5_JS_VER.'"></script>
<script src="<?=G5_JS_URL?>/common.js?ver='.G5_JS_VER.'"></script>
<script src="<?=G5_JS_URL?>/wrest.js?ver='.G5_JS_VER.'"></script>
<script src="<?=G5_JS_URL?>/placeholders.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>

<link rel="stylesheet" href="<?=G5_JS_URL?>/font-awesome/css/font-awesome.min.css">


</head>
<body<?php echo isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>



<div id="wrapper">

    <div id="container" class="container-small">

        <h1 id="container_title"><?=$title_ment?></h1>
        <div class="container_wr">
            <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
            <input type="hidden" name="edu_idx" id="edu_idx" value="<?=$edu_idx?>">
            <input type="hidden" name="edu_type" id="edu_type" value="<?=$edu_type?>">
            <input type="hidden" name="choice_type" id="choice_type" value="<?=$choice_type?>">
            <input type="hidden" name="edu_onoff_type" id="edu_onoff_type" value="<?=$edu_onoff_type?>">

            <label for="sfl" class="sound_only">검색대상</label>
            <select name="sfl" id="sfl">
                <option value="">분류선택</option>
                <option value="mb_id" <?php if($_GET['sfl'] == "mb_id") echo "selected";?>>ID</option>
                <option value="mb_name" <?php if($_GET['sfl'] == "mb_name") echo "selected";?>>이름</option>
                <option value="mb_doseongu" <?php if($_GET['sfl'] == "mb_doseongu") echo "selected";?>>도선구</option>
                <option value="mb_hp" <?php if($_GET['sfl'] == "mb_hp") echo "selected";?>>휴대폰</option>
            </select>
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
            <input type="submit" class="btn_submit" value="검색">
            </form>

            <div class="local_desc01 local_desc">
                <p>
                    도선구 검색시 번호를 등록 하세요.<br>
                    "1" : 부산항, "2" : 여수항, "3" : 인천항, "4" : 울산항, "5" : 평택항, "6" : 마산항 <br>
                    "7" : 대산항, "8" : 포항항, "9" : 군산항, "10" : 목포항, "11" : 동해항, "12" : 제주항
                </p>
            </div>

            <form name="edu_apply_from" id="edu_apply_from" onsubmit="return edu_apply_submit(this);" method="post">
            <input type="hidden" name="edu_idx" id="edu_idx_form" value="<?=$edu_idx?>">
            <input type="hidden" name="edu_type" id="edu_type_form" value="<?=$edu_type?>">
            <input type="hidden" name="choice_type" id="choice_type_form" value="<?=$choice_type?>">
            <input type="hidden" name="edu_onoff_type" id="edu_onoff_type_form" value="<?=$edu_onoff_type?>">
            <input type="hidden" name="page" id="page" value="">
            <input type="hidden" name="w" id="w" value="d">

            <div class="tbl_head01 tbl_wrap" style="width:700px;">
                <table>
                <caption><?=$title_ment?></caption>
                <thead>
                <tr>
                    <th scope="col" id="apply_list_chk" rowspan="2" >
                        <label for="chkall" class="sound_only">전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                    <th scope="col">아이디</th>
                    <th scope="col">이름</a></th>
                    <th scope="col">도선구</th>
                    <th scope="col">휴대폰</th>
                </tr>

                </thead>
                <tbody>
<?php
$sql_m = " select mb_no, mb_id, mb_name, mb_doseongu,mb_hp from kmp_member where (mb_intercept_date = '' OR mb_memo = '' OR mb_leave_date = '') {$mk_sql} {$search} order by mb_no desc";
$result_m = sql_query($sql_m);

for ($i=0; $row_m=sql_fetch_array($result_m); $i++) {
?>
                    <tr>
                    <td><input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row_m['mb_no'] ?>"></td>
                        <td><?=$row_m['mb_id']?></td>
                        <td><?=$row_m['mb_name']?></td>
                        <td><?=get_doseongu_name($row_m['mb_doseongu'])?></td>
                        <td><?=$row_m['mb_hp']?></td>
                    </tr>
<?php
}
if (!$i)
echo "<tr><td colspan='5' class=\"empty_table\">회원이 없습니다.</td></tr>";
?>
                </tbody>
                </table>
            </div>

            <div align="center">
                <input type="submit" name="act_button" value="등록" onclick="document.pressed=this.value" class="btn btn_01">
            </div>

            </form>
        </div>

<script>
    function edu_apply_submit(){
        if (!is_checked("chk[]")) {
            alert(document.pressed+" 하실 회원을 하나 이상 선택하세요.");
            return false;
        }

        if(confirm("선택한 회원을 등록 하시겠습니까?")) {
            var ajaxUrl = "ajax_admin_edu_apply_regi.php";
            var queryString = $("form[name=edu_apply_from]").serialize() ;

            $.ajax({
                type		: "POST",
                dataType    : 'text',
                url			: ajaxUrl,
                data        : queryString,
                success: function(data){
                    if(trim(data) == "no_idx"){
                        alert('등록 하실 회원을 하나 이상 선택하세요.');
                        return false;
                    }

                    if(trim(data) == "fail"){
                        alert('잘못된 경로입니다.');
                        return false;
                    }

                    if(trim(data) == "ok"){
                        alert("등록 되었습니다.");
                        win_close();
                        opener.location.reload();
                        //location.reload();
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
</script>

<script>
    function win_close(){
        window.open('', '_self', '');
        window.close();
        return false;
    }
</script>


<script>
$(".scroll_top").click(function(){
     $("body,html").animate({scrollTop:0},400);
})
</script>

<!-- <p>실행시간 : <?php echo get_microtime() - $begin_time; ?> -->

<script src="<?php echo G5_ADMIN_URL ?>/admin.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script src="<?php echo G5_JS_URL ?>/jquery.anchorScroll.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script>
$(function(){

    var admin_head_height = $("#hd_top").height() + $("#container_title").height() + 5;

    $("a[href^='#']").anchorScroll({
        scrollSpeed: 0, // scroll speed
        offsetTop: admin_head_height, // offset for fixed top bars (defaults to 0)
        onScroll: function () {
          // callback on scroll start
        },
        scrollEnd: function () {
          // callback on scroll end
        }
    });

    var hide_menu = false;
    var mouse_event = false;
    var oldX = oldY = 0;

    $(document).mousemove(function(e) {
        if(oldX == 0) {
            oldX = e.pageX;
            oldY = e.pageY;
        }

        if(oldX != e.pageX || oldY != e.pageY) {
            mouse_event = true;
        }
    });

    // 주메뉴
    var $gnb = $(".gnb_1dli > a");
    $gnb.mouseover(function() {
        if(mouse_event) {
            $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
            $(this).parent().addClass("gnb_1dli_over gnb_1dli_on");
            menu_rearrange($(this).parent());
            hide_menu = false;
        }
    });

    $gnb.mouseout(function() {
        hide_menu = true;
    });

    $(".gnb_2dli").mouseover(function() {
        hide_menu = false;
    });

    $(".gnb_2dli").mouseout(function() {
        hide_menu = true;
    });

    $gnb.focusin(function() {
        $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
        $(this).parent().addClass("gnb_1dli_over gnb_1dli_on");
        menu_rearrange($(this).parent());
        hide_menu = false;
    });

    $gnb.focusout(function() {
        hide_menu = true;
    });

    $(".gnb_2da").focusin(function() {
        $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
        var $gnb_li = $(this).closest(".gnb_1dli").addClass("gnb_1dli_over gnb_1dli_on");
        menu_rearrange($(this).closest(".gnb_1dli"));
        hide_menu = false;
    });

    $(".gnb_2da").focusout(function() {
        hide_menu = true;
    });

    $('#gnb_1dul>li').bind('mouseleave',function(){
        submenu_hide();
    });

    $(document).bind('click focusin',function(){
        if(hide_menu) {
            submenu_hide();
        }
    });

    // 폰트 리사이즈 쿠키있으면 실행
    var font_resize_act = get_cookie("ck_font_resize_act");
    if(font_resize_act != "") {
        font_resize("container", font_resize_act);
    }
});

function submenu_hide() {
    $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
}

function menu_rearrange(el)
{
    var width = $("#gnb_1dul").width();
    var left = w1 = w2 = 0;
    var idx = $(".gnb_1dli").index(el);

    for(i=0; i<=idx; i++) {
        w1 = $(".gnb_1dli:eq("+i+")").outerWidth();
        w2 = $(".gnb_2dli > a:eq("+i+")").outerWidth(true);

        if((left + w2) > width) {
            el.removeClass("gnb_1dli_over").addClass("gnb_1dli_over2");
        }

        left += w1;
    }
}

</script>


</body>
</html>