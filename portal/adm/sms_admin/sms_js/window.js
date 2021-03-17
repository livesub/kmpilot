// JavaScript Document
<!--

function window_resize(winWidth, winHeight) {

	// 스크롤 없애기
	document.body.style.overflow='hidden';

	var clintAgent = navigator.userAgent;
	var foxHeight = 80;
	var ieHeight = 60;
	var ie7Height = 80;

	if ( clintAgent.indexOf("MSIE") != -1 )	// IE 일 경우
	{
		var res = isIE7();
		if( res ) {
			window.resizeTo(winWidth, winHeight + ie7Height);
		} else if (clintAgent.indexOf("MSIE 8") != -1) {
			window.resizeTo(winWidth, winHeight + ie7Height);
		} else {
			window.resizeTo(winWidth, winHeight + ieHeight);
		}
	}
	else	// IE 가 아닐 경우
	{
		window.resizeTo(winWidth, winHeight + foxHeight);
	}
}

// 레이어 트랜지션
function doTrans(obj) {

	if(document.all) {

		f = new Array();
		f[0] = "Slide(slidestyle=HIDE,Bands=1)";                                // PUSH, SWAP
		f[1] = "Spiral(GridSizeX=8,GridSizeY=8)";
		f[2] = "Stretch(stretchstyle=SPIN)";                                    // HIDE, PUSH
		f[3] = "Strips(motion=leftdown)";                                       // leftup, rightdown, rightup
		f[4] = "Wheel(spokes=10)";                
		f[5] = "Zigzag(GridSizeX=8,GridSizeY=8)";
		f[6] = "Barn(motion=out,orientation=vertical)";                         // horizontal
		f[7] = "Blinds(Bands=10,direction=left)";                               // right, up, down
		f[8] = "Checkerboard(Direction=right,SquaresX=2,SquaresY=2)";           // up, down, left
		f[9] = "Fade(Overlap=1.00)";                                            // 0.75, 0.50
		f[10]= "GradientWipe(GradientSize=0.25,wipestyle=0,motion=forward)";    // style: 0, 1 motion : reverse
		f[11]= "Inset()";
		f[12]= "Iris(irisstyle=PLUS,motion=out)";                               // circle, diamond, cross, square, star
		f[13]= "Pixelate(MaxSquare=50)";
		f[14]= "RadialWipe(wipestyle=CLOCK)";                                   // wedge, radial
		f[15]= "RandomBars()";
		f[16]= "RandomDissolve()";
		
		date = new Date();
		
		effect = date.getSeconds() % f.length;
		obj.style.filter = "progid:DXImageTransform.Microsoft." + f[effect];
		obj.filters[0].apply();
		obj.style.visibility = "hidden";
		obj.filters[0].play();

	} else {
	
		obj.style.visibility = "hidden";
	
	}
}

function Speam_No() { //v2.0
	var openFile = 'include/nomail.php';
	var Selcol = showModalDialog("" + openFile + "","Speam_No","font-size:12; dialogWidth:605px; dialogHeight:380px; center:yes; status:no; help:no; scroll:no"); 
}

function openWindow(name, url, left, top, width, height, toolbar, menubar, statusbar, scrollbar, resizable) {
	toolbar_str = toolbar ? 'yes' : 'no';
	menubar_str = menubar ? 'yes' : 'no';
	statusbar_str = statusbar ? 'yes' : 'no';
	scrollbar_str = scrollbar ? 'yes' : 'no';
	resizable_str = resizable ? 'yes' : 'no';
	return window.open(url, name, 'left='+left+',top='+top+',width='+width+',height='+height+',toolbar='+toolbar_str+',menubar='+menubar_str+',status='+statusbar_str+',scrollbars='+scrollbar_str+',resizable='+resizable_str);
}
function create_popup( url, formname, width, height )
{
	_popup = getPopUpFlag( openWindow(formname, url, getCenterX(width, "window"), getCenterY(height, "window"), width, height, 0, 0, 0, 0, 0) );
}
function openWindowModal(name, url, left, top, width, height, toolbar, menubar, statusbar, scrollbar, resizable) {
	toolbar_str = toolbar ? 'yes' : 'no';
	menubar_str = menubar ? 'yes' : 'no';
	statusbar_str = statusbar ? 'yes' : 'no';
	scrollbar_str = scrollbar ? 'yes' : 'no';
	resizable_str = resizable ? 'yes' : 'no';
	showModalDialog(url, name, "font-family:Verdana; font-size:12; dialogWidth:" + width + "px; dialogHeight:" + height + "px; dialogLeft:" + left + "px; dialogTop:" + top + "px; status:" + statusbar_str + "; help:no; scroll:" + scrollbar_str + "");

}
function getCenterX(width) {

	var left = (screen.width/2) - (width/2);

	return left;
}


function getCenterY(height) {

	var top = (screen.height/2) - (height/2);

	return top;
}


function getCenterX2(width, type) {

	var an = getNavigator();
	var left = 0;
	
	if(type == "window") {

		if(an == "IE")
			left = (screen.width/2) - (width/2);
		else
			left = (self.window.innerWidth/2) - (width/2);

	} else if(type == "layer") {

		if(an == "IE")
			left = (document.body.clientWidth/2) - (width/2);
		else
			left = (self.window.innerWidth/2) - (width/2);
	
	} else {

		left = 0;
	}


	if(left < 0)
		left = 0;

	return left;
}



function getCenterY2(height, type) {

	var an = getNavigator();
	var top = 0;
	
	if(type == "window") {

		if(an == "IE")
			top = (screen.height/2) - (height/2);
		else
			top = (self.window.innerHeight/2) - (height/2);

	} else if(type == "layer") {

		if(an == "IE")
			top = (document.body.clientHeight/2) - (height/2);
		else
			top = (self.window.innerHeight/2) - (height/2);
	
	} else {

		top = 0;
	}


	if(top < 0)
		top = 0;

	return top;
}

function getPopUpFlag(win)
{
	try{ win.focus(); return win; }
	catch(e)
	{
		//if (confirm('\n팝업(PopUp)이 차단되어 있습니다.\n\n팝업차단을 풀어주셔야 요청하신 기능을 수행할 수 있습니다.               \n\n팝업 차단관련 안내페이지로 이동하시겠습니까?'))
		//{
		//	location.href = "";
		//}
		alert('\n팝업(PopUp)이 차단되어 있습니다.\n\n팝업차단을 풀어주셔야 요청하신 기능을 수행할 수 있습니다.               ');
	}
}
function Show_Open(url, w, h) {
	openWindow('_open', url, getCenterX(w), getCenterY(h), w, h, 0, 0, 0, 0, 0);
}
function Show_Open2(url, w, h) {
	openWindow('_open', url, getCenterX(w), getCenterY(h), w, h, 0, 0, 0, 1, 0);
}
function Show_Open3(url, w, h) {
	openWindow('_open', url, getCenterX(w), getCenterY(h), w, h, 0, 0, 0, 1, 1);
}
function Show_Open4(url, w, h, name) {
	openWindow(name, url, getCenterX(w), getCenterY(h), w, h, 0, 0, 0, 0, 0);
}

//	function OpenPostSearch(post1, post2, addr, nextaddr)
//	{
//		openWindow('_zipcode', './?MGubn=zipcode&post_type=zip&fld_name1=' + post1 + '&fld_name2=' + post2 + '&fld_name3=' + addr + '&fld_name4=' + nextaddr, getCenterX(500), getCenterY(250), 500, 250, 0, 0, 0, 0, 0);
//	}

function OpenPostSearch(from, post, addr){
	PopupPost = openWindow('_zipcode', 'global.zipcode.php?form_name='+from+'&zip_field='+post+'&addr_field='+addr, getCenterX(400, "window"), getCenterY(250, "window"), 400, 250, 0, 0, 0, 1, 0);
}
function OpenPostSearchReg(from, post, addr, target){
	PopupPost = openWindow('_zipcode', 'global.zipcode.php?form_name='+from+'&zip_field='+post+'&addr_field='+addr+'&target='+target, getCenterX(400, "window"), getCenterY(250, "window"), 400, 250, 0, 0, 0, 1, 0);
}

function OpenMemberSearch(url, w, h){
	PopupMember = openWindow('_member',url, getCenterX(w), getCenterY(h), w, h, 0, 0, 0, 1, 0);
}


function SMS_Send( )
{
	openWindow('fix_sms','/Body/SMS',getCenterX(550),getCenterY(450), 550, 450,0, 0, 0, 0, 0);
}

/*
	윈도우 OPEN 
*/
function wopen(tar_url, win_name, w, h) {
	if(trim(tar_url) != '') {
		openWindow(win_name, tar_url, getCenterX(w), getCenterY(h), w, h, 0, 0, 0, 0, 0);
	}
	return;
}
//-->
