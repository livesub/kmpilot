<?php
    function SqlFetchRow($conn, $query){
        $result = mysqli_query($conn, $query);
        $fetchrow = mysqli_fetch_row($result);
        return $fetchrow;
    }

    function SqlFatchArray($result){
        $row = mysqli_fetch_array($result);
        return $row;
    }

    function SqlQuery($conn, $query){
          $now_ment = "잘못됨";
          $result = mysqli_query($conn, $query) or die($now_ment);
        return $result;
    }

    function SqlNumRows($result){
        $record = mysqli_num_rows($result);
        return $record;
    }

    function SqlInsertId($conn){
        $last_insert_id = mysqli_insert_id($conn);
        return $last_insert_id;
    }

    function SqlClose($conn){
        mysqli_close($conn);
    }
?>