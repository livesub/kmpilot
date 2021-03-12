<?php
    $menu_introduce = "";
    $menu_new = "";
    $menu_community = "";
    $menu_member = "";

    $sub_notice = "";
    if(strpos($_SERVER['REQUEST_URI'], "notice_{$lang_type}") !== false) {
        $menu_new = " class='active' ";
        if(strpos($_SERVER['REQUEST_URI'], "notice_{$lang_type}") !== false){
            $sub_notice = " class='active' ";
        }
    } else {
        //echo "없군요.";
    }
?>
    <div class="sub-container community-container">
        <div class="sub-title">
            <dl>
                <dt><?=$lang['menu_news']?></dt>
                <dd><?=$lang['sub_news_ment']?></dd>
            </dl>
        </div>
        <div class="navigation">
            <div>
                <div class="home">
                    <div><a href="/">home</a></div>
                </div>
                <div depth="1">
                    <div><?=$lang['menu_news']?></div>
                    <ul>
                        <li>
                            <a href="../introduce/greet.html"><?=$lang['about_association']?></a>
                        </li>
                        <li <?=$menu_new?>>
                            <a href="bbs/board.php?bo_table=notice_kr"><?=$lang['menu_news']?></a>
                        </li>
<?php
    if($lang_type == "kr" || $lang_type == ""){
?>
                        <li>
                            <a href="#"><?=$lang['menu_community']?></a>
                        </li>
                        <li>
                            <a href="#"><?=$lang['menu_edu']?></a>
                        </li>

                        <!-- 로그인 시 표시 -->
<?php
        if ($is_member) {
?>
                        <li>
                            <a href="#"><?=$lang['menu_member']?></a>
                        </li>
<?php
        }
?>
                        <li>
                            <a href="../passage/plan.html"><?=$lang['sub_passage_plan']?></a>
                        </li>
                        <li>
                            <a href="../passage/calc.html"><?=$lang['sub_ferry_fee']?></a>
                        </li>
<?php
    }
?>
                    </ul>
                </div>
                <div depth="2">
                    <div><?=$lang['menu_notice']?></div>
                    <ul>
                        <li <?=$sub_notice?>>
                            <a href="<?=G5_URL?>/bbs/board.php?bo_table=notice_<?=$lang_type?>"><?=$lang['main_notice_bbs']?></a>
                        </li>
<?php
    if($lang_type == "kr" || $lang_type == ""){
?>
                        <li>
                            <a href="./photoNews.html"><?=$lang['menu_poto']?></a>
                        </li>

                        <li>
                            <a href="./promotionVideo.html"><?=$lang['menu_movie']?></a>
                        </li>
<?php
    }else{
?>
                        <li>
                            <a href="./promotionVideo.html"><?=$lang['menu_eng_news']?></a>
                        </li>
<?php
    }
?>
                        <li>
                            <a href="./passage.html"><?=$lang['magazine']?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>