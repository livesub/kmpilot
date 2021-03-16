<?php
    if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

    // add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
    add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>
            <form class="board write" name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
            <input type="hidden" name="w" value="<?php echo $w ?>">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">

            <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        if ($is_notice) {
            $option .= PHP_EOL.'<li class="chk_box"><input type="checkbox" id="notice" name="notice"  class="selec_chk" value="1" '.$notice_checked.'>'.PHP_EOL.'<label for="notice"><span></span>공지</label></li>';
        }
        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= PHP_EOL.'<li class="chk_box"><input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" class="selec_chk" value="'.$html_value.'" '.$html_checked.'>'.PHP_EOL.'<label for="html"><span></span>html</label></li>';
            }
        }
        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= PHP_EOL.'<li class="chk_box"><input type="checkbox" id="secret" name="secret"  class="selec_chk" value="secret" '.$secret_checked.'>'.PHP_EOL.'<label for="secret"><span></span>비밀글</label></li>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }
        if ($is_mail) {
            $option .= PHP_EOL.'<li class="chk_box"><input type="checkbox" id="mail" name="mail"  class="selec_chk" value="mail" '.$recv_email_checked.'>'.PHP_EOL.'<label for="mail"><span></span>답변메일받기</label></li>';
        }
    }
    echo $option_hidden;
    ?>

<?php if ($is_category) { //카테고리 방식일떄 디자인 추가 해야 함?>
    <div class="bo_w_select write_div">
        <label for="ca_name" class="sound_only">분류<strong>필수</strong></label>
        <select name="ca_name" id="ca_name" required>
            <option value="">분류를 선택하세요</option>
            <?php echo $category_option ?>
        </select>
    </div>
<?php } ?>




                <div class="write-form">
<?php
    if ($is_name) {
?>
                    <div class="row">
                        <div class="col">작성자</div>
                        <div class="col"><input type="text"  name="wr_name" value="<?php echo $name ?>" id="wr_name" required /></div>
                    </div>
<?php
    }
?>
                    <div class="row">
                        <div class="col">제목</div>
                        <div class="col">
                            <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required maxlength="255" class="full" placeholder="제목을 입력해 주세요"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">내용</div>
                        <div class="col <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                            <?=$editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                        </div>
                    </div>

<?php
    for ($i=0; $is_file && $i<$file_count; $i++) {
?>
                    <div class="row">
                        <div class="col">파일첨부</div>
                        <div class="col">
                            <div class="file_uploader">
                                <label>파일첨부<input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>"/></label>
                            </div>



                            <div class="files">
<?php

        if($w == 'u' && $file[$i]['file']) {


?>
                                <div>전국도선사주소현황.pdf
<?php
            if($is_admin || $list[$i]['mb_id'] == $member['mb_id']){
?>
                                    <div class="remove" onclick="">remove</div>
<?php
            }
?>
                                </div>
<?php
        }
?>
                            </div>
                        </div>
                    </div>
<?php
    }
?>

<?php
    if ($is_password) {
?>
                    <div class="row">
                        <div class="col">비밀번호</div>
                        <div class="col">
                            <input type="password"  ame="wr_password" id="wr_password" <?php echo $password_required ?> placeholder="비밀번호를 입력해 주세요" />
                        </div>
                    </div>
<?php
    }
?>

<?php
    if ($is_use_captcha) { //자동등록방지
?>
                    <div class="row">
                        <div class="col">등록인증코드</div>
                        <div class="col">
                            <!-- 등록인증코드 영역 -->
                            <?=$captcha_html ?>
                        </div>
                    </div>
<?php
    }
?>
                </div>

<?php
    if ($is_use_captcha) { //자동등록방지
?>
                <div class="privacy-policy">
                    <div>※ 개인정보보호를 위한 이용자 동의사항</div>
                    <div>
                        &lt;수집하려는 개인정보 항목&gt;<br>
                        도선사협회는 회원가입을 위해 아래와 같은 개인정보를 수집하고 있습니다.<br><br>
                        ▶ 이름, 이용자ID, 비밀번호, 생년월일, 회원구분, 지역, 이메일<br>
                        또한 서비스 이용과정에서 아래와 같은 정보들이 생성되어 수집될 수 있습니다.<br><br>
                        ▶ 서비스 이용기록, 접속 로그, 쿠키, 접속 IP 정보<br><br>
                        &lt;개인정보의 수집ㆍ이용 목적&gt;<br>
                        도선사협회는 수집한 개인정보를 다음의 목적을 위해 활용합니다.<br><br>
                        ▶ 회원 관리<br>
                        회원제 서비스 이용에 따른 본인확인, 개인 식별, 비인가 사용 방지, 만14세 미만 아동 개인 정보 수집 시 법정 대리인 동의여부 확인, 추후 법정 대리인 본인확인, 분쟁 조정을 위한 기록보존, 공지사항 전달<br><br>
                        ▶ 패밀리사이트 및 서비스 개선에 활용<br>
                        패밀리사이트 통합인증로그인(SSO) 서비스 제공을 위한 자료, 회원의 서비스 이용에 대한 통계<br><br>
                        &lt;개인정보의 보유 및 이용 기간&gt;<br>
                        원칙적으로, 회원탈퇴 혹은 회원 제명 이후에는 해당 개인 정보를 지체 없이 파기합니다.<br>
                        단, 다음의 정보에 대해서는 아래의 이유로 명시한 기간 동안 보존합니다.<br>
                        ▶ 이용자ID 및 이용 기록<br>
                        - 보존 이유 : 도선사협회 및 패밀리사이트 서비스 이용의 혼선방지 및 통계 정보 유지<br>
                        - 보존 기간 : 영구
                    </div>
                    <div>
                        <label>
                            <input type="checkbox" id="bbs_secret" name="bbs_secret">
                            <label for="secret"></label>
                            <span>개인정보 취급방침을 읽었으며 내용에 동의합니다.</span>
                        </label>
                    </div>
                </div>
<?php
    }
?>
                <div class="btns">
                    <button class="btn" type="submit" id="btn_submit" accesskey="s">등록</button>
                    <button class="btn btn-white" onclick="history.back()">취소</button>
                </div>
            </form>
        </div>


<script>
    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }
<?php
    if ($is_use_captcha) { //자동등록방지
?>
        if($("input:checkbox[name=bbs_secret]").is(":checked") == false) {
            alert("개인정보 취급방침에 동의하셔야 합니다.");
            $("#bbs_secret").focus();
            return false;
        }
<?php
    }
?>
/*
        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }
*/

<?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
</script>