<?php

include_once 'lib/defaults.php';

header('Content-Type: text/html; charset=UTF-8');

echo
    '<!DOCTYPE html>'
    .'<html>'
        .'<head>'
            .'<title>System Monitor Viewer</title>'
            .'<link rel="stylesheet" type="text/css" href="index.css" />'
        .'</head>'
        .'<body>'
            .'<form action="view.php">'
                .'<div>'
                    .'<label>'
                        .'<div>Address:</div>'
                        ."<input type=\"text\" name=\"address\" placeholder=\"$defaults[address]\" />"
                    .'</label>'
                .'</div>'
                .'<div>'
                    .'<label>'
                        .'<div>Host Header:</div>'
                        ."<input type=\"text\" name=\"hostHeader\" placeholder=\"$defaults[hostHeader]\" />"
                    .'</label>'
                .'</div>'
                .'<div>'
                    .'<label>'
                        .'<div>Key:</div>'
                        .'<input type="text" name="key" />'
                    .'</label>'
                .'</div>'
                .'<input type="submit" value="Fetch" />'
            .'</form>'
        .'</body>'
    .'</html>';
