<?php


//Um die Bilder zu finden HTTP_ROOT ;-)
require_once "../../common.php";

//Sagen, dass dies eine CSS_Datei ist!
header("Content-type: text/css");
?>
 
body{
	font-size:9pt;
	font-family:monospace;
	background-image:url(<?php echo HTTP_ROOT."/showroom/images/contentBackground.gif"; ?>);
    background-repeat:repeat-y;
    margin:0px;
    padding:0px;
}

.headline{
  
  	font-weight:bold;
  	font-family:Verdana;
  	width:100%;
  	border-bottom:1px solid gray;
  	background-color:#dedede;
  	background-image:url(<?php echo HTTP_ROOT."/showroom/images/contentHeadlineBackground.gif"; ?>);
    background-repeat:repeat-y;
    padding-left:5px;
    height:40px;
	
}

.code{
  
  padding:5px;
  overflow:auto;
  height:550px;
  	
}


.subHeadline{
	
	width:100%;
	color:steelblue;
	border-top:2px dotted gray;
	background-color:white;
	margin-top:10px;
	background-image:url(<?php echo HTTP_ROOT."/showroom/images/showroom_separator.gif"; ?>);
    background-repeat:repeat-x;
	font-size:10pt;
	
	
}











