<?php

header("Location: intent://".$_GET["intent"]."#Intent".
";scheme=".$_GET["scheme"].
";package=".$_GET["package"].
";end");
exit();
header("Location: intent://tw.yahoo.com#Intent;scheme=http;package=com.android.chrome;end");
exit();
?>
