<?php

$charging_times = array();
//for($i=1; $i<=\Backpack\Settings\app\Models\Setting::get('max_charging_times'); $i++) {
$charging_times[1] = '1 hour';
for ($i=2; $i<=10; $i++){
    $charging_times[$i]=$i.' hours';
}

return [
    'charging_times' => $charging_times,
    'default_role' => 3,
    'owner_role' => 2,
];
