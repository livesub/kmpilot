<?php
include_once('./_common.php');

if(!$is_admin){
    alert('권한이 없습니다!');
    exit;
}
$idx = $_GET['idx'];
$u = $_GET['u'];

if($idx == '' && $u == ''){
    alert('잘못된 접근입니다.');
    exit;
}

$sql_common = " from CMS_MAGAZINE_NEW_TEST ";
$sql_main = '';
if(isset($idx) && $idx != ''){
    $sql_search = " where (1) and PARENTIDX = {$idx} order by IDX";
    $sql_main = " select * {$sql_common} where IDX = {$idx}";
}else{
    $sql_search = '';
}
$result = '';
if($idx != ''){
    $g5['title'] = '도선지 수정';
}else{
    $g5['title'] = '도선지 추가';
}

include_once('./admin.head.php');
if(isset($idx) && $idx !='') {
    $sql = " select * {$sql_common} {$sql_search}";
    $result = sql_query($sql);
    if (isset($sql_main) && $sql_main != '') {
        $result_main = sql_fetch($sql_main);
        if(!$result_main){
            alert('등록되지 않은 도선지입니다. 등록 후 이용해주세요');
            exit;
        }
    }
}

$sql_count = " select count(*) as cnt {$sql_common} {$sql_search}   ";
$row_count = sql_fetch($sql_count);
$total_count = 0;
if(isset($idx) && $idx != ''){
    $total_count = $row_count['cnt'];
}


$colspan = 16;
$m_title = '';
$m_author = '';
$section = '';
$s_write = '';
$m_image = '';
$c_num = '';
$m_idx = '';
?>
    <script src="../js/jquery.form.js"></script>
    <form name="fmagazinelist" id="fmagazinelist" onsubmit="return fmagazinelist_submit(this);"  method="post" enctype="multipart/form-data">
        <input type="hidden" name="html_total" id="html_total" value="<?=$total_count?>">
        <input type="hidden" name="token" value="">
        <input type='hidden' name='del_type_con' id='del_type_con' value=''>
        <input type='hidden' name='form_count' id='form_count' value=''>
        <?php if(isset($result_main) && $result_main != ''){
            $m_title = $result_main['M_TITLE'];
            $m_author = $result_main['M_AUTHOR'];
            $section = $result_main['SECTION'];
            $s_write = $result_main['S_YEAR']."-".$result_main['S_MONTH']."-".$result_main['S_DAY'];
            $m_image = $result_main['M_IMG'];
            $m_idx = $result_main['IDX'];
        }?>
        <input type="hidden" name="M_IDX" id="M_IDX" value="<?=$m_idx?>">
        <label for="M_TITLE">제목</label>
        <input type="text" name="M_TITLE" id="M_TITLE" value="<?=$m_title?>" required>
        <br>
        <label for="M_AUTHOR">저자</label>
        <input type="text" name="M_AUTHOR" id="M_AUTHOR" value="<?=$m_author?>" required>
        <br>
        <label for="SECTION">연호</label>
        <select id="SECTION" name="SECTION" required>
            <option value="창간호" <?php echo get_selected($section, "창간호"); ?>>창간호</option>
            <option value="신년호" <?php echo get_selected($section, "신년호"); ?>>신년호</option>
            <option value="봄호" <?php echo get_selected($section, "봄호"); ?>>봄호</option>
            <option value="여름호" <?php echo get_selected($section, "여름호"); ?>>여름호</option>
            <option value="가을·겨울호" <?php echo get_selected($section, "가을·겨울호"); ?>>가을·겨울호</option>
            <option value="특별호" <?php echo get_selected($section, "특별호"); ?>>특별호</option>
        </select>
        <br>
        <label for="S_write">발간일</label >
        <input type="date" name="S_write" id="S_write" value="<?=$s_write?>" required>
        <br>
        <label for="main_img"></label>
        <input type="file" name="main_title_img" id="main_title_img" onchange="check_file()" <?php echo $m_image != '' ? '': 'required' ?>>
        <?php
        $m_image_file = G5_DATA_PATH.'/magazine_test/'.$m_image;
        if (file_exists($m_image_file) && $m_image != '') {
            $m_image_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $m_image_file);
            $m_image_filemtile2 = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?'.filemtime($m_image_file) : '';
            echo '<img src="'.$m_image_url.$m_image_filemtile2.'" alt="" width="150" height="150>';
            echo '<label for="del_m_image">삭제</label><input type="checkbox" id="del_m_image" name="del_m_image" value="1">';
        }
        ?>
        <div class="tbl_head01 tbl_wrap">
            <table >
                <thead>
                <tr>
                    <th scope="col" id="mb_list_chk" rowspan="" >순서</th>
                    <th scope="col" id="mb_list_id" colspan="">그룹</a></th>
                    <th scope="col" id="mb_list_name">소제목</a></th>
                    <th scope="col" id="mb_list_auth">페이지수</th>
                    <th scope="col" id="mb_list_auth">파일첨부</th>
                    <th scope="col" id="mb_list_auth"><input type="button" id="add_content" value="행 추가"></th>
                </tr>
                </thead>
                <tbody id="con_tb">
                <?php
                for ($i=0; $row=sql_fetch_array($result); $i++) {
                    $bg = 'bg'.($i%2);
                    ?>

                    <tr class="<?php echo $bg; ?>" id="tr_<?=$i?>">
                        <td>
                            <?php echo $i+1 ?><input type="hidden" id="idx_<?=$i?>" name="idx_<?=$i?>" value="<?=$row['IDX']?>">
                        </td>
                        <td>
                            <?php echo get_doseonge_mean_value("doseonge_mean_".$i,1,16,$row['SCGCODE'],"required"); ?>
<!--                            <select name="doseonge_mean">-->
<!--                                <option value="인사말" --><?php //echo get_selected($row['SECTION'], "인사말"); ?><!-- >인사말</option>-->
<!--                                <option value="신년사" --><?php //echo get_selected($row['SECTION'], "신년사"); ?><!-- >신년사</option>-->
<!--                                <option value="권두언" --><?php //echo get_selected($row['SECTION'], "권두언"); ?><!-- >권두언</option>-->
<!--                                <option value="협회소식" --><?php //echo get_selected($row['SECTION'], "협회소식"); ?><!-- >협회소식</option>-->
<!--                                <option value="특별기획" --><?php //echo get_selected($row['SECTION'], "특별기획"); ?><!-- >특별기획</option>-->
<!--                                <option value="초대석" --><?php //echo get_selected($row['SECTION'], "초대석"); ?><!-- >초대석</option>-->
<!--                                <option value="인터뷰" --><?php //echo get_selected($row['SECTION'], "인터뷰"); ?><!-- >인터뷰</option>-->
<!--                                <option value="특집" --><?php //echo get_selected($row['SECTION'], "특집"); ?><!-- >특집</option>-->
<!--                                <option value="도선기고" --><?php //echo get_selected($row['SECTION'], "도선기고"); ?><!-- >도선기고</option>-->
<!--                                <option value="도선연구" --><?php //echo get_selected($row['SECTION'], "도선연구"); ?><!-- >도선연구</option>-->
<!--                                <option value="도선연단" --><?php //echo get_selected($row['SECTION'], "도선연단"); ?><!-- >도선연단</option>-->
<!--                                <option value="해외정보" --><?php //echo get_selected($row['SECTION'], "해외정보"); ?><!-- >해외정보</option>-->
<!--                                <option value="법률정보" --><?php //echo get_selected($row['SECTION'], "법률정보"); ?><!-- >법률정보</option>-->
<!--                                <option value="단체소식" --><?php //echo get_selected($row['SECTION'], "단체소식"); ?><!-- >단체소식</option>-->
<!--                                <option value="편집후기" --><?php //echo get_selected($row['SECTION'], "편집후기"); ?><!-- >편집후기</option>-->
<!--                                <option value="기타" --><?php //echo get_selected($row['SECTION'], "기타"); ?><!-- >기타</option>-->
<!--                            </select>-->
                        </td>
                        <td headers="mb_list_name"><input type="text" name="C_TITLE_<?=$i?>" id="C_TITLE_<?=$i?>" class="frm_input" value="<?php echo get_text($row['C_TITLE']);?>" required></td>
                        <td> <input type="number" value="<?= $row['C_PAGE']?>" name="C_PAGE_<?=$i?>" id="C_PAGE_<?=$i?>" required min="1"></td>
                        <td> <input type="file" name="FILENAME_ORG_<?=$i?>" id="FILENAME_ORG_<?=$i?>" <?php echo $row['FILENAME_ORG']? '' : 'required'; ?>><?=$row['FILENAME_ORG']?> </td>
                        <?php //echo ($row['FILENAME']) ? "<label for='del_c_image'>삭제</label><input type='checkbox' id='del_c_image_{$i}' name='del_c_image_{$i}' value='1'>":'' ;?>
                        <td><button type="button" id="del_content" name="del_content" value="<?=$i?>" onclick="remove_value_this(this.value)">삭제</button></td>
                    </tr>
                    <?php
                }
                if ($i == 0) {?>
                    <!-- echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>"; -->
                    <tr class='<?php echo $bg; ?>' id="tr_<?=$i?>">
                        <td>
                            <?=$total_count+1?>
                        </td>
                        <td>
                            <?php echo get_doseonge_mean_value('doseonge_mean_'.$i,1,16,'',"required"); ?>
                        </td>
                        <td><input type='text' name='C_TITLE_<?=$i?>' id='C_TITLE_<?=$i?>' class='frm_input' value='' required></td>
                        <td> <input type='number' placeholder='(3자리 까지 입력)' name="C_PAGE_<?=$i?>" id="C_PAGE_<?=$i?>" required min="1"></td>
                        <td> <input type='file' name="FILENAME_ORG_<?=$i?>" id="FILENAME_ORG_<?=$i?>" required> </td>
                        <td><button type="button" id="del_content" name="del_content" value="<?=$i?>" onclick="remove_value_this(this.value)">삭제</button></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="btn_fixed_top">
            <a href="./magazine_list.php" class="btn btn_02">목록</a>
            <?php if(isset($result_main) && $result_main != '') {?>
                <input type="submit" name="act_button" value="도선지삭제" onclick="document.pressed=this.value" class="btn btn_01">
                <input type="submit" name="act_button" value="수정" onclick="document.pressed=this.value" class="btn btn_01">
            <?php }else{?>
                <input type="submit" name="act_button" value="저장" onclick="document.pressed=this.value" class="btn btn_01">
            <?php }?>
        </div>


    </form>

<?php //echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

    <script>
        $count = parseInt($('#html_total').val());
        if(!$count){
            $count = 1;
        }
        for($m = 0; $m<$count; $m++){
            if($m == 0){
                $count_val = $m;
            }else{
                $count_val += ","+$m;
            }
        }
        console.log($count_val);
        console.log($count);
        let html = "";
        //행 추가 버튼에 관한 함수
        $(document).ready(function(){
            $("#add_content").click(function(){
                html = "<tr id='tr_"+$count+"'>";
                html += "<td>"+($count+1)+"</td>";
                html += "<td><select name='doseonge_mean_"+($count)+"' required>";
                html +="<option value='인사말' >인사말</option>";
                html += "<option value='신년사' >신년사</option>";
                html += "<option value='권두언' >권두언</option>";
                html +="<option value='협회소식' >협회소식</option>";
                html +="<option value='특별기획' >특별기획</option>";
                html +="<option value='초대석' >초대석</option>";
                html +="<option value='인터뷰' >인터뷰</option>";
                html +="<option value='특집' >특집</option>";
                html +="<option value='도선기고' >도선기고</option>";
                html +="<option value='도선연구' >도선연구</option>";
                html +="<option value='도선연단' >도선연단</option>";
                html +="<option value='해외정보' >해외정보</option>";
                html +="<option value='법률정보' >법률정보</option>";
                html +="<option value='단체소식' <>단체소식</option>";
                html +="<option value='편집후기' >편집후기</option>";
                html +="<option value='기타' >기타</option>";
                html +="</select></td>";
                html +="<td><input type='text' name='C_TITLE_"+$count+"' id='C_TITLE_"+$count+"' class='frm_input' value='' required></td>";
                html += "<td> <input type='number' placeholder='(3자리까지 입력)' name='C_PAGE_"+$count+"' id='C_PAGE_"+$count+"' required min='1'></td>";
                html += "<td> <input type='file' name='FILENAME_ORG_"+$count+"' id='FILENAME_ORG_"+$count+"' required> </td>";
                html += "<td><button type='button' id='del_content' name='del_content' value='"+$count+"' onclick='remove_value_this(this.value)'>삭제</button></td>";
                html += "</tr>"
                $("#con_tb").append(html);
                //console.log($('#S_write').val());
                if($count != 0){
                    $count_val += ","+$count;
                }else{
                    $count_val = $count;
                }
                console.log($count_val);
                $count++;
            });
        })
        //삭제버튼에 관한 함수
        function remove_value_this(value){
            //console.log('삭제 이벤트 들어옴'+value);
            if(!confirm('내용을 정말 삭제 하시겠습니까??')){
                return false;
            }
            let del_id_value = 'idx_'+value;
            let delete_value = $('#'+del_id_value).val();
            //console.log(delete_value);
            let content_del = "<input type='hidden' name='content_del' id='content_del' value='"+delete_value+"'>";

            let del_id = "tr_"+value;
            $("#"+del_id).remove();
            //console.log('요소안에 남은 개수'+$("tbody tr").length);
            if($("tbody tr").length == 0){
                $count = 0;
                $count_val = '';
            }
            if(value == 0 && $count_val != ''){
                $count_val = $count_val.replace(value+',','');
                //console.log($count_val);
            }else if(value != 0){
                $count_val = $count_val.replace(','+value,'');
                //console.log($count_val);
            }
            $("form").append(content_del);
            // let select = $("#"+del_id).attr('name');
            // // 'testC' 라고 alert에 표출.
            // console.log('그 태그의 네임 값 확인해보자'+select);

            //삭제 시에도 ajax 호출
            ajax_process();
        }


        function fmagazinelist_submit(f)
        {
            // let del_type = "<input type='hidden' name='del_type' id='del_type' value='"+document.pressed+"'>";
            console.log(document.pressed);
            if(document.pressed === "도선지삭제") {
                if(!confirm(document.pressed +"를 정말 하시겠습니까?")) {
                    return false;
                }
                //$("form").append(del_type);
                $('#del_type_con').val(document.pressed);

            }else if(document.pressed === "저장" || document.pressed === "수정") {
                if(!confirm("자료를 "+document.pressed+"하시겠습니까?")) {
                    return false;
                }
                //let input_hidden = "<input type='hidden' name='form_count' id='form_count' value='"+$count_val+"'>";
                //$("form").append(input_hidden);
                //$("form").append(del_type);
                $('#form_count').val($count_val);
                console.log($('#form_count').val());
                $('#del_type_con').val(document.pressed);
            }
            //return true;
            ajax_process(); //ajax 호출
        }

        function ajax_process(){
            let ajaxUrl_1 = "ajax_magazine_regi.php";
            //let queryString_magazine = $("form[name=fmagazinelist]").serialize() ;
            //let formData = new FormData($("form"));

            // var options = {
            //     type : "post",
            //     dataType : 'json', //JSON형태로 전달도 가능합니다.
            //     url: "ajax_magazine_regi.php",
            //     success: function(res){
            //         alert(res.msg); //res Object안에 msg에는 결과 메시지가 담겨있습니다.
            //     },
            //     error: function(res){
            //         alert("에러가 발생했습니다.")
            //     }
            // }
            //
            // $('#fmagazinelist').submit(function() { //submit이 발생하면
            //     $(this).ajaxSubmit(options); //옵션값대로 ajax비동기 동작을 시키고
            //     return false; //기본 동작인 submit의 동작을 막아 페이지 reload를 막는다.
            // });

            //ajax form submit
            //action 말고 밑에 url로 선언해주면 그 곳으로 리턴되지않는다.
            $('#fmagazinelist').ajaxSubmit({
                url : ajaxUrl_1,
                beforeSubmit: function (data,form,option) {
                    //validation체크
                    //막기위해서는 return false를 잡아주면됨
                    //alert('보내기 전 입니다.')
                    return true;
                },
                success: function(data){
                    //성공후 서버에서 받은 데이터 처리
                    if (trim(data) == "ok_mo") {
                        alert("수정 되었습니다.");
                        //location.replace("<?=G5_ADMIN_URL?>/magazine_form.php?idx=<?=$m_idx?>");
                        location.reload(true);
                    }else if(trim(data) == "ok_in"){
                        alert("등록 되었습니다.");
                        location.replace("<?=G5_ADMIN_URL?>/magazine_list.php");
                    }else if (trim(data) == "no") {
                        alert("문제가 발생 하였습니다. 개발자에게 문의 하세요.");
                        location.reload(true);
                    }else if(trim(data) == "ok_del"){
                        alert("삭제 되었습니다.");
                        location.replace("<?=G5_ADMIN_URL?>/magazine_list.php");
                    }else if(trim(data) == "ok_content_del"){
                        alert("삭제 되었습니다.");
                        //alert(data);
                    }else if(trim(data) == "no_content_del"){
                        alert("삭제 되었습니다.");
                        //location.reload(true);
                    }else{
                        alert("문제가 발생 하였습니다. 개발자에게 문의 하세요.");
                        //alert("data");
                    }
                },
                error: function(data){
                    //에러발생을 위한 code페이지
                    console.log('에러발생 : '+data);
                }
            });
            //$.ajax({
            //    type		: "POST",
            //    dataType    : "text",
            //    url			: ajaxUrl_1,
            //    data        : queryString_magazine,
            //    success: function(data){
            //        if (trim(data) == "ok") {
            //            alert("등록 및 수정 되었습니다.");
            //            location.reload(true);
            //        }else if (trim(data) == "no") {
            //            alert("문제가 발생 하였습니다. 개발자에게 문의 하세요.");
            //            location.reload(true);
            //        }else if(trim(data) == "ok_del"){
            //            alert("삭제 되었습니다.");
            //            location.replace("<?//=G5_ADMIN_URL?>///magazine_list.php");
            //        }else {
            //            //alert("삭제 되었습니다.");
            //            //location.replace("<?//=G5_ADMIN_URL?>///magazine_form.php?idx=<?//=$m_idx?>//");
            //            alert(data);
            //        }
            //        //alert(data);
            //    },
            //    error: function () {
            //        console.log('error');
            //    }
            //});
        }
        //input file에 값이 있을 경우 자동으로 체크박스 체크하는 함수
        function check_file(){
            //alert('들어오기는 하니?');
            if($('#main_title_img').val() != ''){
                $("input:checkbox[id='del_m_image']").prop("checked", true);
            }else{
                $("input:checkbox[id='del_m_image']").prop("checked", false);
            }
        }
    </script>

<?php
include_once ('./admin.tail.php');
