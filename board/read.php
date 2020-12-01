<?php
    //데이터 베이스 연결하기
    include "../config.php";
    include "../libs/libs.php";

    $no = $_GET[no];
    $id = $_GET[id];
    $page = $_GET[page];

    // 조회수 업데이트
    $query = "UPDATE $board SET BB_view = BB_view + 1 WHERE BB_id = $_GET[id]";
    $result = SqlQuery($conn, $query);

    // 글 정보 가져오기
    $query = "SELECT * FROM $board WHERE BB_id = $_GET[id]";
    $result = SqlQuery($conn, $query);
    $row = SqlFatchArray($result);
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
<table width=580 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
<tr>
    <td height=20 colspan=4 align=center bgcolor=#999999>
        <font color=white><B><?=strip_tags($row[BB_title]);?>
        </B></font>
    </td>
</tr>
<tr>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>글쓴이</td>
    <td width=240 bgcolor=white><?=$row[BB_name]?></td>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>이메일</td>
    <td width=240 bgcolor=white><?=$row[BB_email]?></td>
</tr>
<tr>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>
        날&nbsp;&nbsp;&nbsp;짜</td><td width=240 bgcolor=white>
        <?=date("Y-m-d", $row[BB_wdate])?></td>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>조회수</td>
    <td width=240 bgcolor=white><?=$row[BB_view]?></td>
</tr>
<tr>
    <td bgcolor=white colspan=4 style="word-break:break-all;">
        <font color=black>
        <pre><?=strip_tags($row[BB_content]);?></pre>
        </font>
    </td>
</tr>
<!-- 기타 버튼 들 -->
<tr>
    <td colspan=4 bgcolor=#999999>
    <table width=100%>
    <tr>
        <td width=280 align=left height=20>
            <a href=list.php?no=<?=$no?>&page=<?=$page?>><font color=white>
            [목록보기]</font></a>
            <a href=reply.php?id=<?=$id?>><font color=white>
            [답글달기]</font></a>
            <a href=write.php><font color=white>
            [글쓰기]</font></a>
            <a href=edit.php?id=<?=$id?>><font color=white>
            [수정]</font></a>
            <a href=predel.php?id=<?=$id?>><font color=white>
            [삭제]</font></a>
        </td>
    </tr>
    </table>
    </td>
</tr>
</table>

