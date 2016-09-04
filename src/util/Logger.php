<?php
namespace TripBuilder\Util;

/**
 * Simple logger, just write everything to a file.
 *
 * @author Elton
 *        
 */
class Logger implements \Psr\Log\LoggerInterface
{

    static $file = __DIR__ . "/messages.log";

    /**
     * Configure logger
     *
     * @param string $file
     *            File to write log
     */
    public static function configure($file)
    {
        self::$file = $file;
    }

    private function writeLog($level, $message, $context)
    {
        // buffer first to avoid "simultaneous" writes from different requests
        $toWrite = date('Y-m-d H:i:s') . " < $level > $message\n";
        if ($context) {
            $toWrite .= print_r($context, true) . "\n";
        }
        
        $f = fopen(self::$file, "a+");
        fwrite($f, $toWrite);
        fclose($f);
    }

    public function emergency($message, array $context = array())
    {
        self::writeLog("emergency", $message, $context);
    }

    public function alert($message, array $context = array())
    {
        self::writeLog("alert", $message, $context);
    }

    public function critical($message, array $context = array())
    {
        self::writeLog("critical", $message, $context);
    }

    public function error($message, array $context = array())
    {
        self::writeLog("error", $message, $context);
    }

    public function warning($message, array $context = array())
    {
        self::writeLog("warning", $message, $context);
    }

    public function notice($message, array $context = array())
    {
        self::writeLog("notice", $message, $context);
    }

    public function info($message, array $context = array())
    {
        self::writeLog("info", $message, $context);
    }

    public function debug($message, array $context = array())
    {
        self::writeLog("debug", $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        self::writeLog("log", $message, $context);
    }
}
