<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//print_r($_POST);
?>
<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>insert page</title></head>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '544847022281598',
      xfbml      : true,
      version    : 'v2.1'
    });

    // ADD ADDITIONAL FACEBOOK CODE HERE
    function onLogin(response) {
  if (response.status == 'connected') {
    FB.api('/me?fields=first_name', function(data) {
      var welcomeBlock = document.getElementById('fb-welcome');
      welcomeBlock.innerHTML = 'Hello, ' + data.first_name + '!';
    });
  }
}

    FB.getLoginStatus(function(response) {
  // Check login status on load, and if the user is
  // already logged in, go directly to the welcome message.
  if (response.status == 'connected') {
    onLogin(response);
  } else {
    // Otherwise, show Login dialog first.
    FB.login(function(response) {
      onLogin(response);
    }, {scope: 'user_friends, email'});
  }
});
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


</script>
<?php
if(count($_POST) != 0) {
  //print_r($_POST);
  $result = $attend = 0;
  $cnt = $_POST['totalcnt'];
  $correct = explode('~',$_POST['correct']);
  $provided = explode('#',$_POST['provided']);
  for($i=1;$i<=$cnt;$i++){
    $correctAnswer = $correct[$i-1]  ;
    $providedAns = $provided[$i-1];
    
        if(intval($providedAns) != 0){ 
            $attend++;
            if(intval($providedAns) == intval($correctAnswer)){
                
                $result++;
            }
        }
    //print $attend;
  }
  print $result.' are correct in '.$attend.' attended questions from total of '.$cnt.' questions';
} else {
?>
<body>
    <script src="quiz/jquery.js"></script>    
    <h1 id="fb-welcome"></h1>
    <form name="quiz" id="quiz" method="post" action="" onsubmit="return finishTask();">
<?php
$file = file_get_contents("quiz/test.txt");
$qnslist = explode("$$$$$$",$file) ;
$cnt = count($qnslist);
$val = $qnslist[0];
$id = 1;
echo '<input type="hidden" name="provided" id="provided" value="">';
echo '<input type="hidden" name="totalcnt" id="totalcnt" value="'.$cnt.'">';
//echo '<input type="hidden" name="filecontent" id="filecontent" value="'.addslashes($file).'">';
/*foreach($qnslist as $key=>$val){
    $id = $key + 1;
    list($qn,$ans) = explode('Answers:',$val);
    print $qn."<br/>";
    list($others,$correct) = explode('correctAnswer:',$ans);
    $answers = explode('|',nl2br(htmlentities($others)));
    //print_r($answers);
    echo '<input type="hidden" name="correct'.$id.'" id="correct'.$id.'" value="'.$correct.'">';
    foreach($answers as $key1=>$val1){
        $newid = $key1 + 1;
        print '<input type="radio" name="prov'.$id.'" id="prov'.$id.'" value="'.$newid.'">'.$val1."<br/>";
    }
}*/

?>
    
    <div id="qnsdiv">
        <?php 
            list($qn,$ans) = explode('Answers:',$val);
            print $id.')'.$qn."<br/>";
            list($others,$correct) = explode('correctAnswer:',$ans);
            $answers = explode('|',nl2br(htmlentities($others)));
            echo '<input type="hidden" name="correct" id="correct" value="'.$correct.'">';
            foreach($answers as $key1=>$val1){
                $newid = $key1 + 1;
                print '<input type="radio" name="prov'.$id.'" id="prov'.$id.'" value="'.$newid.'">'.$val1."<br/>";
            }
            $newpassid = $id+1;
            $jsonArray = json_encode($qnslist);
            //print $jsonArray;
            $click = "nextbtnMoves(".$newpassid.",".$cnt.");";
            if($id < $cnt)
                print '<input type="button" name="next'.$id.'" id="next'.$id.'" value="Next >>" onclick="'.$click.'">';
            else
                print '<input type="submit" name="quizSubmit" id="quizSubmit" value="Finish" onsubmit="return finishTask();">';
         ?>        
    </div> 
    
</form>
<?php }  ?>
    

<script>
    var correctArray='';
    function nextbtnMoves(ival,total){
        var current = ival - 1;
        var correct = $('#correct').val();
        var radval;
        //alert($("input[name=prov"+current+"]:checked").val());
        if($("input[name=prov"+current+"]:checked").length == 0) 
            radval = '0';
        else
            radval = $("input[name=prov"+current+"]:checked").val();
        if(correctArray == '')
            correctArray = correct;
        else{
            correctArray = correctArray.replace(/(\r\n|\n|\r)/gm,'');
            correctArray = correctArray + '~' + correct;
        }
        var param = "ival="+ival+"&total="+total+"&correct="+correct;
        if ($("#provided").val() == '')
            $("#provided").val(radval);
        else
            $("#provided").val($("#provided").val()+"#"+radval);
        console.log($("#provided").val());
        console.log(correctArray);
        $.ajax({url:"quiz/demo.php?"+param,success:function(result){
            //console.log(result);
            $("#qnsdiv").html(result);
        }});
        //document.getElementById('qnsdiv').innerHTML = html;
    }
    function finishTask(){
        var current = $('#totalcnt').val();
        var correct = $('#correct').val();
        correctArray = correctArray.replace(/(\r\n|\n|\r)/gm,'');
        correctArray = correctArray + '~' + correct;
        $("#provided").val($("#provided").val()+"#"+$("input[name=prov"+current+"]:checked").val());
        $("#correct").val(correctArray);
        console.log($("#provided").val());
        console.log(correctArray);
        //alert(correctArray);
        return true;
    }
</script> 
</body>
</html>    