
document.write('<script language="javascript" type="text/javascript" src="'+CMS_FOLDER+'/sms_js/jquery.js"></script>');
document.write('<script language="javascript" type="text/javascript" src="'+CMS_FOLDER+'/sms_js/common.js"></script>');
document.write('<script language="javascript" type="text/javascript" src="'+CMS_FOLDER+'/sms_js/ajax_lib.js"></script>');
document.write('<script language="javascript" type="text/javascript" src="'+CMS_FOLDER+'/sms_js/formcheck.js"></script>');
document.write('<script language="javascript" type="text/javascript" src="'+CMS_FOLDER+'/sms_js/utils_lab.js"></script>');
//document.write('<script language="javascript" type="text/javascript" src="'+CMS_FOLDER+'/sms_js/site.js"></script>');
document.write('<script language="javascript" type="text/javascript" src="'+CMS_FOLDER+'/sms_js/window.js"></script>');
function getEditorContent(str){
	var tmpCont = str;

	var tmp1 = tmpCont.substring(parseInt(str.indexOf("<body"))+5,  tmpCont.length);
	var tmp2 = tmp1.substring(parseInt(tmp1.indexOf(">")+1), tmp1.length);
    var pResult = tmp2.substring(0, tmp2.indexOf("</body>") );
	return pResult;
}


