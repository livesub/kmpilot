<?php
include_once('./_common.php');


$page_type = $_POST['page_type'];
$page = $_POST['page'];
$total_page = $_POST['total_page'];
$bo_table = $_POST['bo_table'];
$qstr = $_POST['qstr'];

//모바일 5개 PC 10개 뿌리기
if(is_mobile() || $page_type == "small_size") $write_pages = get_paging_front(5, $page, $total_page, get_pretty_url($bo_table, '', $qstr.'&amp;page='));
else if($page_type == "large_size") $write_pages = get_paging_front(10, $page, $total_page, get_pretty_url($bo_table, '', $qstr.'&amp;page='));
//$write_pages = get_paging_front(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, get_pretty_url($bo_table, '', $qstr.'&amp;page='));

$list_href = '';
$prev_part_href = '';
$next_part_href = '';
if ($is_search_bbs) {
    $list_href = get_pretty_url($bo_table);

    $patterns = array('#&amp;page=[0-9]*#', '#&amp;spt=[0-9\-]*#');

    //if ($prev_spt >= $min_spt)
    $prev_spt = $spt - $config['cf_search_part'];
    if (isset($min_spt) && $prev_spt >= $min_spt) {
        $qstr1 = preg_replace($patterns, '', $qstr);
        $prev_part_href = get_pretty_url($bo_table,0,$qstr1.'&amp;spt='.$prev_spt.'&amp;page=1');
        $write_pages = page_insertbefore($write_pages, '<a href="'.$prev_part_href.'" class="pg_page pg_prev">이전검색</a>');
    }

    $next_spt = $spt + $config['cf_search_part'];
    if ($next_spt < 0) {
        $qstr1 = preg_replace($patterns, '', $qstr);
        $next_part_href = get_pretty_url($bo_table,0,$qstr1.'&amp;spt='.$next_spt.'&amp;page=1');
        $write_pages = page_insertafter($write_pages, '<a href="'.$next_part_href.'" class="pg_page pg_end">다음검색</a>');
    }
}

echo $write_pages;
?>

