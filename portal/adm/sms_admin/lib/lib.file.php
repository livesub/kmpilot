<?php
//if (!is_object($GLOBALS['bye'])) { print "bye~"; exit; }

// 파일명을 고유명으로변경한다.
function setFileKey($file_name){
	return md5(uniqid($file_name)).".".getFileExt($file_name);
}

function fetch_url($url){
	$url_parsed = parse_url($url);
	$host = $url_parsed["host"];
	$port = $url_parsed["port"];
	if($port==0) $port = 80;
	$path = $url_parsed["path"];

	if(empty($path)) $path = "/";
	if(empty($host)) return false;

	if($url_parsed["query"] != "") $path .= "?".$url_parsed["query"];
	$out = "GET ".$path." HTTP/1.0\r\nHost: ".$host."\r\n\r\n";
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	usleep(50);
	if($fp){
		socket_set_timeout($fp, 30);
		fwrite($fp, $out);
		$body = false;
		while(!feof($fp)){
			$buffer = fgets($fp, 1024);
			if($body) $content .= $buffer;
			if($buffer=="\r\n")    $body = true;
		}
		fclose($fp);
	}else{
		return false;
	}
	return $content;
}

function getFileSave($fileUrl,$saveFile,$saveDir){
	GLOBAL $lib, $gd_width;

	$image = fetch_url($fileUrl);
	$f = fopen( $saveDir . "/" . $saveFile,"w+");
	$lock=flock($f,2);
	if($lock){
		fwrite($f,$image);
	}
	//@chmod($saveDir."/".$saveFile, 0666 );
	//flock($f,3);
	//print $image;
	fclose($f);
}

function filesize_human($byte, $sosu=2, $zero_string=NULL){
	if ($byte == 0 && $zero_string != NULL){
		return $zero_string;
	}else if ($byte < 1048576){
		$h1 = $byte / 1024;
		$h2 = " KB";
	}else if ($byte < 1073741824){
		$h1 = $byte / 1048576;
		$h2 = " MB";
	}else{
		$h1 = $byte / 1073741824;
		$h2 = " GB";
	}	// if()

	return number_format(ceil($h1*100)/100, $sosu).$h2;
}	// function()

function filesize2num($num,$unit){
	if ($unit=="K"){ $rnum = $num *1024; }
	else if ($unit=="M"){ $rnum = $num *1024*1024; }
	else $rnum = $num;
	$rnum = (int)$rnum;
	return $rnum;
}

function byteunit ($byte, $soft, $kcut) {
	// soft = 1 : 1000 단위 허용
	// kcut = Kbyte 단위 소수점 이하 생략
	if ($soft && !($byte%1000)) { $unit = 1000; }
	else { $unit = 1024; }

	if ($byte >= ($unit*$unit*$unit)) {
		$result = sprintf("%.1f", $byte / ($unit*$unit*$unit)) . " GB";
	}
	elseif ($byte >= ($unit*$unit)) {
		$result = sprintf("%.1f", $byte / ($unit*$unit)) . " MB";
		if ($kcut) { $result = ereg_replace("\.[0-9]+", "", $result); }
	}
	elseif ($byte >= ($unit)) {
		$result = sprintf("%.1f", $byte / ($unit)) . " KB";
		if ($kcut) { $result = ereg_replace("\.[0-9]+", "", $result); }
	}
	else { $result = $byte; }
	$result = eregi_replace("\.00([A-Z]+)", "\\1", $result);
	return $result;
}

// 지정된 디렉토리의 파일 정보를 구함
function getdirinfo($path) {
	$handle=@opendir($path);
	while($info = readdir($handle)) {
		if($info != "." && $info != "..") {
			$dir[] = $info;
		}
	}
	closedir($handle);
	return $dir;
}

// 파일을 삭제하는 함수
function z_unlink($filename) {
	@chmod($filename,0777);
	$handle = @unlink($filename);
	if(@file_exists($filename)) {
		@chmod($filename,0775);
		$handle=@unlink($filename);
	}
	return $handle;
}

// 지정된 파일의 내용을 읽어옴
function zReadFile($filename) {
	if(!file_exists($filename)) return '';

	$f = fopen($filename,"r");
	$str = fread($f, filesize($filename));
	fclose($f);

	return $str;
}

// 지정된 파일에 주어진 데이타를 씀
function zWriteFile($filename, $str) {
	$f = fopen($filename,"w");
	$lock=flock($f,2);
	if($lock) {
		fwrite($f,$str);
	}
	flock($f,3);
	fclose($f);
}

// 지정된 파일이 Locking중인지 검사
function check_fileislocked($filename) {
	$f=@fopen($filename,w);
	$count = 0;
	$break = true;
	while(!@flock($f,2)) {
		$count++;
		if($count>10) {
			$break = false;
			break;
		}
	}
	if($break!=false) @flock($f,3);
	@fclose($f);
}

// 순환적으로 디렉토리를 삭제
function zRmDir($path){
	/*
	$directory = dir($path);

	while($entry = $directory->read()) {
		if ($entry != "." && $entry != "..") {
			if (Is_Dir($path."/".$entry)) {
				zRmDir($path."/".$entry);
			} else {
				@UnLink ($path."/".$entry);
			}
		}
	}
	$directory->close();
	@RmDir($path);
	*/
}

// 순환적으로 디렉토리를 삭제
function zRmDirTest($path) {
	$directory = dir($path);
	while($entry = $directory->read()) {
		if ($entry != "." && $entry != "..") {
			if (is_dir($path."/".$entry)) {
				zRmDirTest($path."/".$entry);
			} else {
				echo "unlink ".$path."/".$entry."<br/>";
			}
		}
	}
	$directory->close();
	echo "RmDir " . $path."<br/>";
}

/*
	디렉토리를 생성
*/
function zMkDir($dir){
	$d = explode('/',$dir);
	$p = '';
	for($i=0,$m=count($d);$i<$m;$i++){
		if($p==''){$p=$d[$i];}
		else{$p.='/'.$d[$i];}
		if(!is_dir($p))	{
			@mkdir($p,0777);
			@chmod($p,0777);
		}
	}
	return true;
}

//파일 업로드
function upload($file,$file_name,$up_dir,$chmod=""){
	//디렉토리생성

	If (!is_dir($up_dir)) {
		return -1;
	}
	if ($file[size]<=0){return -1;}

	//파일 업로드
	if (!copy($file["tmp_name"], $up_dir."/".$file_name)) {
		return false;
		exit;
	}
	//임시파일 삭제
	@unlink($file["tmp_name"]);
	if($chmod) @chmod($up_dir."/".$file_name, $chmod);

	return $file_name;
}

function getFileInfo($file_path){
	$fileInfo = pathinfo($file_name);
	return $fileInfo;
}
function getFileExt($file_name){
	$fileInfo = pathinfo($file_name);
	return strtolower($fileInfo["extension"]);
}

function getFileExtIcon($file_name,$mode=""){
	global $SITE;
	$fileInfo = pathinfo($file_name);
	$fileExt = strtolower($fileInfo["extension"]);
	$fileIconExtArr = array(
		"jpg"=>"jpg","jpeg"=>"jpg","jpe"=>"jpeg",
		"gif"=>"gif","bmp"=>"bmp",
		"alz"=>"alz","doc"=>"doc","docx"=>"docx","exe"=>"exe",
		"psd"=>"psd"
	);
	$ficonName = "";
	if(file_exists($SITE['path']['web_root'].$SITE["global"]["img"]."/ficon/".$fileExt.".gif")){
		$ficonName = $fileExt;
	}else if($fileIconExtArr["{$fileExt}"]!="") {
		$ficonName = $fileIconExtArr["{$fileExt}"];
	}else{
		$ficonName="default";
	}

	if($ficonName){
		if ($mode!=""){
			$ficon=$ficonName;
		}else{
		$ficon = "<img src='".$SITE['global']['img']."/ficon/".$ficonName.".gif' alt='".$fileExt." 파일'/>";
		}
	}
	return $ficon;
	//
}

//업로드 파일 배열 재구성
function reArrayFiles($file_post,$file_content=null) {
	$file_ary = array();
	$file_count = count($file_post['name']);
	if ($file_count>0){
		$file_keys = array_keys($file_post);

		foreach($file_post["name"] as $_idx=> $_val){
			foreach ($file_keys as $key) {
				$file_ary[$_idx][$key] = $file_post[$key][$_idx];
				if($file_content[$_idx]!="") $file_ary[$_idx]["FILE_CONTENT"] = $file_content[$_idx];
			}
		}
	}
	return $file_ary;
}
function &checkExe($_FILENAME,$EXT_ARR){
	$EXT_LIST = implode("|",$EXT_ARR);
	if (preg_match("/\.($EXT_LIST)/i",$_FILENAME)){
		return true;
	}
	else return false;
}

function fileCheck($UP_FILE, $ABLE_ARR){
	global $CFG;
	$err_msg ="";
	for ($fi = 0; $fi < count($UP_FILE);$fi++){
		unset($tmpfile);
		$tmpfile = $UP_FILE[$fi];

		//삭제 체크
		if ($tmpfile["name"]!=""){
			if ($tmpfile["error"]==1) $err_msg .= $tmpfile["name"]." 파일이 서버설정 업로드 가능용량을 초과하였습니다.\\n";
			elseif ($tmpfile["error"]!=0)	$err_msg .= $tmpfile["name"]." 파일이 정상적으로 업로드되지 않았습니다.\\n";

			//파일 확장자 체크(업로드가능한 파일만 등록)
			if (count($ABLE_ARR["FILE_EXT"])>0){
				$chk_exe = checkExe($tmpfile["name"],$ABLE_ARR["FILE_EXT"]);

				if(!$chk_exe){
					$err_msg .= $tmpfile["name"]." 파일은 업로드 가능한 파일이 아닙니다.1";
				}
			}
			if($chk_exe){
				//기본 확장자 체크
				if($CFG["allow_ext"]){
						$allow_ext_arr = explode(";",$CFG["allow_ext"]);
					$allow_chk = checkExe($tmpfile["name"],$allow_ext_arr);
					if(!$allow_chk) { $err_msg .= $tmpfile["name"]." 파일은 업로드 가능한 파일이 아닙니다.";}
				}
				if($CFG["deny_ext"]){
					$deny_ext_arr = explode(";",$CFG["deny_ext"]);
					$deny_chk = checkExe($tmpfile["name"],$deny_ext_arr);
					if($deny_chk) { $err_msg .= $tmpfile["name"]." 파일은 업로드 가능한 파일이 아닙니다.2";}
				}
			}

			//파일 개별 용량 체크
			if($ABLE_ARR["FILE_SIZE"] >0 && $tmpfile["size"] > $ABLE_ARR["FILE_SIZE"]){
				$err_msg .= $tmpfile["name"]." 최대 업로드 용량을 초과하였습니다.\\n최대 업로드 용량 : ".$ABLE_ARR["FILE_SIZE"]."Byte\\n";
			}
			$total_size += $tmpfile["size"] ;

		}
	}

	if($ABLE_ARR["FILE_TOTAL_SIZE"]>0 && $total_size>$ABLE_ARR["FILE_TOTAL_SIZE"]) {
		$err_msg .= " 최대 업로드 용량을 초과하였습니다.\\n최대 업로드 가능 용량 : ".$ABLE_ARR["FILE_TOTAL_SIZE"]."Byte\\n";
	}
	return $err_msg ;
}

//파일 업로드 & DB 저장
function saveUpFiles($FILE,$R_DATA=null,$MODE="insert",$gubun="data"){
	global $DB,$SITE,$Board;

	$_Tables_ = $R_DATA["TABLES"];
	$_UP_PATH_ = $R_DATA["UPLOAD_PATH"];

	$fileExt = getFileExt($FILE["name"]);	//파일 확장자
	$file_name = md5(mktime().$FILE["name"]).".".$fileExt;

	//echo $file_name.":".$_UP_PATH_;
	$save_file_name = upload($FILE,$file_name,$_UP_PATH_);

	if($save_file_name){
		//if (preg_match("/\.(jp[e]?g|gif|png)$/i", $_UP_PATH_."/".$save_file_name))        {
			$img_size = @getimagesize($_UP_PATH_."/".$save_file_name);
		//}

		//동영상파일일경우 심볼릭 링크 경로 지정
		$sURL_name = "";
		if(preg_match("/(wmv|avi|mpg|mpeg|asf|wmf)/i",$fileExt)){
			$sURL_name= mktime()."_".md5($FILE["name"]).".".$fileExt;
			@exec("ln -s ".$_UP_PATH_."/".$save_file_name." ".$SITE['path']['upload']."/movie/".$sURL_name);//동영상파일일경우
		}

		$F_DATA["SURL"] = $sURL_name;
		if($R_DATA["CLUB_CODE"]){
			$F_DATA["CLUB_CODE"] = $R_DATA["CLUB_CODE"];						//업로드 구분 키
		}
		$F_DATA["BOARD_CODE"] = $R_DATA["BOARD_CODE"];						//업로드 구분 키
		$F_DATA["PARENTIDX"] = $R_DATA["PARENTIDX"];		//업로드 구분 키- 인덱스
		$F_DATA["NUM"]		= $R_DATA["NUM"];		//업로드 구분 키- 인덱스

		$F_DATA["FILENAME_ORG"] = $FILE["name"];						//원본 파일명
		$F_DATA["FILENAME"] = $save_file_name;							//저장된 파일 이름
		$F_DATA["FILESIZE"] = $FILE["size"];									//파일용량
		$F_DATA["FILETYPE"] = $FILE["type"];								//저장된 경로(절대경로)
		$F_DATA["FILEPATH"] = getDirPath($R_DATA["UPLOAD_PATH"]);				//저장된 경로(절대경로)

		$F_DATA["FILE_CONTENT"] = $FILE["FILE_CONTENT"];			//파일 설명
		$F_DATA["IMG_W"] = $img_size[0];								//이미지가로
		$F_DATA["IMG_H"] = $img_size[1];								//이미지세로
		$F_DATA["IMG_TYPE"] = $img_size[2];							//이미지타입
		// (1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order, 9 = JPC, 10 = JP2, 11 = JPX, 12= JB2, 13 = SWC, 14 = IFF)

		$F_DATA["REG_DATE"] = mktime();				//업로드 날짜
		$F_DATA["REG_IP"] = $_SERVER["REMOTE_ADDR"];	//업로드 된 IP
		$F_DATA["REG_USER_ID"] = $R_DATA["REG_USER_ID"];

		$F_DATA["GUBUN"] = $gubun;

		if ($MODE=="update"){
			$qry = $DB->updateQuery($_Tables_,$F_DATA," IDX='".$R_DATA["IDX"]."'");
		}else{
			$F_DATA["IDX"] = newDataIdx($_Tables_,"IDX");
			$qry = $DB->insertQuery($_Tables_,$F_DATA);
		}
		if ($qry) $result =$DB->dbQuery($qry);
	}
	return $result;
}


//임시파일 파일 업로드 & DB 저장
function saveUpTmpFiles($FILE,$R_DATA=null,$MODE="insert",$gubun="data"){
	global $DB,$SITE,$Board;

	$_Tables_ = $R_DATA["_TABLES_"];
	$_UP_PATH_ = $R_DATA["UPLOAD_PATH"];

	//임시 저장된 파일 이름 그대로 사용함
	if(file_exists($FILE)){
		@copy($FILE,$_UP_PATH_."/".$R_DATA["FILENAME"]);

		//동영상파일일경우 심볼릭 링크 경로 지정
		$fileExt = getFileExt($R_DATA["FILENAME"]);	//파일 확장자
		$sURL_name="";
		if($fileExt =="avi" ){
			$sURL_name= mktime()."_".md5($R_DATA["FILENAME_ORG"]).".".$fileExt;
			@exec("ln -s ".$_UP_PATH_."/".$R_DATA["FILENAME"]." ".$SITE['path']['upload']."/movie/".$sURL_name);//동영상파일일경우
		}
		if($sURL_name){ $F_DATA["SURL"] = $sURL_name; }

		$F_DATA["BOARD_CODE"] = $R_DATA["BOARD_CODE"];						//업로드 구분 키
		$F_DATA["PARENTIDX"] = $R_DATA["PARENTIDX"];		//업로드 구분 키- 인덱스
		$F_DATA["NUM"] = $R_DATA["NUM"];		//업로드 구분 키- 인덱스

		$F_DATA["FILENAME_ORG"] = $R_DATA["FILENAME_ORG"];						//원본 파일명
		$F_DATA["FILENAME"] = $R_DATA["FILENAME"];							//저장된 파일 이름
		$F_DATA["FILESIZE"] = $R_DATA["FILESIZE"];									//파일용량
		$F_DATA["FILETYPE"] = $R_DATA["FILETYPE"];								//저장된 경로(절대경로)
		$F_DATA["FILEPATH"] = getDirPath($R_DATA["UPLOAD_PATH"]);				//저장된 경로(절대경로)

		$F_DATA["FILE_CONTENT"] = $R_DATA["FILE_CONTENT"]?$R_DATA["FILE_CONTENT"]:$R_DATA["FILENAME_ORG"];			//파일 설명
		$F_DATA["IMG_W"] = $R_DATA["IMG_W"];								//이미지가로
		$F_DATA["IMG_H"] = $R_DATA["IMG_H"];								//이미지세로
		$F_DATA["IMG_TYPE"] =$R_DATA["IMG_TYPE"];							//이미지타입

		$F_DATA["REG_DATE"] = mktime();				//업로드 날짜
		$F_DATA["REG_IP"] = $_SERVER["REMOTE_ADDR"];	//업로드 된 IP
		$F_DATA["REG_USER_ID"] = $R_DATA["REG_USER_ID"];

		$F_DATA["GUBUN"] = $gubun;

		if(preg_match("/(gif|jpg|png|jpeng|JPG|GIF)/i",$F_DATA["FILENAME"])){
			$_THUMB_UP_PATH_ = $_UP_PATH_."/thumb/";
			$_THUMB_NAME_	 = "thumb_".$F_DATA["FILENAME"];
			if(!is_dir($_THUMB_UP_PATH_)) @exec("mkdir -p -m0777 ".$_THUMB_UP_PATH_);
			//썸네일 생성함수 인자 : 썸네일저장될경로,저장될파일이름,원본이미지경로,원본이미지파일이름,썸네일가로,세로

			$T_DATA = make_thumb($_THUMB_UP_PATH_,$_THUMB_NAME_,$_UP_PATH_."/",$F_DATA["FILENAME"],$Board->config["IMG_THUMB_W"],$Board->config["IMG_THUMB_H"],$opt="");
			$F_DATA["THUMBNAIL_NAME"] = $T_DATA["file_name"];
		}
		if ($MODE=="update"){
			$qry = $DB->updateQuery($_Tables_,$F_DATA,"IDX='".$R_DATA["FILE_IDX"]."'");
		}else{
			$F_DATA["IDX"] = newDataIdx($_Tables_,"IDX");
			$qry = $DB->insertQuery($_Tables_,$F_DATA);
		}
		if ($qry) $result =$DB->dbQuery($qry);
	}
	return $result;
}

function get_MIME($url) {
	$url = str_replace("http://", "", $url);

	list($domain, $file) = explode("/", $url, 2);
	$fid = fsockopen($domain, 80);
	fputs($fid, "GET /$file HTTP/1.0\r\nHost: $domain\r\n\r\n");
	$buffer = fgets($fid, 128);
	if(!ereg("200 OK", $buffer))
	return "NULL";
	while(!eregi("Content-type: ", $buffer)) // MIME 출력할때까지 버퍼 업데이트
	$buffer = fgets($fid, 128);
	fclose($fid);

	list($conttype, $junk) = explode(";", $buffer, 2); // 버퍼의 서브스트링(MIME) 구하기
	$conttype = trim($conttype);
	$MIME = substr($conttype, 14, strlen($conttype) - 14);

	return $MIME;
}


function getFileType($filename){
	$file_ext = strtolower(substr(strrchr($filename,"."),1));
	switch($file_ext){
		case "pdf": $ctype="application/pdf"; break;
		case "exe": $ctype="application/octet-stream"; break;
		case "zip": $ctype="application/zip"; break;
		case "doc": $ctype="application/msword"; break;
		case "xls": $ctype="application/vnd.ms-excel"; break;
		case "xlsx": $ctype="application/vnd.ms-excel"; break;
		case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpeg":
		case "jpg": $ctype="image/jpg"; break;
		case "mp3": $ctype="audio/mpeg"; break;
		case "flv": $ctype="video/x-flv"; break;
		case "wav": $ctype="audio/x-wav"; break;
		case "mpeg":
		case "mpg":
		case "mpe": $ctype="video/mpeg"; break;
		case "mov": $ctype="video/quicktime"; break;
		case "avi": $ctype="video/x-msvideo"; break;
		case "hwp": $ctype="application/haansofthwp"; break;
		//The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
		case "php":
		case "htm":
		case "html":
		case "txt": $ctype="application/x-msdownload"; break;// die("<b>Cannot be used for ".$file_ext." files!</b>"); break;
		default: $ctype="application/force-download";
	}
	return $ctype;
}

function FileDown($file_path,$file_name,$file_key){
	//First, see if the file exists
	$downFile = $file_path."/".$file_key;

	if (!is_file($downFile)) { die("<b>404 File not found!</b>"); }
	//Gather relevent info about file
	$len = filesize($downFile);
	$filename = basename($downFile);
	$file_ext = strtolower(substr(strrchr($filename,"."),1));
	//This will set the Content-Type to the appropriate setting for the file
	switch($file_ext){

		case "pdf": $ctype="application/pdf"; break;
		case "exe": $ctype="application/octet-stream"; break;
		case "zip": $ctype="application/zip"; break;
		case "doc":
		case "docx": $ctype="application/msword"; break;
		case "xls": $ctype="application/vnd.ms-excel"; break;
		case "xlsx": $ctype="application/vnd.ms-excel"; break;
		case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "bmp": $ctype="image/bmp"; break;
		case "jpeg":
		case "jpg": $ctype="image/jpg"; break;
		case "mp3": $ctype="audio/mpeg"; break;
		case "wav": $ctype="audio/x-wav"; break;
		case "flv": $ctype="video/x-flv"; break;
		case "mpeg":
		case "mpg":
		case "mpe": $ctype="video/mpeg"; break;
		case "mov": $ctype="video/quicktime"; break;
		case "avi": $ctype="video/x-msvideo"; break;
		case "hwp": $ctype="application/x-hwp"; break;
		//The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
		case "php":
		case "htm":
		case "html":
		case "txt": $ctype="application/x-msdownload"; break;// die("<b>Cannot be used for ".$file_ext." files!</b>"); break;
		default: $ctype="application/force-download";
	}
	$chk_mobile = mobile_check();
	if($chk_mobile){
		$file_name = iconv("euc-kr","utf-8",$file_name);
		switch($chk_mobile){
			default: $ret_rength = header("Content-Length: ".$len); break;
			case "iPhone":case "iPod":case "IPad": break;
		}
	}

	//Begin writing headers $this->_mobile_check()

	header("Cache-Control: public");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Content-Description: File Transfer");
	//Use the switch-generated Content-Type
	header("Content-Type: ".$ctype);
	//Force the download
//	$file_name = iconv("euc-kr","utf-8",$file_name);
//	$file_name = mb_convert_encoding($file_name,"euc-kr","utf-8");
	$header="Content-Disposition:attachment;filename=".$file_name.";";
	header($header);
	Header("Content-Description: PHP3 Generated Data");
	header("Content-Transfer-Encoding: binary");
	$c_length = $ret_rength;
	ob_clean();
	flush();
	readfile($downFile);
	exit;
}
function mobile_check(){
	$mobile_agent = array("iPhone","iPod","IPad","Android","Blackberry","SymbianOS|SCH-M\d+","Opera Mini","Windows CE","Nokia","Sony","Samsung","LGTelecom","SKT","Mobile","Phone");
	for($i=0; $i<count($mobile_agent); $i++){
		if(preg_match("/$mobile_agent[$i]/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return $mobile_agent[$i];
			break;
		}
	}
}
?>
