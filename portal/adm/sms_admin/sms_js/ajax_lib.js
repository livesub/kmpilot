// JavaScript Document




//*****************************************************************************
// FORM 데이터 수집 및 바인딩 함수 정의
//*****************************************************************************
function ajax_data_bind(objname, obj_xml)
{
	if (obj_xml)
	{
		//var ret_val = path_val(obj_xml, "/DocRoot/Content");
		//
//		alert(obj_xml);

		//if (ret_val == "LOGIN")
		//{
		//	location.href = "/Body/Main.php";
		//}
		//else
		//{
			//$("#"+objname).show('highlight', '', 1000);
			$("#"+objname).empty();
			$("#"+objname).html(obj_xml);
		//}
	}
}



//////////////////////////////////////////////////////////////////////////////////////////
// JSON request_json(String, Hash, String):
// 원격 서버로부터 JSON 데이터를 요청하여 결과를 반환
//////////////////////////////////////////////////////////////////////////////////////////
function request_json(req_url, req_data, callback, async)
{
	var ret_data = null;

	if ( typeof async == 'undefined' ) {
		async = false;
	}
	var param = req_data+"&"+jQuery.param({'ret': 'json', 'tm': make_tm()});
	$.ajax({
		onlyOnce: async,
		type: "POST",
		url	: req_url,
		data: param,
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
			alert('죄송합니다. 서버와의 통신이 원활하지 못합니다. (' + textStatus + ' - ' + errorThrown + ')');
		},
		success: function(json)
		{
//alert("ok==> "+json);
			ret_data = json;
			if ( callback != null && callback != '' )
			{
				eval(callback + "(ret_data);");
			}
		}
	});
}

function request_html(req_url, req_data, callback, async) {
	var ret_data = null;

	if ( typeof async == 'undefined' ) {
		async = false;
	}
	param = req_data;

	$.ajax({
		onlyOnce: async,
		type: "post",
		url	: req_url,
		data: param,
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
			alert('죄송합니다. 서버와의 통신이 원활하지 못합니다. (' + textStatus + ' - ' + errorThrown + ')');
		},
		success: function(html){
			ret_data = html;
			if ( callback != null && callback != '' ){
				eval(callback + "ret_data);");
			}
		}
	});
}

function request_xml(req_url, req_data, callback, async)
{
	var ret_data = null;

	if ( typeof async == 'undefined' ) {
		async = false;
	}
	param = req_data;

	$.ajax({
		onlyOnce: async,
		type: "POST",
		url	: req_url,
		data: param,
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
			alert('죄송합니다. 서버와의 통신이 원활하지 못합니다. (' + textStatus + ' - ' + errorThrown + ')');
		},
		success: function(html)
		{
			ret_data = html;
			if ( callback != null && callback != '' )
			{
				eval(callback + "ret_data);");
			}
		}
	});
}


function requestSecureJSON(req_url, req_data, callback, async) {
	var ret_data = null;

	if ( typeof async == 'undefined' ) {
		async = false;
	}
	var param = req_data+"&"+jQuery.param({'ret': 'json', 'security': '1', 'tm': make_tm()});
	var encryptParam = 'Par=' + Encrypt(param);
	$.ajax({
		onlyOnce: async,
		type: "post",
		url	: req_url,
		data: encryptParam,
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
			alert('죄송합니다. 서버와의 통신이 원활하지 못합니다. (' + textStatus + ' - ' + errorThrown + ')');
		},
		success: function(json)
		{
			ret_data = json;
			if ( callback != null && callback != '' )
			{
				eval(callback + "(ret_data);");
			}
		}
	});
}

function requestSecureXML(req_url, req_data, callback, async) {

	var ret_data = null;

	if ( typeof async == 'undefined' ) {
		async = false;
	}
	param = req_data+"&"+jQuery.param({'ret': 'xml', 'security': '1', 'tm': make_tm()});
	var encryptParam = 'Par=' + Encrypt(param);

	$.ajax({
		onlyOnce: async,
		type: "post",
		url	: req_url,
		data: encryptParam,
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
			alert('죄송합니다. 서버와의 통신이 원활하지 못합니다. (' + textStatus + ' - ' + errorThrown + ')');
		},
		success: function(html)
		{
			ret_data = html;
			if ( callback != null && callback != '' )
			{
				eval(callback + "ret_data);");
			}
		}
	});
}



//////////////////////////////////////////////////////////////////////////////////////////
// int make_tm(void):
// 현재 시간에 대한 TIMESTAMP 반환
//////////////////////////////////////////////////////////////////////////////////////////
function make_tm(format) {
	var now = new Date();
	var year=now.getYear(); // 년도 가져오기
	var month=now.getMonth()+1; // 월 가져오기 (+1)
	var date=now.getDate(); // 날짜 가져오기
	var hour=now.getHours(); // 시간 가져오기
	var min=now.getMinutes(); // 분 가져오기
	var sec=now.getSeconds(); // 초 가져오기
	var mils=now.getMilliseconds(); // 밀리초 가져오기

	var tm = "";

	if ( typeof format == "undefined" || format == null || format == "" ) {
		tm = year + "" + month + "" + date + "" + hour + "" + min + "" + sec + "" + mils;
	} else if ( format == "D" ) {
		tm = year + "" + month + "" + date;
	} else if ( format == "H" ) {
		tm = year + "" + month + "" + date + "" + hour;
	}

	return tm;
}
