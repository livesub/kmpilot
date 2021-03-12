<?php
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_PATH.'/head.php');

if($lang_type == "kr"){
    $table_chk = "kmp_write_photo_news";
    $table_link = "photo_news";
}else{
    $table_chk = "kmp_write_news_en";
    $table_link = "news_en";
}
?>

<div class="main-container">

<div class="thumbnails">
    <div class=" swiper-container">
        <div class="swiper-wrapper">
<?php
    //메인 이미지 무한 호출
    $sql_main = " select image_name from {$g5['main_image_table']}  where type = 'A' order by turn desc ";
    $result_main = sql_query($sql_main);
    for ($i=0; $row_main=sql_fetch_array($result_main); $i++) {
        $image_path = "";
        $image_path = G5_DATA_URL."/main_image/{$row_main['image_name']}";
?>
            <div class="swiper-slide" style="background-image: url('<?=$image_path?>')" ></div>
<?php
    }
?>
        </div>
    </div>
    <!-- 페이징 -->
    <div class="swiper-pagination"></div>
    <script>
        new Swiper('.swiper-container', {
            autoHeight : true,
            loop : true,
            pagination : { // 페이징
                el : '.swiper-pagination',
                clickable : true, // 페이징을 클릭하면 해당 영역으로 이동, 필요시 지정해 줘야 기능 작동
            },
        });
    </script>
</div>
<div class="notice-news">
    <div class="title">
        <div class="icon">Notice/News</div>
        <div>
            <dl>
                <dt><?=$lang['main_notice']?></dt>
                <dd><?=$lang['main_notice_ment']?></dd>
            </dl>
        </div>
    </div>
    <div class="contents">

        <div>
            <div class="box-title">
                <?=$lang['main_notice_bbs']?>
                <a class="more" href="bbs/board.php?bo_table=notice_<?=$lang_type?>"><?=$lang['main_notice_more']?></a>
            </div>
            <div class="box-contents">
                <ul>
<?php
    //공지사항
    $sql_notice = " select wr_id,wr_subject,wr_datetime from kmp_write_notice_{$lang_type} order by wr_id DESC limit 0,4 ";
    $result_notice = sql_query($sql_notice);
    for ($j=0; $row_notice=sql_fetch_array($result_notice); $j++) {
        $wr_datetime_cut = explode(" ",$row_notice['wr_datetime']);
?>
                    <li><div><a href="bbs/board.php?bo_table=notice_<?=$lang_type?>&wr_id=<?=$row_notice['wr_id']?>"><?=$row_notice['wr_subject']?></a></div><div><?=date_point_change($wr_datetime_cut[0])?></div></li>
<?php
    }
?>
                </ul>
            </div>
        </div>

        <div>
            <div class="box-title">
                <?=$lang['main_news_bbs']?>
                <a class="more" href="bbs/board.php?bo_table=<?=$table_link?>"><?=$lang['main_news_more']?></a>
            </div>
            <div class="box-contents">
                <ul>
<?php
    //뉴스
    $sql_news = " select wr_id,wr_subject,wr_datetime from {$table_chk} order by wr_id DESC limit 0,4 ";
    $result_news = sql_query($sql_news);
    for ($j=0; $row_news=sql_fetch_array($result_news); $j++) {
        $news_wr_datetime_cut = explode(" ",$row_news['wr_datetime']);
?>
                    <li><div><a href="bbs/board.php?bo_table=<?=$table_link?>&wr_id=<?=$row_news['wr_id']?>"><?=$row_news['wr_subject']?></a></div><div><?=date_point_change($news_wr_datetime_cut[0])?></div></li>
<?php
    }
?>
                </ul>
            </div>
        </div>
    </div>
</div>





        <div class="introduce">
            <div>
                <div class="title inlineBlock">
                    <div class="icon">Introduce</div>
                    <div>
                        <dl>
                            <dt>지회 소개</dt>
                            <dd>대한민국<br>각 항만에서 활약하는<br>12개 도선사회를<br>소개합니다.</dd>
                            <dd>대한민국 각 항만에서 활약하는<br>12개 도선사회를 소개합니다.</dd>
                        </dl>
                    </div>
                </div>
                <div class="contents inlineBlock">
                    <div>
                        <div>
                            <div class="box-contents">
                                <div>
                                    <div class="select">
                                        <select id="branch" onchange="showBranch(event.target.value)">
                                            <option value="">지역을 선택하세요</option>
                                            <option value="0">부산</option>
                                            <option value="1">여수</option>
                                            <option value="2">인천</option>
                                            <option value="3">울산</option>
                                            <option value="4">평택</option>
                                            <option value="5">마산</option>
                                            <option value="6">대산</option>
                                            <option value="7">포항</option>
                                            <option value="8">군산</option>
                                            <option value="9">목포</option>
                                            <option value="10">동해</option>
                                            <option value="11">제주</option>
                                        </select>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>최상문</span></div>
                                            <div>
                                                <img src="resources/branches/busan.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">051-465-1651~4</div>
                                            <div class="fax">051-463-1652</div>
                                            <div class="address">[48943] 부산시 중구 충장대로 24, 201호<br>(구) 국제여객터미널</div>
                                            <div class="site"><a>www.busanpilot.co.kr</a></div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>임형도</span></div>
                                            <div>
                                                <img src="resources/branches/yeosu.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">061-660-1383</div>
                                            <div class="fax">061-666-0322</div>
                                            <div class="address">[59744]전남 여수시 공화북6길 37(덕충동)</div>
                                            <div class="site"><a>www.yspilot.co.kr</a></div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>하용구</span></div>
                                            <div>
                                                <img src="resources/branches/incheon.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">032-883-8111∼4</div>
                                            <div class="fax">032-884-7091</div>
                                            <div class="address">[22332]인천시 중구 서해대로366, 정석빌딩<br> 신관 812호</div>
                                            <div class="site"><a>www.incheonpilot.com</a></div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>전필호</span></div>
                                            <div>
                                                <img src="resources/branches/ulsan.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">052-261-7703</div>
                                            <div class="fax">052-266-4256</div>
                                            <div class="address">[44780]울산시 남구 장생포고래로 205</div>
                                            <div class="site"><a>www.ulsanpilot.co.kr</a></div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>전복연</span></div>
                                            <div>
                                                <img src="resources/branches/pyeongtaek.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">031-683-2691~2</div>
                                            <div class="fax">031-683-2183</div>
                                            <div class="address">[17952]경기도 평택시 포승읍<br>포승공단순환로 466-5번지</div>
                                            <div class="site"><a>www.ptpilot.co.kr</a></div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>조영균</span></div>
                                            <div>
                                                <img src="resources/branches/masan.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">055-222-8122~5</div>
                                            <div class="fax">055-222-8126</div>
                                            <div class="address">[51716]경남 창원시 마산합포구<br>제2부두로 32(신포동1가)</div>
                                            <div class="site"><a>www.mspilot.co.kr</a></div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>정준권</span></div>
                                            <div>
                                                <img src="resources/branches/daesan.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">041-664-5684</div>
                                            <div class="fax">041-681-4968</div>
                                            <div class="address">[31909]충남 서산시 대산읍 대산1로 81<br>2층 201호</div>
                                            <div class="site"><a>www.ds-pilot.co.kr</a></div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>최만택</span></div>
                                            <div>
                                                <img src="resources/branches/pohang.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">054-242-5221~2</div>
                                            <div class="fax">054-242-5223</div>
                                            <div class="address">[37703]경북 포항시 북구 용당로 175,<br>3층</div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>승병준</span></div>
                                            <div>
                                                <img src="resources/branches/gunsan.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">063-445-4077,7334</div>
                                            <div class="fax">063-445-4070</div>
                                            <div class="address">[54002]전북 군산시 요죽길 76,<br>(구) 202호 (오식도동, 한성필하우스 상가)</div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>박재화</span></div>
                                            <div>
                                                <img src="resources/branches/mokpo.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">061-242-3721</div>
                                            <div class="fax">061-242-4721</div>
                                            <div class="address">[58759]전남 목포시 해안로 182<br>목포연안여객터미널 309호</div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>정춘호</span></div>
                                            <div>
                                                <img src="resources/branches/donghae.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">033-535-0172</div>
                                            <div class="fax">033-535-0173</div>
                                            <div class="address">[25759]강원도 동해시 천곡로 71<br>흥국생명빌딩 8층</div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <div class="branch-info">
                                        <div class="title">
                                            <div>협회장<br/><span>박윤영</span></div>
                                            <div>
                                                <img src="resources/branches/jeju.jfif" alt="">
                                            </div>
                                        </div>
                                        <div class="contents">
                                            <div class="phone">064-722-8586</div>
                                            <div class="fax">064-722-8587</div>
                                            <div class="address">[63281]제주도 제주시 임항로111<br>2층(건입동, 제주항연안여객터미널)</div>
                                        </div>
                                        <button class="btn btn-close" onclick="closeBranchModal()">닫기</button>
                                    </div>
                                    <script>
                                        if(window.innerWidth > 1024) {
                                            //pc버전 첫 브랜치 항목 표시
                                            document.querySelector(".introduce .contents .box-contents .select select").value = "0";
                                            showBranch(0);
                                        }
                                    </script>
                                </div>
                                <div class="map">
                                    <!-- 마커 클릭 시 해당 지회 정보 표시 -->
                                    <div class="marker busan active" title="부산" onclick="showBranch(0)"></div>
                                    <div class="marker yeosu" title="여수" onclick="showBranch(1)"></div>
                                    <div class="marker incheon" title="인천" onclick="showBranch(2)"></div>
                                    <div class="marker ulsan" title="울산" onclick="showBranch(3)"></div>
                                    <div class="marker pyeongtaek" title="평택" onclick="showBranch(4)"></div>
                                    <div class="marker masan" title="마산" onclick="showBranch(5)"></div>
                                    <div class="marker daesan" title="대산" onclick="showBranch(6)"></div>
                                    <div class="marker pohang" title="포항" onclick="showBranch(7)"></div>
                                    <div class="marker gunsan" title="군산" onclick="showBranch(8)"></div>
                                    <div class="marker mokpo" title="목포" onclick="showBranch(9)"></div>
                                    <div class="marker donghae" title="동해" onclick="showBranch(10)"></div>
                                    <div class="marker jeju" title="제주" onclick="showBranch(11)"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




<div class="links">
    <div class="passage-plan">
        <div>Passage Plan</div>
        <div><span>바로가기</span></div>
    </div>
    <div class="passage-plan">
        <div>도선료 계산</div>
        <div><span>바로가기</span></div>
    </div>
    <div class="passage-plan">
        <div>도선지 바로가기</div>
        <div><span>바로가기</span></div>
    </div>
</div>
</div>

<?php
include_once(G5_PATH.'/tail.php');