<?php
    //데이터 베이스 연결하기
    include "../config.php";
    include "../libs/libs.php";

    $query = "SELECT BB_pass FROM $board WHERE BB_id=$_GET[id]";
    $result = SqlQuery($conn, $query);
    $row = SqlFatchArray($result);

    if ($_POST[pass]==$row[BB_pass] )
    {
        $query = "DELETE FROM $board WHERE BB_id=$_GET[id] ";
        $result=SqlQuery($conn, $query);
    }
    else
    {
        echo ("
        <script>
        alert('비밀번호가 틀립니다.');
        history.go(-1);
        </script>
        ");
        exit;
    }
?>
<center>
<meta http-equiv='Refresh' content='3; URL=list.php'>
<FONT size=2 >삭제되었습니다.</font>
