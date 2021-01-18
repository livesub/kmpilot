<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

run_event('pre_head');

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

$ajaxpage = G5_URL.'/lang_change.php';
?>

<!-- 상단 시작 { -->
<div id="hd">

    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
    }
    ?>

    <div id="hd_wrapper">

        <ul class="hd_login">
            <?php if ($is_member) {  ?>
            <li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php"><?=$lang['member_modi']?></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/logout.php"><?=$lang['member_logout']?></a></li>
            <?php if ($is_admin) {  ?>
            <li class="tnb_admin"><a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">관리자</a></li>
            <?php }  ?>
            <?php } else {  ?>
            <li><a href="<?php echo G5_BBS_URL ?>/login.php"><?=$lang['member_login']?></a></li>
            <?php }  ?>
            <select name="lang_change" id="lang_change" onchange="lang_change();">
                <option value="kr" <?php if($lang_type == "kr" || $lang_type == "") echo "selected"?>>KOREA</option>
                <option value="en" <?php if($lang_type == "en") echo "selected"?>>ENGLISH</option>
            </select>

        </ul>
        <ul>
            <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=edu_notice_<?=$lang_type?>"><font color="#ffffff"><?=$lang['menu_notice']?></font></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=edu_video_<?=$lang_type?>"><font color="#ffffff"><?=$lang['menu_movie']?></font></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=pilot_license_renewal_list"><font color="#ffffff"><?=$lang['lecture_title1']?></font></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=pilot_mending_list"><font color="#ffffff"><?=$lang['lecture_title2']?></font></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=pilot_necessary_list"><font color="#ffffff"><?=$lang['lecture_title3']?></font></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=pilot_lecture_chk_list"><font color="#ffffff"><?=$lang['mypage_title1']?></font></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=pilot_certificates_issued_list"><font color="#ffffff"><?=$lang['mypage_title2']?></font></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/content.php?co_id=pilot_mypage"><font color="#ffffff"><?=$lang['member_mypage']?></font></a></li>
        </ul>
    </div>

<script>
    function lang_change()
    {
        var ajaxUrl = "<?=$ajaxpage?>";
        $.ajax({
            type		: "POST",
            dataType    : "text",
            url			: ajaxUrl,
            data		: {
                "lang_type" : $("#lang_change").val(),
            },
            success: function(data){
                if(trim(data) == "OK"){
                    location.href = "<?=G5_URL?>";
                    //location.reload();
                }
                console.log(data);
            },
            error: function () {
                    console.log('error');
            }
        });
    }
</script>




</div>
<!-- } 상단 끝 -->


<hr>

<!-- 콘텐츠 시작 { -->
<div id="wrapper">
    <div id="container_wr">

    <div id="container">
