
//사이트 등록하기
function openSiteAddForm(){
	frmWin = new msgPopupWin({w:"700px",h:"350px",setStyle:true,title:"사이트 등록"});
	$(frmWin.bodyPannel).load("./site.site_cfg.php?prcCode=ajax",function (){frmWin.setCloseBtns();});
	
}
//사이트 수정하기
function openSiteModifyForm(site_code){
	frmWin = new msgPopupWin({w:"700px",h:"450px",setStyle:true,title:"사이트 수정"});
	$(frmWin.bodyPannel).load("./site.site_cfg.php?prcCode=ajax&sCode="+site_code,function (){frmWin.setCloseBtns();});
}

//사이트 삭제하기
function openSiteDeleteForm(site_code){
	frmWin = new msgPopupWin({w:"400px",h:"200px",setStyle:true,title:"사이트 삭제"});
	$(frmWin.bodyPannel).load("./site.site_delete.php?sCode="+site_code,function (){frmWin.setCloseBtns();});
}

//사이트 레이아웃 설정 수정하기
function openSiteLayoutForm(site_code){
	frmWin = new msgPopupWin({w:"1000px",h:"680px",setStyle:true,title:"레이아웃 설정"});
	$(frmWin.bodyPannel).load("./site.site_layout.php?sCode="+site_code,function (){frmWin.setCloseBtns();});
}
function reopenSiteLayoutForm(){
	var sCode=$('#sel_sCode option:selected').val();
	frmWin.close();
	openSiteLayoutForm(sCode);
}

//서브 메뉴별 레이아웃 설정
function setSubGroupLayout(sCode,pCode){
	if(pCode==""){
		$("#subGroupLayout").load("./site.site_layout_sub_default.php?sCode="+sCode,function(){});
	}else{
		$("#subGroupLayout").load("./site.site_layout_page.php?sCode="+sCode+"&pCode="+pCode,function(){});
	}
}

//서브 메뉴 상단 메뉴 관리
function setSubGroupLayout(sCode,pCode){
	if(pCode==""){
		$("#subGroupLayout").load("./site.site_layout_sub_default.php?sCode="+sCode,function(){});
	}else{
		$("#subGroupLayout").load("./site.site_layout_page.php?sCode="+sCode+"&pCode="+pCode,function(){});
	}
}

//사이트 코드 중복체크
function siteCodeCheck(site_code){
	try
	{
		$("#board_code_chk").html("<img src='"+CMS_FOLDER+"/_img/common/blit_loadingBit.gif' /> 중복 코드 검사 중...");
		var url		= "./site.site_action.php";
		$.post(url,{'prcCode':"ajax",'mode': 'site_code_check', 'sCode': site_code},function(r){
			switch(r.result){
				case 0:	//중복없음
					$("#site_code_chk").html("<img src='"+CMS_FOLDER+"/_img/icon/icon_check.gif' /> 등록 가능한 코드 입니다.");
					break;
				case 1:	//중복
					$("#site_code_chk").html("<img src='"+CMS_FOLDER+"/_img/icon/icon_ext.gif' /> 등록된 코드 있음");
					break;
				case 2:	//중복
					$("#site_code_chk").html("<img src='"+CMS_FOLDER+"/_img/icon/icon_ext.gif' /> 등록된 폴더 있음");
					break;
				default:break;
			}

		},"json");

	}
	catch(e){alert(e);}
}

function doSiteAction(){
	var fm = document.forms['frm_sitereg'];
	if(FormCheck(fm)){
		return true;
	}
	return false;
}

function doSiteDeleteAction(){
	try
	{
		var del_Code = $("#sCode").val();
		if(del_Code){
			$("#process_msg").html("<img src='"+CMS_FOLDER+"/_img/common/blit_loadingBit.gif' /> 처리중입니다...");
			var url		= "./site.site_action.php";
			$.post(url,{'prcCode':"ajax",'mode': 'site_delete','sCode': del_Code},function(r){
				switch(r.result){
					case 0:	//삭제완료
						$("#btn_delete").hide();
						$("#btn_cancel").hide();
						$("#btn_close").show();
						$("#process_msg").html("<img src='"+CMS_FOLDER+"/_img/icon/icon_check.gif' /> 정상적으로 삭제 처리 되었습니다.");
						break;
					case 1:	//삭제 않됨.
						$("#process_msg").html("<img src='"+CMS_FOLDER+"/_img/icon/icon_ext.gif' /> 삭제가 되지 않았습니다.");
						break;
					case 2:	//삭제 않됨.
						$("#process_msg").html("<img src='"+CMS_FOLDER+"/_img/icon/icon_ext.gif' /> 폴더가 삭제가 되지 않았습니다.");
						break;
					default:break;
				}
			},"json");
		}else{
			$("#btn_delete").hide();
			$("#btn_cancel").hide();
			$("#btn_close").show();
			$("#process_msg").html("<img src='"+CMS_FOLDER+"/_img/icon/icon_ext.gif' /> 삭제할 사이트가 지정되지 않았습니다.");
		}
	}
	catch(e){alert(e);return false;}
}

function setFooterLayout(){
	var footer = $("#prevFooter");
	var flogo = $("#prevFlogo");
	var copyright = $("#prevFcopy");
	var flinker = $("#prevFlinker");
	footer.addClass("gridBg");
	footer.css({"height":"100px","border":"1px dotted red","overflow":"hidden"});
	flogo.css({"position":"absolute","height":"40px","width":"100px","border":"1px solid blue","background":"#f8f8f8"});
	copyright.css({"position":"absolute","height":"40px","width":"300px",left:"180px","border":"1px solid blue","background":"#f8f8f8"});

	$("#prevFooter").resizable();
	$(flogo).resizable();

	$(flogo).draggable({
		start: function() {
			$(this).addClass("dragStart");
		},
		drag: function() {
			$("#footerInfo").html("[LOGO] left :"+getRelativeOffset(footer,$(this)).left + " / top : " +getRelativeOffset(footer,$(this)).top);
		},
		stop: function() {
			$(this).removeClass("dragStart");
			$("#footerInfo").html("[LOGO] left :"+getRelativeOffset(footer,$(this)).left + " / top : " +getRelativeOffset(footer,$(this)).top);
		}
	});

	$(copyright).draggable({
		start: function() {
			$(this).addClass("dragStart");
		},
		drag: function() {
			$("#footerInfo").html("[LOGO] left :"  + getRelativeOffset(footer,$(this)).left + " / top : " +getRelativeOffset(footer,$(this)).top);
		},
		stop: function() {
			$(this).removeClass("dragStart");
			//$("#footerInfo").html("[LOGO] left :"  + getRelativeOffset(footer,$(this)).left + " / top : " +getRelativeOffset(footer,$(this)).top);
		}
	});
}

//하단 링크 컨텐츠 미리보기
function prevFootAreaContents(){
}

//링크 컨텐츠 관리 
var linkObj = {
	rootObj:null,
	total:0,last_n:0,subObj:null,ctrl:false,
	init:function(id){
		var this_s = this;
		this.rootObj = $("#" + id);
		this.cloneObj = $("li:first-child",this.rootObj);
		this.subObj= $("li",this.rootObj);
		this.total = this.subObj.length;
		this.last_n = this.total +1;
		this.setOrder();
		this.rootObj.sortable({afterReset:function (){ this_s.setOrder();}});
		var this_s = this;
	},
	setOrder:function(obj_id){
		this.subObj= $("li",this.rootObj);

		//키보드 및 버튼 액션 초기화
		$(this.subObj).unbind("keydown");
		$(this.subObj).unbind("keyup");
		$(".isDelBtn",this.subObj).unbind("click");
		$(".isAddBtn",this.subObj).unbind("click");
	
		for (i=0;i<this.subObj.length ;i++ ){
			$(this.subObj[i]).attr("n",i+1);
			$(".isField1 input[type='hidden']:nth(0)",this.subObj[i]).val(i+1);

			$(".isField1 input.isLock",this.subObj[i]).attr("name","link_lock["+(i+1) +"]");
			if($(this.subObj[i]).attr("islock")==1){
				$(".isField1 input.isLock",this.subObj[i]).val("1");
			}else{
				$(".isField1 input.isLock",this.subObj[i]).val("0");
			}
			$(".isField1 input[type='hidden']:nth(2)",this.subObj[i]).attr("name","link_orgSeq["+(i+1) +"]");
	
			//$(".isField1 input[type='hidden']:nth(1)",this.subObj[i]).val(i+1);
			$(".isField2 select",this.subObj[i]).attr("name","link_type["+(i+1) +"]");
			$(".isField2 input[type='text']",this.subObj[i]).attr("name","link_title["+(i+1) +"]");
			$(".isField3 input[type='text']",this.subObj[i]).attr("name","link_url["+(i+1) +"]");
			$(".isField4 input[type='file']",this.subObj[i]).attr("name","link_file["+(i+1) +"]");
			$(".isField5 input[type='file']",this.subObj[i]).attr("name","link_file_o["+(i+1) +"]");

			$(".isField6 input[type='checkbox']:nth(0)",this.subObj[i]).attr("name","link_use["+(i+1) +"]");
			$(".isField6 input[type='checkbox']:nth(1)",this.subObj[i]).attr("name","link_target["+(i+1) +"]");
			
			$(".isField7 a.isDelBtn",this.subObj[i]).attr("href","javascript:linkObj.Del("+(i+1)+")");
			$(".isField7 a.isAddBtn",this.subObj[i]).attr("href","javascript:linkObj.Add("+(i+1)+")");
		}
		var this_s = this;
		$(this.subObj).bind("keydown",function(e){return this_s.setKeyDown(this,e)});
		$(this.subObj).bind("keyup",function(e){return this_s.setKeyUp(this,e)});

		return null;
	},
	setKeyDown:function(obj,e){
		if(e.which==17) this.rootObj.ctrl = true;
		 if(this.rootObj.ctrl && e.which == 13){
			 //순서체크
			 var chkObj = e.target.parentNode.parentNode;
			 var n = $(chkObj).attr("n");
			 this.Add(n);
			 return false;
		 }
		  if(this.rootObj.ctrl && e.which == 46){
			 //순서체크
			 var chkObj = e.target.parentNode.parentNode;
			 var n = $(chkObj).attr("n");
			 this.Del(n);
			 return false;
		 }
	},
	setKeyUp:function(obj,e){
		if(e.which==17) this.rootObj.ctrl = false;
	},
	Add:function(n){
		var this_s = this;
		var obj = this.cloneObj.clone();
	
		$("input[type='text']",$(obj)).val("");
		$("input[type='checkbox']",obj).attr("checked",false);
		var targetObj = (n!="")? $("li:nth("+(parseInt(n)-1)+")",this.rootObj): $("li:last",this.rootObj);
		$(targetObj).after(obj);
		
		$(obj).attr("islock","0");
		$(".isField1 input[type='hidden']:nth(2)",$(obj)).val("");

		$(".isField4",$(obj)).html('<input type="file" class="file" size="10" /> ');
		$(".isField5",$(obj)).html('<input type="file" class="file" size="10" /> ');
		$("input[type='text']:nth(1)",$(obj)).focus();
		this.setOrder();
	},
	Del:function(n){
		if($("li",this.rootObj).length<2) {alert("더이상 삭제하실 수 없습니다.");return;}
		var obj = $("li:last",this.rootObj);
		
		if(n=="") var obj = $("li:last",this.rootObj);
		else var obj =$("li:nth("+(parseInt(n)-1)+")",this.rootObj);

		if($(obj).attr("islock")=="1") {alert("삭제하실 수 없는 항목입니다");return;}
		else $(obj).remove();

		this.setOrder();
		$("input:nth("+n+")",this.rootObj).focus();
	}
}


function delLinkImg(field,idx){
	if(confirm("파일을 삭제하시겠습니까?\n삭제하신 후에는 다시 복구하실 수 없습니다.")){
		$.ajax({
			type:"post",url:"./site.site_action.php",data:"prcCode=ajax&mode=delLinkImg&linkField="+field+"&linkIdx="+idx,
			 success: function(msg){
				$("#" +field+"_"+idx).remove();
				$("#"+field+"_file_"+idx).removeClass("hidden");
		   }
		});
	}
}


function SiteWorkTypeView(cl,id){
	$("." + cl + "_sub").hide();
	if(id==0){
		$("#site_W_sub").show();
		if($("#site_work_type_S").attr("checked")){
			setStyleLayer2('site_W',0);
		}
	}
}