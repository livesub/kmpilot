
function doOpenLayerBox(setting){
	var this_s = this;	
	
	this.title	= setting.title;
	this.maxh	= setting.maxh ? setting.maxh : 500;
	this.w	= setting.w;
	this.h	= setting.h;
	this.modal		= setting.modal ? setting.modal : true;
	this.resize		= setting.resize ? setting.resize : false;
	this.position	= setting.position ? setting.position : 'top';
	
	$.fx.speeds._default = 500;
	$("#dialog").dialog({
		autoOpen: true,
		modal: this_s.modal,
		resizable:	this_s.resizable,
		maxHeight: this_s.maxh,
		title: this_s.title,
		width:	this_s.w,
		height:	this_s.h,
		position: this_s.position,
		show: 'highlight',
		hide: 'highlight'
	});		
	$("#dialog").load(setting.url);
}

function doCloseLayerBox(){
	$("#dialog").dialog('close')
}

//전체선택
function check_all(obj1,obj2){
	$(obj2).attr("checked",obj1.checked);
}

function doLayerClose(){
	top.document.location.reload();
}

function checkLogin(){
	var chk =	$.ajax({  url: "/default/modules/member/_action.php?mode=checkLogin",  async: false }).responseText;
	return chk;
}
function openLoginDialog(){
	var loginWin =  new msgPopupWin({w:415,h:350,msgWinDoc:"",setStyle:true,title:"로그인",closeBtns:$(".closeBtn")});
	$(loginWin.bodyPannel).load("/html/member/?page=login&pop=2&url=" + encodeURIComponent(document.location.href));


}

 function number_format(data) 
    {
        
        var tmp = '';
        var number = '';
        var cutlen = 3;
        var comma = ',';
        var i;
       if(parseInt(data)==0) return 0;
		data = String(data);
        len = data.length;
        mod = (len % cutlen);
        k = cutlen - mod;
        for (i=0; i<data.length; i++) 
        {
            number = number + data.charAt(i);
            
            if (i < data.length - 1) 
            {
                k++;
                if ((k % cutlen) == 0) 
                {
                    number = number + comma;
                    k = 0;
                }
            }
        }

        return number;
    }
function sprintf(zero,text){

	len = zero.length;
	r_txt = zero + text;
	f_len = r_txt.length;
	s_len = f_len - len;
	r_txt = r_txt.slice(s_len,f_len);
	return r_txt;
}
function file_size(num){
	var n = parseInt(num);
	var n1 = n;
	var u = "KB";
	
	 if (n < 1048576)	n1 =  n / 1024;
	 else if(n<1073741824) { n1 = n/1048576;  u = "MB"; }
	 else {n1 = n/1073741824 ; u = "GB";} 
	
	n1 = parseInt(n1 * 100)/100;
	return n1 + u;
}
//체크된 체크박스 확인
function chk_select(f,chk_name,num){
	
		if (typeof f!="object") var obj = document.getElementById(f);
		else var obj = f;
		
		var cnt=0;
		for(var i = 0;i < obj.elements.length;i++) { 
			var currEl = obj.elements[i]; 
			if (currEl.getAttribute("type")  =="checkbox" && currEl.name==chk_name && currEl.checked==true ){
				cnt++;
			}
		}
		if (cnt<num) return false;
		else return true;

}
//선택된 값을 문자열로 연결
function make_select_list(frm_obj,select_name,split){
	var full_chk_list  ="";
	var split_str="";
	if (split=="") split=",";
	for(var i = 0;i < frm_obj.elements.length;i++) { 
			var currEl = frm_obj.elements[i]; 
			if (currEl.name==select_name && currEl.checked==true){
				full_chk_list = full_chk_list + split_str +currEl.value;
				split_str = split;
			}
	}
	return full_chk_list;
}


/*레이어팝업 */
var msgPopupWin;
if (msgPopupWin == undefined) {
	msgPopupWin = function (settings) {
		this.init(settings);
	};
}
msgPopupWin.prototype.init = function (settings) {

	var this_s = this;
	var msg_wrap = document.createElement("div");
	msg_wrap.className="pop_windoc";
	
	var msg_pan = document.createElement("div");
	msg_pan.className = "pop_windoc_bg";
	$(msg_wrap).append(msg_pan);
	$("body").prepend(msg_wrap);
	
	this.backPannel = msg_pan;
	this.backPannel.onclick=function(){
		$(this.parentNode).remove();
	}

	var msg_body = document.createElement("div");
	msg_body.className="pop_windoc_box";
	
	$(msg_body).draggable({ handle: 'div.pop_winTop',scroll: true,helper: 'original'});
	$("div").disableSelection();

	if(settings.w!=undefined) msg_body.style.width =settings.w ;
	if(settings.h!=undefined) msg_body.style.height =settings.h ;
	
	$(msg_wrap).append(msg_body);
	setCenterPos(msg_wrap,msg_body);
	this.title = "";

	if(settings.title)  this.title = settings.title;
	if(settings.setStyle){

		var winDocWrap = document.createElement("div");
		var winDocTop = document.createElement("div");

		$(winDocWrap).addClass("pop_winWrap");
		$(winDocTop).addClass("pop_winTop");
		$(winDocTop).css("cursor","move");
		$(winDocTop).append("<span class='pop_title'>"+this.title+"</span>");

		var winCloseBtn = document.createElement("span");
		$(winCloseBtn).addClass("pop_close");
		$(winCloseBtn).css("cursor","pointer");
		$(winCloseBtn).html("<img src='"+CMS_FOLDER+"/_img/button/sbtn_close.gif'/>");
		$(winCloseBtn).click(function(){this_s.close();	});
		$(winDocTop).append(winCloseBtn);

		$(winDocWrap).append(winDocTop);
		$(winDocWrap).append("<div class='pop_winBody'><div class='pop_body'></div></div>");
		$(winDocWrap).append("<div class='pop_winFoot'></div>");
		
		
		$(msg_body).append($(winDocWrap));
		//this.bodyPannel = $(".pop_winBody ",$(winDocWrap));
		this.bodyPannel = $(".pop_body ",$(winDocWrap));
		
		var tmpTopH =$(winDocWrap).height() -  ($(winDocTop).height() + $(".pop_winFoot",winDocWrap).height());
		var tmpTopW =$(winDocWrap).width() -  20;
		 $(".pop_winBody ",$(winDocWrap)).height(tmpTopH-10);
		$($(this.bodyPannel)).width($(".pop_winBody").width()-20);
		//this.bodyPannel.height(tmpTopH-10);
		
	}else{
		this.bodyPannel = msg_body;
	}

	$(this.bodyPannel).append(settings.msgWinDoc);

	if(settings.closeBtns){
		for(var i=0; i<settings.closeBtns.length;i++){
			$(settings.closeBtns[i]).click(function(){this_s.close();	});
		}
	}
	this.settings = settings;
	this.obj = msg_wrap;
	this.setShow();
}

msgPopupWin.prototype.setContents = function(html){	$(this.bodyPannel).html(html); this.setCloseBtns();}
msgPopupWin.prototype.setContSize =  function(){$(".contents",this.bodyPannel).width($(this.bodyPannel).width()-20)}
msgPopupWin.prototype.addContents = function(html){	$(this.bodyPannel).append(html); this.setCloseBtns();}
msgPopupWin.prototype.setTitle = function(str){	this.title = str; $(".pop_title",$(this.obj)).html(str);}
msgPopupWin.prototype.setShow = function(){	$(this.obj).show();}
msgPopupWin.prototype.setHide = function(){	$(this.obj).hide();}
msgPopupWin.prototype.close = function(){	$(this.obj).remove();}
msgPopupWin.prototype.setCloseBtns = function(){
	var this_s = this;
	if(this.settings.closeBtns){
		for(var i=0; i<this.settings.closeBtns.length;i++){
			$(this.settings.closeBtns[i]).click(function(){this_s.close();	});
		}
	}
	$(".closeBtn",this_s.bodyPannel).click(function(){this_s.close();	});
	//this.setContSize();
}


function setCenterPos(doc,obj){
	var _left = ((doc.clientWidth-obj.offsetWidth)/2);
	 _left =  (parseInt(_left)>0) ? _left : 0 ;
	obj.style.left =_left + "px";


	var _top = ((doc.offsetHeight-obj.offsetHeight)/2);
	 _top =  (parseInt(_top)>0) ? _top : 0 ;
	obj.style.top =_top + "px";
}

function openLayerPage(url,width,height,page_title){
	frmWin = new msgPopupWin({w:width+"px",h:height+"px",setStyle:true,title:page_title});
	$(frmWin.bodyPannel).load(url,function (){frmWin.setCloseBtns();});
}

//상대위치 구하기
function getRelativeOffset(obj1,obj2){
	var offset1 = obj1.offset();
	var offset2 = obj2.offset();
	var roffset = {left:offset2.left - offset1.left -1 , top: offset2.top-offset1.top -1}
	return roffset;
}


function setTabContents(tabid,num,contid,url){
	var tabObj = $("#" + tabid);
	
//	if(tabObj.attr("over")!=num){
		tabObj.attr("over",num);
		$("li:not(:nth("+(num-1)+"))",tabObj).removeClass("over");
		$("li:nth("+(num-1)+")",tabObj).addClass("over");
		$("#"+contid).load(url);
//	}
}

function setFrameContents(tabid,num,contid,url){
	var tabObj = $("#" + tabid);
	
//	if(tabObj.attr("over")!=num){
		tabObj.attr("over",num);
		$("li:not(:nth("+(num-1)+"))",tabObj).removeClass("over");
		$("li:nth("+(num-1)+")",tabObj).addClass("over");
		//$("#"+contid).load(url);
		$("#"+contid).attr("src",url);

//	}
}


function setStyleLayer(objid,val){
	var obj1 = $("#"+objid+"_box");
	var obj2 = $("#"+objid+"_layer");

	if(val=="P"){
		obj2.hide();
		
	}else{
		obj2.show();
		
		$(obj2).width(obj1.width());
		$(obj2).height(obj1.height());
	}
}

function setStyleLayer2(objid,val){
	var obj1 = $("#"+objid+"_box");
	var obj2 = $("#"+objid+"_layer");

	if(val){
		obj2.hide();
		
	}else{
		obj2.show();
		
		$(obj2).width(obj1.width());
		$(obj2).height(obj1.height());
	}
}

function contBoxView(id,type){
	
	$("." + id + "_sub").hide();
	$("#" + id + type+ "_sub").show();
	if (type == '3'){
		oEditors.getById["content"].exec("MSG_EDITING_AREA_RESIZE_STARTED", []); 
		oEditors.getById["content"].exec("RESIZE_EDITING_AREA", [0, 380]); //타입은 px단위의 Number입니다.
		oEditors.getById["content"].exec("MSG_EDITING_AREA_RESIZE_ENDED", []); 
		//에디터를 둘러싼 iframe 사이즈 변경 
		oEditors.getById["content"].exec("SE_FIT_IFRAME", []); 
		
	}

	if(parent!=undefined && $("#framecontents",parent)!=null && $("#frameBody")!=null ){
		parent.setIframeSize("framecontents","100%",$("#frameBody").height());
	}
}

function contBranchBoxView(id,type){
	
	$("." + id + "_sub").hide();
	$("#" + id + type+ "_sub").show();
	$("#chk_flag").val("");
	$("#SET_USER_ID").val("");
	$("#SET_USER_NAME").val("");
	
	if (type == '3'){
		oEditors.getById["content"].exec("MSG_EDITING_AREA_RESIZE_STARTED", []); 
		oEditors.getById["content"].exec("RESIZE_EDITING_AREA", [0, 380]); //타입은 px단위의 Number입니다.
		oEditors.getById["content"].exec("MSG_EDITING_AREA_RESIZE_ENDED", []); 
		//에디터를 둘러싼 iframe 사이즈 변경 
		oEditors.getById["content"].exec("SE_FIT_IFRAME", []); 
		
	}

	if(parent!=undefined && $("#framecontents",parent)!=null && $("#frameBody")!=null ){
		parent.setIframeSize("framecontents","100%",$("#frameBody").height());
	}
}

function contBoxViewWin(id,type,w,h){
	
	$("." + id + "_sub").hide();
	$("#" + id + type+ "_sub").show();
	$("#chk_flag").val("");
	$("#SET_USER_ID").val("");
	$("#SET_USER_NAME").val("");

	window.resizeTo(w,h);
}

var addItemObj = function(func,id){
	
	this.funcName = func;
	this.rootObj =  $("#" + id);
	this.cloneObj = $("li:first-child",this.rootObj);
	this.subObj= $("li",this.rootObj);
	this.total = this.subObj.length;
	this.last_n = this.total +1;
	this.rootObj.sortable({afterReset:function (){ this_s.setOrder();}});

	var this_s = this;

	this.setOrder = function(){
		this.subObj= $("li",this.rootObj);
		//키보드 및 버튼 액션 초기화
		$(this.subObj).unbind("keydown");
		$(this.subObj).unbind("keyup");
		$(".isDelBtn",this.subObj).unbind("click");
		$(".isAddBtn",this.subObj).unbind("click");
		
		for (i=0;i<this.subObj.length ;i++ )
		{
			
			var n = i+1;
			$(this.subObj[i]).attr("n",n);

			//모든 Input 요소 Name, Value 셋팅
			var inputObjs = $("input",$(this.subObj[i]));
			for (j=0;j<inputObjs.length ;j++ )
			{
				if($(inputObjs[j]).attr("fname")!="" && $(inputObjs[j]).attr("fname")!=undefined){
					$(inputObjs[j]).attr("name",$(inputObjs[j]).attr("fname")+"["+n+"]");
				}
			}
			
			$(".isDelBtn",this.subObj[i]).attr("href","javascript:"+this.funcName+".Del("+(i+1)+")");
			$(".isAddBtn",this.subObj[i]).attr("href","javascript:"+this.funcName+".Add("+(i+1)+")");

		}
	


		var this_s = this;
		//$(this.subObj).bind("keydown",function(e){return this_s.setKeyDown(this,e)});
		//$(this.subObj).bind("keyup",function(e){return this_s.setKeyUp(this,e)});


		return null;


	}
	
	this.Add = function(n){
		var this_s = this;
		var obj = this.cloneObj.clone();
	

		$("input[type='text']",$(obj)).val("");
		$("input[type='checkbox']",obj).attr("checked",false);
		var targetObj = (n!="")? $("li:nth("+(parseInt(n)-1)+")",this.rootObj): $("li:last",this.rootObj);
		$(targetObj).after(obj);

		$(obj).attr("islock","0");
		//$(obj).attr("initVal","0");
		$("input[initVal='1']",$(obj)).val("");
		$("input[type='text']:nth(0)",$(obj)).focus();
		
		this.setOrder();
		
	},

	this.Del = function(n){
		

		if($("li",this.rootObj).length<2) {alert("더이상 삭제하실 수 없습니다.");return;}
		var obj = $("li:last",this.rootObj);
		
		if(n=="")	var obj = $("li:last",this.rootObj);
		else			var obj =$("li:nth("+(parseInt(n)-1)+")",this.rootObj);

		if($(obj).attr("islock")=="1") {alert("삭제하실 수 없는 항목입니다");return;}
		else $(obj).remove();
		
		
		this.setOrder();
		$("input:nth("+n+")",this.rootObj).focus();
	}

	this.setOrder();
	return this;

}


function set_option(obj,idx,t_name,t_value){
	if (obj.length<1) obj.length=1;
	obj.options[idx].text = t_name;
	obj.options[idx].value= t_value;
}

function add_option(obj,t_name,t_value){
	var idx = obj.length;
	obj.length= idx + 1;

	obj.options[idx].text = t_name;
	obj.options[idx].value= t_value;
}
function set_select(obj_id,t_name){
	var obj = document.getElementById(obj_id);
	obj.length = 1;
	obj.options[0].text = t_name;
	obj.options[0].value="";
}

function in_array(arr,str){
	for ( var i=0;i<arr.length;i++ )
	{
		if(arr[i]==str) return true;
	}
	return false;
}
//셀렉트박스 옵션 삭제
function select_list_del(list_obj)
{
	if (typeof list_obj!="object") var list_obj = document.getElementById(list_obj);

	if (list_obj.length==0)
	{
		alert("삭제할 항목이 없습니다.");
		return;
	}
	if (list_obj.value=="")
	{
		alert("삭제할 항목을 선택해주세요");
		list_obj.focus();
		return;
	}
	var chk_del_str = "";
	chk_del_str += "선택한 항목을 목록에서 제외하시겠습니까?";
	var chk_del = confirm(chk_del_str);

	if (chk_del==true)
	{
		tot_len = list_obj.length;
		for (j=tot_len-1;j>=0;j--)
		{
			if (list_obj.options[j].selected==true)
				list_obj.remove(j);
		}
	}

}

//멀티 셀렉트 전체 선택
function multi_select_all(obj_id){
	var obj =  document.getElementById(obj_id);
	for (i=0;i<obj.length;i++ ){
		obj.options[i].selected=true;
	}
}


function setIframeSize(obj,w,h){
	$("#" +obj).width(w).height(h);
}


function check_mail(){
	var frm = document.forms['theFrom'];
	
	if (frm.EMAIL_LIST.value!="" && frm.EMAIL_LIST.value!="self") {
		frm.EMAIL_DOMAIN.style.display = 'none';
		frm.EMAIL_DOMAIN.value = frm.EMAIL_LIST.value;
	} else if (frm.EMAIL_LIST.value=="self") {
		frm.EMAIL_DOMAIN.value = '';
		frm.EMAIL_DOMAIN.style.display = '';
		frm.EMAIL_DOMAIN.focus();
	}
}

function msgPopupWinClose(a){
	if(a=="")a="slow";$(".pop_windoc").fadeOut(a)
}

function doMultiDelete(s_mode){
	var fm = document.forms['theFrom'];	
	var Item = chk_check();
	Item.split(",");

	if(Item.length > 0){
		if(confirm("삭제하시겠습니까?")){
		fm.mode.value	 = s_mode;
		fm.sel_idx.value = Item;
		fm.submit();
		return true;
			}else return false;
	}else{
		alert("삭제할 데이터를 선택 하세요!");
		return false;
	}
	
	return false;
}


var oldText = "";

function replaceComma(str) { // 콤마 없애기 
	while(str.indexOf(",") > -1) { 
		str = str.replace(",", ""); 
	} 
	return str; 
} 

function numChk(num){

	var rightchar = replaceComma(num.value);
	var moneychar = "";

	for(index = rightchar.length-1; index>=0; index--){
		splitchar = rightchar.charAt(index);
		if (isNaN(splitchar)) {
			alert(splitchar +"는 숫자가 아닙니다. \n다시 입력해주세요");
			num.value = "";      //num.value = oldText; 이전text반환
			num.focus();
			return;
		}
		moneychar = splitchar+moneychar;
		if(index%3==rightchar.length%3&&index!=0){ moneychar=','+moneychar; }
	}
	oldText = moneychar;
	num.value = moneychar;
}
