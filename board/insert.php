<?php
    //데이터 베이스 연결하기
    include "../config.php";
    include "../libs/libs.php";

    $query = "SELECT MIN(BB_ans_ori_id) FROM $board"; //ans_ori_id컬럼의 최소값을구하여 그 값에 -1을 해준 값이 된다
    $ans_ori_id = SqlFatchArray (SqlFetchRow ($conn, $query));
    $ans_ori_id = $ans_ori_id[0]-1; //$ans_ori_id[0]대신에 $ans_ori_id["MIN(ans_ori_id)"]를 넣어주어도 결과는 같다

    $query = "INSERT INTO $board SET
                BB_name = '$_POST[name]',
                BB_pass = '$_POST[pass]',
                BB_email = '$_POST[email]',
                BB_title = '$_POST[title]', 
                BB_view = 0,
                BB_wdate = UNIX_TIMESTAMP(),
                BB_ip = '$_SERVER[REMOTE_ADDR]', 
                BB_content = '$_POST[content]',
                BB_ans_ori_id = $ans_ori_id,
                BB_ans_ord = 0,
                BB_ans_depth = 0
    ";

    $result = SqlQuery($conn, $query);



    if($result){
        $last_insert_id = SqlInsertId($conn);  //마지막 저장된 auto_increment 값 가져오기
        $query = "UPDATE $board SET BB_ans_ori_id = $last_insert_id WHERE BB_id = $last_insert_id";
        $result = SqlQuery($conn, $query);
        
        //데이터베이스와의 연결 종료
        SqlClose($conn);

        echo ("<meta http-equiv='Refresh' content='1; URL=list.php'>");
    }else{
        echo ("<meta http-equiv='Refresh' content='1; URL=write.php'>");
    }
?>
<center>
<font size=2>정상적으로 저장되었습니다.</font>

