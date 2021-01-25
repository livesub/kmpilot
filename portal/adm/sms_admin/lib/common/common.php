<?php

class SuremPacket
{
	var $szCmd;
	var $szType;
	var $szDate;
	var $szTime;
	var $szUsercode;
	var $szUsername;
	var $szDeptcode;
	var $szDeptname;
	var $szStatus;
	var $szCallphone1;
	var $szCallphone2;
	var $szCallphone3;
	var $szCallmessage;
	var $szCallurl;
	var $szRdate;
	var $szRtime;
	var $szDummy;
	var $lMember;
	var $szReqphone1;
	var $szReqphone2;
	var $szReqphone3;
	var $szReqname;
	var $szReserved;

	var $ServiceID;
	var $TotalPrice;
	var $CallPrice;
	var $SeqNum;

	function addnull($index)
	{
		$str = "";

		for ($i=0;$i<$index;$i++)
			$str .= chr(0x00);

		return $str;
	}


	function getconnect($servername)
	{
		$errno = 1;
		if( $servername == "testserver")
		{
			$SERVER_IP = gethostbyname ("test.surem.com"); // ������� �ʴ� DNS, ���� �������� ����
		}

		else
		{
			$SERVER_IP = gethostbyname ("messenger.surem.com"); //���� ���� IP ���
		}

		$PORT = 8080;  //��Ʈ ��ȣ


		$TIME_OUT = 15;

		$fp = fsockopen( $SERVER_IP, $PORT, $errno, $errstr, $TIME_OUT);	//���� ����


		if(!$fp && $servername == "testserver" )
			return "-2";

		//���� ���� ���н� �ٸ� ������ ���� �õ�
		if( !$fp)
		{
			$SERVER_IP = gethostbyname ("messenger3.surem.com"); //���� ���� IP ���
			$fp = fsockopen( $SERVER_IP, $PORT, $errno, $errstr, $TIME_OUT);	//���� ����

			//�ٸ� ������ ���н� ���� ���� ó��
			if(!$fp)
				$result="-1";
			else
				$result=$fp;
		}
		else
			$result = $fp;

		//���� ������ ���� ������ ��ȯ
		return $fp;
	}

	function disconnect($fp)
	{
		fclose($fp);
	}
	function int2byte( $i )
	{

		settype($i,integer);

		$temp = sprintf("%c",($i&0xFF));
		$temp = $temp.sprintf("%c",($i>>8&0xFF));
		$temp = $temp.sprintf("%c",($i>>16&0xFF));
		$temp = $temp.sprintf("%c",($i>>24&0xFF));

		return $temp;
	}
	function byte2str($i)
	{

		$a = substr($i,0,1);
		$b = substr($i,1,1);
		$c = substr($i,2,1);
		$d = substr($i,3,1);


		$temp = ord($a) *1 + ord($b)*256 + ord($c) * 65536 + ord($d) * 16777216;

		return (int)$temp;



	}



	function int2byte2( $i )
	{

		settype($i,integer);

		$temp = sprintf("%c",($i&0xFF));
		$temp = $temp.sprintf("%c",($i>>8&0xFF));

		return $temp;
	}


	function sendsms($member,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname)
	{
		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		 $this->szCmd = "B";
		 $this->szType = "C";
		 $this->szDate = date('Ymd',$cdate).$this->addnull(2);
		 $this->szTime = date('His',$cdate).$this->addnull(2);
		 $this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		 $this->szUsername = $username . $this->addnull(16 - strlen($username));
		 $this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		 $this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		if( $rdate == "00000000" && $rtime=="000000")
			$this->szStatus = chr(0x00);
		else
			$this->szStatus = "R";


		$this->szCallphone1 = $callphone1.$this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $callphone2.$this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $callphone3.$this->addnull(4 - strlen($callphone3));
		$this->szCallmessage = $callmessage .$this->addnull(120 - strlen($callmessage));

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->int2byte($member);
		$this->szReqphone1 = $reqphone1 . $this->addnull(4 - strlen($reqphone1));
		$this->szReqphone2 = $reqphone2 . $this->addnull(4 - strlen($reqphone2));
		$this->szReqphone3 = $reqphone3 . $this->addnull(4 - strlen($reqphone3));
		$this->szReqname = $callname . $this->addnull(32 - strlen($callname));
		$this->szReserved = $this->addnull(32);

		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str.$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->lMember;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->szReserved;

		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;
	}

	function sendurl($member,$callphone1,$callphone2,$callphone3,$callmessage,$callurl,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname)
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		 $this->szCmd = "I";
		 $this->szType = "C";
		 $this->szDate = date('Ymd',$cdate).$this->addnull(2);
		 $this->szTime = date('His',$cdate).$this->addnull(2);
		 $this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		 $this->szUsername = $username . $this->addnull(16 - strlen($username));
		 $this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		 $this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		if( $rdate == "00000000" && $rtime=="000000")
			$this->szStatus = chr(0x00);
		else
			$this->szStatus = "R";


		$this->szCallphone1 = $callphone1.$this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $callphone2.$this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $callphone3.$this->addnull(4 - strlen($callphone3));


		$this->szCallmessage = $callmessage .$this->addnull(80 - strlen($callmessage));
		$this->szCallurl = $callurl .$this->addnull(62 - strlen($callurl));

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szReqphone1 = $reqphone1 . $this->addnull(4 - strlen($reqphone1));
		$this->szReqphone2 = $reqphone2 . $this->addnull(4 - strlen($reqphone2));
		$this->szReqphone3 = $reqphone3 . $this->addnull(4 - strlen($reqphone3));
		$this->szReqname = $callname . $this->addnull(32 - strlen($callname));

		$this->szDummy = $this->addnull(1);
		$this->lMember = $this->int2byte($member);


		$this->szReserved = $this->addnull(4);

		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szCallurl.$this->szRdate.$this->szRtime;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname;
$Str = $Str.$this->szDummy.$this->lMember.$this->addnull(4).$this->addnull(4).$this->szReserved;

		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;

	}

	function  batchsms($rdate,$rtime,$filename)
	{

		global $usercode,$username,$deptcode,$deptname;


		$fp= fopen($filename,"r");

		if(!$fp)
		{
			return "-1";
		}


		$buf=fread($fp,filesize($filename));


		$line=split("\n",$buf);

		$num=count($line);

		$arr = split("\t",$line[0]);

		 $this->szCmd = "U";
		 $this->szType = "C";
		 $this->szDate = date('Ymd',$cdate).$this->addnull(2);
		 $this->szTime = date('His',$cdate).$this->addnull(2);
		 $this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		 $this->szUsername = $username . $this->addnull(16 - strlen($username));
		 $this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		 $this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		if( $rdate == "00000000" && $rtime=="000000")
			$this->szStatus = chr(0x00);
		else
			$this->szStatus = "R";


		$this->szCallphone1 = $arr[2].$this->addnull(4 - strlen($arr[2]));
		$this->szCallphone2 = $arr[3].$this->addnull(4 - strlen($arr[3]));
		$this->szCallphone3 = $arr[4].$this->addnull(4 - strlen($arr[4]));
		$this->szCallmessage = $arr[8] .$this->addnull(120 - strlen($arr[8]));

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->int2byte($arr[0]);
		$this->szReqphone1 = $arr[5] . $this->addnull(4 - strlen($arr[5]));
		$this->szReqphone2 = $arr[6] . $this->addnull(4 - strlen($arr[6]));
		$this->szReqphone3 = $arr[7] . $this->addnull(4 - strlen($arr[7]));
		$this->szReqname = $arr[1] . $this->addnull(32 - strlen($arr[1]));
		$this->szReserved = $num.$this->addnull(16-strlen($num));

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->addnull(4);
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname;
$Str = $Str.$this->lMember.$this->addnull(4).$this->addnull(4).$this->addnull(4).$this->szReserved;

		$fsoc=$this->getconnect("messenger");

		fwrite($fsoc, $Str,328);
		flush();

		socket_set_timeout($fsoc, 10);

		$res=fread($fsoc,400);

		$result =substr($res,94,1);

		for($idx=0;$idx<$num;$idx++)
		{
			$line[$idx]=nl2br($line[$idx]);
			$line[$idx]=str_replace("<br />",chr(0x00),$line[$idx]);
		}

		for($idx=1;$idx<$num;$idx++)
		{
			$arr = split("\t",$line[$idx]);


			$arr[8]=$arr[8].chr(0x00);

			$this->szCallphone1 = $arr[2].$this->addnull(5 - strlen($arr[2]));
			$this->szCallphone2 = $arr[3].$this->addnull(5 - strlen($arr[3]));
			$this->szCallphone3 = $arr[4].$this->addnull(5 - strlen($arr[4]));

			$this->szReqphone1 = $arr[5] . $this->addnull(5 - strlen($arr[5]));
			$this->szReqphone2 = $arr[6] . $this->addnull(5 - strlen($arr[6]));
			$this->szReqphone3 = $arr[7] . $this->addnull(5 - strlen($arr[7]));
			$this->szReqname = $arr[1] . $this->addnull(16 - strlen($arr[1]));
			$this->szReserved = $this->addnull(8);
			$this->szCallmessage = $arr[8] .$this->addnull(120 - strlen($arr[8]));
			$this->szRdate = $rdate.$this->addnull(1);
			$this->szRtime = $rtime.$this->addnull(1);



$Str = $this->szCallphone1.$this->szCallphone2.$this->szCallphone3.$this->szStatus;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->addnull(1);
$Str = $Str.$this->int2byte($arr[0]).$this->addnull(4).$this->szCallmessage;
$Str = $Str.$this->szRdate.$this->szRtime.$this->szReserved;

			fwrite($fsoc, $Str,200);
			flush();
			socket_set_timeout($fsoc, 10);
			$res=fread($fsoc,200);

//			�������� ���� Packet�� ����� ó���ϰ� ������ �ҽ� �ڵ� ����.
//
//			if (substr($res,15,1) == "O" )
//				{ ... }

		}

		socket_set_timeout($fsoc, 10);
		$res=fread($fsoc,400);


		$this->disconnect($fsoc);

		return $res;

	}


	function  batchurl($rdate,$rtime,$filename)
	{

		global $usercode,$username,$deptcode,$deptname;


		$fp= fopen($filename,"r");

		if(!$fp)
	{
			return "-1";
		}


		$buf=fread($fp,filesize($filename));

		$line=split("\n",$buf);

		$num=count($line);

		for($idx=0;$idx<$num;$idx++)
		{
			$line[$idx]=nl2br($line[$idx]);
			$line[$idx]=str_replace("<br />",chr(0x00),$line[$idx]);
		}

		$arr = split("\t",$line[0]);

		 $this->szCmd = "V";
		 $this->szType = "C";
		 $this->szDate = date('Ymd',$cdate).$this->addnull(2);
		 $this->szTime = date('His',$cdate).$this->addnull(2);
		 $this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		 $this->szUsername = $username . $this->addnull(16 - strlen($username));
		 $this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		 $this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		if( $rdate == "00000000" && $rtime=="000000")
			$this->szStatus = chr(0x00);
		else
			$this->szStatus = "R";
		$this->szCallphone1 = $arr[2].$this->addnull(4 - strlen($arr[2]));
		$this->szCallphone2 = $arr[3].$this->addnull(4 - strlen($arr[3]));
		$this->szCallphone3 = $arr[4].$this->addnull(4 - strlen($arr[4]));
		$this->szCallmessage = $arr[9] .$this->addnull(80 - strlen($arr[9]));
		$this->szCallurl = $arr[8] .$this->addnull(62 - strlen($arr[8]));

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szDummy = $this->addnull(1);
		$this->lMember = $this->int2byte($arr[0]);
		$this->szReqphone1 = $arr[5] . $this->addnull(4 - strlen($arr[5]));
		$this->szReqphone2 = $arr[6] . $this->addnull(4 - strlen($arr[6]));
		$this->szReqphone3 = $arr[7] . $this->addnull(4 - strlen($arr[7]));
		$this->szReqname = $arr[1] . $this->addnull(32 - strlen($arr[1]));
		$this->szReserved = $this->int2byte($num);

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szCallurl.$this->szRdate.$this->szRtime;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname;
$Str = $Str.$this->szDummy.$this->lMember.$this->addnull(4).$this->addnull(4).$this->szReserved;

		$fsoc=$this->getconnect("messenger");

		fwrite($fsoc, $Str,328);
		flush();

		socket_set_timeout($fsoc, 10);

		$res=fread($fsoc,400);

		$result =substr($res,94,1);

		for($idx=1;$idx<$num;$idx++)
		{
			$arr = split("\t",$line[$idx]);

//			echo $arr[9];

			$arr[9]=$arr[9].chr(0x00);

			$this->szCallphone1 = $arr[2].$this->addnull(5 - strlen($arr[2]));
			$this->szCallphone2 = $arr[3].$this->addnull(5 - strlen($arr[3]));
			$this->szCallphone3 = $arr[4].$this->addnull(5 - strlen($arr[4]));

			$this->szCallmessage = $arr[9] .$this->addnull(80 - strlen($arr[9]));
			$this->szCallurl = $arr[8] .$this->addnull(62 - strlen($arr[8]));


			$this->szReqphone1 = $arr[5] . $this->addnull(5 - strlen($arr[5]));
			$this->szReqphone2 = $arr[6] . $this->addnull(5 - strlen($arr[6]));
			$this->szReqphone3 = $arr[7] . $this->addnull(5 - strlen($arr[7]));
			$this->szReqname = $arr[1] . $this->addnull(16 - strlen($arr[1]));
			$this->szReserved = $this->addnull(2);
			$this->szCallmessage = $arr[9] .$this->addnull(80 - strlen($arr[9]));
			$this->szCallurl = $arr[8] .$this->addnull(62 - strlen($arr[8]));
			$this->szRdate = $rdate.$this->addnull(1);
			$this->szRtime = $rtime.$this->addnull(1);



$Str = $this->szCallphone1.$this->szCallphone2.$this->szCallphone3.$this->szStatus;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->addnull(1);
$Str = $Str.$this->int2byte($arr[0]).$this->addnull(4).$this->szCallmessage;
$Str = $Str.$this->szCallurl.$this->addnull(2);

			fwrite($fsoc, $Str,200);
			flush();
			socket_set_timeout($fsoc, 10);
			$res=fread($fsoc,200);

//			�������� ���� Packet�� ����� ó���ϰ� ������ �ҽ� �ڵ� ����.
//
//			if (substr($res,15,1) == "O" )
//				{ ... }

		}

		socket_set_timeout($fsoc, 10);
		$res=fread($fsoc,400);


		$this->disconnect($fsoc);

		return $res;

	}

	function reserveCancel($member,$date,$callphone1,$callphone2,$callphone3)
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		$this->szCmd = "C";
		$this->szType = "C";
		$this->szDate = $date.$this->addnull(2);
		$this->szTime = $this->addnull(8);
		$this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		$this->szUsername = $this->addnull(16);
		$this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		$this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		$this->szStatus = "1";

		$this->szCallphone1 = $callphone1.$this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $callphone2.$this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $callphone3.$this->addnull(4 - strlen($callphone3));
		$this->szCallmessage = $this->addnull(120);

		$this->szRdate = $this->addnull(10);
		$this->szRtime = $this->addnull(8);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->int2byte($member);
		$this->szReqphone1 = $this->addnull(4);
		$this->szReqphone2 = $this->addnull(4);
		$this->szReqphone3 = $this->addnull(4);
		$this->szReqname = $this->addnull(32);
		$this->szReserved = $this->addnull(16);

		$this->SeqNum = $this->addnull(4);
		$this->ServiceID = $this->addnull(4);
		$this->TotalPrice = $this->addnull(4);
		$this->CallPrice = $this->addnull(4);

		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->SeqNum;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->lMember.$this->ServiceID.$this->TotalPrice.$this->CallPrice.$this->szReserved;


		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;
	}

	function reserveUpdate($member,$date,$rdate,$rtime,$callphone1,$callphone2,$callphone3)
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		$this->szCmd = "C";
		$this->szType = "E";
		$this->szDate = $date.$this->addnull(2);
		$this->szTime = $this->addnull(8);
		$this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		$this->szUsername = $this->addnull(16);
		$this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		$this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		$this->szStatus = "1";

		$this->szCallphone1 = $callphone1.$this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $callphone2.$this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $callphone3.$this->addnull(4 - strlen($callphone3));
		$this->szCallmessage = $this->addnull(120);

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->int2byte($member);
		$this->szReqphone1 = $this->addnull(4);
		$this->szReqphone2 = $this->addnull(4);
		$this->szReqphone3 = $this->addnull(4);
		$this->szReqname = $this->addnull(32);
		$this->szReserved = $this->addnull(16);

		$this->SeqNum = $this->addnull(4);
		$this->ServiceID = $this->addnull(4);
		$this->TotalPrice = $this->addnull(4);
		$this->CallPrice = $this->addnull(4);

		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->SeqNum;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->lMember.$this->ServiceID.$this->TotalPrice.$this->CallPrice.$this->szReserved;


		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;
	}

	function checkMoney()
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		$this->szCmd = "J";
		$this->szType = "C";
		$this->szDate = date('Ymd',$cdate).$this->addnull(2);
		$this->szTime = date('His',$cdate).$this->addnull(2);
		$this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		$this->szUsername = $this->addnull(16);
		$this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		$this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		$this->szStatus = "S";

		$this->szCallphone1 = $this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $this->addnull(4 - strlen($callphone3));
		$this->szCallmessage = $this->addnull(120);

		$this->szRdate = $this->addnull(10);
		$this->szRtime = $this->addnull(8);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->addnull(4);
		$this->szReqphone1 = $this->addnull(4);
		$this->szReqphone2 = $this->addnull(4);
		$this->szReqphone3 = $this->addnull(4);
		$this->szReqname = $this->addnull(32);
		$this->szReserved = $this->addnull(16);

		$this->SeqNum = $this->addnull(4);
		$this->ServiceID = $this->addnull(4);
		$this->TotalPrice = $this->addnull(4);
		$this->CallPrice = $this->addnull(4);

		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->SeqNum;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->lMember.$this->ServiceID.$this->TotalPrice.$this->CallPrice.$this->szReserved;


		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;
	}

}

?>