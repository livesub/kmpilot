// JavaScript Document


/*
	모든 Check Box 선택을 반전 한다.
*/
function SelectAll(form) {
  var fm = form;
  var fme = fm.elements;

  var chkVal = fm.selectall.checked;

  for(i = 0; i < fme.length; i++) {
    if(fme[i].type == "checkbox" && fme[i].name == "chk[]" && fme[i].disabled == false) {
		fme[i].checked = chkVal;
	}
  }
}

function SelectOne(c,i){
	if(c.checked){
		$("#tr_"+i).addClass("bg_s");
	}else{
		$("#tr_"+i).removeClass("bg_s");
	}
}


// 리스트 모드에서 체크박스의 선택된 값만 가져온다.
function chk_check(){
	var l = document.getElementsByName("chk[]");
	var que = new Array();
	var i2 = 0;
	for (var i = 0; i < l.length; i++){
		if (l[i].checked == true){
			que[i2] = l[i].value;
			i2++;
		}
	}
	return que.join(",");
}
function chk_check2(n){
	var l = document.forms[n].elements;
	var que = new Array();
	var i2 = 0;
	for (var i = 0; i < l.length; i++){
		if (l[i].checked == true){
			que[i2] = l[i].value;
			i2++;
		}
	}
	return que.join(",");
}


/****************************************************************************/       
/* 두 항목의 텍스트 비교 함수                                               */       
/* 2000.11.21 commany                                                       */       
/* [회원등록에서 비밀번호와 재입력비밀번호의 텍스트를 비교]                 */       
/****************************************************************************/       
/* as_pre_fin는 이전 항목을 말한다.                                         */       
/* as_fin는 현재 포커스가 있는 항목을 말한다.                               */       
function f_compare_text(as_pre_fin,as_fin){
	if(isValid_passwd(as_fin)){
		if (as_pre_fin.value && as_fin.value){
			if (as_pre_fin.value != as_fin.value){                                               
				alert('방금전에 입력한 비밀번호와 같지 않습니다. \n다시 입력해 주시기 바랍니다.');
				as_fin.value = '';                                                                
				as_fin.focus();                                                            
				return false;                                                                     
			}else{
				return true;                                                                      
			}
		}
	}
}




function selCtrlAdd(obj_id, txt_id)
{
	var obj_sel = document.getElementById(obj_id);//$("#"+obj_id);
	var obj_txt = $("#"+txt_id);

	if (obj_txt.val().length == 0) {
		alert ("추가할 항목을 입력해주세요.");
		return;
	}

	for (i=0; i<obj_sel.options.length; i++) {
		if (obj_txt.val().trim() == obj_sel.options[i].val()) {
			alert ("이미 동일한 항목이 추가되어 있습니다.");
			return;
		}
	}

	var new_opt = new Option;
	new_opt.value = obj_txt.val();
	new_opt.text = obj_txt.val();
	var new_idx = obj_sel.options.length;
	obj_sel.options[new_idx] = new_opt;
	obj_sel.selectedIndex = new_idx;

	obj_txt.val("");
}


function selCtrlRemove(obj_id)
{
	var obj = document.getElementById(obj_id);

	if (obj.selectedIndex < 0) {
		alert ("삭제할 항목을 선택해 주세요.");
		return;
	}

	var cur_idx = obj.selectedIndex;
	obj.options.remove(cur_idx);
}