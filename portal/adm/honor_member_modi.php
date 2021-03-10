<?php

include_once ("./_common.php");
//alert('들어왔나?');
?>
 <script src="../js/jquery-1.12.4.min.js"></script>
<?php
$w = $_GET['w'];
if($w == 'u'){
    if(!isset($_POST['honor_member_id']) || $_POST['honor_member_id'] == ''){
        ?>
        <script>
            alert('잘못된 접근입니다.');
            window.close();
        </script>
    <?php
    }

    $member_id = $_POST['honor_member_id'];

    $sql_sel_honor = " select * from kmp_MEMBER_HONOR where IDX = {$member_id}";
    $result_honor = sql_fetch($sql_sel_honor);
}

?>
<?php if($w == 'u'){?>

<form name="honor_form" id="honor_form" enctype="multipart/form-data" method="post" onsubmit="ajax_honor_sel('c','honor_form')">
    <table>
        <tr>
            <input type="hidden" name="m_regi_id" id="m_regi_id" value="<?=$result_honor['H_USER_ID']?>">
            <th>이름(한글)</th>
            <td><input type="text" id="H_USER_NAME" name="H_USER_NAME" value="<?=$result_honor['H_USER_NAME']?>"></td>
        </tr>
        <tr>
            <th>사진</th>
            <td><input type="file" id="honor_pic" name="honor_pic" onchange="check_file()"></td>
            <td>
            <?php if(isset($result_honor['H_USER_PHOTO']) && $result_honor['H_USER_PHOTO'] != ''){
                $icon_file2 = G5_DATA_PATH.'/honor_member/'.get_mb_icon_name($result_honor['H_USER_PHOTO']);
                if (file_exists($icon_file2)) {
                    $icon_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $icon_file2);
                    $icon_filemtile2 = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?'.filemtime($icon_file2) : '';
                    echo '<img src="'.$icon_url.$icon_filemtile2.'" alt="" width="100px" height="100px">';
                    echo '<input type="checkbox" id="del_m_image" name="del_m_image" value="1">삭제';
                }
                ?>
            <?php }?>
            </td>
        </tr>
        <tr>
            <th>생년</th>
            <td><input type="text" id="H_USER_BIRTH" name="H_USER_BIRTH" value="<?=$result_honor['H_USER_BIRTH']?>" maxlength="4">ex) 1996</td>
        </tr>
        <tr>
            <th>퇴직</th>
            <td><input type="text" id="H_RETIRE_DATE" name="H_RETIRE_DATE" value="<?=$result_honor['H_RETIRE_DATE']?>" maxlength="4">ex) 1996</td>
        </tr>
        <tr>
            <th>소속</th>
            <td>
                <?php echo get_doseongu_select("H_USER_GROUP_KEY",0,12,$result_honor['H_USER_GROUP_KEY']) ?>
            </td>
        </tr>
        <tr>
            <th>직책</th>
            <td>
                <select id="H_POSITION" name="H_POSITION">
                    <option value="" <?=get_selected($result_honor['H_POSITION'],'')?>>선택</option>
                    <option value="1" <?=get_selected($result_honor['H_POSITION'],1)?>>감사</option>
                    <option value="2" <?=get_selected($result_honor['H_POSITION'],2)?>>부회장</option>
                    <option value="3" <?=get_selected($result_honor['H_POSITION'],3)?>>회장</option>
                    <option value="4" <?=get_selected($result_honor['H_POSITION'],4)?>>고문</option>
                </select>
            </td>
        </tr>
    </table>
    <button type="submit">저장하기</button><button onclick="window.close();">취소</button>
</form>

<?php } else if($w == 'i'){?>
<!-- <body onbeforeunload="close_pop()"> -->
    <label for="regi_user">
        <input type="radio" name="regi_user" id="regi_user" onclick="checked_div()">
        사용자 지정
    </label>
    <label>
        <input type="radio" name="regi_user" id="new_user" checked onclick="checked_div()">
        신규등록
    </label>
    <div style="display: none" id="regi_div" onbeforeunload="close_pop()">
        <form  id="regi_form" name="regi_form" method="post" onsubmit="ajax_honor_sel('i','regi_form')">
            <table>
                <tr>
                    <th>이름(한글)</th>
                    <td><input type="text" id="regi_name" name="regi_name" readonly><button type="button" onclick="open_popup('',450,450,'honor_member_search.php','honor_member_search')">사용자 찾기</button></td>
                    <input type="hidden" id="regi_id" name="regi_id" value="">
                </tr>
                <tr>
                    <td><button id="save_regi" type="submit">저장하기</button><button type="button" onclick="close_pop();">취소</button></td>
                </tr>
            </table>
        </form>
    </div>
    <div id="new_div" onbeforeunload="close_pop()">
        <form id="new_form" name="new_form" method="post" onsubmit="ajax_honor_sel('n', 'new_form')">
            <table>
                <tr>
                    <th>이름(한글)</th>
                    <td><input type="text" id="new_name" name="new_name"></td>
                </tr>
                <tr>
                    <th>사진</th>
                    <td><input type="file" id="new_photo" name="new_photo"></td>
                </tr>
                <tr>
                    <th>생년</th>
                    <td><input type="text" id="new_birth_year" name="new_birth_year">ex) 1996</td>
                </tr>
                <tr>
                    <th>퇴직년</th>
                    <td><input type="text" id="new_retire_year" name="new_retire_year" minlength="4" maxlength="4">ex) 1996</td>
                </tr>
                <tr>
                    <th>소속</th>
                    <td><?=get_doseongu_select('new_doseongu')?></td>
                </tr>
                <tr>
                    <th>직책</th>
                    <td>
                        <select id="new_position" name="new_position">
                            <option value="" >선택</option>
                            <option value="1" >감사</option>
                            <option value="2" >부회장</option>
                            <option value="3" >회장</option>
                            <option value="4" >고문</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><button type="submit">저장하기</button><button onclick="window.close();">취소</button></td>
                </tr>
            </table>
        </form>
    </div>
<!-- s -->
<?php }?>
<script>
    let child = '';
    window.onload = checked_div();
    // $(document).ready(function(){ // 태그 등의 셋팅이 완료되었을 시점에 이벤트 발생
    //     checked_div();
    // });
    function checked_div(){
        let new_div = document.getElementById('new_div');
        let regi_div = document.getElementById('regi_div');
        if($('input[id="regi_user"]').is(':checked')){
            //console.log('사용자 지정 체크');
            new_div.style.display = 'none';
            regi_div.style.display = 'block';
        }else if($('input[id="new_user"]').is(':checked')){
            //console.log('신규 등록 체크');
            new_div.style.display = 'block';
            regi_div.style.display = 'none';
        }
    }
    function open_popup(id,width,height,action,target){
        //$("#"+field).val(id);
        let popupX = (window.screen.width / 2) - (width / 2);
        // 만들 팝업창 좌우 크기의 1/2 만큼 보정값으로 빼주었음
        //console.log($("#honor_member_id").val());
        let popupY= (window.screen.height /2) - (height / 2);
        // 만들 팝업창 상하 크기의 1/2 만큼 보정값으로 빼주었음
        //let pop = $("#"+id);
        //pop.attr("action", action);
        //pop.attr("target", target); //window.open 두번째 인자와 값이 같아야 한다.

       child =  window.open(action, target, 'status=no, height='+height+', width='+width+', ' +
            'left='+ popupX + ', top='+ popupY + ', screenX='+ popupX + ', screenY= '+ popupY);
        //pop.submit();
    }

    //text 창 값 변화 시 ajax를 이용 honor 멤버 체크를 하는 함수
    function ajax_honor_sel(id,form){
        console.log(2);
        //if(document.getElementById('regi_name').value != ''){
        //console.log(document.getElementById('regi_id').value);
        let ajaxUrl_1 = '';
        ajaxUrl_1 = "ajax_honor_regi.php?m="+id;
        console.log(ajaxUrl_1);
        let form_id = "#"+form;
        //let queryString_regi_form = $("form[name=regi_form]").serialize();
        let queryString_regi_form = $(form_id).serialize();
        $.ajax({
            type		: "POST",
            dataType    : "text",
            url			: ajaxUrl_1,
            data        : queryString_regi_form,
            success: function(data){
                switch (data){
                    case "이미 등록된 회원입니다." : alert(data);  $('#save_regi').attr('disabled',true); break;
                    case "등록 가능한 회원입니다." : alert(data); $('#save_regi').attr('disabled',false); break;
                    case "등록이 완료되었습니다." : alert(data);  opener.parent.location.reload();
                        window.close(); break;
                    // case "수정하기 입니다." : alert(data);  opener.parent.location.reload();
                    //     window.close(); break;
                    default : alert(data); opener.parent.location.reload();
                        window.close(); break;
                }
            },
            error: function () {
                console.log('error');
            }
        });
        //    }
    }


    function data_sel(data,data2){
        console.log(1);
        let regi_name = document.getElementById('regi_name').setAttribute('value',data);
        let regi_id = document.getElementById('regi_id').setAttribute('value',data2);
        //regi_name.value = data;
        //regi_name.setAttribute('value',data);
        //regi_id.setAttribute('value',data2);
        //regi_id.value = data2;
        //console.log(regi_id.value);
        ajax_honor_sel('r','regi_form');
    }

    function close_pop(){
        console.log(33333);
        window.close();
        if(child != ''){
            child.close();
        }
    }


    function check_file(){
        //alert('들어오기는 하니?');
        if($('#honor_pic').val() != ''){
            $("input:checkbox[id='del_m_image']").prop("checked", true);
        }else{
            $("input:checkbox[id='del_m_image']").prop("checked", false);
        }
    }
</script>
