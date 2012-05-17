<?php                                                                                          
/**                                                                                            
 *                                                                                             
 * Provides a wrapper for standard input/output handles.                                       
 *                                                                                             
 * @author Gu Weigang <guweigagn@baidu.com>
 *
 * @package Bull.Cli
 *
 */
class Bull_Cli_Stdio
{

    /**                                                                                        
     *                                                                                         
     * A handle for standard input.                                                            
     *                                                                                         
     * @var resource                                                                           
     *                                                                                         
     */
    protected $stdin;

    /**                                                                                        
     *                                                                                         
     * A handle for standard output.                                                           
     *                                                                                         
     * @var resource                                                                           
     *                                                                                         
     */
    protected $stdout;

    /**                                                                                        
     *                                                                                         
     * A handle for standard error.                                                            
     *                                                                                         
     * @var resource                                                                           
     *                                                                                         
     */
    protected $stderr;

    /**                                                                                                    
     *                                                                                                     
     * A Vt100 object to format output.                                                                    
     *                                                                                                     
     * @var Vt100                                                                                          
     *                                                                                                     
     */
    protected $vt100;

    /**                                                                                                    
     *                                                                                                     
     * Constructor.                                                                                        
     *                                                                                                     
     * @param resource $stdin A handle for standard input.                                                 
     *                                                                                                     
     * @param resource $stdout A handle for standard output.                                               
     *                                                                                                     
     * @param resource $stderr A handle for standard error.                                                
     *                                                                                                     
     */
    public function __construct ()
    {
        $this->stdin  = fopen('php://stdin', 'r');
        $this->stdout = fopen('php://stdout', 'w+');
        $this->stderr = fopen('php://stderr', 'w+');
        $this->vt100  = new Bull_Cli_Vt100();
    }

    /**                                                                                                    
     *                                                                                                     
     * Returns the standard input handle.                                                                  
     *                                                                                                     
     * @return resource                                                                                    
     *                                                                                                     
     */
    public function getStdin()
    {
        return $this->stdin;
    }
    /**                                                                                  
     *                                                                                   
     * Returns the standard output handle.                                               
     *                                                                                   
     * @return resource                                                                  
     *                                                                                   
     */
    public function getStdout()
    {
        return $this->stdout;
    }

    /**                                                                                  
     *                                                                                   
     * Returns the standard error handle.                                                
     *                                                                                   
     * @return resource                                                                  
     *                                                                                   
     */
    public function getStderr()
    {
        return $this->stderr;                                                            
    }

    /**                                                                                  
     *                                                                                   
     * Gets user input from the command line and trims the end-of-line.                  
     *                                                                                   
     * @return void                                                                      
     *                                                                                   
     */
    public function in()
    {
        return rtrim(fgets($this->stdin), PHP_EOL);
    }

    /**                                                                                  
     *                                                                                   
     * Gets user input from the command line and leaves the end-of-line in               
     * place.                                                                            
     *                                                                                   
     * @return void                                                                      
     *                                                                                   
     */
    public function inln()
    {
        return fgets($this->stdin);
    }

    /**                                                                                      
     *                                                                                       
     * Prints text to standard output via the Vt100 formatter **without**                    
     * a trailing newline.                                                                   
     *                                                                                       
     * @param string $string The text to print to standard output.                           
     *                                                                                       
     * @return void                                                                          
     *                                                                                       
     */
    public function out($string = null)
    {
        $this->vt100->write($this->stdout, $string);                                         
    }

    /**                                                                                      
     *                                                                                       
     * Prints text to standard output via the Vt100 formatter **with**                       
     * a trailing newline.                                                                   
     *                                                                                       
     * @param string $string The text to print to standard output.                           
     *                                                                                       
     * @return void                                                                          
     *                                                                                       
     */
    public function outln($string = null)
    {
        $this->vt100->writeln($this->stdout, $string);
    }

    /**                                                                                      
     *                                                                                       
     * Prints text to standard error via the Vt100 formatter **without**                     
     * a trailing newline.                                                                   
     *                                                                                       
     * @param string $string The text to print to standard error.                        
     *                                                                                   
     * @return void                                                                      
     *                                                                                   
     */
    public function err($string = null)
    {
        $this->vt100->write($this->stderr, $string);
    }

    /**                                                                                  
     *                                                                                   
     * Prints text to standard error via the Vt100 formatter **without**                 
     * a trailing newline.                                                               
     *                                                                                   
     * @param string $string The text to print to standard error.                        
     *                                                                                   
     * @return void                                                                      
     *                                                                                   
     */
    public function errln($string = null)
    {
        $this->vt100->writeln($this->stderr, $string);
    }
}
