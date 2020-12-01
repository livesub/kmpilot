<?php

    $hostname = "localhost";
    $username = "root";
    $userpw = "1";
    $databasename = "dev1_db";


    $board = "Board_Basic";//이 변수는 테이블명 이렇게 하면 sql문을 쓸 때 편함

    $conn = mysqli_connect($hostname, $username, $userpw, $databasename);
    mysqli_select_db($conn, $databasename) or die('DB 선택 실패');

?>