<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
}
else {
    // 상태바에 표시될 제목
    $g5_head_title = implode(' | ', array_filter(array($g5['title'], $config['cf_title'])));
}

$g5['title'] = strip_tags($g5['title']);
$g5_head_title = strip_tags($g5_head_title);


/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">



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

<?php
add_javascript('<script src="'.G5_JS_URL.'/jquery-1.12.4.min.js"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/jquery-migrate-1.4.1.min.js"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/jquery.menu.js?ver='.G5_JS_VER.'"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/common.js?ver='.G5_JS_VER.'"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/wrest.js?ver='.G5_JS_VER.'"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/placeholders.min.js"></script>', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/font-awesome/css/font-awesome.min.css">', 0);
/*
if(G5_IS_MOBILE) {
    add_javascript('<script src="'.G5_JS_URL.'/modernizr.custom.70111.js"></script>', 1); // overflow scroll 감지
}
*/
if(!defined('G5_IS_ADMIN'))
    echo $config['cf_add_script'];
?>


    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.1/css/swiper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.1/js/swiper.min.js"></script>

    <!-- kakao map -->
<!--    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=2c6fad3af712ab58d7b8b0c66f379400"></script>-->

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?=G5_PUBLISH_URL?>/scss/reset.css">
    <link rel="stylesheet" href="<?=G5_PUBLISH_URL?>/scss/layout.css">
    <link rel="stylesheet" href="<?=G5_PUBLISH_URL?>/scss/style.css">
    <link rel="stylesheet" href="<?=G5_PUBLISH_URL?>/scss/responsive.css">

    <title><?=$lang['title']?></title>
</head>
<body>