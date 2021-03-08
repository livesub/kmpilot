<?php
include_once('./_common.php');

if($member['mb_id'] != 'yongsanzip'){
    alert('권한이 없습니다.');
    exit;
}

$sql_sel_magazine = " select * from CMS_MAGAZINE order by M_SORT desc ,IDX asc;";
$result_sel_magazine = sql_query($sql_sel_magazine);
$sql_idx = '';
$count = '';
$count1 = '';
$count2 = '';
$sql_idx_re ='';
for($i=0; $row=sql_fetch_array($result_sel_magazine); $i++){
    //echo $row['PARENTIDX'];
    $sql_parentidx = $row['PARENTIDX'];
    if(isset($sql_parentidx) && $sql_parentidx == 0){
        $sql_insert_new = " insert into CMS_MAGAZINE_NEW set PARENTIDX = {$row['PARENTIDX']} , SITE_CODE = '{$row['SITE_CODE']}' , S_YEAR = '{$row['S_YEAR']}' , S_MONTH = '{$row['S_MONTH']}' ,
        S_DAY = '{$row['S_DAY']}' , SECTION = '{$row['SECTION']}' , M_AUTHOR = '{$row['M_AUTHOR']}', CGCODE = '{$row['CGCODE']}', SCGCODE = '{$row['SCGCODE']}', GUBUN = '{$row['GUBUN']}' ,
        DEPTH = {$row['DEPTH']} , M_TITLE = '{$row['M_TITLE']}' , M_CONT = '{$row['M_CONT']}', M_IMG = '{$row['M_IMG']}' , M_SORT = {$row['M_SORT']} , C_TITLE = '{$row['C_TITLE']}',
        C_PAGE = '{$row['C_PAGE']}', FILENAME_ORG = '{$row['FILENAME_ORG']}', FILENAME = '{$row['FILENAME']}', FILETYPE = '{$row['FILETYPE']}', C_SORT = {$row['C_SORT']} ";
        $result = sql_query($sql_insert_new);
        $sql_idx = sql_fetch("select last_insert_id()");
        $sql_idx_re = $sql_idx['last_insert_id()'];
        //alert('마지막 increment num :'.$sql_idx['last_insert_id()']);
        if($result){
            $count1++;
        }
    }

    if($sql_parentidx != 0 && $sql_idx_re != 0){
        //echo $row['PARENTIDX'];
        $c_title = addslashes($row['C_TITLE']);
        $sql_insert_new2 = " insert into CMS_MAGAZINE_NEW set PARENTIDX = {$sql_idx_re} , SITE_CODE = '{$row['SITE_CODE']}' , S_YEAR = '{$row['S_YEAR']}' , S_MONTH = '{$row['S_MONTH']}' ,
        S_DAY = '{$row['S_DAY']}' , SECTION = '{$row['SECTION']}' , M_AUTHOR = '{$row['M_AUTHOR']}', CGCODE = '{$row['CGCODE']}', SCGCODE = '{$row['SCGCODE']}', GUBUN = '{$row['GUBUN']}' ,
        DEPTH = {$row['DEPTH']} , M_TITLE = '{$row['M_TITLE']}' , M_CONT = '{$row['M_CONT']}', M_IMG = '{$row['M_IMG']}' , M_SORT = {$row['M_SORT']} , C_TITLE = '{$c_title}',
        C_PAGE = '{$row['C_PAGE']}', FILENAME_ORG = '{$row['FILENAME_ORG']}', FILENAME = '{$row['FILENAME']}', FILETYPE = '{$row['FILETYPE']}', C_SORT = {$row['C_SORT']} ";
        //alert('언제 나오나 도선지 내용 쿼리문 \n'.$sql_insert_new2);
        $result_con = sql_query($sql_insert_new2);
        if ($result_con) {
            $count2++;
        }else{
            alert('도선지 내용 입력 오류 발생');
        }
   }
    $count++;
}
echo "총 실행 결과 : ".$count."\n 총 메인도선지 실행결과 : ".$count1."\n 총 도선지 내용 실행결과 : ".$count2;