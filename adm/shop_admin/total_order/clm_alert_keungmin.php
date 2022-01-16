<?php
include_once('./_common.php');


$api_key = $_GET['api_key'];

$sql = "select * from lt_job_order where jo_id = 62";

$resulet = sql_fetch($sql);

echo $resulet['jo_it_name'];
echo $api_key;

?>

<style>
    /* #audio_alert {display : none;} */
</style>
<div id="audio_alert" >
    시작
</div>
<script>
    var audio = document.createElement("AUDIO")
    document.body.appendChild(audio);
    audio.src = "./SAMPLE_1.MP3";

    
    
    

    

    

    setTimeout(() => {
        document.getElementById("audio_alert").click();
    }, 2000);

    


    document.body.addEventListener("click", function () {
        audio.play();
    });

    function start_audio(){
        alert("asdf");
    }


    
</script>
