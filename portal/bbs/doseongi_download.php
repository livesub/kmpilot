<?php
include_once('./_common.php');

// clean the output buffer
ob_end_clean();

$file_idx = '';
if(isset($_GET['idx']) && $_GET['idx'] != ''){
    $file_idx = $_GET['idx'];
}

if(!isset($file_idx) || $file_idx == ''){
    alert('잘못된 접근입니다. 다시 시도해주세요');
}

$sql_sel_idx = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$file_idx}";
$result_sel_idx = sql_fetch($sql_sel_idx);
$original = '';
if(isset($result_sel_idx) && $result_sel_idx != ''){
    //원래 이름을 담는다.
    $original = rawurlencode($result_sel_idx['FILENAME_ORG']);
    //서버에 저장되어 있는 이름으로 작성
    $filepath = G5_DATA_PATH.'/magazine_test/'.$result_sel_idx['FILENAME'];
    $filepath = addslashes($filepath);
    //$file_exist_check = (!is_file($filepath) || !file_exists($filepath)) ? false : true;
    if(is_file($filepath) && file_exists($filepath)){
        if(preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT']) && preg_match("/5\.5/", $_SERVER['HTTP_USER_AGENT'])) {
            header("content-type: doesn/matter");
            header("content-length: ".filesize($filepath));
            header("content-disposition: attachment; filename=\"$original\"");
            header("content-transfer-encoding: binary");
        } else if (preg_match("/Firefox/i", $_SERVER['HTTP_USER_AGENT'])){
            header("content-type: file/unknown");
            header("content-length: ".filesize($filepath));
            //header("content-disposition: attachment; filename=\"".basename($file['bf_source'])."\"");
            header("content-disposition: attachment; filename=\"".$original."\"");
            header("content-description: php generated data");
        } else {
            header("content-type: file/unknown");
            header("content-length: ".filesize($filepath));
            header("content-disposition: attachment; filename=\"$original\"");
            header("content-description: php generated data");
        }
        header("pragma: no-cache");
        header("expires: 0");
        flush();

        $fp = fopen($filepath, 'rb');

    // 4.00 대체
    // 서버부하를 줄이려면 print 나 echo 또는 while 문을 이용한 방법보다는 이방법이...
    //if (!fpassthru($fp)) {
    //    fclose($fp);
    //}

        $download_rate = 10;

        while(!feof($fp)) {
            //echo fread($fp, 100*1024);
            /*
            echo fread($fp, 100*1024);
            flush();
            */

            print fread($fp, round($download_rate * 1024));
            flush();
            usleep(1000);
        }
        fclose ($fp);
        flush();
    }else{
        alert('등록된 파일이 없습니다. 다시 시도해주세요');
    }
}else{
    alert('등록된 파일이 없습니다. 다시 시도해주세요');
}



