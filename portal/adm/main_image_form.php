<?php
$sub_menu = "100050";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'w');

$w = $_GET['w'];
$idx = $_GET['idx'];

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';
    $html_title = ' 추가';
}
else if ($w == 'u')
{
    $data = sql_fetch(" select * from {$g5['main_image_table']} where idx = '{$idx}' ");

    $html_title = ' 수정';
}

$g5['title'] = '메인 페이지 이미지 설정'.$html_title;
include_once('./admin.head.php');
?>


<form name="fimage" id="fimage" action="./main_image_form_update.php" onsubmit="return fimage_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="idx" value="<?php echo $data['idx'] ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>


    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="subject">제목<?php echo $sound_only ?></label></th>
        <td colspan="3">
            <input type="text" name="subject" value="<?=htmlspecialchars(stripslashes($data['subject'])) ?>" id="subject" required class="frm_input required" size="50"  maxlength="250">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="turn">출력순서<?php echo $sound_only ?></label></th>
        <td><input type="turn" name="turn" id="turn" value="<?=$data['turn']?>" required class="frm_input required alnum_"  size="5" maxlength="2"  onkeydown="this.value=this.value.replace(/[^0-9]/g,'')" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" onblur="this.value=this.value.replace(/[^0-9]/g,'')"></td>

        <th scope="row"><label for="subject">협회/교육 선택<?php echo $sound_only ?></label></th>
        <td>
            <select name="type" id="type">
                <option value="A" <?php if($data['type'] == "" || $data['type'] == "A") echo "selected";?>>협회</option>
                <option value="E" <?php if($data['type'] == "E") echo "selected";?>>교육센터</option>
            </select>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="image_name">이미지 첨부<?php echo $sound_only ?></label></th>
        <td colspan="3">
            <?php echo help('이미지 크기는 <strong>넓이 '.G5_MAIN_IMG_WIDTH.'픽셀 높이 '.G5_MAIN_IMG_HEIGHT.'픽셀</strong>로 해주세요.') ?>
            <input type="file" name="image_name" id="image_name">
        </td>
    </tr>
<?php
    if($data['image_name'] != ""){
        $image_path = G5_DATA_URL."/main_image/{$data['image_name']}'";
?>
    <tr>
    </tr>
    <tr>
        <td colspan="4">
            <img src='<?=$image_path?>' width='<?=G5_MAIN_IMG_WIDTH?>' height='<?=G5_MAIN_IMG_HEIGHT?>'>
        </td>
    </tr>
<?php
    }
?>

    </tbody>
    </table>
</div>
<div class="btn_fixed_top">
    <a href="./main_image_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>
    function fimage_submit(f)
    {
        if (!f.image_name.value.match(/\.(gif|jpe?g|png)$/i) && f.image_name.value) {
            alert('이미지는 이미지 파일만 가능합니다.');
            return false;
        }

        return true;
    }
</script>

<?php
include_once ('./admin.tail.php');