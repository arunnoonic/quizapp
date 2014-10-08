<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//print_r($_POST);
?>
<script>
window.fbAsyncInit = function() {
    FB.init({
      appId      : '544847022281598',
      xfbml      : true,
      version    : 'v2.1'
    });

    // ADD ADDITIONAL FACEBOOK CODE HERE
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

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
});</script>
<?php
if(count($_POST) != 0) {
    
  $result = $attend = 0;
  $cnt = $_POST['totalcnt']  ;
  for($i=1;$i<=$cnt;$i++){
    $correctAnswer = $_POST['correct'.$i]  ;
    $provided = $_POST['prov'.$i];
    
        if($provided != ''){ 
            $attend++;
            if(intval($provided) == intval($correctAnswer)){
                
                $result++;
            }
        }
    //print $result;
  }
  print $result.' are correct in '.$attend.' attended questions from total of '.$cnt.' questions';
} else {
?>

<form name="quiz" id="quiz" method="post" action="">
<?php
$file = file_get_contents("quiz/test.txt");
$qnslist = explode("$$$$$$",$file) ;
$cnt = count($qnslist);
echo '<input type="hidden" name="totalcnt" id="totalcnt" value="'.$cnt.'">';
foreach($qnslist as $key=>$val){
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
}
?>
    <input type="submit" name="quizSubmit" id="quizSubmit" value="Finish">
</form>
<?php } ?>