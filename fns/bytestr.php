<?php

function bytestr ($bytes) {
    $names = array('B', 'KB', 'MB', 'GB', 'TB');
    foreach ($names as $name) {
        if ($bytes > 1024) $bytes /= 1024;
        else return number_format($bytes, 1).$name;
    }
}
