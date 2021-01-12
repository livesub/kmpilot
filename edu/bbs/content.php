<?php
include_once('./_common.php');

$co_id = isset($_GET['co_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_GET['co_id']) : 0;
$co_seo_title = isset($_GET['co_seo_title']) ? clean_xss_tags($_GET['co_seo_title'], 1, 1) : '';

//dbconfig파일에 $g5['content_table'] 배열변수가 있는지 체크
if( !isset($g5['content_table']) ){
    die('<meta charset="utf-8">관리자 모드에서 게시판관리->내용 관리를 먼저 확인해 주세요.');
}

// 내용
if($co_seo_title){
    $co = get_content_by_field($g5['content_table'], 'content', 'co_seo_title', generate_seo_title($co_seo_title));
    $co_id = $co['co_id'];
} else {
    $co = get_content_db($co_id);
}

if( ! (isset($co['co_seo_title']) && $co['co_seo_title']) && $co['co_id'] ){
    seo_title_update($g5['content_table'], $co['co_id'], 'content');
}

if($co_id == "")
{
    alert('등록된 내용이 없습니다.');
    echo("<script>location.href='/';</script>");
    exit;
}

include_once(G5_PATH.'/head.php');
include_once(G5_SKIN_PATH.'/'.$co_id.'.php');
include_once(G5_PATH.'/tail.php');