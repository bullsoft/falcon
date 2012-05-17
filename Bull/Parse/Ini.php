<?php
class Bull_Parse_Ini extends Bull_Parse_Abstract
{
    /**
     *
     * Loads in the ini file specified in filename.
     *
     */
    public function load($file, $section_name = null)
    {
        $realfile = Bull_Util_File::exists($file);
        
        if (!$realfile) {
            throw new Bull_Parse_Exception("File: {$file} Not exists.");
        }
        
        $process_sections = true;
        // load the raw ini file
        $ini = parse_ini_file($realfile, $process_sections);

        // fail if there was an error while processing the specified ini file
        if ($ini === false) {
            return false;
        }
        // reset the result array
        $this->configs = array();
        if ($process_sections === true) {
            // loop through each section
            foreach ($ini as $section => $contents) {
                // process sections contents
                $this->processSection($section, $contents);
            }
        } else {
            // treat the whole ini file as a single section
            $this->configs = $this->_processSectionContents($ini);
        }
        //  extract the required section if required
        if ($process_sections === true) {
            if ($section_name !== null) {
                // return the specified section contents if it exists
                if (isset($this->configs[$section_name])) {
                    return $this->configs[$section_name];
                } else {
                    throw new Bull_Parse_Exception('Section ' . $section_name .
                                                    ' not found in the ini file ' . $realfile);
                }
            }
        }
        return $this;
    }


    /**
     * Process contents of the specified section
     *
     * @param string $section Section name
     * @param array $contents Section contents
     * @throws Exception
     * @return void
     */
    private function processSection($section, array $contents)
    {
        // the section does not extend another section
        if (stripos($section, ':') === false) {
            $this->configs[$section] = $this->processSectionContents($contents);

            // section extends another section
        } else {
            // extract section names
            list($ext_target, $ext_source) = explode(':', $section);
            $ext_target = trim($ext_target);
            $ext_source = trim($ext_source);

            // check if the extended section exists
            if (!isset($this->configs[$ext_source])) {
                throw new Bull_Parse_Exception('Unable to extend section ' .
                                                $ext_source . ', section not found');
            }

            // process section contents
            $this->configs[$ext_target] = $this->processSectionContents($contents);

            // merge the new section with the existing section values
            $this->configs[$ext_target] = $this->arrayMergeRecursive($this->configs[$ext_source], $this->configs[$ext_target]);
        }
    }


    /**
     * Process contents of a section
     *
     * @param array $contents Section contents
     * @return array
     */
    private function processSectionContents(array $contents)
    {
        $result = array();

        // loop through each line and convert it to an array
        foreach ($contents as $path => $value) {
            // convert all a.b.c.d to multi-dimensional arrays
            $process = $this->processContentEntry($path, $value);

            // merge the current line with all previous ones
            $result = $this->arrayMergeRecursive($result, $process);
        }
        
        return $result;
    }


    /**
     * Convert a.b.c.d paths to multi-dimensional arrays
     *
     * @param string $path Current ini file's line's key
     * @param mixed $value Current ini file's line's value
     * @return array
     */
    private function processContentEntry($path, $value)
    {
        $pos = strpos($path, '.');

        if ($pos === false) {
            return array($path => $value);
        }

        $key = substr($path, 0, $pos);
        $path = substr($path, $pos + 1);

        $result = array(
            $key => $this->processContentEntry($path, $value),
        );

        return $result;
    }


    /**
     * Merge two arrays recursively overwriting the keys in the first array
     * if such key already exists
     *
     * @param mixed $a Left array to merge right array into
     * @param mixed $b Right array to merge over the left array
     * @return mixed
     */
    private function arrayMergeRecursive($a, $b)
    {
        // merge arrays if both variables are arrays
        if (is_array($a) && is_array($b)) {
            // loop through each right array's entry and merge it into $a
            foreach ($b as $key => $value) {
                if (isset($a[$key])) {
                    $a[$key] = $this->arrayMergeRecursive($a[$key], $value);
                } else {
                    if($key === 0) {
                        $a= array(0 => $this->arrayMergeRecursive($a, $value));
                    } else {
                        $a[$key] = $value;
                    }
                }
            }
        } else {
            // one of values is not an array
            $a = $b;
        }
        return $a;
    }
}
