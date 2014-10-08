<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$file = file_get_contents("test.txt");
$qnslist = explode("$$$$$$",$file) ;
$total = $_GET['total'];
$ival = $_GET['ival'];
$cur = $_GET['correct'];
$id = $ival;
$val = $qnslist[$id-1];
//print $val;
list($qn,$ans) = explode('Answers:',$val);
print $id.')'.$qn."<br/>";
list($others,$correct) = explode('correctAnswer:',$ans);
$answers = explode('|',nl2br(htmlentities($others)));
$newCorrect = $cur."#".$correct;
echo '<input type="hidden" name="correct" id="correct" value="'.$correct.'">';
foreach($answers as $key1=>$val1){
    $newid = $key1 + 1;
    print '<input type="radio" name="prov'.$id.'" id="prov'.$id.'" value="'.$newid.'">'.$val1."<br/>";
}
$newpassid = $id+1;
$jsonArray = json_encode($qnslist);
//print $jsonArray;
$click = "nextbtnMoves(".$newpassid.",".$total.");";
if($id < $total)
    print '<input type="button" name="next'.$id.'" id="next'.$id.'" value="Next >>" onclick="'.$click.'">';
else
    print '<input type="submit" name="quizSubmit" id="quizSubmit" value="Finish" onsubmit="return finishTask();">';

