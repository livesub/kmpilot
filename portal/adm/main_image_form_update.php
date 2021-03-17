<?php
$sub_menu = "100050";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

if ($w == 'u')
    check_demo();

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

$subject = addslashes(trim($_POST['subject']));
$turn = $_POST['turn'];
$type = $_POST['type'];
$idx = $_POST['idx'];

if($w == ""){
    //등록
    if($_FILES['image_name']['name'] == ""){
        alert("첨부 이미지가 없습니다.","./main_image_form.php");
        exit;
    }

    if (isset($_FILES['image_name']) && is_uploaded_file($_FILES['image_name']['tmp_name'])) {
        $main_img_dir = G5_DATA_PATH.'/main_image';
        $image_regex = "/(\.(gif|jpe?g|png))$/i";
        if (!preg_match($image_regex, $_FILES['image_name']['name'])) {
            alert($_FILES['image_name']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match($image_regex, $_FILES['image_name']['name'])) {

            @mkdir($main_img_dir, G5_DIR_PERMISSION);
            @chmod($main_img_dir, G5_DIR_PERMISSION);

            //이미지 파일 이음 변환
            $file_tmp = explode(".",$_FILES['image_name']['name']);
            $file_name_change = md5(uniqid(rand(), true)).".".$file_tmp[1];

            $dest_path = $main_img_dir.'/'.$file_name_change;
            move_uploaded_file($_FILES['image_name']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > G5_MAIN_IMG_WIDTH || $size[1] > G5_MAIN_IMG_HEIGHT) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($file_name_change, $main_img_dir, $main_img_dir, G5_MAIN_IMG_WIDTH, G5_MAIN_IMG_HEIGHT, true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($main_img_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }
    $result = sql_query(" insert into {$g5['main_image_table']} set subject ='{$subject}', image_name='{$file_name_change}', turn='{$turn}', type='{$type}'  ");
    $ment = "저장";
}else if($w == "u"){
    //수정
    $row = sql_fetch(" select * from {$g5['main_image_table']} where idx = '{$idx}' ");

    if($_FILES['image_name']['name'] != ""){
        //이미지가 새로 들어 왔을때
        if($row['image_name'] != ""){
            //기존에 이미지가 있었다면 이미지 삭제
            $delete = @unlink(G5_DATA_PATH."/main_image/".$row['image_name']);
        }

        if (isset($_FILES['image_name']) && is_uploaded_file($_FILES['image_name']['tmp_name'])) {
            $main_img_dir = G5_DATA_PATH.'/main_image';
            $image_regex = "/(\.(gif|jpe?g|png))$/i";
            if (!preg_match($image_regex, $_FILES['image_name']['name'])) {
                alert($_FILES['image_name']['name'] . '은(는) 이미지 파일이 아닙니다.');
            }

            if (preg_match($image_regex, $_FILES['image_name']['name'])) {

                @mkdir($main_img_dir, G5_DIR_PERMISSION);
                @chmod($main_img_dir, G5_DIR_PERMISSION);

                //이미지 파일 이음 변환
                $file_tmp = explode(".",$_FILES['image_name']['name']);
                $file_name_change = md5(uniqid(rand(), true)).".".$file_tmp[1];

                $dest_path = $main_img_dir.'/'.$file_name_change;
                move_uploaded_file($_FILES['image_name']['tmp_name'], $dest_path);
                chmod($dest_path, G5_FILE_PERMISSION);

                if (file_exists($dest_path)) {
                    $size = @getimagesize($dest_path);
                    if ($size[0] > G5_MAIN_IMG_WIDTH || $size[1] > G5_MAIN_IMG_HEIGHT) {
                        $thumb = null;
                        if($size[2] === 2 || $size[2] === 3) {
                            //jpg 또는 png 파일 적용
                            $thumb = thumbnail($file_name_change, $main_img_dir, $main_img_dir, G5_MAIN_IMG_WIDTH, G5_MAIN_IMG_HEIGHT, true, true);
                            if($thumb) {
                                @unlink($dest_path);
                                rename($main_img_dir.'/'.$thumb, $dest_path);
                            }
                        }
                        if( !$thumb ){
                            // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                            @unlink($dest_path);
                        }
                    }
                }
            }
        }
        $result = sql_query(" update {$g5['main_image_table']} set subject ='{$subject}', image_name='{$file_name_change}', turn='{$turn}', type='{$type}' where idx='{$idx}' ");
    }else{
        //첨부 이미지 없이 수정 들어 왔을 경우
        $result = sql_query(" update {$g5['main_image_table']} set subject ='{$subject}', turn='{$turn}', type='{$type}' where idx='{$idx}' ");
    }
    $ment = "수정";
}

alert("정상적으로 {$ment} 되었습니다.","./main_image_list.php");
?>