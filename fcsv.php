<?php
$lines = [];
$f = fopen('php://memory', 'w+');

for ($i= 0;$i < 20; $i++) {
    $line  = [];
    for ($j = 0; $j < 4; $j++) {
        $line[] = '" ' . uniqid() . ',' . uniqid() . '"';
    }
    $lines[] = implode('::', $line);
}

fputcsv($f, $lines);
 
rewind($f);
$contents = stream_get_contents($f);  //=> "foobar"
echo $contents;

