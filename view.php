<?php

function createRetryForm ($address, $hostHeader, $key) {
    return
        '<div>'
            .'<form action="index.php" style="display: inline-block; vertical-align: top; margin-left: 16px;">'
                .'<input class="button" type="submit" value="Retry" />'
                .'<input type="hidden" name="address" value="'.htmlspecialchars($address).'" />'
                .'<input type="hidden" name="hostHeader" value="'.htmlspecialchars($hostHeader).'" />'
                .'<input type="hidden" name="key" value="'.htmlspecialchars($key).'" />'
            .'</form>'
        .'</div>';
}

include_once 'fns/bytestr.php';
include_once 'fns/request_strings.php';
include_once 'lib/debug.php';
include_once 'lib/defaults.php';

list($address, $hostHeader, $key) = request_strings('address', 'hostHeader', 'key');

if ($address === '') $addressValue = $defaults['address'];
else $addressValue = $address;

if ($hostHeader === '') $hostHeaderValue = $defaults['hostHeader'];
else $hostHeaderValue = $hostHeader;

$title = htmlspecialchars($addressValue).' status';

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "http://$addressValue/$key",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => ["Host: $hostHeaderValue"],
]);
$response = curl_exec($ch);
if ($response) {
    $data = json_decode($response);
    if ($data) {

        $uptime = (double)$data->uptime;

        $totalmem = $data->totalmem;
        $usedmem = $totalmem - $data->freemem;

        $totalmem = bytestr($totalmem)." ($totalmem bytes)";
        $usedmem = bytestr($usedmem)." ($usedmem bytes)";

        $loadavg = $data->loadavg;
        $loadavg = number_format($loadavg[0], 2).', '.number_format($loadavg[1], 2).', '.number_format($loadavg[2], 2);

        $memory = [];
        foreach ($data->freememHistory as $item) {
            $memory[] = [
                'time' => $item->time,
                'value' => $data->totalmem - $item->value,
            ];
        }

        $processor = [];
        foreach ($data->loadavgHistory as $item) {
            $processor[] = [
                'time' => $item->time,
                'value' => $item->value[0] / $data->numCpus,
            ];
        }

        $network = [];
        foreach ($data->networkHistory as $item) {
            $network[] = [
                'time' => $item->time,
                'value' => $item->sent + $item->received,
            ];
        }

        $clientData = [
            'memory' => $memory,
            'processor' => $processor,
            'network' => $network,
        ];

        $content =
            '<div style="text-align: left">'
                .'<div style="display: inline-block; vertical-align: top">'
                    .'<div class="item">'
                        .'<div class="label">'
                            .'<label>General</label>'
                        .'</div>'
                        .'<div class="field" style="width: 300px">'
                            .'<div class="property">'
                                .'<div>Uptime:</div>'
                                ."<div>$uptime</div>"
                            .'</div>'
                            .'<div class="property">'
                                .'<div>Load average:</div>'
                                ."<div>$loadavg</div>"
                            .'</div>'
                            .'<div class="property">'
                                .'<div>Total memory:</div>'
                                ."<div>$totalmem</div>"
                            .'</div>'
                            .'<div class="property">'
                                .'<div>Used memory:</div>'
                                ."<div>$usedmem</div>"
                            .'</div>'
                        .'</div>'
                    .'</div>'
                .'</div>'
                .'<div style="display: inline-block; vertical-align: top">'
                    .'<div class="item">'
                        .'<div class="label">'
                            .'<label>Memory</label>'
                        .'</div>'
                        .'<div class="field" id="memoryGraphWrapper"></div>'
                    .'</div>'
                    .'<div class="item">'
                        .'<div class="label">'
                            .'<label>Processor</label>'
                        .'</div>'
                        .'<div class="field" id="processorGraphWrapper"></div>'
                    .'</div>'
                    .'<div class="item">'
                        .'<div class="label">'
                            .'<label>Network</label>'
                        .'</div>'
                        .'<div class="field" id="networkGraphWrapper"></div>'
                    .'</div>'
                .'</div>'
            .'</div>'
            .'<script type="text/javascript">'
            .'var data = '.json_encode($clientData)."\n"
            .'</script>';

        if (defined('DEBUG')) {
            $content .=
                '<script type="text/javascript" src="javascript/FormatBytes.js"></script>'
                .'<script type="text/javascript" src="javascript/FormatPercent.js"></script>'
                .'<script type="text/javascript" src="javascript/Graph.js"></script>'
                .'<script type="text/javascript" src="javascript/Legend.js"></script>'
                .'<script type="text/javascript" src="javascript/MemoryGraph.js"></script>'
                .'<script type="text/javascript" src="javascript/NetworkGraph.js"></script>'
                .'<script type="text/javascript" src="javascript/ProcessorGraph.js"></script>'
                .'<script type="text/javascript" src="view.js"></script>';
        } else {
            $content .=
                '<script type="text/javascript" src="view.compressed.js?"></script>';
        }

    } else {
        $content =
            '<div class="errorText">The key is invalid.</div>'
            .createRetryForm($address, $hostHeader, $key);
    }
} else {
    $content =
        '<div class="errorText">'.curl_error($ch).'</div>'
        .createRetryForm($address, $hostHeader, $key);
}

header('Content-Type: text/html; charset=UTF-8');

if (defined('DEBUG')) {
    $cssLinks =
        '<link rel="stylesheet" type="text/css" href="javascript/Graph.css" />'
        .'<link rel="stylesheet" type="text/css" href="javascript/Legend.css" />'
        .'<link rel="stylesheet" type="text/css" href="index.css" />'
        .'<link rel="stylesheet" type="text/css" href="view.css" />';
} else {
    $cssLinks = '<link rel="stylesheet" type="text/css" href="view.compressed.css?2" />';
}

echo
    '<!DOCTYPE html>'
    .'<html>'
        .'<head>'
            ."<title>$title</title>"
            .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'
            .'<link rel="icon" type="text/html" href="images/favicon.png" />'
            .$cssLinks
        .'</head>'
        .'<body>'
            .'<div style="display: inline-block; vertical-align: middle; height: 100%"></div>'
            .'<div style="display: inline-block; vertical-align: middle">'
                ."<h1>$title</h1>"
                .$content
            .'</div>'
        .'</body>'
    .'</html>';
