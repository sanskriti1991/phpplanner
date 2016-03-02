<!--
Yoffset= 20;    // change the popup position.
Xoffset= 0;    // modify these values to ...


var nav,old,iex=(document.all),yyy=-1000;
if(navigator.appName=="Netscape"){(document.layers)?nav=true:old=true;}

if(!old){
	var skn=(nav)?document.dek:dek.style;
if(nav)document.captureEvents(Event.MOUSEMOVE);
	document.onmousemove=get_mouse;
}

function popup(msg){
var content= "<TABLE BORDER=\"1\" BORDERCOLOR=\"black\" CELLPADDING=\"0\" CELLSPACING=\"0  style='border : 1;filter:progid:DXImageTransform.Microsoft.dropShadow(Color=CCCCCC,offX=2,offY=2,positive=true);overflow:hidden'><TR class=\"popup\"><TD ALIGN=\"left\" nowrap class=\"popup\"><FONT size=\"1\" face=\"verdana\">"+msg+"</FONT></TD></TR></TABLE>";
if(old){
	alert(msg);
	return;
} 
else{yyy=Yoffset;
 if(nav){
 	skn.document.write(content);
	skn.document.close();
	skn.visibility="visible"
 }
 if(iex){
 	document.all("dek").innerHTML=content;
	skn.visibility="visible"
	}
 	Xoffset = document.all("dek").offsetWidth/2*(-1);
 }
}

function get_mouse(e){
      var x=(nav)?e.pageX:event.x+document.body.scrollLeft;
	  skn.left=x+Xoffset;
      var y=(nav)?e.pageY:event.y+document.body.scrollTop;
	  skn.top=y+yyy;
}

function kill(){
      if(!old){yyy=-1000;skn.visibility="hidden";}
}
-->