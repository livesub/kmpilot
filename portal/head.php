<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

run_event('pre_head');
/*
if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/head.php');
    return;
}
*/
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
<div class="wrap">
    <div class="header">
        <div class="navigation">
            <div class="header-main">
                <ul class="header-menu">
                    <li>
                        <a href="#"><?=$lang['about_association']?></a>
                        <div class="header-sub-menu">
                            <ul>
                                <li><a href="./pages/introduce/greet.html"><?=$lang['welcome_message']?></a></li>
                                <li><a href="./pages/introduce/history.html"><?=$lang['history']?></a></li>
                                <li><a href="./pages/introduce/honor.html"><?=$lang['honor_pilot']?></a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#"><?=$lang['menu_news']?></a>
                        <div class="header-sub-menu">
                            <ul>
                                <li><a href="bbs/board.php?bo_table=notice_kr"><?=$lang['menu_notice']?></a></li>
                                <li><a href="../../pages/news/photoNews.html"><?=$lang['menu_poto']?></a></li>
                                <li><a href="../../pages/news/promotionVideo.html"><?=$lang['menu_movie']?></a></li>
                                <li><a href="../../pages/news/passage.html"><?=$lang['magazine']?></a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#"><?=$lang['menu_community']?></a>
                        <div class="header-sub-menu">
                            <ul>
                                <li><a href="#"><?=$lang['menu_community']?></a></li>
                                <li><a href="#"><?=$lang['menu_free_board']?></a></li>
                                <li><a href="#"><?=$lang['menu_pds_board']?></a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#"><?=$lang['menu_edu']?></a>
                        <div class="header-sub-menu">
                        </div>
                    </li>
                    <!-- 로그인 시 표시 -->
<?php
    if ($is_member) {
?>
                    <li>
                        <a href="#"><?=$lang['menu_member']?></a>
                        <div class="header-sub-menu">
                            <ul>
                                <li><a href="#"><?=$lang['menu_conference']?></a></li>
                                <li><a href="#"><?=$lang['menu_newsletter']?></a></li>
                                <li><a href="#"><?=$lang['menu_info']?></a></li>
                                <li><a href="#"><?=$lang['menu_member_search']?></a></li>
                                <li><a href="#"><?=$lang['menu_member_info']?></a></li>
                            </ul>
                        </div>
                    </li>
<?php
    }
?>
                </ul>
            </div>
        </div>
        <a href="/" class="logo">
            <h1>도선사</h1>
        </a>
        <div class="hamburger" onclick="openHam()">
            <span>햄버거</span>
        </div>
        <div class="ham-modal">
            <div class="ham-background" onclick="closeHam()"></div>
            <div class="ham-modal-dialog">
<?php
    if ($is_member) {
?>
                <div>
                    <!-- 로그인 시 표시 -->
                    <div class="btn video-conference"><?=$lang['button_meeting']?></div>
                    <div class="btn btn-close" onclick="closeHam()">close</div>
                </div>
<?php
    }
?>
                <div class="login-info">
<?php
    if ($is_member) {
?>
                    <div onclick="login_chk('out');"><?=$lang['member_logout']?></div>
<?php
    }else{
?>
                    <div onclick="openLoginModal()"><?=$lang['member_login']?></div>
<?php
    }
?>

<?php
    if($lang_type == "kr" || $lang_type == ""){
?>
                    <div><a href="javascript:lang_change('en')">ENG</a></div>
<?php
    }else{
?>
                    <div><a href="javascript:lang_change('kr')">KOR</a></div>
<?php
    }
?>
                </div>
                <div class="header-menu">
                    <script>
                        function onClickHeaderMenu(evnt){
                            document.querySelectorAll('.ham-modal .header-menu li.active').forEach(function(el) {
                                el.classList.remove('active');
                            });
                            evnt.target.parentElement.classList.add('active');
                        }
                    </script>
                    <ul>
                        <li>
                            <div onclick="onClickHeaderMenu(event)"><?=$lang['about_association']?></div>
                            <div class="header-sub-menu">
                                <ul>
                                    <li><a href="./pages/introduce/greet.html"><?=$lang['welcome_message']?></a></li>
                                    <li><a href="./pages/introduce/history.html"><?=$lang['history']?></a></li>
                                    <li><a href="./pages/introduce/honor.html"><?=$lang['honor_pilot']?></a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div onclick="onClickHeaderMenu(event)"><?=$lang['menu_news']?></div>
                            <div class="header-sub-menu">
                                <ul>
                                    <li><a href="bbs/board.php?bo_table=notice_kr"><?=$lang['menu_notice']?></a></li>
                                    <li><a href="../../pages/news/photoNews.html"><?=$lang['menu_poto']?></a></li>
                                    <li><a href="../../pages/news/promotionVideo.html"><?=$lang['menu_movie']?></a></li>
                                    <li><a href="../../pages/news/passage.html"><?=$lang['magazine']?></a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div onclick="onClickHeaderMenu(event)"><?=$lang['menu_community']?></div>
                            <div class="header-sub-menu">
                                <ul>
                                    <li><a href="#"><?=$lang['menu_community']?></a></li>
                                    <li><a href="#"><?=$lang['menu_free_board']?></a></li>
                                    <li><a href="#"><?=$lang['menu_pds_board']?></a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div onclick="onClickHeaderMenu(event)"><a href="#"><?=$lang['menu_edu']?></a></div>
                        </li>
                        <!-- 로그인 시 표시 -->
<?php
    if ($is_member) {
?>
                        <li>
                            <div onclick="onClickHeaderMenu(event)"><?=$lang['menu_member']?></div>
                            <div class="header-sub-menu">
                                <ul>
                                    <li><a href="#"><?=$lang['menu_conference']?></a></li>
                                    <li><a href="#"><?=$lang['menu_newsletter']?></a></li>
                                    <li><a href="#"><?=$lang['menu_info']?></a></li>
                                    <li><a href="#"><?=$lang['menu_member_search']?></a></li>
                                    <li><a href="#"><?=$lang['menu_member_info']?></a></li>
                                </ul>
                            </div>
                        </li>
<?php
    }
?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="pack-btn">
            <ul class="header-menu">
<?php
    if($lang_type == "kr" || $lang_type == ""){
?>
                <li><a href="javascript:lang_change('en')">ENG</a></li>
<?php
    }else{
?>
                <li><a href="javascript:lang_change('kr')">KOR</a></li>
<?php
    }
?>

<?php
    if ($is_member) {
?>
                <!-- 로그인 시 표시 -->
                <li><a href="#"><?=$lang['button_meeting']?></a></li>
<?php
    }
?>
            </ul>
        </div>
        <div class="log-info">
<?php
    if ($is_member) {
?>
            <!-- 로그인 시 "로그아웃" 표시 -->
            <a href="javascript:login_chk('out')"><?=$lang['member_logout']?></a>
<?php
    }else{
?>
            <!-- 로그인 시 표시 -->
            <a href="javascript:void(0);" onclick="openLoginModal()"><?=$lang['member_login']?></a>
<?php
    }
?>
        </div>
        <div class="login modal fade">
            <div class="modal-background" onclick="closeLoginModal()"></div>
            <div class="modal-dialog">
            <form name="log_in" id="log_in" method="post" action="">
                <div class="modal-dialog-title"><?=$lang['member_login']?><div class="btn-close" onclick="closeLoginModal()"></div></div>
                <div class="modal-dialog-description"><?=$lang['member_login_ment']?></div>
                <div class="modal-dialog-contents">
                    <div>
                        <input type="text" name="mb_id" id="login_id" required placeholder="<?=$lang['member_id']?>" >
                        <input type="password" name="mb_password" id="login_pw" placeholder="<?=$lang['member_pw']?>">
                    </div>
                </div>
                <div class="modal-dialog-footer">
                    <button type="submit" class="btn normal" onclick="login_chk('in')"><?=$lang['member_login']?></button>
                </div>
            </form>
            </div>
        </div>
    </div>


    <script>
        function lang_change(lang_chk)
        {
            var ajaxUrl = "<?=$ajaxpage?>";
            $.ajax({
                type		: "POST",
                dataType    : "text",
                url			: ajaxUrl,
                data		: {
                    "lang_type" : lang_chk,
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

        function login_chk(chk){
            if(chk == "in"){
                if($("#login_id").val() == ""){
                    alert("아이디를 입력 하세요.");
                    $("#login_id").focus();
                    return false;
                }

                if($("#login_pw").val() == ""){
                    alert("비밀번호를 입력 하세요.");
                    $("#login_pw").focus();
                    return false;
                }
                var chk_ment = "<?=$lang['member_login_chk']?>"
            }else{
                var chk_ment = "<?=$lang['member_logout_chk']?>"
            }

            var ajaxUrl = "<?=G5_BBS_URL?>/ajax_log_check.php";
            $.ajax({
                type		: "POST",
                dataType    : "text",
                url			: ajaxUrl,
                data		: {
                    "chk_type" : chk,
                    "mb_id" : $("#login_id").val(),
                    "mb_password" : $("#login_pw").val(),
                },
                success: function(data){
                    if(trim(data) == "fatal"){
                        alert("<?=$lang['fatal_err']?>");
                        closeLoginModal();
                        return false;
                    }

                    if(trim(data) == "member_no"){
                        alert("<?=$lang['member_no']?>");
                        return false;
                    }

                    if(trim(data) == "member_access_no"){
                        alert("<?=$lang['member_access_no']?>");
                        return false;
                    }

                    if(trim(data) == "member_secession"){
                        alert("<?=$lang['member_secession']?>");
                        return false;
                    }

                    if(trim(data) == "OK"){
                        alert(chk_ment);
                        location.reload();
                    }

                    console.log(data);
                },
                error: function () {
                    console.log('error');
                }
            });
        }
    </script>
