<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<form class="board write">
                <div class="write-form">
                    <div class="row">
                        <div class="col">작성자</div>
                        <div class="col"><input type="text" /></div>
                    </div>
                    <div class="row">
                        <div class="col">제목</div>
                        <div class="col">
                            <input type="text" class="full" placeholder="제목을 입력해 주세요"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">내용</div>
                        <div class="col">
                            <textarea class="full" placeholder="글을 입력해 주세요"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">파일첨부</div>
                        <div class="col">
                            <div class="file_uploader">
                                <label>파일첨부<input type="file" /></label>
                            </div>
                            <div class="files">
                                <div>전국도선사주소현황.pdf<div class="remove" onclick="">remove</div></div>
                                <div>전국도선사주소현황.pdf<div class="remove" onclick="">remove</div></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">비밀번호</div>
                        <div class="col">
                            <input type="password" placeholder="비밀번호를 입력해 주세요" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">등록인증코드</div>
                        <div class="col">
                            <!-- 등록인증코드 영역 -->
                        </div>
                    </div>
                </div>
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
                            <input type="checkbox" id="secret" name="secret">
                            <label for="secret"></label>
                            <span>개인정보 취급방침을 읽었으며 내용에 동의합니다.</span>
                        </label>
                    </div>
                </div>
                <div class="btns">
                    <button class="btn" onclick="">등록</button>
                    <button class="btn btn-white" onclick="history.back()">취소</button>
                </div>
            </form>
        </div>