<?php
header("Location: intent://scan/#Intent".
";scheme=".$_GET["scheme"].
";package=".$_GET["package"].
";action=".$_GET["action"].
";category=".$_GET["category"].
";component=".$_GET["component"].
";end");
exit();
?>
