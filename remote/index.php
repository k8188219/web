<?php
set_time_limit(0);

#&#20597;&#28204;&#36664;&#20837;
if(!$_GET["f"]){
?>
    <script>
	function myprint()
	{
		document.getElementsByTagName('p')[0].innerHTML ='<a href="?f='+document.getElementsByTagName('input')[0].value+'">link</a>' 
	}
	</script>
    <input></input>
    <button onclick='myprint()'>button</button>
    <p></p>
<?php
    exit();
}else{
    $url = $_GET["f"];
}
$size = 0;#&#25972;&#20491;&#27284;&#26696;&#22823;&#23567;
$record = 0;#&#26368;&#21021;&#21462;&#24471;&#30070;&#26696;&#22823;&#23567;&#26041;&#27861;&#30340;&#21443;&#25976;
$count = 0;#&#26368;&#21021;&#21462;&#24471;&#30070;&#26696;&#22823;&#23567;&#26041;&#27861;&#30340;&#21443;&#25976;
$time = 0;#&#37325;&#26032;&#36899;&#25509;&#30340;&#27425;&#25976;

#&#26368;&#21021;&#21462;&#24471;&#27284;&#26696;&#22823;&#23567;&#26041;&#27861;
/*$lines_array = get_headers($url);
for($i=0;$i<count($lines_array)-1;$i++){
    similar_text($lines_array[$i],"Content-Length:",$percent);
    if($percent>$record){
        $record = $percent;
        $count = $i;
    }
}
$size = (int)ltrim($lines_array[$count],"Content-Length: ");*/

#&#21462;&#24471;&#27284;&#26696;&#22823;&#23567;&#26041;&#27861;

if ($_GET["c"]) {
    $opts = array(
        'http'=>array(
            'method'=>"GET",
            'header'=>"Cookie: ".$_GET["c"]
        )
    );
    stream_context_set_default($opts);
}
$size = get_headers($url,1)["Content-Length"];
$head = get_headers($url,0);
foreach ($head as $v) {
    header($v,false);
}
ob_start();
var_dump($head);
var_dump($head);
$err = ob_get_clean();
error_log($err);

$ch = curl_init($url);
if($_GET["c"]){
    curl_setopt($ch, CURLOPT_COOKIE, $_GET["c"]);
}
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$file = curl_exec($ch);
$position = (int)curl_getinfo($ch)['size_download'];
curl_close($ch);

if($size>$position){
ob_start();
var_dump(
array(
    'url'=>$url,
    'position'=>$position,
    'size'=>$size,
    'time'=>$time
)
);
$err = ob_get_clean();
error_log($err);
    checkFinish($url,$position,$size,$time);
}

function checkFinish($url,$position,$size,$time){
    #log&#27425;&#25976;
    $time++;
    $test = fopen('log','w+');
    fwrite($test,$time);
    fclose($test);
    
    $ch = curl_init($url);
    if($_GET["c"]){
        curl_setopt($ch, CURLOPT_COOKIE, $_GET["c"]);
    }
    curl_setopt($ch,CURLOPT_RESUME_FROM,$position);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
    curl_setopt($ch,CURLOPT_DNS_CACHE_TIMEOUT,3600);
    $file = curl_exec($ch);
    $position += (int)curl_getinfo($ch)['size_download'];
    curl_close($ch);
    
    if($size>$position && $time < 1000){
ob_start();
var_dump(
array(
    'url'=>$url,
    'position'=>$position,
    'size'=>$size,
    'time'=>$time
)
);
$err = ob_get_clean();
error_log($err);
        checkFinish($url,$position,$size,$time);
    }else{
        $test = fopen('log','a+');
        fwrite($test,'.end');
        fclose($test);
    }
}
?>
