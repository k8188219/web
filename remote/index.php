<?php
ini_set('max_execution_time', 1200);#&#35373;&#32622;php&#22519;&#34892;&#26368;&#22823;&#26178;&#38291;(&#31186;)

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
$size = get_headers($url,1)["Content-Length"];
$head = get_headers($url,0);
foreach ($head as $v) {
    header($v);
}

#&#23531;&#20837;Header
//header("Accept-Ranges: bytes");
//header("Content-Length: $size");


$ch = curl_init($url);#&#24314;&#31435;curl&#36899;&#32218;
$cookiiiiie = 'NID=188=soOIekGERhT-uPnSvVXWwIbB4BWU9jbr7M8tBjc0eTf_4wFNtXgdpWhZcTx_fKRqxFfZIKaWQuQlMbxxG2oWr6LGMS6DRmbMbIUvT2-VtikkDzvL5vRtHxvctKZNV1ZA_NsUDT44fmblahd4V35qF24WaoinxTHK2908RsR1W7w;B=0';
curl_setopt($ch, CURLOPT_COOKIE, $cookiiiiie);
$file = curl_exec($ch);#&#22519;&#34892;curl
$position = (int)curl_getinfo($ch)[size_download];#&#21462;&#19979;&#36617;&#27284;&#26696;&#22823;&#23567;
curl_close($ch);#&#38364;&#38281;&#36899;&#32218;

#&#26159;&#21542;&#23436;&#25104;&#19979;&#36617;
if($size>$position){
    checkFinish($url,$position,$size,$time);
}

function checkFinish($url,$position,$size,$time){
    #log&#27425;&#25976;
    $time++;
    $test = fopen('log','w+');
    fwrite($test,$time);
    fclose($test);
    
    $ch = curl_init($url);#&#24314;&#31435;curl&#36899;&#32218;
    curl_setopt($ch,CURLOPT_RESUME_FROM,$position);#&#26039;&#40670;&#32396;&#20659;
$cookiiiiie = 'NID=188=soOIekGERhT-uPnSvVXWwIbB4BWU9jbr7M8tBjc0eTf_4wFNtXgdpWhZcTx_fKRqxFfZIKaWQuQlMbxxG2oWr6LGMS6DRmbMbIUvT2-VtikkDzvL5vRtHxvctKZNV1ZA_NsUDT44fmblahd4V35qF24WaoinxTHK2908RsR1W7w;B=0';
curl_setopt($ch, CURLOPT_COOKIE, $cookiiiiie);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);#curl&#36899;&#32218;&#19981;&#20013;&#26039;
    curl_setopt($ch,CURLOPT_DNS_CACHE_TIMEOUT,3600);#DNS&#26283;&#23384;1&#23567;&#26178;
    $file = curl_exec($ch);#&#22519;&#34892;
    $position += (int)curl_getinfo($ch)[size_download];#&#21462;&#32317;&#19979;&#36617;&#27284;&#26696;&#22823;&#23567;
    curl_close($ch);#&#38364;&#38281;&#36899;&#32218;
    
    #&#30906;&#35469;&#26159;&#21542;&#23436;&#27284;
    if($size>$position){
        checkFinish($url,$position,$size,$time);
    }else{
        #log&#32080;&#26463;
        $test = fopen('log','a+');
        fwrite($test,'.end');
        fclose($test);
    }
}
?>
