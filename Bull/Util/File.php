<?php
/**
 * Utility class for static file methods.
 * 
 * 
 */

class Bull_Util_File
{
    /**
     * 
     * The path of the file currently being used by Solar_File::load().
     * 
     * @var string
     * 
     * @see load()
     * 
     */
    protected static $_file;
    
    /**
     * 
     * Hack for [[php::file_exists() | ]] that checks the include_path.
     * 
     * Use this to see if a file exists anywhere in the include_path.
     * 
     * {{code: php
     *     $file = 'path/to/file.php';
     *     if (Bull_Util_File::exists('path/to/file.php')) {
     *         include $file;
     *     }
     * }}
     * 
     * @param string $file Check for this file in the include_path.
     * 
     * @return mixed If the file exists and is readble in the include_path,
     * returns the path and filename; if not, returns boolean false.
     * 
     */
    public static function exists($file)
    {
        // no file requested?
        $file = trim($file);
        if (! $file) {
            return false;
        }
        
        // using an absolute path for the file?
        // dual check for Unix '/' and Windows '\',
        // or Windows drive letter and a ':'.
        $abs = ($file[0] == '/' || $file[0] == '\\' || $file[1] == ':');
        if ($abs && file_exists($file)) {
            return $file;
        }
        
        // using a relative path on the file
        $path = explode(PATH_SEPARATOR, ini_get('include_path'));
        foreach ($path as $base) {
            // strip Unix '/' and Windows '\'
            $target = rtrim($base, '\\/') . DIRECTORY_SEPARATOR . $file;
            if (file_exists($target)) {
                return $target;
            }
        }
        // never found it
        return false;
    }
    
    
    /**
     * 
     * Uses [[php::include() | ]] to run a script in a limited scope.
     * 
     * @param string $file The file to include.
     * 
     * @return mixed The return value of the included file.
     * 
     */
    public static function load($file)
    {
        Bull_Util_File::$_file = Bull_Util_File::exists($file);
 
        if (! Bull_Util_File::$_file) {
            // could not open the file for reading
            $code = 'ERR_FILE_NOT_READABLE';
            throw new Exception($code);
        }
        
        // clean up the local scope, then include the file and
        // return its results.
        unset($file);
        
        return include Bull_Util_File::$_file;
    }
    

}