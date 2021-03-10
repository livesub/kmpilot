<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

run_event('pre_head');

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

//언어팩 쿠키 저장 함수 경로 추가
$ajaxpage = G5_URL.'/lang_change_portal.php';
?>
<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
    }
    ?>
    <div id="tnb">
    	<div class="inner">
			<ul id="hd_qnb">
	            <li><a href="<?php echo G5_BBS_URL ?>/faq.php">FAQ</a></li>
	            <li><a href="<?php echo G5_BBS_URL ?>/qalist.php">Q&A</a></li>
	            <li><a href="<?php echo G5_BBS_URL ?>/new.php">새글</a></li>
	            <li><a href="<?php echo G5_BBS_URL ?>/current_connect.php" class="visit"><?=$lang['connect_user']?><strong class="visit-num"><?php echo connect(); // 현재 접속자수, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?></strong></a></li>
	            <li>
<?php
    if($lang_type_portal == "kr" || $lang_type_portal == ""){
?>
    <a href="javascript:lang_change('en');">ENG</a>
<?php
    }else{
?>
    <a href="javascript:lang_change('kr');">KOR</a>
<?php
    }
?>

<!--
	            <select name="lang_change" id="lang_change" onchange="lang_change();">
                    <option value="kr" <?php if($lang_type_portal == "kr" || $lang_type_portal == "") echo "selected"?>>KOREA</option>
                    <option value="en" <?php if($lang_type_portal == "en") echo "selected"?>>ENGLISH</option>
                </select>
-->
                </li>
	        </ul>
		</div>
    </div>
    <div id="hd_wrapper">

        <div id="logo">
            <a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>"></a>
        </div>

<!--        <div class="hd_sch_wr">-->
<!--            <fieldset id="hd_sch">-->
<!--                <legend>사이트 내 전체검색</legend>-->
<!--                <form name="fsearchbox" method="get" action="--><?php //echo G5_BBS_URL ?><!--/search.php" onsubmit="return fsearchbox_submit(this);">-->
<!--                <input type="hidden" name="sfl" value="wr_subject||wr_content">-->
<!--                <input type="hidden" name="sop" value="and">-->
<!--                <label for="sch_stx" class="sound_only">검색어 필수</label>-->
<!--                <input type="text" name="stx" id="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">-->
<!--                <button type="submit" id="sch_submit" value="검색"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>-->
<!--                </form>-->
<!---->
<!--                <script>-->
<!--                function fsearchbox_submit(f)-->
<!--                {-->
<!--                    if (f.stx.value.length < 2) {-->
<!--                        alert("검색어는 두글자 이상 입력하십시오.");-->
<!--                        f.stx.select();-->
<!--                        f.stx.focus();-->
<!--                        return false;-->
<!--                    }-->
<!---->
<!--                    // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.-->
<!--                    var cnt = 0;-->
<!--                    for (var i=0; i<f.stx.value.length; i++) {-->
<!--                        if (f.stx.value.charAt(i) == ' ')-->
<!--                            cnt++;-->
<!--                    }-->
<!---->
<!--                    if (cnt > 1) {-->
<!--                        alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");-->
<!--                        f.stx.select();-->
<!--                        f.stx.focus();-->
<!--                        return false;-->
<!--                    }-->
<!---->
<!--                    return true;-->
<!--                }-->
<!--                </script>-->
<!---->
<!--            </fieldset>-->
<!---->
<!--            --><?php //echo popular(); // 인기검색어, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정  ?>
<!--        </div>-->
        <ul class="hd_login">
            <?php if ($is_member) {  ?>
          <!--  <li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php"><?=$lang['member_modi']?></a></li> -->
                <li><a href="<?php echo G5_BBS_URL ?>/register_form.php?w=u"><?=$lang['member_modi']?></a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/logout.php"><?=$lang['member_logout']?></a></li>
            <?php
            //$member_id = $member['mb_id'];
//            $sql_sel_auth = " select * from {$g5['auth_table']} where mb_id ='".$member_id."' and au_menu = '200100'";
//            $result = sql_query($sql_sel_auth);
            $result = get_auth_member_exits($member['mb_id'], 200100);
            if ($result && !$is_admin) {  ?>
            <li class="tnb_admin"><a href="<?= G5_ADMIN_URL?>/member_list.php"><?=$lang['member_check']?></a></li>
            <?php }  ?>
            <?php if ($is_admin) {  ?>
            <li class="tnb_admin"><a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">관리자</a></li>
            <?php }  ?>
            <?php } else {  ?>
            <li><a href="<?php echo G5_BBS_URL ?>/register.php"><?=$lang['member_sign_up']?></a></li>
<!--            <li><a href="--><?php //echo G5_BBS_URL ?><!--/login.php">--><?//=$lang['member_login']?><!--</a></li>-->
            <li><button id="popup_open_btn" onclick="modal('my_modal')"><?=$lang['member_login']?></button></li>
            <div id="my_modal">
                <?php
                //include ('/kmpilot/portal/bbs/login.php');
                //alert(G5_BBS_URL);
                $_COOKIE['current_page'] = $_SERVER['REQUEST_URI'];
                if( function_exists('social_check_login_before') ){
                    $social_login_html = social_check_login_before();
                }
                //include_once('./_head.sub.php');
                //$url = isset($_GET['url']) ? strip_tags($_GET['url']) : '';
                $url = isset($_COOKIE['current_page']) ? strip_tags($_COOKIE['current_page']) : '';
                // url 체크
                check_url_host($url);
                // 이미 로그인 중이라면
                if ($is_member) {
                    if ($url)
                        goto_url($url);
                    else
                        goto_url(G5_URL);
                }
                $login_url        = login_url($url);
                $login_action_url = G5_HTTPS_BBS_URL."/login_check.php";
                // 로그인 스킨이 없는 경우 관리자 페이지 접속이 안되는 것을 막기 위하여 기본 스킨으로 대체
                $login_file = $member_skin_path.'/login_modal.skin.php';
                if (!file_exists($login_file))
                    $member_skin_path   = G5_SKIN_PATH.'/member/basic';
                include_once($member_skin_path.'/login_modal.skin.php');
                run_event('member_login_tail', $login_url, $login_action_url, $member_skin_path, $url);
//                include_once('./_tail.sub.php');
                ?>
            <a class="modal_close_btn">닫기</a>
            </div>
            <?php }  ?>
        </ul>
    </div>

    <nav id="gnb">
        <h2>메인메뉴</h2>
        <div class="gnb_wrap">
            <ul id="gnb_1dul">
                <li class="gnb_1dli gnb_mnal"><button type="button" class="gnb_menu_btn" title="전체메뉴"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">전체메뉴열기</span></button></li>
                <?php
				$menu_datas = get_menu_db(0, true);
				$gnb_zindex = 999; // gnb_1dli z-index 값 설정용
                $i = 0;
                foreach( $menu_datas as $row ){
                    if( empty($row) ) continue;
                    $add_class = (isset($row['sub']) && $row['sub']) ? 'gnb_al_li_plus' : '';
                ?>
                <li class="gnb_1dli <?php echo $add_class; ?>" style="z-index:<?php echo $gnb_zindex--; ?>">
                    <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="gnb_1da"><?php echo $row['me_name'] ?></a>
                    <?php
                    $k = 0;
                    foreach( (array) $row['sub'] as $row2 ){

                        if( empty($row2) ) continue;

                        if($k == 0)
                            echo '<span class="bg">하위분류</span><div class="gnb_2dul"><ul class="gnb_2dul_box">'.PHP_EOL;
                    ?>
                        <li class="gnb_2dli"><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>" class="gnb_2da"><?php echo $row2['me_name'] ?></a></li>
                    <?php
                    $k++;
                    }   //end foreach $row2

                    if($k > 0)
                        echo '</ul></div>'.PHP_EOL;
                    ?>
                </li>
                <?php
                $i++;
                }   //end foreach $row

                if ($i == 0) {  ?>
                    <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                <?php } ?>
            </ul>
            <div id="gnb_all">
                <h2>전체메뉴</h2>
                <ul class="gnb_al_ul">
                    <?php

                    $i = 0;
                    foreach( $menu_datas as $row ){
                    ?>
                    <li class="gnb_al_li">
                        <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="gnb_al_a"><?php echo $row['me_name'] ?></a>
                        <?php
                        $k = 0;
                        foreach( (array) $row['sub'] as $row2 ){
                            if($k == 0)
                                echo '<ul>'.PHP_EOL;
                        ?>
                            <li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>"><?php echo $row2['me_name'] ?></a></li>
                        <?php
                        $k++;
                        }   //end foreach $row2

                        if($k > 0)
                            echo '</ul>'.PHP_EOL;
                        ?>
                    </li>
                    <?php
                    $i++;
                    }   //end foreach $row

                    if ($i == 0) {  ?>
                        <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                    <?php } ?>
                </ul>
                <button type="button" class="gnb_close_btn"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div id="gnb_all_bg"></div>
        </div>
    </nav>

</div>
<!-- } 상단 끝 -->
<style>
    #my_modal {
        display: none;
        width: 400px;
        /*height: 200px;*/
        /*padding: 20px 60px;*/
        background-color: #dde4e9;
        border: 1px solid #888;
        border-radius: 3px;
    }
    #my_modal .modal_close_btn {
        position: absolute;
        top: 5px;
        right: 5px;
        -webkit-text-fill-color: #0f192a;
    }
    html.modal-open {
        overflow-y: hidden;
    }
</style>

<hr>

<!-- 콘텐츠 시작 { -->
<div id="wrapper">
    <div id="container_wr">

    <div id="container">
        <?php if (!defined("_INDEX_")) { ?><h2 id="container_title"><span title="<?php echo get_text($g5['title']); ?>"><?php echo get_head_title($g5['title']); ?></span></h2><?php }
        ?>
        <script>

            $(function(){
                $(".gnb_menu_btn").click(function(){
                    $("#gnb_all, #gnb_all_bg").show();
                });
                $(".gnb_close_btn, #gnb_all_bg").click(function(){
                    $("#gnb_all, #gnb_all_bg").hide();
                });
            });

            function lang_change(lang_type)
            {
                var ajaxUrl = "<?=$ajaxpage?>";
                $.ajax({
                    type		: "POST",
                    dataType    : "text",
                    url			: ajaxUrl,
                    data		: {
                        "lang_type" : lang_type,
                    },
                    success: function(data){
                        if(trim(data) == "en"){
                            //여기서 분기점을 이용해 영문페이지로 넘기자
                            //location.replace("<?=G5_URL?>");
                            location.href = "<?=G5_URL?>/index_en.php";
                            //location.reload();
                        }else{
                            //location.replace("<?=G5_URL?>");
                            location.href = "<?=G5_URL?>";
                        }
                        console.log(data);
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            }

        </script>
