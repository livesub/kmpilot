<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가;
//최고 관리자 한명 더 추가
if ($member['mb_id'] == 'yongsanzip') $is_admin = 'super';
if ($member['mb_level'] == 9) $is_admin = 'super';