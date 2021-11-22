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
    exit;
}
$url = $_GET["f"];
$size = 0; // download file size
$time = 0; // reconnection times


if ($_GET["s"]) {
    header("Content-Length: " . $_GET["s"], true);
    $size = (int) $_GET["s"];
}

$ch = curl_init($url);
if ($_GET["c"]) {
    curl_setopt($ch, CURLOPT_COOKIE, $_GET["c"]);
}

if ($_GET["h"])curl_setopt($ch, CURLOPT_HTTPHEADER, $_GET["h"]);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $header_line) use (&$size) {
    $len = strlen($header_line);
    $header_line_arr = explode(':', $header_line, 2);
    if (preg_match("/Content-Length/i", $header_line_arr[0])) {
        $size = (int) $header_line_arr[1];
    }
    if (!preg_match("/Location|content-encoding|/i", $header_line_arr[0])) {
        header($header_line, false);
    }
    return $len;
});
curl_exec($ch);
$position = (int) curl_getinfo($ch)['size_download'];
curl_close($ch);

checkFinish($url, $position, $size, $time);

function checkFinish($url, $position, $size, $time)
{
    if ($size <= $position) {
        exit;
    }
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
    curl_exec($ch);
    $position += (int) curl_getinfo($ch)['size_download'];
    curl_close($ch);

    if ($time < 1000) {
        checkFinish($url, $position, $size, $time);
    } else {
        $test = fopen('log', 'a+');
        fwrite($test, '.end');
        fclose($test);
    }
}
?>
