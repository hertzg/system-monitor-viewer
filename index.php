<?php

include_once 'fns/request_strings.php';
include_once 'lib/defaults.php';

list($address, $hostHeader, $key) = request_strings('address', 'hostHeader', 'key');

header('Content-Type: text/html; charset=UTF-8');

echo
    '<!DOCTYPE html>'
    .'<html>'
        .'<head>'
            .'<title>System Monitor Viewer</title>'
            .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'
            .'<link rel="icon" type="text/html" href="images/favicon.png" />'
            .'<link rel="stylesheet" type="text/css" href="index.css" />'
        .'</head>'
        .'<body>'
            .'<div style="display: inline-block; vertical-align: middle; height: 100%"></div>'
            .'<div style="display: inline-block; vertical-align: middle">'
                .'<h1>System Monitor Viewer</h1>'
                .'<form action="view.php">'
                    .'<div class="item">'
                        .'<div class="label">'
                            .'<label for="addressInput">Address:</label>'
                        .'</div>'
                        .'<div class="field">'
                            .'<input id="addressInput" type="text"'
                            .' value="'.htmlspecialchars($address).'"'
                            ." name=\"address\" placeholder=\"$defaults[address]\" />"
                        .'</div>'
                    .'</div>'
                    .'<div class="item">'
                        .'<div class="label">'
                            .'<label for="hostHeaderInput">Host Header:</label>'
                        .'</div>'
                        .'<div class="field">'
                            .'<input id="hostHeaderInput" type="text"'
                            .' value="'.htmlspecialchars($hostHeader).'"'
                            ." name=\"hostHeader\" placeholder=\"$defaults[hostHeader]\" />"
                        .'</div>'
                    .'</div>'
                    .'<div class="item">'
                        .'<div class="label">'
                            .'<label for="keyInput">Key:</label>'
                        .'</div>'
                        .'<div class="field">'
                            .'<input id="keyInput" type="text" name="key"'
                            .' value="'.htmlspecialchars($key).'" />'
                        .'</div>'
                    .'</div>'
                    .'<div class="item" style="text-align: center;">'
                        .'<input class="button" type="submit" value="View Data" />'
                    .'</div>'
                .'</form>'
            .'</div>'
        .'</body>'
    .'</html>';
