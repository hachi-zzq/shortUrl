<?php
include 'hash.php';
$sol = '<br />' . PHP_EOL;
echo dec2Any('56800235583', 62), $sol; // ZZZZZZ
echo any2Dec('ZZZZZZ', 62), $sol; // 56800235583
echo dec2Any('123456', 62), $sol; // w7e
echo any2Dec('w7e', 62), $sol; // 123456