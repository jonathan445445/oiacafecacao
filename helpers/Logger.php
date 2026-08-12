<?php

class Logger {
    private static $logPath;
    private static $initialized = false;

    public static function init() {
        if (self::$initialized) {
            return;
        }
        
        self::$logPath = LOG_PATH;
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0777, true);
        }
        
        self::$initialized = true;
    }

    private static function writeLog($level, $message, $context = []) {
        self::init();
        
        $date = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logLine = "[{$date}] [{$level}] {$message} {$contextStr}" . PHP_EOL;
        
        $logFile = self::$logPath . '/' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }

    public static function info($message, $context = []) {
        self::writeLog('INFO', $message, $context);
    }

    public static function warning($message, $context = []) {
        self::writeLog('WARNING', $message, $context);
    }

    public static function error($message, $context = []) {
        self::writeLog('ERROR', $message, $context);
    }

    public static function debug($message, $context = []) {
        if (APP_DEBUG) {
            self::writeLog('DEBUG', $message, $context);
        }
    }

    public static function contact($message, $context = []) {
        self::init();
        
        $date = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logLine = "[{$date}] [CONTACT] {$message} {$contextStr}" . PHP_EOL;
        
        $logFile = self::$logPath . '/contact-' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
}
