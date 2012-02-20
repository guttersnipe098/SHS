<?php
/*
Copyright (C) 2006 Michael Altfield <maltfiel _at_ spsu .dot. edu>

This file is part of Study Hard Software.

Study Hard Software is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Import the crib sheet
	The crib sheet is a file containing the questions and answers that Study Hard Software will pull in and display (ask) to the user.  The format of the crib sheet is as follows:
	
	question:answer
	question:answer
	
	...that is, each question and answer seperated by a colon (:), and each
	individual question&answer combination is seperated by a newline.
	
	Because the functioning of this application places
	the formentioned question into the questions js array (q[]) AND into the the answers js array (a[]), and
	the formentioned answer into the answers js array (a[]) AND into the questions js array (q[]),
	the string to the left of the colon on the crib sheet is not *necessarly* a "question" at all.
	The benefit of this is that the SHS asks you jepordy-like questions where it gives you the answer
	and you have to think of the question--for absolute preperation for that exam :).
	The result of this, however, is that we cannot call the string to the left a "question"
	and the string to the right an "answer", as they could be ambigious.  In the future, and
	througout this application, the string to the left of the colon will be called "left" and
	the string to the right of the colon will be called "right".
Now, Import the crib sheet
*/

$filename = $_GET['id'];
if( !preg_match( '/^[0-9a-zA-Z]*$/', $filename ) ){
	die('fuck you' );
}

$crib=file_get_contents( $filename );
$file=explode("\n",$crib);

unset( $file[ sizeof($file)-1 ] );

for ($i=0;$i<sizeof($file);$i++) {
	$file[$i]=explode(":",$file[$i]);
}
#$iSentinal=sizeof($file)*2;
#$num=0;
#for ($i=sizeof($file);$i<$iSentinal;$i++) {
	#$file[$i][0]=$file[$num][1];
	#$file[$i][1]=$file[$num][0];
	#$num++;
#}
shuffle($file);
?>
<script type="text/javascript">
var q=new Array();
var a=new Array();
var r=new Array();
pos=0;
sa='<input type="button" value="Show Answer" onclick="showAnswer()" id="button"/>';
rb='<input type="button" value="Correct" onclick="markCorrect()" id="button"/>&nbsp;&nbsp;&nbsp;<input type="button" value="Incorrect" onclick="markIncorrect()"/>';
<?php
$iSentinel=sizeof($file);
for ($i=0;$i<$iSentinel;$i++) {
	//ambigious left
	$file[$i][0]=str_replace('"','', trim($file[$i][0]) );
	//ambigious right
	$file[$i][1]=str_replace('"','', trim($file[$i][1]) );
	?>
	//question
	q[<?php echo $i?>]="<?php echo $file[$i][0]?>";
	//answer
	a[<?php echo $i?>]="<?php echo $file[$i][1]?>";
	//result
	r[<?php echo $i?>]=0;
	<?php
}
?>
function showAnswer() {
	document.getElementById("a").innerHTML=a[pos];
	document.getElementById("b").innerHTML=rb;
	document.getElementById("button").focus();
}
function markCorrect() {
	r[pos]=1;
	nextQ();
}
function markIncorrect() {
	r[pos]=0;
	nextQ();
}
function nextQ() {
	if (pos<<? echo sizeof($file)?>) {
		pos++;
		document.getElementById("b").innerHTML=sa;
		document.getElementById("a").innerHTML='&nbsp;';
		document.getElementById("q").innerHTML='Question #'+(pos+1)+"<br/>"+q[pos];
		document.getElementById("button").focus();
	} else {
		document.getElementById("b").innerHTML='';
		document.getElementById("a").innerHTML='';
		document.getElementById("q").innerHTML="Questions You Marked Incorrect:\n\n";
		for (i=0;i<r.length;i++) {
			if (r[i]==0) {
				document.write("Question #"+(i+1)+": "+q[i]+"<br/>&nbsp;&nbsp;&nbsp; Answer: "+a[i]+"<br/><br/>");
			}
		}
		for (i=0;i<r.length;i++) {
			if (r[i]==0) {
				document.write(q[i]+":"+a[i]+"<br/>");
			}
		}
	}
}
</script>
<div id="q">Question #1<br/><?php echo $file[0][0]?></div>
<br/><br/>
<div id="a">&nbsp;</div>
<div id="b"><input type="button" value="Show Answer" onclick="showAnswer()"/></div>
