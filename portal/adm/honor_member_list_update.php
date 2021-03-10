<?php
$sub_menu = "200200";
include_once('./_common.php');

check_demo();

if (! (isset($_POST['chk']) && is_array($_POST['chk']))) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();
$mode = '';
$honor_img_dir = G5_DATA_PATH.'/honor_member/';

if ($_POST['act_button'] == "선택수정") {
    
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        
        $post_position = isset($_POST['H_POSITION_'.$k]) ? (int) $_POST['H_POSITION_'.$k] : 0;

        $sql = " update kmp_MEMBER_HONOR set H_POSITION = '{$post_position}' where IDX = {$_POST['IDX_'.$k]} ";
        $result_up_position = sql_query($sql);
        if(!$result_up_position){
            alert('update 구문 오류'.$sql);
            exit;
        }
    }
    $mode='m';
    
} else if ($_POST['act_button'] == "선택삭제") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
       // 실제 번호를 넘김
       $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;

       //파일 삭제를 위해 파일명을 가져온다.
       $sql_sel_honor = " select * from kmp_MEMBER_HONOR where IDX = {$_POST['IDX_'.$k]} ";
       $result_sel_honor = sql_fetch($sql_sel_honor);
       if(!$result_sel_honor){
        alert('삭제할 회원이 없습니다.! 다시 시도해주세요');
        exit;
       }else if($result_sel_honor){
        
        //이름을 찾아 그 파일 삭제
        $del_name = $honor_img_dir.$result_sel_honor['H_USER_PHOTO'];
        unlink($del_name);
       }


       $sql = " delete from kmp_MEMBER_HONOR where IDX = {$_POST['IDX_'.$k]} ";
        $result_del_position = sql_query($sql);
        if(!$result_del_position){
            alert('delete 구문 오류'.$sql);
            exit;
        }
    }
    $mode='d';
}

if ($msg)
    //echo '<script> alert("'.$msg.'"); </script>';
    alert($msg);
//run_event('admin_member_list_update', $_POST['act_button'], $mb_datas);

//goto_url('./honor_member_list.php?'.$qstr);

goto_url('./honor_member_list.php?msg='.$mode);
