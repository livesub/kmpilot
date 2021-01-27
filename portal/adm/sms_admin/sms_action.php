<?php
include_once("./_common.php");
//require_once(G5_ADMIN_PATH."/sms_admin/lib/class.WebApp.php");
require_once(G5_ADMIN_PATH."/sms_admin/lib/class.SURESMS.php");
include_once(G5_ADMIN_PATH."/sms_admin/lib/lib.file.php");

$SMS = new SURESMS();

switch($_POST['mode']){
	case "mb_addr":
        $result = sql_query(" select * from {$g5['member_table']} where mb_hp != '' order by mb_no desc ");

        while($row = sql_fetch_array($result)){
            $mList[] = $row;
        }
		print "{\"count\":".count($mList).",\"list\":[";
		foreach($mList as $_idx=>$_list){
            //$_xml[] = '{"mb_id":"'.$_list['mb_id'].'","mb_name":"'.$_list['mb_name'].'","m_key":"'.$_list['USER_KEY'].'","m_idx":"'.$_list['IDX'].'","m_phone":"'.$_list['USER_MOBILE'].'"}';
            $_xml[] = '{"mb_id":"'.$_list['mb_id'].'","mb_name":"'.$_list['mb_name'].'","mb_no":"'.$_list['mb_no'].'","mb_hp":"'.$_list['mb_hp'].'"}';
		}
		print @implode(",",$_xml);
		print "]}";
		exit;
    break;


	case "mb_search":
		if($_POST['s_word']){
			$_where[] = "mb_hp like '%".trim($_POST["s_word"])."%' OR mb_name like '%".trim($_POST["s_word"])."%'";
		}
		if($_POST['addr_sw']){
            if($_POST['addr_sw'] != "회원 찾기")
            {
                //$_where[] = "mb_name LIKE '%".$_POST['addr_sw']."%'";
                $_where[] = "mb_hp like '%".trim($_POST["addr_sw"])."%' OR mb_name like '%".trim($_POST["addr_sw"])."%'";
            }
		}

		if($_POST['addr_group_type']=="B"){
/*
			$_where[] = "trim(M.USER_ID)=trim(MB.USERID)";
			$_where[] = "trim(USER_GROUPID)='".$_POST['addr_group']."'";

            $qry_table = $MEM->_Tables["member"]." M,(SELECT USERID,USER_GROUPID from ".$MEM->_Tables["membership"].") MB";
*/
		}else{
			if($_POST['addr_group']){

				//$_where[] = "gr_id = '".$_POST['addr_group']."'";
			}
			$qry_table = $MEM->_Tables["member"];
		}
		if (count($_where) > 0){
			$qryWhere = @implode(" and ", @$_where);
			$retValue = " where ".$qryWhere;
        }

        //그룹으로 들어 왔을 경우
        if($_POST['addr_group_type']=="G"){

            if (count($_where) > 0){
                $_where = " (a.mb_hp like '%".trim($_POST["addr_sw"])."%' OR a.mb_name like '%".trim($_POST["addr_sw"])."%')";
                $retValue = " and ".$_where;
            }

            $result = sql_query(" select a.mb_no as mb_no, a.mb_name as mb_name, a.mb_hp as mb_hp from {$g5['member_table']} a INNER JOIN {$g5['group_member_table']} b ON a.mb_id = b.mb_id and b.gr_id='{$_POST[addr_group]}' and mb_hp != '' ".$retValue." ORDER BY mb_no desc ");
            while($row = sql_fetch_array($result)){
                $get_list[] = $row;
            }

        }else if($_POST['addr_group_type']=="B"){
            if (count($_where) > 0){
                $_where = " (mb_hp like '%".trim($_POST["addr_sw"])."%' OR mb_name like '%".trim($_POST["addr_sw"])."%')";
                $retValue = " and ".$_where;
            }

            $result = sql_query("select * from {$g5['member_table']} where mb_hp != '' and mb_doseongu='{$_POST['addr_group']}' ".$retValue." ORDER BY mb_no desc ");

            while($row = sql_fetch_array($result)){
                $get_list[] = $row;
            }

        }else{

			$result = sql_query("select * from {$g5['member_table']}  ".$retValue." and mb_hp != '' ORDER BY mb_no desc ");
            while($row = sql_fetch_array($result)){
                $get_list[] = $row;
            }
        }

		$mList = $get_list;
		print "{\"count\":".count($mList).",\"list\":[";
		foreach($mList as $_idx=>$_list){
            //$_xml[] = '{"m_id":"'.$_list['USER_ID'].'","m_name":"'.$_list['USER_NAME'].'","m_key":"'.$_list['USER_KEY'].'","m_idx":"'.$_list['IDX'].'","m_phone":"'.$_list['USER_MOBILE'].'"}';
            $_xml[] = '{"mb_id":"'.$_list['mb_id'].'","mb_name":"'.$_list['mb_name'].'","mb_no":"'.$_list['mb_no'].'","mb_hp":"'.$_list['mb_hp'].'"}';
		}
		print @implode(",",$_xml);
		print "]}";
		exit;
	break;

    //그룹 리스트
    case "group_addr":
        $result = sql_query(" select * from {$g5['group_table']} where gr_id != 'community' ");
        while($row = sql_fetch_array($result)){
            $groupList[] = $row;
        }
		print "{\"count\":".count($groupList).",\"list\":[";
		foreach($groupList as $_idx=>$_list){
            $row_group_member = sql_fetch(" select count(*) as cnt from {$g5['group_member_table']} where gr_id = '{$_list['gr_id']}' ");
            $group_member_count = $row_group_member['cnt'];
            //$_xml[] = '{"m_gid":"'.$_list['USER_GROUPID'].'","m_gname":"'.$_list['DESCRIPTION'].'","m_gkey":"'.$_list['USER_GROUPID'].'","m_gcount":"'.$G_CNT.'"}';
            $_xml[] = '{"gr_id":"'.$_list['gr_id'].'","gr_subject":"'.$_list['gr_subject'].'","m_gcount":"'.$group_member_count.'"}';
		}
		print @implode(",",$_xml);
		print "]}";
		exit;
	break;

    //지회 리스트
	case "branch_addr":
		$branchList = array(1 => "부산항도선사회", 2 => "여수항도선사회", 3 => "인천항도선사회", 4 => "울산항도선사회", 5 => "평택항도선사회", 6 => "마산항도선사회", 7 => "대산항도선사회", 8 => "포항항도선사회", 9 => "군산항도선사회", 10 => "목포항도선사회", 11 => "동해항도선사회", 12 => "제주항도선사회");
        print "{\"count\":".count($branchList).",\"list\":[";
		foreach($branchList as $_idx=>$_list){
            $row = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_hp != '' and mb_doseongu='{$_idx}' ");
            $doseongu_count = $row['cnt'];

			$_xml[] = '{"br_key":"'.$_idx.'","br_name":"'.$_list.'","br_count":"'.$doseongu_count.'"}';
		}
		print @implode(",",$_xml);
		print "]}";
		exit;
	break;


    //교육 리스트
	case "edu_addr":
        print "{\"count\":12,\"list\":[";

        $eduList = array(1 => "부산항도선사회");
        $edu_apply_count = 0;
        $ment = "교육명들어감";
		foreach($eduList as $_idx=>$_list){
            $_xml[] = '{"edu_key":"'.$_idx.'","edu_name":"'.$ment.'","edu_count":"'.$edu_apply_count.'"}';
		}
		print @implode(",",$_xml);
		print "]}";
		exit;
	break;


	case "recent_addr":
		$recentList = $SMS->getRecentList();
		print "{\"count\":".count($recentList).",\"list\":[";
		foreach($recentList as $_idx=>$_list){
			$chk_member = $MEM->getMember($_list['USER_ID']);
			$ret_phone  =  $_list['RPHONE1']."-".$_list['RPHONE2']."-".$_list['RPHONE3'];
			$ret_member = ($chk_member["IDX"])? "1":"0";
			$_xml[] = '{"m_rid":"'.$_list['USER_ID'].'","m_rname":"'.$_list['RECV_NAME'].'","m_ridx":"'.$chk_member['IDX'].'","m_rphone":"'.$ret_phone.'","m_rchk":"'.$ret_member.'"}';
		}
		print @implode(",",$_xml);
		print "]}";
		exit;
	break;
	case "important_addr":
		$importantList = $MEM->getMemberList(" where I_MARK='1'");
		print "{\"count\":".count($importantList).",\"list\":[";
		foreach($importantList as $_idx=>$_list){
			$ret_member = ($_list["USER_MOBILE"])? "1":"0";
			$_xml[] = '{"impt_id":"'.$_list['USER_ID'].'","impt_name":"'.$_list['USER_NAME'].'","impt_idx":"'.$_list['IDX'].'","impt_phone":"'.$_list['USER_MOBILE'].'","impt_m_chk":"'.$ret_member.'"}';
		}
		print @implode(",",$_xml);
		print "]}";
		exit;
	break;

	case "send_sms":
		$result = $SMS->send_mobile($_POST);
		alert("발송되었습니다.","sms_write.php");
/*
		if($result == "" || $result == 0){
			alert("발송에 실패 하였습니다.");
			exit;
		}else{
			alert("발송되었습니다.","sms_write.php");
			exit;
		}
*/
	break;

	case "config_modify":
		unset($CFG);
		$CFG['cms_mng']				= $_POST['cms_mng'];
		$CFG['cms_sms_number']		= $_POST['cms_sms_number'];
		$CFG['cms_sms_title']		= $_POST['cms_sms_title'];
		$mkeys	= array("CFG" => $CFG);

		reset( $mkeys );
		while ( list( $mk, $mv ) = each( $mkeys ) ) {
			if (is_array($mv)) {
				while ( list( $k, $v ) = each( $mv ) ) {
					$v = addslashes( $v );
					$result = sql_query("UPDATE SMS_CONFIG SET value='".$v."' WHERE mkey='".$mk."' AND skey='".$k."'");
				}
			}
		}
		alert("수정되었습니다.");
	break;
	case "sms_skin_insert":
		$result = $SMS->create_sms_skin($_POST);
		$Wapp->alertReload("등록되었습니다.","parent");
		break;

	case "sms_skin_modify":
		$result = $SMS->modifySMS($_POST["idx"],$_POST);
		$Wapp->alertReload("수정되었습니다.","parent");
		break;
	case "sms_skin_delete":
		$SMS->delete_sms_skin($_POST);
		$Wapp->alertReload("삭제되었습니다.","top");
		break;
	case "sms_data_delete":
		$SMS->delete_sms_data($_POST);
		$Wapp->alertReload("삭제되었습니다.","top");
		break;

    case "up_img":
        $result = $SMS->up_img($_FILES);
		if(is_file($result['pathFile'])){
			echo "<script type=\"text/javascript\">";
			echo "window.parent.imageProcFunc('".$result['pathFile']."','".$result['fileName']."','".$result['fileFolder']."','".G5_DATA_URL."');";
			echo "</script>";
			exit;
		}else{
            //$Wapp->alertReload("등록 파일이 없습니다.","top");
            alert("등록 파일이 없습니다.");
			exit;
		}
		break;

	case "del_img":
		$result = $SMS->delete_sms_img($_POST);
		print "{\"count\":".$result."}";
		exit;
		break;

	case "kakao":
		$result = $SMS->send_kakao($_POST);

echo "result=====> ".$result;
exit;
		alert("발송되었습니다.","kakao_write.php");
	break;



}

?>
