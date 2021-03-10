<?php
include_once('./_common.php');

$mb_id = '';
$m = '';
$m = $_GET['m'];
$content_file = ''; //파일에 관한 것을 담는 변수
$image_regex = "/(\.(gif|jpe?g|png))$/i"; //이미지 정규식

$honor_img_dir = G5_DATA_PATH.'/honor_member/';

if($m == ''){
    echo "잘못된접근입니다. 다시시도해주세요";
    exit;
}

if(isset($_POST['regi_id']) && $_POST['regi_id'] != ''){
    $mb_id = $_POST['regi_id'];
}

//사용자 찾기 값이 나올 경우
if($m == 'r'){

    if($mb_id == ''){
        echo'넘어온 아아디 값이 없습니다. 다시 확인해주세요';
        exit;
    }

    $sql_sel_honor = " select * from kmp_MEMBER_HONOR where H_USER_ID = '{$mb_id}' ";
    $result_sel_honor = sql_fetch($sql_sel_honor);
    if($result_sel_honor['H_USER_ID']){
        echo "이미 등록된 회원입니다.";
        exit;
    }else{
        echo "등록 가능한 회원입니다.";
        exit;
    }

//기존사용자등록에서 저장하기를 눌렀을 경우 2021.03.09 16:30 확인완료
}else if($m == 'i'){
    $sql_sel_member = " select * from {$g5['member_table']} where mb_id = '{$mb_id}' ";
    $result_sel_member = sql_fetch($sql_sel_member);
    if(!$result_sel_member['mb_id']){
        echo "등록된 회원이 없습니다! 다시 확인해주세요";
        exit;
    }else{
        //파일 복사하기 및 이동하기
        //파일이 있는지 확인하기
        $md_name = '';
        $md_name_query = '';
        $mb_dir = substr($result_sel_member['mb_id'],0,2);
        $icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.get_mb_icon_name($result_sel_member['mb_id']).'.gif';
        //파일이 있을 경우 복사하기
        if (file_exists($icon_file)) {
            //폴더 있는지 확인
            if( !is_dir($honor_img_dir) ){
                @mkdir($honor_img_dir, G5_DIR_PERMISSION);
                @chmod($honor_img_dir, G5_DIR_PERMISSION);
            }
            $md_name = md5($result_sel_member['mb_id']);
            $result_copy = copy($icon_file,$honor_img_dir.$md_name.'.gif');
            chmod($md_name.'gif', G5_FILE_PERMISSION);
            if(!$result_copy){
                echo "파일 복사 오류!";
                exit;
            }
            $md_name_query = " H_USER_PHOTO = '{$md_name}.gif' ,";
        }
        $mb_birth = explode('-', $result_sel_member['mb_birth']);
        $sql_in_honor = " insert into kmp_MEMBER_HONOR set H_USER_GROUP_KEY = '{$result_sel_member['mb_doseongu']}' , H_USER_ID = '{$result_sel_member['mb_id']}' ,
                               H_USER_NAME = '{$result_sel_member['mb_name']}' , {$md_name_query} H_POSITION = '' , H_USER_BIRTH = '{$mb_birth[0]}' ,
                               H_RETIRE_DATE = '{$result_sel_member['mb_retire_date']}' , REG_DATE = now() , REG_IP = '{$_SERVER["REMOTE_ADDR"]}' ";
        $result_in_honor = sql_query($sql_in_honor);
        if(!$result_in_honor){
            echo "명예도선사 insert 구문 오류!";
            exit;
        }
        echo "등록이 완료되었습니다.";
        exit;
    }
//수정하기 일 경우 테스트 완료 kkw 2021.03.10 10:30
}else if($m == 'c'){
    // echo "수정하기 입니다.";
    // exit;
    $save_name = '';

    //명예도선사 이미지 삭제가 있을 경우 삭제 하기 또는 이미지가 들어왔을 경우
    if((isset($_POST['del_m_image']) && $_POST['del_m_image'] != '') || (isset($_FILES['honor_pic']) && $_FILES['honor_pic'] != '')){
        $sql_sel_user_img = " select * from kmp_MEMBER_HONOR where H_USER_ID = {$_POST['m_regi_id']} ";
        $result_sel_user_img = sql_fetch($sql_sel_user_img);
        //이름을 찾아 그 파일 삭제
        $del_name = $honor_img_dir.$result_sel_user_img['H_USER_PHOTO'];
        unlink($del_name);
    }
    //이미지가 들어왔을 경우 넣고 DB에 저장
    if(isset($_FILES['honor_pic']) && $_FILES['honor_pic']){
        $content_file = $_FILES['honor_pic'];

        //형식이 맞지 않을 경우 돌려보내기
        if (!preg_match($image_regex, $content_file['name'])) {
            echo $content_file['name']." 은(는) 이미지 파일이 아닙니다.";
            exit;
        }

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
            echo "파일 오류 사항 : ".$message;
            exit;
        }
        //오류가 없을 시 진행
        if (preg_match($image_regex, $content_file['name'])) {

            @mkdir($honor_img_dir, G5_DIR_PERMISSION);
            @chmod($honor_img_dir, G5_DIR_PERMISSION);

            //파일이름 암호화 해놓기
            $md_name_modi = md5($content_file['name']);
            //확장자 나누기
            $ext_file = explode('/', $content_file['type']);

            //저장될 이름
            $save_name = $md_name_modi.".".$ext_file[1];

            $dest_path = $honor_img_dir.$md_name_modi.".".$ext_file[1];

            move_uploaded_file($content_file['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            
        }

    }
    $save_name_query = '';
    if($save_name != ''){
        $save_name_query = "H_USER_PHOTO = '{$save_name}' ,";
    }
    
    //DB에 추가 하기
    $sql_up_honor = "update kmp_MEMBER_HONOR set H_USER_GROUP_KEY = '{$_POST['H_USER_GROUP_KEY']}' , H_USER_NAME = '{$_POST['H_USER_NAME']}' , {$save_name_query} H_POSITION = '{$_POST['H_POSITION']}' , H_USER_BIRTH = '{$_POST['H_USER_BIRTH']}' ,
    H_RETIRE_DATE = '{$_POST['H_RETIRE_DATE']}' , REG_IP = '{$_SERVER["REMOTE_ADDR"]}' where H_USER_ID = '{$_POST['m_regi_id']}' ";
    $result_up_honor = sql_query($sql_up_honor);
    if(!$result_up_honor){
        echo "update 문 오류 발생";
        exit;
    }
    echo "수정이 완료되었습니다.";
    exit;


//신규등록 일 경우
}else if($m =='n'){
    echo "신규등록입니다.";
    exit;
    
}
