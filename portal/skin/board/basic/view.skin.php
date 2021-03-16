<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
        <div class="board detail-content">
            <div class="title">
                    <div>
<?php
            echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력
?>
                    </div>
                    <div>
                        <div><?=$view['name'] ?></div>
                        <div><?=date("Y.m.d H:i", strtotime($view['wr_datetime'])) ?></div>
                    </div>
                </div>

                <div class="content">
                <?=get_view_thumbnail($view['content']); ?>
                </div>

<?php
    $cnt = 0;
    if ($view['file']['count']) {
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                $cnt++;
        }
    }

    if($cnt) {
?>
                <div class="attached list">
<?php
        // 가변 파일
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source']) {
?>
                    <div class="pdf"><a href="<?=$view['file'][$i]['href'];?>"><?php echo $view['file'][$i]['source'] ?></a></div>
<?php
            }
        }
?>
                </div>
<?php
    }
?>

<?php
    if($board['bo_10_subj'] == 1){
        include_once(G5_BBS_PATH.'/view_comment.php');
    }
?>

                <div class="btns">
                    <button class="btn btn-white" onclick="list_page();">목록</button>
<?php
    if ($update_href) {
?>
<li><a href="<?php echo $update_href ?>">수정1111111<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></li>
<?php
    }
?>


                    <button class="btn btn-white" onclick="openPwdCheckModal(modifyBoard, 1)"><a href="<?php echo $update_href ?>">수정</a></button>
                    <button class="btn btn-white" onclick="openPwdCheckModal(removeBoard, 1)">삭제</button>
                </div>


                <div class="histories list">
                    <div class="prev"><a href="#">도선사 사업자 선정 재공고</a></div>
                    <div class="next"><a href="#">도선사 협회 공지사항</a></div>
                </div>
            </div>


            <script>
                function list_page(){
                    location.href = "<?=$list_href ?>";
                }
            </script>

            <script>
                let doCallback = null;
                let board_id = null;
                function openPwdCheckModal(callback, id){
                    doCallback = callback;
                    board_id = id;
                    document.querySelector('body').classList.add('modal-open');
                    document.querySelector('.modal.pwdCheck').classList.add('in');
                }
                function closeBoardModal() {
                    document.querySelector('body').classList.remove('modal-open');
                    document.querySelector('.modal.pwdCheck').classList.remove('in');
                }
                function checkPassword() {
                    closeBoardModal();

                    //if the password correct
                    if(doCallback != null && board_id != null) doCallback(board_id);
                    //or not
                    return;
                }
                function modifyBoard(id) {
                    location.href = "./board_write.html?board_id="+id;
                }
                function removeBoard(id) {
                    //removeBoard
                    alert("remove this board "+id);
                }
            </script>
            <div class="pwdCheck modal fade">
                <div class="modal-background" onclick="closeBoardModal()"></div>
                <div class="modal-dialog">
                    <div class="modal-dialog-title">비밀번호 확인<div class="btn-close" onclick="closeBoardModal()"></div></div>
                    <div class="modal-dialog-description">본 게시물의 비밀번호를 확인합니다.<br><span class="urgent">* 글 작성시 사용한 패스워드를 입력해 주세요</span></div>
                    <div class="modal-dialog-contents">
                        <div>
                            <input type="password" placeholder="패스워드">
                        </div>
                    </div>
                    <div class="modal-dialog-footer">
                        <button class="btn normal" onclick="checkPassword()">확인</button>
                    </div>
                </div>
            </div>
        </div>