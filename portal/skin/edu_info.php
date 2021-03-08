<?php
$g5['title'] = "교육안내";
$get_edu = $_GET['edu_mean'];
?>
<style>
    #CR{
        display: none;
    }
    #CE{
        display: none;
    }
    #CC{
        display: none;
    }
</style>

<label for="edu_mean">교육 안내</label>
<select id="edu_mean" name="edu_mean" onchange="move_edu()">
    <option>교육 종류</option>
    <option value="CR" <?php echo get_selected($get_edu, "CR")?> >도선사 면허갱신</option>
    <option value="CE" <?php echo get_selected($get_edu, "CE")?> >도선사 보수</option>
    <option VALUE="CC"<?php echo get_selected($get_edu, "CC")?> >필수 도선사 교육</option>
</select>
<table id="CR">
    <th>면허갱신</th>
    <td>면허갱신 1</td>

</table>
<table id="CE">
    <th>면허보수</th>
    <td>면허보수 1</td>
</table>
<table id="CC">
    <th>필수 도선사</th>
    <td>필수 도선사 1</td>
</table>

<script>
    function move_edu() {
        $('table').hide();
        let value = $('#edu_mean').val();
        if(value != ""){
        //console.log(value);
        let tag = $('#' + value);
        tag.show();
        }
    }
</script>