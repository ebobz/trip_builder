<?php

/*
 * Simple log configuration
 * (static usage of Logger requires manual include)
 */
require_once (__DIR__ . "/src/LoggerInterface.php");
require_once (__DIR__ . "/src/util/Logger.php");
TripBuilder\Util\Logger::configure(__DIR__ . "/log/messages.log");

/*
 * PHP will not automaticly parse PUT requests
 * I'll parse them into $_POST and $_REQUEST
 */
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (strpos($input_content, "Content-Disposition") !== false) {
        $tmp = parse_raw_http_request($input_content);
    } else {
        mb_parse_str($input_content, $tmp);
    }
    if (is_array($tmp)) {
        foreach ($tmp as $k => $v) {
            $_REQUEST[$k] = $v;
            $_POST[$k] = $v;
        }
    }
}

/*
 * This code below is a little hack for identifying if the input is realy in UTF-8
 * credits: https://stackoverflow.com/questions/910793/detect-encoding-and-make-everything-utf-8/3479658#3479658
 * it will basicly try to decode, encode once again and then compare, if it's different the encoding received is a bad UTF8
 * this can be simulated sending via CURL from windows command prompt
 */
$phpInput = file_get_contents('php://input');
$phpInputUtf8Dec = utf8_decode($phpInput);
$phpInputUtf8Enc = utf8_encode($phpInput);
$phpInputUtf8DecEnc = utf8_encode($phpInputUtf8Dec);
if ($phpInputUtf8DecEnc != $phpInput) {
    $phpInput = $phpInputUtf8Enc;
    if (is_array($_POST)) {
        foreach ($_POST as $k => $v) {
            $_POST[$k] = utf8_encode($v);
        }
    }
    if (is_array($_GET)) {
        foreach ($_GET as $k => $v) {
            $_GET[$k] = utf8_encode($v);
        }
    }
}

function parse_raw_http_request($input_content)
{
    // credits em: http://stackoverflow.com/questions/5483851/manually-parse-raw-http-data-with-php
    $a_data = array();
    // grab multipart boundary from content type header
    preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
    $boundary = $matches[1];
    
    // split content by boundary and get rid of last -- element
    $a_blocks = preg_split("/-+$boundary/", $input_content);
    array_pop($a_blocks);
    
    // loop data blocks
    foreach ($a_blocks as $id => $block) {
        if (empty($block))
            continue;
            
            // parse uploaded files
        if (strpos($block, 'application/octet-stream') !== FALSE) {
            // match "name", then everything after "stream" (optional) except for prepending newlines
            preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
        } else { // parse all other fields
                 // match "name" and optional value in between newline sequences
            preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
        }
        $a_data[$matches[1]] = $matches[2];
    }
    return $a_data;
}

/*
 * Autoloader
 */
spl_autoload_register(function ($class) {
    $prefix = "TripBuilder\\";
    $base_dir = __DIR__ . "/src";
    
    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }
    
    // get the relative class name
    $relative_class = substr($class, $len);
    $explosion = explode("\\", $relative_class);
    for ($i = 0; $i < count($explosion) - 1; $i ++) {
        $explosion[$i] = strtolower($explosion[$i]);
    }
    $explosion[$i] = $explosion[$i];
    $relative_class = implode("\\", $explosion);
    
    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . "/" . str_replace('\\', '/', $relative_class) . '.php';
    // echo "<br>-$file-<br>";
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});