<?php
    //데이터 베이스 연결하기
    include "../config.php";
    include "../libs/libs.php";

    //원본글의  값을 구하는 쿼리
    $query = " SELECT BB_ans_ori_id, BB_ans_ord, BB_ans_depth FROM $board WHERE BB_id = $_POST[id] ";
    $result = SqlQuery($conn, $query);
    $data = SqlFatchArray($result);
    //$data = SqlFatchArray(SqlQuery($conn, $query)); <-- 이렇게 써도됨

    $ans_ori_id = $data[BB_ans_ori_id]; //원본 게시글 id값 넣기
    $ans_ord = $data[BB_ans_ord] + 1;   //
    $ans_depth = $data[BB_ans_depth] + 1;

    $query = "UPDATE $board SET BB_ans_ord = BB_ans_ord + 1 WHERE BB_ans_ori_id = $data[BB_ans_ori_id] AND BB_ans_ord > $data[BB_ans_ord]";
    $result = SqlQuery($conn, $query);
      
    $query_in = " INSERT INTO $board SET
                    BB_name = '$_POST[name]',
                    BB_pass = '$_POST[pass]',
                    BB_email = '$_POST[email]',
                    BB_title = '$_POST[title]',
                    BB_view = 0,
                    BB_wdate = UNIX_TIMESTAMP(),
                    BB_ip = '$_SERVER[REMOTE_ADDR]',
                    BB_content = '$_POST[content]',
                    BB_ans_ori_id = $ans_ori_id,
                    BB_ans_ord = $ans_ord,
                    BB_ans_depth = $ans_depth
    ";

    //이렇게 써도 됨
    //$query = "INSERT INTO $board (thread,depth,name,pass,email";
    //$query .= ",title,view,wdate,ip,content)";
    //$query .= " VALUES ('" . ($_POST[parent_thread]-1) . "'";
    //$query .= ",'" . ($parent_depth+1) ."','$_POST[name]','$_POST[pass]','$_POST[email]'";
    //$query .= ",'$_POST[title]',0, UNIX_TIMESTAMP(),'$_SERVER[REMOTE_ADDR]'";
    //$query .= ",'$_POST[content]')";
    $result = SqlQuery($conn, $query_in);

    if($result){
        echo ("<meta http-equiv='Refresh' content='1; URL=list.php'>");
    }else{
        echo ("<meta http-equiv='Refresh' content='1; URL=write.php'>");
    }

    //데이터베이스와의 연결 종료
    SqlClose($conn);

    // 새 글 쓰기인 경우 리스트로..
    echo ("<meta http-equiv='Refresh' content='3; URL=list.php'>");
?>
<center>
<font size=2>정상적으로 저장되었습니다.</font>
