<?php

$charging_times = array();
$charging_times[1] = '1 hour';
for ($i=2; $i<=10; $i++){
    $charging_times[$i]=$i.' hours';
}

return [
    'charging_times' => $charging_times,
];
