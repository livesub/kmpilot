<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원정보 입력/수정 시작 { -->

<div class="register">
    <script type="text/javascript" src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
    <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
    <script type="text/javascript" src="<?php echo G5_JS_URL ?>/common.js"></script>
    <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
    <?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
        <script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
    <?php } ?>

    <form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="url" value="<?php echo $urlencode ?>">
        <input type="hidden" name="agree" value="<?php echo $agree ?>">
        <input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
        <input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
        <input type="hidden" name="cert_no" value="">
        <?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
        <?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
            <input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
            <input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
        <?php }  ?>

        <div id="register_form" class="form_01">
            <div class="register_form_inner">
                <ul>
                    <li>
                        <label for="mb_doseongu">도선구</label><br>
                        <input type="text" id="mb_doseongu" readonly class="frm_input" size="50" value="<?php echo get_doseongu_name($member['mb_doseongu']) ?>">
                    </li>
                    <li>
                        <label for="mb_lead_code">도선 약호</label><br>
                        <input type="text" id="mb_lead_code" readonly class="frm_input" size="50" value="<?php echo $member['mb_lead_code'] ?>">
                    </li>
                    <li>
                        <label for="mb_group">그룹</label><br>
                        <?php if(isset($mb_group[0]) && $mb_group[0] != ''){
                            for($i1 =0; $i1< count($mb_group); $i1++){?>
                                <input type="text" id="mb_group" readonly class="frm_input" size="50" value="<?php echo get_group_name($mb_group[$i1]) ?>"><br>
                                <?php
                            }
                        }else { ?>
                            <input type="text" id="mb_group" readonly class="frm_input" size="50" value="속한 그룹이 없습니다."><br>
                            <?php
                        }
                        ?>
                    </li>
                    <li>
                        <label for="mb_birth">생년월일</label><br>
                        <input type="date" id="mb_birth" class="frm_input" size="50" name="mb_birth" value="<?php echo $member['mb_birth'] ?>">
                    </li>
                    <li>
                        <label for="mb_sex">성별</label><br>
                        <input type="text" id="mb_sex" readonly class="frm_input" size="50" value="<?php echo $member['mb_sex']==1? '남자' :  '여자'; ?>">
                    </li>
                    <li>
                        <label for="$mb_edu_list">교육신청현황</label><br>
                        <!--                    $mb_edu_list 회원 교육신청 및 이수관련 정보를 담는 변수-->
                        <?php if(isset($mb_edu_list) &&$mb_edu_list != ''){
                            for($i2 = 0; $i2< count($mb_edu_list); $i2++){?>
                                <input type="text" readonly id="$mb_edu_list_1" class="frm_input" size="50" value="<?=$mb_edu_list[$i2]['edu_type_name']?>   - <?= substr($mb_edu_list[$i2]['apply_date'],0,10) ?>">
                            <?php   }
                        }else{
                            ?>
                            <input type="text" readonly id="$mb_edu_list" class="frm_input" size="50" value="신청현황이 없습니다.">
                            <?php ?>
                            <?php
                        } ?>
                    </li>
                    <li>
                        <label for="$mb_edu_com">교육이수현황</label><br>
                        <?php if(isset($mb_edu_list) && $mb_edu_list != ''){
                            for($i3 =0; $i3< count($mb_edu_list); $i3++){?>
                                <input type="text" readonly id="$mb_edu_list" class="frm_input" size="50" value="<?=$mb_edu_list[$i3]['edu_type_name']?>   - <?php echo ($mb_edu_list[$i3]['lecture_completion_status'] == 'N')? '미이수' : '이수'; ?>">
                            <?php }
                        }else{
                            ?>
                            <input type="text" readonly id="$mb_edu_list_2" class="frm_input" size="50" value="이수현황이 없습니다.">
                            <?php
                        }
                        ?>
                    </li>
                </ul>
            </div>


            <div id="register_form" class="form_01">
                <div class="register_form_inner">
                    <h2>사이트 이용정보 입력</h2>
                    <ul>
                        <li>
                            <label for="reg_mb_id">
                                아이디<strong class="sound_only">필수</strong>
                                <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>
                                <span class="tooltip">영문자, 숫자, _ 만 입력 가능. 최소 2자이상 입력하세요.</span>
                            </label>
                            <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input full_input <?php echo $required ?> <?php echo $readonly ?>" minlength="2" maxlength="20" placeholder="아이디">
                            <span id="msg_mb_id"></span>
                        </li>
                        <li class="half_input left_input margin_input">
                            <label for="reg_mb_password">비밀번호<strong class="sound_only">필수</strong></label>
                            <input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호" autocomplete="off">
                        </li>
                        <li class="half_input left_input">
                            <label for="reg_mb_password_re">비밀번호 확인<strong class="sound_only">필수</strong></label>
                            <input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호 확인" autocomplete="off">
                        </li>
                    </ul>
                </div>

                <div class="tbl_frm01 tbl_wrap register_form_inner">
                    <h2>개인정보 입력</h2>
                    <ul>
                        <li>
                            <label for="reg_mb_name">이름<strong class="sound_only">필수</strong></label>
                            <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> class="frm_input full_input <?php echo $required ?> <?php echo $readonly ?>" size="10" placeholder="이름">
                            <?php
                            if($config['cf_cert_use']) {
                                if($config['cf_cert_ipin'])
                                    echo '<button type="button" id="win_ipin_cert" class="btn_frmline">아이핀 본인확인</button>'.PHP_EOL;
                                if($config['cf_cert_hp'])
                                    echo '<button type="button" id="win_hp_cert" class="btn_frmline">휴대폰 본인확인</button>'.PHP_EOL;

                                echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
                            }
                            ?>
                            <?php
                            if ($config['cf_cert_use'] && $member['mb_certify']) {
                                if($member['mb_certify'] == 'ipin')
                                    $mb_cert = '아이핀';
                                else
                                    $mb_cert = '휴대폰';
                                ?>

                                <div id="msg_certify">
                                    <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
                                </div>
                            <?php } ?>
                            <?php if ($config['cf_cert_use']) { ?>
                                <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>
                                <span class="tooltip">아이핀 본인확인 후에는 이름이 자동 입력되고 휴대폰 본인확인 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
                            <?php } ?>
                        </li>
                        <!--	            --><?php //if ($req_nick) {  ?>
                        <!--	            <li>-->
                        <!--	                <label for="reg_mb_nick">-->
                        <!--	                	닉네임<strong class="sound_only">필수</strong>-->
                        <!--	                	<button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>-->
                        <!--						<span class="tooltip">공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)<br> 닉네임을 바꾸시면 앞으로 --><?php //echo (int)$config['cf_nick_modify'] ?><!--일 이내에는 변경 할 수 없습니다.</span>-->
                        <!--	                </label>-->
                        <!--	                -->
                        <!--                    <input type="hidden" name="mb_nick_default" value="--><?php //echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?><!--">-->
                        <!--                    <input type="text" name="mb_nick" value="--><?php //echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?><!--" id="reg_mb_nick" required class="frm_input required nospace full_input" size="10" maxlength="20" placeholder="닉네임">-->
                        <!--                    <span id="msg_mb_nick"></span>	                -->
                        <!--	            </li>-->
                        <!--	            --><?php //}  ?>

                        <li>
                            <label for="reg_mb_email">E-mail<strong class="sound_only">필수</strong>

                                <?php if ($config['cf_use_email_certify']) {  ?>
                                    <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>
                                    <span class="tooltip">
	                    <?php if ($w=='') { echo "E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다."; }  ?>
                        <?php if ($w=='u') { echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다."; }  ?>
	                </span>
                                <?php }  ?>
                            </label>

                            <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
                            <input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="frm_input email full_input required" size="70" maxlength="100" placeholder="E-mail">

                        </li>

                        <?php if ($config['cf_use_homepage']) {  ?>
                            <li>
                                <label for="reg_mb_homepage">홈페이지<?php if ($config['cf_req_homepage']){ ?><strong class="sound_only">필수</strong><?php } ?></label>
                                <input type="text" name="mb_homepage" value="<?php echo get_text($member['mb_homepage']) ?>" id="reg_mb_homepage" <?php echo $config['cf_req_homepage']?"required":""; ?> class="frm_input full_input <?php echo $config['cf_req_homepage']?"required":""; ?>" size="70" maxlength="255" placeholder="홈페이지">
                            </li>
                        <?php }  ?>

                        <li>
                            <?php if ($config['cf_use_tel']) {  ?>

                                <label for="reg_mb_tel">전화번호<?php if ($config['cf_req_tel']) { ?><strong class="sound_only">필수</strong><?php } ?></label>
                                <input type="text" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" <?php echo $config['cf_req_tel']?"required":""; ?> class="frm_input full_input <?php echo $config['cf_req_tel']?"required":""; ?>" maxlength="20" placeholder="전화번호">
                            <?php }  ?>
                        </li>
                        <li>
                            <?php if ($config['cf_use_hp'] || $config['cf_cert_hp']) {  ?>
                                <label for="reg_mb_hp">휴대폰번호<?php if ($config['cf_req_hp']) { ?><strong class="sound_only">필수</strong><?php } ?>
                                    <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>
                                    <!--                        <span class="tooltip">-없이 작성해주세요</span>-->
                                </label>
                                <input type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="frm_input full_input <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="20" placeholder="휴대폰번호 - 없이 작성해주세요">
                                <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                                    <input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
                                <?php } ?>
                            <?php }  ?>
                        </li>

                        <?php if ($config['cf_use_addr']) { ?>
                            <li>
                                <label>주소</label>
                                <?php if ($config['cf_req_addr']) { ?><strong class="sound_only">필수</strong><?php }  ?>
                                <label for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                                <input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input twopart_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="5" maxlength="6"  placeholder="우편번호">
                                <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                                <input type="text" name="mb_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input frm_address full_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="50"  placeholder="기본주소">
                                <label for="reg_mb_addr1" class="sound_only">기본주소<?php echo $config['cf_req_addr']?'<strong> 필수</strong>':''; ?></label><br>
                                <input type="text" name="mb_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address full_input" size="50" placeholder="상세주소">
                                <label for="reg_mb_addr2" class="sound_only">상세주소</label>
                                <br>
                                <input type="text" name="mb_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="reg_mb_addr3" class="frm_input frm_address full_input" size="50" readonly="readonly" placeholder="참고항목">
                                <label for="reg_mb_addr3" class="sound_only">참고항목</label>
                                <input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
                            </li>
                        <?php }  ?>
                        <li>
                            <label>학력 사항</label>
                            <label for="high_name">고등학교</label>
                            <input type="text" name="high_name" id="high_name"  class="frm_input" size="20" value="<?=$high_name?>" placeholder="학교 명">
                            <input type="text" name="high_major" id="high_major" class="frm_input" size="20" value="<?=$high_major?>" placeholder="전공">
                            <?= get_grade_value('high_status',0,3, $high_status);?>
                            <br>
                            <label for="university_name">대학교</label>
                            <input type="text" name="university_name" id="university_name" class="frm_input" size="20" value="<?=$university_name?>" placeholder="학교 명">
                            <input type="text" name="university_major" id="university_major" class="frm_input" size="20" value="<?=$university_major?>" placeholder="전공">
                            <?= get_grade_value('university_status',0,3, $university_status);?>
                        </li>
                    </ul>
                </div>

                <div class="tbl_frm01 tbl_wrap register_form_inner">
                    <h2>기타 개인설정</h2>
                    <ul>
                        <?php if ($config['cf_use_signature']) {  ?>
                            <li>
                                <label for="reg_mb_signature">경력사항<?php if ($config['cf_req_signature']){ ?><strong class="sound_only">필수</strong><?php } ?></label>
                                <textarea name="mb_signature" id="reg_mb_signature" <?php echo $config['cf_req_signature']?"required":""; ?> class="<?php echo $config['cf_req_signature']?"required":""; ?>"   placeholder="경력사항"><?php echo $member['mb_signature'] ?></textarea>
                            </li>
                        <?php }  ?>

                        <?php if ($config['cf_use_profile']) {  ?>
                            <li>
                                <label for="reg_mb_profile">기타사항</label>
                                <textarea name="mb_profile" id="reg_mb_profile" <?php echo $config['cf_req_profile']?"required":""; ?> class="<?php echo $config['cf_req_profile']?"required":""; ?>" placeholder="기타사항"><?php echo $member['mb_profile'] ?></textarea>
                            </li>
                        <?php }  ?>

                        <?php if ($config['cf_use_member_icon'] && $member['mb_level'] >= $config['cf_icon_level']) {  ?>
                            <li>
                                <label for="reg_mb_icon" class="frm_label">
                                    최신면허사본
                                    <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>
                                    <span class="tooltip">이미지 크기는 가로 <?php echo $config['cf_member_icon_width'] ?>픽셀, 세로 <?php echo $config['cf_member_icon_height'] ?>픽셀 이하로 해주세요.<br>
gif, jpg, png파일만 가능하며 용량 <?php echo number_format($config['cf_member_icon_size']) ?>바이트 이하만 등록됩니다.</span>
                                </label>
                                <input type="file" name="mb_icon" id="reg_mb_icon">

                                <?php if ($w == 'u' && file_exists($mb_icon_path)) {  ?>
                                    <img src="<?php echo $mb_icon_url ?>" alt="회원아이콘">
                                    <input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
                                    <label for="del_mb_icon" class="inline">삭제</label>
                                <?php }  ?>

                            </li>
                        <?php }  ?>

                        <?php if ($member['mb_level'] >= $config['cf_icon_level'] && $config['cf_member_img_size'] && $config['cf_member_img_width'] && $config['cf_member_img_height']) {  ?>
                            <li class="reg_mb_img_file">
                                <label for="reg_mb_img" class="frm_label">
                                    회원이미지
                                    <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>
                                    <span class="tooltip">이미지 크기는 가로 <?php echo $config['cf_member_img_width'] ?>픽셀, 세로 <?php echo $config['cf_member_img_height'] ?>픽셀 이하로 해주세요.<br>
	                    gif, jpg, png파일만 가능하며 용량 <?php echo number_format($config['cf_member_img_size']) ?>바이트 이하만 등록됩니다.</span>
                                </label>
                                <!--	                s-->

                                <?php if ($w == 'u' && file_exists($mb_img_path)) {  ?>
                                    <img src="<?php echo $mb_img_url ?>" alt="회원이미지">
                                    <!--	                <input type="checkbox" name="del_mb_img" value="1" id="del_mb_img">-->
                                    <!--	                <label for="del_mb_img" class="inline">삭제</label>-->
                                <?php }  ?>

                            </li>
                        <?php } ?>

                        <li class="chk_box">
                            <!--		        	<input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" --><?php //echo ($w=='' || $member['mb_mailling'])?'checked':''; ?><!-- class="selec_chk">-->
                            <!--		            <label for="reg_mb_mailling">-->
                            <!--		            	<span></span>-->
                            <!--		            	<b class="sound_only">메일링서비스</b>-->
                            <!--		            </label>-->
                            <!--		            <span class="chk_li">정보 메일을 받겠습니다.</span>-->
                        </li>

                        <?php if ($config['cf_use_hp']) { ?>
                            <li class="chk_box">
                                <!--		            <input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" --><?php //echo ($w=='' || $member['mb_sms'])?'checked':''; ?><!-- class="selec_chk">-->
                                <!--		        	<label for="reg_mb_sms">-->
                                <!--		            	<span></span>-->
                                <!--		            	<b class="sound_only">SMS 수신여부</b>-->
                                <!--		            </label>-->
                                <!--		            <span class="chk_li">휴대폰 문자메세지를 받겠습니다.</span>-->
                            </li>
                        <?php } ?>

                        <?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능 ?>
                            <!--		        <li class="chk_box">-->
                            <!--		            <input type="checkbox" name="mb_open" value="1" id="reg_mb_open" --><?php //echo ($w=='' || $member['mb_open'])?'checked':''; ?><!-- class="selec_chk">-->
                            <!--		      		<label for="reg_mb_open">-->
                            <!--		      			<span></span>-->
                            <!--		      			<b class="sound_only">정보공개</b>-->
                            <!--		      		</label>      -->
                            <!--		            <span class="chk_li">다른분들이 나의 정보를 볼 수 있도록 합니다.</span>-->
                            <!--		            <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>-->
                            <!--		            <span class="tooltip">-->
                            <!--		                정보공개를 바꾸시면 앞으로 --><?php //echo (int)$config['cf_open_modify'] ?><!--일 이내에는 변경이 안됩니다.-->
                            <!--		            </span>-->
                            <!--		            <input type="hidden" name="mb_open_default" value="--><?php //echo $member['mb_open'] ?><!--"> -->
                            </li>
                        <?php } else { ?>
                            <li>
                                <!--	                정보공개-->
                                <!--	                <input type="hidden" name="mb_open" value="--><?php //echo $member['mb_open'] ?><!--">-->
                                <!--	                <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">설명보기</span></button>-->
                                <!--	                <span class="tooltip">-->
                                <!--	                    정보공개는 수정후 --><?php //echo (int)$config['cf_open_modify'] ?><!--일 이내, --><?php //echo date("Y년 m월 j일", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?><!-- 까지는 변경이 안됩니다.<br>-->
                                <!--	                    이렇게 하는 이유는 잦은 정보공개 수정으로 인하여 쪽지를 보낸 후 받지 않는 경우를 막기 위해서 입니다.-->
                                <!--	                </span>-->

                            </li>
                        <?php }  ?>

                        <?php
                        //회원정보 수정인 경우 소셜 계정 출력
                        if( $w == 'u' && function_exists('social_member_provider_manage') ){
                            social_member_provider_manage();
                        }
                        ?>

                        <?php if ($w == "" && $config['cf_use_recommend']) {  ?>
                            <li>
                                <label for="reg_mb_recommend" class="sound_only">추천인아이디</label>
                                <input type="text" name="mb_recommend" id="reg_mb_recommend" class="frm_input" placeholder="추천인아이디">
                            </li>
                        <?php }  ?>

                        <!--	            <li class="is_captcha_use">-->
                        <!--	                자동등록방지-->
                        <!--	                --><?php //echo captcha_html(); ?>
                        <!--	            </li>-->
                    </ul>
                </div>
                <div>
                    <div class="tbl_frm01 tbl_wrap register_form_inner">
                        <h2>면허 관리 정보</h2>
                        <ul>
                            <li>
                                <label for="mb_license_mean">면허 종류</label>
                                <input type="text" id="mb_license_mean" readonly class="frm_input" size="50" value="<?=get_license_mean($member['mb_license_mean'])?>">
                            </li>
                            <li>
                                <label for="mb_first_license_day">최초 면허 발급일</label>
                                <input type="text" id="mb_first_license_day" readonly class="frm_input" size="50" value="<?=date_return_empty_space($member['mb_first_license_day'])?>">
                                <label for="mb_license_renewal_day">면허 갱신일</label>
                                <input type="text" id="mb_license_renewal_day" readonly class="frm_input" size="50" value="<?=date_return_empty_space($member['mb_license_renewal_day'])?>">
                            </li>
                            <li>
                                <label for="mb_validity_day_from">면허 유효기간</label>
                                <input type="text" id="mb_validity_day_from" readonly  class="frm_input" size="50"value="<?=date_return_empty_space($member['mb_validity_day_from'])?> ">부터
                                <br>
                                <input type="text" id="mb_validity_day_to" readonly value="<?=date_return_empty_space($member['mb_validity_day_to'])?>" class="frm_input" size="50">까지
                            </li>
                            <li>
                                <label>최신 면허 사본</label>
                                <!--                        --><?php
                                //                        $mb_dir = substr($member['mb_id'],0,2);
                                //                        $icon_file = G5_DATA_PATH.'/member_license/'.$mb_dir.'/'.get_mb_icon_name($member['mb_id']).'.gif';
                                //                        if (file_exists($icon_file)) {
                                //                            echo get_member_profile_img($member['mb_id']);
                                //                        }
                                //                        ?>
                                <?php
                                $mb_dir = substr($member['mb_id'],0,2);
                                $icon_file2 = PORTAL_DATA_PATH.'/member_license/'.$mb_dir.'/'.get_mb_icon_name($member['mb_id']).'.gif';
                                if (file_exists($icon_file2)) {
                                    $icon_url = str_replace(PORTAL_DATA_PATH, PORTAL_DATA_URL, $icon_file2);
                                    $icon_filemtile2 = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?'.filemtime($icon_file2) : '';
                                    echo '<img src="'.$icon_url.$icon_filemtile2.'" alt="">';

                                }
                                ?>
                            </li>
                            <li>
                                <label>정년연정현황</label>
                                <input type="text" id="mb_license_ext_day_from" readonly class="frm_input" size="50" value="연장됨(기간: <?=date_return_empty_space($member['mb_license_ext_day_from'])?> ~ <?=date_return_empty_space($member['mb_license_ext_day_to'])?>)">
                            </li>
                            <li>
                                <label>국가 필수 도선사 여부</label>
                                <?php if($member['required_pilot_status_from']!= '' && $member['required_pilot_status_to'] != ''){?>
                                    <input type="text" id="required_pilot" readonly class="frm_input" size="50" value="해당(기간 : <?=$member['required_pilot_status_from']?> ~ <?=$member['required_pilot_status_to']?>)">
                                <?php }else{?>
                                    <input type="text" id="required_pilot" readonly class="frm_input" size="50" value="설정 교육이 없습니다.">
                                <?php }?>
                            </li>
                            <li>
                                <label>해심 재결 사항</label>
                                <?php $sql_sel_punishment = " select * from {$g5['member_punishment']} where mb_id ='{$member['mb_id']}'";
                                $result_sel_punish = sql_query($sql_sel_punishment);
                                //alert('쿼리문'.$sql_sel_punishment);
                                $count_sel_punish = 0;
                                for($i=0; $row=sql_fetch_array($result_sel_punish); $i++){
                                    ?>
                                    <input type="text" id="mb_punishment" readonly class="frm_input" size="50" value="<?=$row['mb_applicable_or_not']?> - <?=$row['mb_punishment']?>      <?=$row['mb_punishment_date']?>">
                                    <br>
                                    <?php
                                    $count_sel_punish ++;
                                } ?>
                                <?php
                                if($count_sel_punish == 0){
                                    ?>
                                    <input type="text" id="mb_punishment" readonly class="frm_input" size="50" value="징계사항이 없습니다.">
                                    <?php
                                }
                                ?>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="btn_confirm">
                <a href="<?php echo G5_URL ?>" class="btn_close">취소</a>
                <button type="submit" id="btn_submit" class="btn_submit" accesskey="s"><?php echo $w==''?'회원가입':'정보수정'; ?></button>
            </div>
    </form>
</div>
<script>
    let pwTest = /[a-zA-Z\w!@#$%|^&|*|(|)]{6,20}/;
    let searchPw = /[|~|`|-|_|+|=|?|>|<|,|.]/;
    $(function() {
        $("#reg_zip_find").css("display", "inline-block");

        <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
        // 아이핀인증
        $("#win_ipin_cert").click(function() {
            if(!cert_confirm())
                return false;

            var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
            certify_win_open('kcb-ipin', url);
            return;
        });

        <?php } ?>
        <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
        // 휴대폰인증
        $("#win_hp_cert").click(function() {
            if(!cert_confirm())
                return false;

            <?php
            switch($config['cf_cert_hp']) {
                case 'kcb':
                    $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                    $cert_type = 'kcb-hp';
                    break;
                case 'kcp':
                    $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                    $cert_type = 'kcp-hp';
                    break;
                case 'lg':
                    $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                    $cert_type = 'lg-hp';
                    break;
                default:
                    echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                    echo 'return false;';
                    break;
            }
            ?>

            certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
            return;
        });
        <?php } ?>
    });

    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
        // 회원아이디 검사
        if (f.w.value == "") {
            var msg = reg_mb_id_check();
            if (msg) {
                alert(msg);
                f.mb_id.select();
                return false;
            }
        }

        if (f.w.value == "") {
            if (f.mb_password.value.length < 6) {
                alert("비밀번호를 6글자 이상 입력하십시오.");
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            alert("비밀번호가 같지 않습니다.");
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 6) {
                alert("비밀번호를 6글자 이상 입력하십시오.");
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value.length > 6) {
            if (!pwTest.test(f.mb_password.value)) {
                alert("비밀번호는 영어소문자 또는 대문자,특수문자(!@#$%^&*())숫자만 허용가능합니다.");
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value.length > 20) {
            alert("비밀번호는 6글자이상 20글자이하로 입력하십시오.");
            f.mb_password.focus();
            return false;
        }

        if (searchPw.test(f.mb_password.value)) {
            alert("비밀번호는 특수문자(!@#$%^&*())만 허용가능합니다.");
            f.mb_password.focus();
            return false;
        }

        // 이름 검사
        if (f.w.value=="") {
            if (f.mb_name.value.length < 1) {
                alert("이름을 입력하십시오.");
                f.mb_name.focus();
                return false;
            }

            /*
            var pattern = /([^가-힣\x20])/i;
            if (pattern.test(f.mb_name.value)) {
                alert("이름은 한글로 입력하십시오.");
                f.mb_name.select();
                return false;
            }
            */
        }

        <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
        // 본인확인 체크
        if(f.cert_no.value=="") {
            alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
            return false;
        }
        <?php } ?>

        // 닉네임 검사
        // if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
        //     var msg = reg_mb_nick_check();
        //     if (msg) {
        //         alert(msg);
        //         f.reg_mb_nick.select();
        //         return false;
        //     }
        // }

        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
            var msg = reg_mb_email_check();
            if (msg) {
                alert(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

        <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
        // 휴대폰번호 체크
        var msg = reg_mb_hp_check();
        if (msg) {
            alert(msg);
            f.reg_mb_hp.select();
            return false;
        }
        <?php } ?>

        if (typeof f.mb_icon != "undefined") {
            if (f.mb_icon.value) {
                if (!f.mb_icon.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                    alert("회원아이콘이 이미지 파일이 아닙니다.");
                    f.mb_icon.focus();
                    return false;
                }
            }
        }

        if (typeof f.mb_img != "undefined") {
            if (f.mb_img.value) {
                if (!f.mb_img.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                    alert("회원이미지가 이미지 파일이 아닙니다.");
                    f.mb_img.focus();
                    return false;
                }
            }
        }

        // if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
        //     if (f.mb_id.value == f.mb_recommend.value) {
        //         alert("본인을 추천할 수 없습니다.");
        //         f.mb_recommend.focus();
        //         return false;
        //     }
        //
        //     var msg = reg_mb_recommend_check();
        //     if (msg) {
        //         alert(msg);
        //         f.mb_recommend.select();
        //         return false;
        //     }
        // }

        <!--    --><?php //echo chk_captcha_js();  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }

    jQuery(function($){
        //tooltip
        $(document).on("click", ".tooltip_icon", function(e){
            $(this).next(".tooltip").fadeIn(400).css("display","inline-block");
        }).on("mouseout", ".tooltip_icon", function(e){
            $(this).next(".tooltip").fadeOut();
        });
    });

</script>

<!-- } 회원정보 입력/수정 끝 -->