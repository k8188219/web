<?php
set_time_limit(0);

// detect input
if (!$_GET["f"]) {
?>
    <script>
        function myprint() {
            document.getElementsByTagName('p')[0].innerHTML = '<a href="?f=' + document.getElementsByTagName('input')[0].value + '">link</a>'
        }
    </script>
    <input></input>
    <button onclick='myprint()'>button</button>
    <p></p>
<?php
    exit();
} else {
    $url = $_GET["f"];
}
$size = 0; // download file size
$time = 0; // reconnection times


// get file size add header
if ($_GET["c"]) {
    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "Cookie: " . $_GET["c"]
        )
    );
    stream_context_set_default($opts);
}
$size = get_headers($url, 1)["Content-Length"];
$head = get_headers($url, 0);
foreach ($head as $v) {
    header($v, false);
}
if ($_GET["s"]) {
    header("Content-Length: " . $_GET["s"], true);
}
ob_start();
var_dump($size);
var_dump($head);
$err = ob_get_clean();
error_log($err);

$ch = curl_init($url);
if ($_GET["c"]) {
    curl_setopt($ch, CURLOPT_COOKIE, $_GET["c"]);
}
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$file = curl_exec($ch);
$position = (int) curl_getinfo($ch)['size_download'];
curl_close($ch);

if ($size > $position) {
    ob_start();
    var_dump(
        array(
            'url' => $url,
            'position' => $position,
            'size' => $size,
            'time' => $time
        )
    );
    $err = ob_get_clean();
    error_log($err);
    checkFinish($url, $position, $size, $time);
}

function checkFinish($url, $position, $size, $time)
{
    // log reconnection times
    $time++;
    $test = fopen('log', 'w+');
    fwrite($test, $time);
    fclose($test);

    $ch = curl_init($url);
    if ($_GET["c"]) {
        curl_setopt($ch, CURLOPT_COOKIE, $_GET["c"]);
    }
    curl_setopt($ch, CURLOPT_RESUME_FROM, $position);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 3600);
    $file = curl_exec($ch);
    $position += (int) curl_getinfo($ch)['size_download'];
    curl_close($ch);

    if ($size > $position && $time < 1000) {
        ob_start();
        var_dump(
            array(
                'url' => $url,
                'position' => $position,
                'size' => $size,
                'time' => $time
            )
        );
        $err = ob_get_clean();
        error_log($err);
        checkFinish($url, $position, $size, $time);
    } else {
        $test = fopen('log', 'a+');
        fwrite($test, '.end');
        fclose($test);
    }
}
?>