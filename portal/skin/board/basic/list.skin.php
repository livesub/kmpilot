<?php
    if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
            <div class="notice board">
            <form name="fsearch" method="get">
                <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
                <input type="hidden" name="sca" value="<?php echo $sca ?>">
                <input type="hidden" name="sop" value="and">
                <div class="search-bar">
                    <div>
                        <div class="select">
                            <select name="sfl" id="sfl">
                                <option value="wr_subject" <?php if($sfl == "wr_subject") echo "selected";?>><?=$lang['bbs_subject']?></option>
                                <option value="wr_content" <?php if($sfl == "wr_content") echo "selected";?>><?=$lang['bbs_content']?></option>
                                <option value="wr_subject||wr_content" <?php if($sfl == "wr_subject||wr_content") echo "selected";?>><?=$lang['bbs_sub_con']?></option>
                                <option value="wr_name,1" <?php if($sfl == "wr_name,1") echo "selected";?>><?=$lang['bbs_wr_name']?></option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div><input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" id="stx" size="25" maxlength="20" placeholder="" /></div>
                        <div>
                            <button class="btn btn-180"><?=$lang['bbs_search']?></button>
                        </div>
                    </div>
                </form>
                </div>
                <div class="list">
                    <div> <!-- tabe -->
                        <div class="thead">
                            <div class="th"><?=$lang['bbs_list_no']?></div>
                            <div class="th"><?=$lang['bbs_list_subject']?></div>
                            <div class="th"><?=$lang['bbs_list_wr_name']?></div>
                            <div class="th"><?=$lang['bbs_list_regi']?></div>
                            <div class="th"><?=$lang['bbs_list_file']?></div>
                        </div>
<?php
    for ($i=0; $i<count($list); $i++) {
?>
                        <div class="row">
<?php
        if ($list[$i]['is_notice']) // 공지사항
            $is_notice = '<strong class="col" style="color:#0070BF">'.$lang["bbs_list_notice"].'</strong>';
        else if ($wr_id == $list[$i]['wr_id'])
            $is_notice =  '<span class="col">열람중</span>';
        else
            $is_notice = $list[$i]['num'];
?>
                            <div class="col"><?=$is_notice?></div>
                            <div class="col"><a class="text-overflow" href="<?php echo $list[$i]['href']?>"><?php echo $list[$i]['subject'] ?>

<?php
        if($board['bo_10_subj'] == 1){

            if ($list[$i]['comment_cnt']) {
?>
                                <span class="reply">[<?=$list[$i]['wr_comment']?>]</span>
<?php
            }
        }
?>
                            </a></div>

                            <div class="col"><?php echo $list[$i]['name'] ?></div>
                            <div class="col"><?=$list[$i]['wr_datetime']?></div>
                            <div class="col">
<?php
        if (isset($list[$i]['icon_file'])){
?>
                                <img src="<?=G5_PUBLISH_URL?>/resources/icons/icon-pdf-24.png">
<?php
        }
?>
                            </div>
                        </div>
<?php
    }
?>

                    </div>
                </div>
<?php
    if ($list_href || $is_checkbox || $write_href) {
        if ($write_href) {
?>
                <div class="btns">
                    <a href="<?php echo $write_href ?>"><button class="btn write">글쓰기</button></a>
                </div>
<?php
        }
    }
?>
                <div class="list-pagination">
                    <div id="page_control">
                    </div>
                </div>
            </div>

<script>
window_width_datermine();
function page_call(page_type){
    $.ajax({
        type		: "POST",
        dataType    : 'html',
        url			: "<?=G5_BBS_URL?>/ajax_pageing.php",
        data		: {
            "page_type"         : page_type,
            "page"              : "<?=$page?>",
            "total_page"        : "<?=$total_page?>",
            "bo_table"          : "<?=$bo_table?>",
            "qstr"              : "<?=$qstr?>"
        },
        success: function(html){
            $("#page_control").html(html);
        },
        error: function () {
            console.log('error');
        }
    });
}
</script>