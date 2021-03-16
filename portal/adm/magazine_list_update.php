<?php
$sub_menu = "300300";
include_once("./_common.php");

check_demo();

auth_check_menu($auth, $sub_menu, "d");

check_admin_token();

$msg = "";
$magazine_img_dir = G5_DATA_PATH.'/magazine_test/';
if ($_POST['act_button'] == "선택삭제") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
       // 실제 번호를 넘김
       $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;

       //파일 삭제를 위해 파일명을 가져온다.
       $sql_sel_honor = " select * from CMS_MAGAZINE_NEW_TEST where IDX = {$_POST['IDX_'.$k]} ";
       $result_sel_honor = sql_fetch($sql_sel_honor);
       if(!$result_sel_honor){
        alert('삭제할 도선지가 없습니다.! 다시 시도해주세요');
        exit;
       }else if($result_sel_honor){
        
            unlink($magazine_img_dir.$result_sel_honor['M_IMG']);
            //썸네일 (메인,상세보기 페이지) 삭제
            $del_ex = explode('.',$result_sel_honor['M_IMG']);
            if($del_ex[1] == "jpeg"){
                $del_ex[1] = "jpg";
            }
            unlink($magazine_img_dir."thumb/thumb-".$del_ex[0]."_295x396.".$del_ex[1]);
            unlink($magazine_img_dir."thumb/thumb-".$del_ex[0]."_250x335.".$del_ex[1]);

            //밑에 내용들 첨부파일 삭제를 위해 이름찾기
            $sql_del_sel_content = " select * from CMS_MAGAZINE_NEW_TEST where PARENTIDX = {$_POST['IDX_'.$k]}";

            //있을 경우 그안에 있는 모든 첨부파일 삭제
            if($result_del_sel_content = sql_query($sql_del_sel_content)){
                //$chance = 0;
                for($c = 0; $del_con = sql_fetch_array($result_del_sel_content); $c++){
                    //$chance++;
                    unlink($magazine_img_dir.$del_con['FILENAME']);
                }
            }

            $sql_del_main = " delete from CMS_MAGAZINE_NEW_TEST where IDX = {$_POST['IDX_'.$k]} ";
            $result_del_main = sql_query($sql_del_main);
            $sql_del_data = " delete from CMS_MAGAZINE_NEW_TEST where PARENTIDX = {$_POST['IDX_'.$k]} ";
            $result_del_data = sql_query($sql_del_data);

            if(!$sql_del_main || !$result_del_data){
                alert('delete 구문 오류'.$result_del_main." 내용 구문 요류".$result_del_data);
                exit;
            }
       }
    }
}

if ($msg)
    //echo '<script> alert("'.$msg.'"); </script>';
    alert($msg);
//run_event('admin_member_list_update', $_POST['act_button'], $mb_datas);

//goto_url('./honor_member_list.php?'.$qstr);

//goto_url('./honor_member_list.php?msg=');
alert('삭제되었습니다.', './magazine_list.php');