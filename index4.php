<?php
$a = '996609604';
$dash = 4;
$stringLen = strlen($a);
$dashPosition = $stringLen - $dash;
$b = substr($a, $dashPosition, $stringLen);
$c = substr($a, 0, $dashPosition);
echo $c;