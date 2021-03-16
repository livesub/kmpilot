<?php
$g5['title'] = "도선지 상세 정보";

$idx = $_GET['idx'];

if($idx == '' || $idx == null){
    alert('잘못된 접근입니다.');
    exit;
}

$sql_list_sel_info = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$idx} ";
$result_list_sel = sql_fetch($sql_list_sel_info);
if(!$result_list_sel){
    alert('해당되는 도선지가 없습니다! \n다시 시도해주세요');
}

$sql_info_sel = " select * from CMS_MAGAZINE_NEW_TEST where PARENTIDX = {$idx} order by IDX";
$result_info_sel = sql_query($sql_info_sel);
$scgcode = '';

$listall = '<a href="'.G5_BBS_URL.'/content.php?co_id=doseonge_list" class="ov_listall">목록으로 이동</a>';
?>
<h1>도선지 상세 정보 페이지</h1>
<h2><?=$listall?></h2>
<div style="display: flex; justify-content: space-around;">
    <div>
        <?php if($result_list_sel['M_IMG']){
                $thumb = '';
                //있을 경우 썸네일 생성
                if(file_exists(G5_DATA_PATH.'/magazine_test/'.$result_list_sel['M_IMG'])){
                    //alert('들어는 왔나?');
                    $img = $result_list_sel['M_IMG'];
                    $source_path = G5_DATA_PATH."/magazine_test/";
                    $target_path = $source_path."thumb/";
                    //폴더 없을 경우 만들기
                    //없을 경우 추가
                    if (!is_dir($target_path)) {
                        @mkdir($target_path, G5_DIR_PERMISSION);
                        @chmod($target_path, G5_DIR_PERMISSION);
                    }
                    $thumb = thumbnail($img,$source_path,$target_path,250,335,false);
                    }
                    ?>
                    <img src="<?=G5_DATA_URL?>/magazine_test/thumb/<?=$thumb?>" alt="">
        <?php }?>
        <br>
        <b><?=$result_list_sel['M_TITLE']?></b>
        <p>저자 : <?=$result_list_sel['M_AUTHOR']?></p>
        <p>연호 : <?=$result_list_sel['S_YEAR']?> <?=$result_list_sel['SECTION'] ?></p>
        <p>발간 : <?=$result_list_sel['S_YEAR']?>-<?=$result_list_sel['S_MONTH']?>-<?=$result_list_sel['S_DAY']?></p>
    </div>
    <div>
        <?php for($si=0; $row=sql_fetch_array($result_info_sel); $si++){
            if($scgcode != $row['SCGCODE']){?>
                <b><?=$row['SCGCODE']?></b>
            <?php }?>
                <p><?=$row['C_TITLE']?> - p. <?=$row['C_PAGE']?></p> <a href="<?=G5_BBS_URL?>/doseongi_download.php?idx=<?=$row['IDX']?>"><?=$row['FILENAME_ORG']?></a> <br>
        <?php
            $scgcode = $row['SCGCODE'];
        }?>
    </div>
</div>


