<?php

include_once 'fns/bytestr.php';
include_once 'fns/request_strings.php';
include_once 'lib/defaults.php';

list($address, $hostHeader, $key) = request_strings('address', 'hostHeader', 'key');

if ($address === '') $address = $defaults['address'];
if ($hostHeader === '') $hostHeader = $defaults['hostHeader'];

$title = htmlspecialchars($address).' status';

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "http://$address/$key",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => ["Host: $hostHeader"],
]);
$response = curl_exec($ch);
if ($response) {
    $data = json_decode($response);
    if ($data) {

        $uptime = (double)$data->uptime;

        $totalmem = $data->totalmem;
        $usedmem = $totalmem - $data->freemem;

        $totalmem = '<b>'.bytestr($totalmem)."</b> ($totalmem bytes)";
        $usedmem = '<b>'.bytestr($usedmem)."</b> ($usedmem bytes)";

        $loadavg = $data->loadavg;
        $loadavg = number_format($loadavg[0], 2).', '.number_format($loadavg[1], 2).', '.number_format($loadavg[2], 2);

        $memory = [];
        foreach ($data->freememHistory as $item) {
            $memory[] = [
                'time' => $item->time,
                'value' => ($data->totalmem - $item->value) / (1024 * 1024),
            ];
        }

        $processor = [];
        foreach ($data->loadavgHistory as $item) {
            $processor[] = [
                'time' => $item->time,
                'value' => $item->value[0],
            ];
        }

        $network = [];
        foreach ($data->networkHistory as $item) {
            $network[] = [
                'time' => $item->time,
                'value' => ($item->sent + $item->received) / 1024,
            ];
        }

        $clientData = [
            'memory' => $memory,
            'processor' => $processor,
            'network' => $network,
        ];

        $content =
            '<div>'
                .'<h2>General</h2>'
                ."<div>Uptime: $uptime</div>"
                ."<div>Load average: $loadavg</div>"
                ."<div>Total memory: $totalmem</div>"
                ."<div>Used memory: $usedmem</div>"
                .'<h2>Memory</h2>'
                .'<div id="memoryGraphWrapper"></div>'
                .'<h2>Processor</h2>'
                .'<div id="processorGraphWrapper"></div>'
                .'<h2>Network</h2>'
                .'<div id="networkGraphWrapper"></div>'
            .'</div>'
            .'<script type="text/javascript" src="javascript/Graph.js?2"></script>'
            .'<script>'
            .'var data = '.json_encode($clientData)."\n"
            ."var dateNow = data.dateNow\n"
            ."var memoryGraph = Graph(dateNow, data.memory)\n"
            ."document.getElementById('memoryGraphWrapper').appendChild(memoryGraph.element)\n"
            ."var processorGraph = Graph(dateNow, data.processor)\n"
            ."document.getElementById('processorGraphWrapper').appendChild(processorGraph.element)\n"
            ."var networkGraph = Graph(dateNow, data.network)\n"
            ."document.getElementById('networkGraphWrapper').appendChild(networkGraph.element)\n"
            .'</script>';

    } else {
        $content = 'The key is invalid.';
    }
} else {
    $content = curl_error($ch);
}

header('Content-Type: text/html; charset=UTF-8');

echo
    '<!DOCTYPE html>'
    .'<html>'
        .'<head>'
            ."<title>$title</title>"
            .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'
            .'<link rel="stylesheet" type="text/css" href="index.css" />'
            .'<link rel="stylesheet" type="text/css" href="view.css" />'
        .'</head>'
        .'<body>'
            ."<h1>$title</h1>"
            .$content
        .'</body>'
    .'</html>';
