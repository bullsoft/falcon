<?php
/**
 * 
 * Utility class for static directory methods.
 * 
 */

class Bull_Util_Dir
{
    /**
     * 
     * The OS-specific temporary directory location.
     * 
     * @var string
     * 
     */
    protected static $_tmp;
    
    /**
     * 
     * Hack for [[php::is_dir() | ]] that checks the include_path.
     * 
     * Use this to see if a directory exists anywhere in the include_path.
     * 
     * {{code: php
     *     $dir = Bull_Util_Dir::exists('path/to/dir')
     *     if ($dir) {
     *         $files = scandir($dir);
     *     } else {
     *         echo "Not found in the include-path.";
     *     }
     * }}
     * 
     * @param string $dir Check for this directory in the include_path.
     * 
     * @return mixed If the directory exists in the include_path, returns the
     * absolute path; if not, returns boolean false.
     * 
     */
    public static function exists($dir)
    {
        // no file requested?
        $dir = trim($dir);
        if (! $dir) {
            return false;
        }
        
        // using an absolute path for the file?
        // dual check for Unix '/' and Windows '\',
        // or Windows drive letter and a ':'.
        $abs = ($dir[0] == '/' || $dir[0] == '\\' || $dir[1] == ':');
        if ($abs && is_dir($dir)) {
            return $dir;
        }
        
        // using a relative path on the file
        $path = explode(PATH_SEPARATOR, ini_get('include_path'));
        foreach ($path as $base) {
            // strip Unix '/' and Windows '\'
            $target = rtrim($base, '\\/') . DIRECTORY_SEPARATOR . $dir;
            if (is_dir($target)) {
                return $target;
            }
        }
        // never found it
        return false;
    }
    
    /**
     * 
     * "Fixes" a directory string for the operating system.
     * 
     * Use slashes anywhere you need a directory separator. Then run the
     * string through fixdir() and the slashes will be converted to the
     * proper separator (for example '\' on Windows).
     * 
     * Always adds a final trailing separator.
     * 
     * @param string $dir The directory string to 'fix'.
     * 
     * @return string The "fixed" directory string.
     * 
     */
    public static function fix($dir)
    {
        $dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
        return rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
    
    /**
     * 
     * Convenience method for dirname() and higher-level directories.
     * 
     * @param string $file Get the dirname() of this file.
     * 
     * @param int $up Move up in the directory structure this many 
     * times, default 0.
     * 
     * @return string The dirname() of the file.
     * 
     */
    public static function name($file, $up = 0)
    {
        $dir = dirname($file);
        while ($up --) {
            $dir = dirname($dir);
        }
        return $dir;
    }
    
    /**
     * 
     * Replacement for mkdir() to supress warnings and throw exceptions in 
     * their place.
     * 
     * @param string $path The directory path to create.
     * 
     * @param int $mode The permissions mode for the directory.
     * 
     * @param bool $recursive Recursively create directories along the way.
     * 
     * @return bool True on success; throws exception on failure.
     * 
     * @see [[php::mkdir() | ]]
     * 
     */
    public static function mkdir($path, $mode = 0777, $recursive = false)
    {
        $result = @mkdir($path, $mode, $recursive);
        if (! $result) {
            $info = error_get_last();
            throw new Exception($info);
        } else {
            return true;
        }
    }
    
    /**
     * 
     * Replacement for rmdir() to supress warnings and throw exceptions in 
     * their place.
     * 
     * @param string $path The directory path to remove
     * 
     * @return bool True on success; throws exception on failure.
     * 
     * @see [[php::rmdir() | ]]
     * 
     */
    public static function rmdir($path)
    {
        $result = @rmdir($path);
        if (! $result) {
            $info = error_get_last();
            throw new Exception($info);
        } else {
            return true;
        }
    }
    
}