<?php

include_once ("./_common.php");
//alert('들어왔나?');
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
<form name="honor_form" id="honor_form" enctype="multipart/form-data" method="post" action="">
    <table>
        <tr>
            <th>이름(한글)</th>
            <td><input type="text" id="H_USER_NAME" name="H_USER_NAME" value="<?=$result_honor['H_USER_NAME']?>"></td>
        </tr>
        <tr>
            <th>사진</th>
            <td><input type="file"></td>
            <?php if(isset($result_honor['H_USER_PHOTO']) && $result_honor['H_USER_PHOTO'] != ''){
                $icon_file2 = G5_DATA_PATH.'/honor_member/'.get_mb_icon_name($result_honor['H_USER_PHOTO']);
                if (file_exists($icon_file2)) {
                    $icon_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $icon_file2);
                    $icon_filemtile2 = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?'.filemtime($icon_file2) : '';
                    echo '<img src="'.$icon_url.$icon_filemtile2.'" alt="">';
                    echo '<input type="checkbox" id="del_mb_license" name="del_mb_license" value="1">삭제';
                }
                ?>
            <?php }else{?>

            <?php }?>
        </tr>
        <tr>
            <th>생년</th>
            <td><input type="text" id="H_USER_BIRTH" name="H_USER_BIRTH" value="<?=$result_honor['H_USER_BIRTH']?>">ex) 1996</td>
        </tr>
        <tr>
            <th>퇴직</th>
            <td><input type="text" id="H_RETIRE_DATE" name="H_RETIRE_DATE" value="<?=$result_honor['H_RETIRE_DATE']?>">ex) 1996</td>
        </tr>
        <tr>
            <th>소속</th>
            <td><input type="text" id="H_USER_GROUP_KEY" name="H_USER_GROUP_KEY" value="<?=get_doseongu_name($result_honor['H_USER_GROUP_KEY'])?>"></td>
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
    <label for="regi_user">
        <input type="radio" name="regi_user" id="regi_user" onclick="checked_div()">
        사용자 지정
    </label>
    <label>
        <input type="radio" name="regi_user" id="new_user" checked onclick="checked_div()">
        신규등록
    </label>
    <div style="display: none" id="regi_div">
        <form>
            <table>
                <tr>
                    <th>이름(한글)</th>
                    <td><input type="text" id="regi_name" name="regi_name"><button type="button" onclick="open_popup('',500,500,'honor_member_search.php','honor_member_search')">사용자 찾기</button></td>
                </tr>
                <tr>
                    <td><button type="submit">저장하기</button><button type="button" onclick="window.close();">취소</button></td>
                </tr>
            </table>
        </form>
    </div>
    <div id="new_div">
        <form>
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
                    <td><input type="text" id="new_birth_year" name="new_birth_year"></td>
                </tr>
                <tr>
                    <th>퇴직년</th>
                    <td><input type="text" id="new_retire_year" name="new_retire_year"></td>
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
<?php }?>
<script>
    window.onload = checked_div();
    // $(document).ready(function(){ // 태그 등의 셋팅이 완료되었을 시점에 이벤트 발생
    //     checked_div();
    // });
    function checked_div(){
        let new_div = document.getElementById('new_div');
        let regi_div = document.getElementById('regi_div');
        if(document.querySelector('input[id="regi_user"]').checked){
            //console.log('사용자 지정 체크');
            new_div.style.display = 'none';
            regi_div.style.display = 'block';
        }else if(document.querySelector('input[id="new_user"]').checked){
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

        window.open(action, target, 'status=no, height='+height+', width='+width+', ' +
            'left='+ popupX + ', top='+ popupY + ', screenX='+ popupX + ', screenY= '+ popupY);
        //pop.submit();
    }
</script>
