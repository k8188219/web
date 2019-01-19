<?php

header("Location: intent://".$_GET["intent"]."#Intent".
";scheme=".$_GET["scheme"].
";package=".$_GET["package"].
";action=".$_GET["action"].
";category=".$_GET["category"].
";component=".$_GET["component"].
";end");
exit();
header("Location: intent://tw.yahoo.com#Intent;scheme=http;package=com.android.chrome;end");
exit();
?>
