<?php
include_once('./_common.php');

$form_count = '';
$real_count = '';
$real_count_total = '';

if(isset($_POST['form_count']) && $_POST['form_count'] != ''){
    $form_count = trim($_POST['form_count']); // 등록된 문자열
    $real_count = explode("," , $form_count); //문자열 분리 후 배열로
    $real_count_total = count($real_count); // 총 배열의 수
}

$mode = '';
$magazine_del = ''; //정보 등록 유형
$result_last_id = ''; //마지막 insert 번호 저장 변수
$result_main = ''; //main 쿼리문에 관한 결과 값 저장 변수
$result_data = ''; //data 쿼리문에 관한 결과 값 저장 변수
$result_del_main = ''; //main 삭제 쿼리문에 관한 결과 값 저장 변수
$result_del_data = ''; //main 삭제 시 data 삭제 쿼리문에 관한 결과 값 저장 변수
$result_normal_del_data = ''; // data만 삭제 시 쿼리문에 관한 결과 값 저장 변수
$content_del = ''; // 그냥 삭제 버튼클릭 시 넘어오는 변수 저장 변수
$image_regex = "/(\.(gif|jpe?g|png))$/i"; //메인 이미지 정규식
$file_regex = "/(\.(gif|jpe?g|png|pdf))$/i"; //하위 첨부파일 정규식
$magazine_img_dir = G5_DATA_PATH.'/magazine_test/';
$magazine_main_name = '';
if(isset($_POST['content_del']) && $_POST['content_del'] != ''){
    $content_del = $_POST['content_del']; // 그냥 삭제 버튼클릭 시 넘어오는 변수 저장 변수
}

if(isset($_POST['del_type_con']) && $_POST['del_type_con'] != ''){
    $magazine_del = $_POST['del_type_con']; //정보 등록 유형
}
//echo $magazine_del;
//exit;
//$magazine_del = $_POST['del_type_con']; //정보 등록 유형
//echo "삭제 유형 : ".$magazine_del;
//exit;
//$main_title_file = $_FILES['main_title_img'];
//echo $main_title_file['name'];
//exit;


if($magazine_del == "수정" || $magazine_del == "저장"){
//    echo $magazine_del;
//    exit;
    //삭제 아닐때
    //메인 도선지 저장 확인 있으면 업데이트 없을 경우 insert
    $sql_main_sel = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$_POST['M_IDX']}";
    $result_main_sel  = sql_query($sql_main_sel);
    $main_img_query = '';
    //날짜 분리 필요 값이 있다면
    $publication_date = '';
    if(isset($_POST['S_write']) && $_POST['S_write'] != ''){
        $publication_date = explode('-',$_POST['S_write']);
    }
    //이미지 관련 변수들 설정
    //메인 타이틀 이미지

    //메인 이미지 삭제가 있을 경우 삭제 하기
    if(isset($_POST['del_m_image']) && $_POST['del_m_image'] != ''){
        $sql_sel_main_img = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$_POST['M_IDX']} ";
        $result_sel_main_img = sql_fetch($sql_sel_main_img);
        //이름을 찾아 그 파일 삭제
        $del_name = $magazine_img_dir.$result_sel_main_img['M_IMG'];
        unlink($del_name);
    }
    //2021.02.26  17 : 04 메인이미지삭제 기능 test 완료 - kkw

//    echo $_FILES['main_title_img']['tmp_name']." : 이미지 tmp.name \n".$_FILES['main_title_img']['name']." : 이미지 입력된 이름";
//    exit;
    if (isset($_FILES['main_title_img']) && $_FILES['main_title_img'] != '') {
        //폴더가 있는지 확인
        if( !is_dir($magazine_img_dir) ){
            @mkdir($magazine_img_dir, G5_DIR_PERMISSION);
            @chmod($magazine_img_dir, G5_DIR_PERMISSION);
        }
        //형식이 맞지 않을 경우 돌려보내기
        if (!preg_match($image_regex, $_FILES['main_title_img']['name'])) {
            echo $_FILES['main_title_img']['name']." 은(는) 이미지 파일이 아닙니다.";
            exit;
        }



        if (preg_match($image_regex, $_FILES['main_title_img']['name'])) {
            @mkdir($magazine_img_dir, G5_DIR_PERMISSION);
            @chmod($magazine_img_dir, G5_DIR_PERMISSION);

            $dest_path = $magazine_img_dir.$_FILES['main_title_img']['name'];
//            echo $dest_path;
//            exit;
            move_uploaded_file($_FILES['main_title_img']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            $magazine_main_name = $_FILES['main_title_img']['name'];

//            if (file_exists($dest_path)) {
//                $size = @getimagesize($dest_path);
//                if ($size[0] > 150 || $size[1] > 150) {
//                    $thumb = null;
//                    if($size[2] === 2 || $size[2] === 3) {
//                        //jpg 또는 png 파일 적용
//                        $thumb = thumbnail($_FILES['main_title_img']['name'], $magazine_img_dir, $magazine_img_dir, 150, 150, true, true);
//                        if($thumb) {
//                            @unlink($dest_path);
//                            rename($magazine_img_dir.'/'.$thumb, $dest_path);
//                        }
//                    }
//                    if( !$thumb ){
//                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
//                        @unlink($dest_path);
//                    }
//                }
//            }
        }
    }
    if(isset($magazine_main_name) && $magazine_main_name != ''){
        $main_img_query = ", M_IMG = '{$magazine_main_name}' ";
    }
    if($result_main_sel){
        //있을 경우 update
        $sql_main_update = '';
        $sql_main_update = " update CMS_MAGAZINE_NEW_TEST set S_YEAR = '{$publication_date[0]}' , S_MONTH = '{$publication_date[1]}' , S_DAY = '{$publication_date[2]}' , SECTION = '{$_POST['SECTION']}' , 
                             M_AUTHOR = '{$_POST['M_AUTHOR']}' , CGCODE = 'D01' , M_TITLE = '{$_POST['M_TITLE']}' ".$main_img_query." , REGI_DATE = now() where IDX = {$_POST['M_IDX']}";
        $result_main = sql_query($sql_main_update);
        if(!$result_main){
            echo "업데이트 문 오류 : ".$sql_main_update;
            exit;
        }
        $result_last_id = $_POST['M_IDX'];
        $mode='m';
    }else{
        //없을 경우 insert
        $sql_main_insert = '';
        $sql_main_insert = " insert into CMS_MAGAZINE_NEW_TEST set PARENTIDX = 0 , SITE_CODE = 'portal' , S_YEAR = '{$publication_date[0]}' , S_MONTH = '{$publication_date[1]}', S_DAY = '{$publication_date[2]}' , 
                            SECTION = '{$_POST['SECTION']}', M_AUTHOR = '{$_POST['M_AUTHOR']}' , CGCODE = 'D01' , GUBUN = 1 , DEPTH = 1 , M_TITLE = '{$_POST['M_TITLE']}' ".$main_img_query." , REGI_DATE = now() ";
        $result_main = sql_query($sql_main_insert);
        //insert 시 제일 마지막에 들어간 번호를 가져온다.
        if($result_main){
            $sql_last_insert_id = sql_fetch("select last_insert_id()");
            $result_last_id = $sql_last_insert_id['last_insert_id()'];
        }else{
            echo "insert 문 오류 : ".$sql_main_insert;
            exit;
        }
        $mode='i';
    }
    //2021.02.25 16:26 등록 수정 파일 없이 등록 수정 test 이상 없음 -kkw

    //도선지 등록이 되었으면 내용등록 필요
    if($result_main){
        for($i = 0; $i < $real_count_total; $i++){
            $filename_org_query = ''; // 파일 원래이름 쿼리 변수
            $filename_query = ''; //파일 이름 쿼리 변수
            $filetype_query = ''; //파일 타입 쿼리 변수

            $magazine_content_name  = ''; //원래 이름 변수
            $dest_path_name = ''; //변화된 이름 변수
            $file_type = '';
            $sql_data_sel = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$_POST['idx_'.$real_count[$i]]}";
            $result_data_sel = sql_fetch($sql_data_sel);
            //첨부파일 넣기
            $content_file = '';
            $content_file = $_FILES['FILENAME_ORG_'.$real_count[$i]];
//            if(!$content_file){
//                echo $i."번째에 파일이 없습니다."." 넘어온 숫자 : ".$real_count[$i];
//                exit;
//            }
            if(isset($content_file) && $content_file != '') {
                //$char .= $content_file['name']." : ";
//                echo "파일있을 때 들어오나?";
//                exit;
                //오류사항 점검
                if(UPLOAD_ERR_OK !=$content_file['error']) {
                    switch ($content_file['error']) {
                        case UPLOAD_ERR_INI_SIZE:
                            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $message = "The uploaded file was only partially uploaded";
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $message = "No file was uploaded";
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            $message = "Missing a temporary folder";
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            $message = "Failed to write file to disk";
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $message = "File upload stopped by extension";
                            break;
                        default:
                            $message = "Unknown upload error";
                            break;
                    }
                    echo $message;
                    exit;
                }
                //없을 경우 추가
                if (!is_dir($magazine_img_dir)) {
                    @mkdir($magazine_img_dir, G5_DIR_PERMISSION);
                    @chmod($magazine_img_dir, G5_DIR_PERMISSION);
                }
                //형식이 맞지 않을 경우 돌려보내기
                if (!preg_match($file_regex, $content_file['name'])) {
                    echo $content_file['name'] . " 은(는) 형식에 맞는 파일이 아닙니다.";
                    exit;
                }

                //DB에 원래 내용이 있고 첨부파일이 있다면 DB원래에 있던 원래 파일 제거
                if($result_data_sel['FILENAME'] != ''){
//                    echo $result_data_sel['FILENAME'];
//                    exit;
                    unlink($magazine_img_dir.$result_data_sel['FILENAME']);
                }

                if (preg_match($file_regex, $content_file['name'])) {
                    @mkdir($magazine_img_dir, G5_DIR_PERMISSION);
                    @chmod($magazine_img_dir, G5_DIR_PERMISSION);
                    $file_name_md5 = md5($content_file['name']).$i;
                    $file_type = $content_file['type'];

                    //확장자 구하기
                    $file_ext = explode('/',$file_type);
                    //암호화된 이름 저장
                    $dest_path_con = $magazine_img_dir.$file_name_md5.".".$file_ext[1];

                    if(!move_uploaded_file($content_file['tmp_name'], $dest_path_con)){
                        echo "업로드 오류!! ".($i+1)."번째 파일에서 오류 발생";
                        exit;
                    }

                    chmod($dest_path_con, G5_FILE_PERMISSION);

                    $magazine_content_name = $content_file['name'];

                    $dest_path_name = $file_name_md5.".".$file_ext[1];

//                    echo $dest_path_name;
//                    exit;

                    //파일이 있을 때만 추가해 파일 업데이트 구문이 작동하도록 변경
                    $filename_org_query = ", FILENAME_ORG = '{$magazine_content_name}' ";
                    $filename_query = ", FILENAME = '{$dest_path_name}' ";
                    $file_type_query = ", FILETYPE = '{$file_type}' ";
                }
            }
            //원본이름과 암호화된 이름을 각각 다른 컬럼에 저장
            if($result_data_sel){
                //있을 경우 update
                $sql_data_update = '';
                $sql_data_update = " update CMS_MAGAZINE_NEW_TEST 
                                    set SCGCODE = '{$_POST['doseonge_mean_'.$real_count[$i]]}' , 
                                    C_TITLE = '{$_POST['C_TITLE_'.$real_count[$i]]}' ,
                                    C_PAGE = '{$_POST['C_PAGE_'.$real_count[$i]]}' 
                                    ".$filename_org_query." 
                                    ".$filename_query." 
                                    ".$file_type_query." ,
                                    REGI_DATE = now() where IDX = {$_POST['idx_'.$real_count[$i]]} ";
                $result_data = sql_query($sql_data_update);
                if(!$result_data){
                    echo "내용 업데이트 오류  : ".$sql_data_update;
                    exit;
                }
            }else{
                //없을 경우 insert
                $sql_data_insert = '';
                $sql_data_insert = " insert into CMS_MAGAZINE_NEW_TEST 
                                    set PARENTIDX = {$result_last_id} , 
                                    SCGCODE = '{$_POST['doseonge_mean_'.$real_count[$i]]}' ,
                                    C_TITLE = '{$_POST['C_TITLE_'.$real_count[$i]]}' ,
                                    C_PAGE = '{$_POST['C_PAGE_'.$real_count[$i]]}' ,
                                    FILENAME_ORG = '{$magazine_content_name}' ,
                                    FILENAME = '{$dest_path_name}' ,
                                    FILETYPE = '{$file_type}' ,
                                    DEPTH = 2 ,  
                                    REGI_DATE = now() ";
                $result_data = sql_query($sql_data_insert);
                if(!$result_data){
                    echo "내용 등록 오류  : ".$sql_data_insert;
                    exit;
                }
            }
        }
//        echo $char;
//        exit;
//        exit;
    }
    //2021.02.25 17:46 insert update 문제 없음 - kkw

    if($result_main && $result_data && $mode == 'm'){
        echo "ok_mo";
        exit;
    }else if($result_main && $result_data && $mode == 'i'){
        echo "ok_in";
        exit;
    }else{
        echo "no";
        exit;
    }
    //도선지 삭제일 경우
    //2021.02.25 17:49 분기별로 들어오는거 확인 -kkw
}else if(trim($magazine_del) == "도선지삭제"){
//        echo "들어는 오니? ".$magazine_del;
//        exit;
        //있는 데이터 인지 확인
        $sql_del_sel = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$_POST['M_IDX']} ";
        $result_del_sel = sql_fetch($sql_del_sel);
//        echo $result_del_sel['M_IMG'];
//        exit;
        if($result_del_sel){
            //있을 경우 삭제
            //이거전에 파일 unlink 등이 필요하다 묶인 파일들 해제 필요
            //메인 이미지 삭제
            if(isset($result_del_sel['M_IMG']) && $result_del_sel['M_IMG'] != ''){
//                echo "메인 이미지 삭제 들어오나?";
//                exit;
                unlink($magazine_img_dir.$result_del_sel['M_IMG']);
                //썸네일 (메인,상세보기 페이지) 삭제
                $del_ex = explode('.',$result_del_sel['M_IMG']);
                if($del_ex[1] == "jpeg"){
                    $del_ex[1] = "jpg";
                }
                unlink($magazine_img_dir."thumb/thumb-".$del_ex[0]."_295x396.".$del_ex[1]);
                unlink($magazine_img_dir."thumb/thumb-".$del_ex[0]."_250x335.".$del_ex[1]);
            }
            //밑에 내용들 첨부파일 삭제를 위해 이름찾기
            $sql_del_sel_content = " select * from CMS_MAGAZINE_NEW_TEST where PARENTIDX = {$_POST['M_IDX']}";

//            echo "선택문 작용 완료? : ".$sql_del_sel_content;
//            exit;
            // 메인 이미지 삭제 확인 완료 2021.03.02 12:34 kkw

            //있을 경우 그안에 있는 모든 첨부파일 삭제
            if($result_del_sel_content = sql_query($sql_del_sel_content)){
                //$chance = 0;
                for($c = 0; $del_con = sql_fetch_array($result_del_sel_content); $c++){
                    //$chance++;
                    unlink($magazine_img_dir.$del_con['FILENAME']);
                }
//                echo "몇 번 도는지 확인 : ".$chance;
//                exit;
            }
            //내용관련 첨부파일 삭제 완료 2021.03.02 13:17 kkw

            $sql_del_main = " delete from CMS_MAGAZINE_NEW_TEST where IDX = {$_POST['M_IDX']} ";
            $result_del_main = sql_query($sql_del_main);
            $sql_del_data = " delete from CMS_MAGAZINE_NEW_TEST where PARENTIDX = {$_POST['M_IDX']} ";
            $result_del_data = sql_query($sql_del_data);
            //DB 내용 삭제 확인 완료 2021.03.02 13:17 kkw
        }else{
            echo " 도선지 삭제 오류 : ".$sql_del_sel." 쿼리문 결과 값 : ".$result_del_sel;
            exit;
        }
    if($result_del_main){
        echo "ok_del";
        exit;
    }else{
        echo "no_del";
        exit;
    }
    //2021.02.25 18:07 도선지 삭제 및 하위 내용 삭제 테스트 확인 첨부파일 관련 unlink가 필요함
    //그냥 삭제일 경우
}else if($magazine_del == ''){
    $result_del_content='';
    //있는 데이터 인지 확인 일단 삭제할 내용의 IDX를 찾는다.
    $sql_content_sel = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$content_del}";
    $result_content = sql_query($sql_content_sel);
    //있을 경우 DB에서 삭제 없을 경우 화면에서만 삭제
    if($result_content){
        //하기 전에 연결되어 있는 첨부파일 unlink해주기
        $result_del_file = sql_fetch_array($result_content);
        //이름을 찾아 그 파일 삭제 (있을 경우에만)
        if(isset($result_del_file['FILENAME']) && $result_del_file['FILENAME'] != ''){
            $result_del_name = $magazine_img_dir.$result_del_file['FILENAME'];
            unlink($result_del_name);
        }
        $sql_del_content = " delete from CMS_MAGAZINE_NEW_TEST where IDX = {$content_del}";
        $result_del_content = sql_query($sql_del_content);
    }
    if($result_del_content){
        echo "ok_content_del";
        exit;
    }else{
        echo "no_content_del";
        exit;
    }
}

