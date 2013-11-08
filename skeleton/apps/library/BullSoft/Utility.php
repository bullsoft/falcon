<?php
namespace BullSoft;
class Utility
{
    public static function getIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";
        }
        return $ip;
    }

    public static function array_column(array $input, $column, $key=null)
    {
        if(function_exists("array_column")) {
            return array_column($input, $column, $key);
        } else {
            $output = array();
            foreach($input as $entry) {
                if ($key && isset($entry[$key])) {
                    $output[$entry[$key]] = $entry[$column];
                } else {
                    $output[] = $entry[$column];
                }
            }
            return $output;
        }
    }

    public static function array_columns(array $input, array $columns, $key=null)
    {
        $output = array();
        foreach($input as $entry) {
            if ($key && isset($entry[$key])) {
                $output[$entry[$key]] = array_intersect_key($entry, array_flip($columns));
            } else {
                $output[] = array_intersect_key($entry, array_flip($columns));
            }
        }
        return $output;
    }
    
    public static function array_change_key(array $input, array $map)
    {
        $output = array();
        foreach($input as $key => $value) {
            if (isset($map[$key])) {
                $output[$map[$key]] = $value;
            } else {
                $output[$key] = $value;
            }
        }
        return $output;
    }

    public static function array_change_keys(array $input, array $map)
    {
        $output = array();
        foreach($input as $entry) {
            $output[] = self::array_change_key($entry, $map);
        }
        return $output;
    }

    public static function array_value(array $input, $index)
    {
        if (array_key_exists($index, $input)) {
            return $input[$index];
        } else {
            return null;
        }
    }

    public static function json_error()
    {
        switch(json_last_error()){
            case JSON_ERROR_NONE:
                return true;
            case JSON_ERROR_DEPTH:
                throw new \Exception("Maximum stack depth exceeded");
            case JSON_ERROR_STATE_MISMATCH:
                throw new \Exception("Underflow or the modes mismatch");
            case JSON_ERROR_CTRL_CHAR:
                throw new \Exception("Unexpected control character found");
            case JSON_ERROR_SYNTAX:
                throw new \Exception("Syntax error, malformed JSON");
            case JSON_ERROR_UTF8:
                throw new \Exception("Malformed UTF-8 characters, possibly incorrectly encoded");
            default:
                throw new \Exception("Unknow json decode error");
        }
    }

    public static function get_file_total_lines($path)
    {
        if(!is_file($path)) {
            throw new \Exception("Not a filename");
        }
        return intval(exec("wc -l " . $path));
    }
}

/* Utility.php ends here */