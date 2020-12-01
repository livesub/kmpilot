<?php
class pageSet {
	var $page; //현재 페이지
	var $perinfo; //페이지별 변수값 총체
	var $pageScale; //한페이지당 라인수
	var $block; //현재 블럭
	var $blockScale; //출력할 블럭의 갯수
	var $totalRecord; //총갯수
	var $totalPage;  //총 페이지수
	var $totalBlock; //총 블럭수
	var $first = 0;      //페이지당 출력할 게시물 범위의 처음
	var $last  = 1;      //페이지당 출력할 게시물 범위의 마지막
	var $isNext;	    //다음 페이지가 있는냐?
	var $tails = '';   //인자를 받는 문자열변수
	var $firstPage;   //첫페이지
	var $lastPage;   //마지막페이지
	var $linkname;
	
	function pageSet($totalpage,$page,$pageScale,$blockScale,$totalRecord,$arr='',$perinfo='') 
	{
		$this->linkname = $linkname;
		$this->page = !$page ? 1 : $page;
		$this->perinfo =$perinfo;
		$this->pageScale = $pageScale;
		$this->blockScale = $blockScale;
		$this->totalRecord = $totalRecord;
		$this->totalPage  = $totalpage;

		$this->totalBlock = ceil($this->totalPage/$this->blockScale);
		$this->block = ceil($this->page/$this->blockScale);
		$this->firstPage  = ($this->block-1)*$this->blockScale;
		$this->lastPage   = $this->block*$this->blockScale;
		if($this->totalBlock<=$this->block) $this->lastPage=$this->totalPage;
		
		if(is_array($arr)) {
			while(list($key,$val) = each($arr)) 
				$this->tails.="$key=$val&";
		}
		
	}
	
	function getPageList() {
		global $linkname;


		for($dPage=$this->firstPage+1; $dPage <= $this->lastPage; $dPage++) 
		{
			if($this->page == $dPage) 
			{
				$pShowPage .= "<b><font color='red'>$dPage</font></b>";
			}
			else 
			{
				if($this->perinfo)
				{
					$pShowPage .= "<a href='$PHP_SELF?$this->perinfo&page=$dPage&$this->tails'>$dPage</a>";
				}
				else
				{

					$pShowPage .= "<a href='$PHP_SELF?page=$dPage&$this->tails'>$dPage </a>";
				}
			}
			
			if($this->lastPage != $dPage) $pShowPage .= "<img src='$skin_dir/images/board_bu/bar.gif' width='2' height='17' hspace='5' align='absmiddle'>";
		}
		
		return $pShowPage;
	}

	function pre10($img){
		global $linkname;

		$firstPage = $this->firstPage - 9;
		//이전페이지 블럭..
		if($this->block > 1) {
			$pShowPage = "<a href='$PHP_SELF?$this->perinfo&page=$firstPage&$this->tails'>$img</a>";
		} else {
			//$pShowPage = "[이전 $this->blockScale]&nbsp;";
			$pShowPage = $img;
		}
		return $pShowPage;
	}

	function next10($img){
		global $linkname;

		//다음 페이지 블럭
		if($this->block < $this->totalBlock) {
			$mPage = $this->lastPage + 1;
			//$pShowPage .= "&nbsp;<a href='$PHP_SELF?$this->perinfo&page=$mPage&$this->tails'>[다음 $this->blockScale]</a>";
			$pShowPage .= "&nbsp;<a href='$PHP_SELF?$this->perinfo&page=$mPage&$this->tails'>$img</a>";
		} else {
			//$pShowPage .= "&nbsp;[다음 $this->blockScale]";
			$pShowPage .= $img;
		}
		return $pShowPage;
	}


	function preFirst($img){
		global $linkname;

		$firstPage = 1;
		//첫페이지 블럭..
		if($this->block > 1) {
			$pShowPage = "<a href='$PHP_SELF?$this->perinfo&page=$firstPage&$this->tails'>$img</a>";
		} else {
			//$pShowPage = "[이전 $this->blockScale]&nbsp;";
			$pShowPage = $img;
		}
		return $pShowPage;
	}

	function nextLast($img){
		global $linkname;

		//맨 마지막 페이지 블럭
		if($this->block < $this->totalBlock) {
			$mPage = $this->totalPage;
			//$pShowPage .= "&nbsp;<a href='$PHP_SELF?$this->perinfo&page=$mPage&$this->tails'>[다음 $this->blockScale]</a>";
			$pShowPage .= "&nbsp;<a href='$PHP_SELF?$this->perinfo&page=$mPage&$this->tails'>$img</a>";
		} else {
			//$pShowPage .= "&nbsp;[다음 $this->blockScale]";
			$pShowPage .= $img;
		}
		return $pShowPage;
	}

	function getPrevPage($text) 
	{		
		global $linkname;
		
		if($this->page > 1) 
		{
			$ppage = $this->page - 1;
			if($this->perinfo)
			{
			 $pShowPage .= "<a href='$PHP_SELF?$this->perinfo&page=$ppage&$this->tails'>$text</a>";
			}
			else
			{
			 $pShowPage .= "<a href='$PHP_SELF?page=$ppage&$this->tails'>$text</a>";
			}
		} 
		else 
		{
			$pShowPage .= "$text";
		}
		return $pShowPage;
	}

	function getNextPage($text) 
	{		global $linkname;
		if($this->page >= 1 && $this->page < $this->totalPage) 
		{
			$npage = $this->page + 1;
			if($this->perinfo){
			$pShowPage .= "<a href='$PHP_SELF?$this->perinfo&page=$npage&$this->tails'>$text</a>";
			}else{

			$pShowPage .= "<a href='$PHP_SELF?page=$npage&$this->tails'>$text</a>";
			}
		} else {
			$pShowPage .= "$text";
		}
		return $pShowPage;
	}
	
}	
?>
