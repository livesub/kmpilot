<?php
    //데이터 베이스 연결하기
    include "../config.php";
    include "../libs/libs.php";
    include "../libs/pageSet.php";



    /*	처음 시작될 게시물 번호 설정
    ***************************/
    $row_num = 15; //한페이지에 나올 전체 이미지 설정

    if($_GET[page] != "")
    {
        $start_num = $row_num * ($_GET[page] - 1);
        $page = $_GET[page];
    }else{
        $page = 1;
        $start_num = 0;
    }


    // 데이터베이스에서 페이지의 첫번째 글($no)부터 $page_size 만큼의 글을 가져온다.
    $query = "SELECT * FROM $board ";
    $result = SqlQuery($conn, $query);

    $total_record = SqlNumRows($result);
    $total_page = ceil($total_record / $row_num);
    $total_page = $total_page == 0 ? 1 : $total_page;


    /*	현 페이지 부분 추출 쿼리
    *************************/
    $now_record = $query."ORDER BY BB_ans_ori_id DESC, BB_ans_ord ASC  LIMIT $start_num,$row_num";
    $result_now_record = SqlQuery($conn, $now_record);




    /*	페이지 관련 처리
    ********************/
    $showPage = new pageSet($total_page,$page,10, 10, $total_record, $tailarr,"");
    $prevPage = $showPage->getPrevPage("<img src='/images/admin/btn_prev.gif' hspace='5' border='0' align='absmiddle'>");
    $nextPage = $showPage->getNextPage("<img src='/images/admin/btn_next.gif' hspace='5' border='0' align='absmiddle'>");
    //	$pre10Page = $showPage->pre10("<img src='/images/board_bu/board_pre10.gif' width='13' height='13' border=0>");
    //	$next10Page = $showPage->next10("<img src='/images/board_bu/board_next10.gif' width='13' height='13' border=0>");
    //	$preFirstPage = $showPage->preFirst("<img src='/images/board_bu/board_pre.gif' width='13' height='13' border=0>&nbsp;");
    //	$nextLastPage = $showPage->nextLast("&nbsp;<img src='/images/board_bu/board_next2.gif' width='13' height='13' border=0>");
    $listPage = $showPage->getPageList();
    $pnPage = $prevPage.$listPage."".$nextPage;
?>

<html>
<head>
<title>계층형 게시판</title>
<style>
<!--
td { font-size : 9pt; }
A:link { font : 9pt; color : black; text-decoration : none;
font-family: 굴림; font-size : 9pt; }
A:visited { text-decoration : none; color : black;
font-size : 9pt; }
A:hover { text-decoration : underline; color : black;
font-size : 9pt;}
-->
</style>
</head>
<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>
<!-- 게시물 리스트를 보이기 위한 테이블 -->
<table width=580 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
<!-- 리스트 타이틀 부분 -->
<tr height=20 bgcolor=#999999>
    <td width=30 align=center>
        <font color=white>번호</font>
    </td>
    <td width=370 align=center>
        <font color=white>제 목</font>
    </td>
    <td width=50 align=center>
        <font color=white>글쓴이</font>
    </td>
    <td width=60 align=center>
        <font color=white>날 짜</font>
    </td>
    <td width=40 align=center>
        <font color=white>조회수</font>
    </td>
</tr>
<!-- 리스트 타이틀 끝 -->
<!-- 리스트 부분 시작 -->

<?php
    $virtual_num = $total_record - $row_num * ($page - 1);
    while($row = SqlFatchArray($result_now_record))
    {
?>
<!-- 행 시작 -->
<tr>
    <!-- 번호 -->
    <td height=20 bgcolor=white align=center>
        <a href="read.php?id=<?=$row[BB_id]?>&no=<?=$no?>"><?=$virtual_num?></a>
    </td>
    <!-- 번호 끝 -->
    <!-- 제목 -->
    <td height=20 bgcolor=white>&nbsp;
<?php
        if ($row[BB_ans_depth] >0){
            echo "<img height=1 width=" . $row[BB_ans_depth]*7 . ">└";//이부분은 답변을 달때 들여쓰기를 위한 곳. 7씩 늘어나게 들여쓰기함
        }
?>
        <a href="read.php?id=<?=$row[BB_id]?>&no=<?=$no?>">
        <?=strip_tags($row[BB_title]);?></a>
    </td>
    <!-- 제목 끝 -->
    <!-- 이름 -->
    <td align=center height=20 bgcolor=white>
        <font color=black>
        <a href="mailto:<?=$row[BB_email]?>"><?=$row[BB_name]?></a>
        </font>
    </td>
    <!-- 이름 끝 -->
    <!-- 날짜 -->
    <td align=center height=20 bgcolor=white>
        <font color=black><?=date("Y-m-d",$row[BB_wdate])//여기서는 date 함수를 써서 유닉스 타임 스탬프 값을 날짜 형식으로 출력함. ?></font>
    </td>
    <!-- 날짜 끝 -->
    <!-- 조회수 -->
    <td align=center height=20 bgcolor=white>
        <font color=black><?=$row[BB_view]?></font>
    </td>
<!-- 조회수 끝 -->
</tr>
<!-- 행 끝 -->

<?php
        /*	가상번호 감소
        ***************/
        $virtual_num--;
    } // end While

//데이터베이스와의 연결을 끝는다.
    SqlClose($conn);
?>
</table>
<!-- 게시물 리스트를 보이기 위한 테이블 끝-->
<!-- 페이지를 표시하기 위한 테이블 -->



<table border=0>
<tr>
    <td width=600 height=20 align=center rowspan=4>
    <font color=gray>
    &nbsp;
    <?=$pnPage?>
</font>
</td>
</tr>
</table>
<a href=write.php>글쓰기</a>
</center>
</body>
</html>
