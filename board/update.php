<?php
    //데이터 베이스 연결하기
    include "../config.php";
    include "../libs/libs.php";

    // 글의 비밀번호를 가져온다.
    $query = "SELECT BB_pass FROM $board WHERE BB_id='$_POST[id]'";
    $result = SqlQuery($conn, $query);
    $row = SqlFatchArray($result);

    //입력된 값과 비교한다.
    if ($_POST[pass]==$row[BB_pass]) { //비밀번호가 일치하는 경우
        $query = "UPDATE $board SET BB_name='$_POST[name]', BB_email='$_POST[email]', BB_title='$_POST[title]', BB_content='$_POST[content]' WHERE BB_id=$_POST[id]";
        $result = SqlQuery($conn, $query);
    }
    else { // 비밀번호가 일치하지 않는 경우
        echo ("
        <script>
        alert('비밀번호가 틀립니다.');
        history.go(-1);
        </script>
        ");
        exit;
    }
    //데이터베이스와의 연결 종료
    SqlClose($conn);


    //수정하기인 경우 수정된 글로..
    echo ("<meta http-equiv='Refresh' content='3; URL=read.php?id=$_POST[id]'>");
?>
<center>
<font size=2>정상적으로 수정되었습니다.</font>
