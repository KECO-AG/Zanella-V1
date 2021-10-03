<?php


//Um Bilder besser zu finden
require_once "../../common.php";

//Create the CSS file
header("Content-type: text/css");
?>
body {
	background-color: white;
	font-size: 0.8em;
	font-family: Verdana, Arial, SunSans-Regular, Sans-Serif;
	color: #404040;
	padding:0;
	margin:0;
}
a {color: #404040;}
a:visited {color:#404040;}
a:hover {color: #000000;}
a:active { color:#000000;}


h1 {
	font-size: 2em;
	font-weight: normal;
 }

h2 {
	font-size: 1.7em;
	font-weight: normal;
}

h3 {
	font-size: 1.4em;
	font-weight: normal;
}

img.download {vertical-align:middle;}

/* ----------container zentriert das layout-------------- */
#container {
	width: 80em;
	margin: 0 auto;
	padding: 0;
	background-color: #C0C0C0;
	border: 2px solid grey;
}

/* ----------banner for logo-------------- */
#banner {
	margin: 0;
	padding: 0;
	background-color: #B0B0B0;
}
#banner p{
	font-size: 2em;
	font-weight: bold;
	margin-left: 10em;
	padding: 25px;
}
#mainnavi {
	margin: 0;
	padding: 0;
	background-color: #C0C0C0;
}

#mainnavi p{
	margin-left: 20em;
	padding: 0 25px;
}

/* -----------------Inhalt--------------------- */
#message {
	padding: 25px;
	color: red;
	font-weight: bold;
}
#message p{
	padding: 5px;
	border: 2px solid red;
}
#content {
	min-height:600px;
	min-width: 600px;
	background-color: #ffffff;
	padding: 0;
	margin: 0 0 0 20em;
	overflow:hidden;
}

p, pre {
	padding: 0 20px 15px 25px;
	margin:0;
}
pre, code { font-size: 1.2em;}

h1 {
	padding: 15px 25px;
	margin:0;
}
h2 {
	padding: 5px 25px;
	margin:0;
}
h3 {
	padding: 5px 25px;
	margin:0;
}

.gross {
	width: 5.71em;
	height: 1.07em;}

/* --------------left navigavtion------------- */
#left {
	float: left;
	width: 20em;
	margin: 0;
	padding:15px 0 0 0;
	color:#C0C0C0;
}
#left ul{
	list-style-type: none ;
	padding: 0 0 0 25px;
	margin: 0;
}
#left ul.borderbottom{
	border-bottom:1px solid #ffffff;
	padding: 0 0 10px 25px;
}

#left li a {
	font-size: 1.3em;
	text-decoration:none;
}
#left  li li a { font-size: 1em; }
#left li{
	margin: 0;
	padding: 0 0 4px 0;
}
#left li li{
	margin: 0;
	padding: 0 0 3px 0;
}
#left li a.selected {
	margin: 0;
	padding: 0 0 0 25px;
	color:#ffffff;
}

#left li li a.selected {
	margin: 0;
	padding: 0;
}


/* -----------footer--------------------------- */
#footer {
	clear: left;
	background-color: #B0B0B0;
	padding: 0;
	margin: 0;
	}
#footer p {
	margin-left: 20em;
	padding: 0 25px;
}
/* ---- Total Rechner ---- */


/* ----   ----*/
fieldset {
   margin-left:25px;
   padding:2px;
   width:500px;
   border:1px solid grey;
}

table {
   padding: 25px;
   font-size: 13px;
   font-family: Verdana, Arial, Helvetica, sans-serif;
}

table.grey {
	margin-left: 25px;
	border-width: 1px;
	border-spacing: ;
	border-style: hidden;
	border-color: gray;
	border-collapse: collapse;
	background-color: white;
}
table.grey th {
	border-width: 1px;
	border-style: inset;
	border-color: gray;
	background-color: #999;

	-moz-border-radius: ;
	font-family: "Arial Black", Gadget, sans-serif;
	font-size: 14px;
	color: #333;
	text-align: left;
}
table.grey td {
	border-width: 1px;
	border-style: inset;
	border-color: gray;
	-moz-border-radius: ;
}
tr.total td {
	background-color: #CCC;
	font-weight: bold;
}
td.total {
	background-color: #CCC;
	font-weight: bold;
}

/* Für Formular ohne Ränder und Abstände */
#noSpaces{
  padding:0px;
  margin:0px;
}



/* Diese Stylesheets sind für die Zentrierung zuständig */
#centerBox {
  text-align:center;
  width:100%;
}

#centerObject {
  margin:0px auto;
}
input.standardField{
   border: 1px solid grey;
   background-color: white;
   font-family:Arial;
   font-size:100%;
   color:black;
   padding:2px;
   width:150px;
   margin:1px;
}

textarea.standardTextarea{
   border: 1px solid grey;
   background-color: white;
   font-family:Arial;
   font-size:100%;
   color:black;
   padding:2px;
   /*width:150px;*/
   margin:1px;
}


input.standardSubmit{
   border: 1px solid silver;
   cursor: pointer;
   font-weight:bold;
   background-color: #eeeeee;
   font-family:Arial;
   font-size:1em;
   color:black;
   padding:2px;
   margin:1px;
}

a.standardSubmit{
   border: 1px solid silver;
   cursor: pointer;
   font-weight:bold;
   background-color: #eeeeee;
   font-family:Arial;
   font-size:100%;
   color:black;
   padding:1px;
   margin:1px;
   text-decoration:none;
}

select.standardSelect{
   border: 1px solid silver;
   font-weight:bold;
   background-color: #eeeeee;
   font-family:Arial;
   font-size:100%;
   color:black;
   width:150px;
}

input.linkLookAlike{
   border: 0px;
   cursor: pointer;
   font-weight:bold;
   font-family:Arial;
   width:100%;
   background-color:transparent;
   text-align:left;
   font-size:100%;
   color:black;
}

#chapter{
  font-weight:bold;
  color:black;
  font-style:italic;
  font-size:12pt;
  border-bottom:1px solid steelblue;
  padding-left:3px;
  width:100%;

}

/* redips--- */

/* add bottom margin between tables */
#table1,
#table2 {
	margin-bottom: 20px;
}

/* drag container */
#drag {
	margin: auto;
	width: 800px;
}

/* set border for images inside DRAG region - exclude image margin inheritance */
/* my WordPress theme had some funny margin settings */
#drag img {
	margin: 1px;
}

/* drag objects (DIV inside table cells) */
.drag {
	cursor: move;
	margin: auto;
	margin-bottom: 1px;
	margin-top: 1px;
	z-index: 10;
	background-color: white;
	text-align: left;
	font-size: 10pt; /* needed for cloned object */
	width: 140px;
	/* height: 100px; */
	line-height: 20px;
	/* round corners */
	border-radius: 4px; /* Opera, Chrome */
	-moz-border-radius: 4px; /* FF */
}


/* drag objects border for the first table */
.t1 {
	border: 2px solid Red;
}
/* drag object border for the second table */
.t2 {
	border: 2px solid Blue;
}
/* cloned objects - third table */
.t3 {
	border: 2px solid Black;
}
/* allow / deny access to cells marked with 'mark' class name */
.mark {
	/*color: white;*/
	background-color: #9B9EA2;
}
/* trash cell */
.trash {
	color: white;
	background-color: #2D4B7A;
}

/* tables */
div#drag table {
	background-color: #e0e0e0;
	border-collapse: collapse;
}


/* input elements in dragging container */
div#drag input {
	cursor: auto;
}
	/* height for input text in DIV element */
	div#drag #d13 input {
		height: 13px;
	}
	/* height for dropdown menu in DIV element */
	div#drag #d5 select {
		height: 20px;
	}

/* table cells */
div#drag td {
	height: 50px;
	width: 148px;
	border: 1px solid white;
	text-align: center;
	vertical-align: top;
	font-size: 10pt;
	padding: 2px;
}

/* "Click" button */
.button {
	background-color: #6A93D4;
	color: white;
	border-width: 1px;
	width: 40px;
	padding: 0px;
}


/* toggle checkboxes at the bottom */
.checkbox {
	margin-left: 13px;
	margin-right: 14px;
	width: 13px; /* needed for IE ?! */
}


/* message cell */
.message_line {
	padding-left: 10px;
	margin-bottom: 3px;
	font-size: 10pt;
	color: #888;
}
