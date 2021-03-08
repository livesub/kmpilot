<?php
include_once('./_common.php');

if($member['mb_id'] != 'yongsanzip'){
    alert('권한이 없습니다.');
    exit;
}

$sql = " select * from CMS_MAGAZINE";
$result = sql_query($sql);
$count = '';
$count1 = '';
$count2 = '';
for($i = 0; $i < $row=sql_fetch_array($result); $i++){

    if(isset($row['SECTION']) && $row['SECTION'] != '' && $row['SECTION'] != null){
        $code_kr_section = get_code_return_kr_section($row['SECTION']);
        $sql_up_section = "update CMS_MAGAZINE set SECTION = '{$code_kr_section}' where IDX = '{$row['IDX']}'";
        $result_section = sql_query($sql_up_section);
        $count1++;
    }

    if(isset($row['SCGCODE']) && $row['SCGCODE'] != '' && $row['SCGCODE'] != null){
        $code_kr = get_code_return_kr($row['SCGCODE']);
        $sql_up_code = "update CMS_MAGAZINE set SCGCODE = '{$code_kr}' where IDX = '{$row['IDX']}' ";
        $result_code = sql_query($sql_up_code);
        $count2++;
    }
    $count++;
}
echo $count." 의 결과 업데이트<br>";
echo "총 ".$count1." 개의 섹션 업데이트<br>";
echo "총 ".$count2." 개의 코드 업데이트<br>";