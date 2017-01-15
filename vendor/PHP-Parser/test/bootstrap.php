<?php

namespace PhpParser;

require __DIR__ . '/../../../vendor/autoload.php';

$extensions = ['xmlwriter', 'xmlreader', 'filter'];
foreach ($extensions as $extension) {
    if (!extension_loaded($extension) && !dl("$extension.so")) {
        die("Could not load required extension $extension\n");
    }
}

function canonicalize($str) {
    // trim from both sides
    $str = trim($str);

    // normalize EOL to \n
    $str = str_replace(array("\r\n", "\r"), "\n", $str);

    // trim right side of all lines
    return implode("\n", array_map('rtrim', explode("\n", $str)));
}
