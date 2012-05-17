<?php
/**
 *
 * 命令行类
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Cli
 *
 */
class Bull_Cli_Front
{
    /**
     *
     * A Context object for the Command.
     *
     * @var Bull_Cli_Context
     *
     */
    protected $context;

    /**
     *
     * A Stdio object for the Command.
     *
     * @var Bull_Cli_Stdio
     *
     */
    protected $stdio;
    
    /**
     * 
     * A Getopt object for the Command; retains the short and long options
     * passed at the command line.
     * 
     * @var Bull_Cli_Getopt
     * 
     */
    protected $getopt;
    
    /**
     * 
     * The option definitions for the Getopt object.
     * 
     * @var array
     * 
     */
    protected $options = array();
    
    /**
     * 
     * Should Getopt be strict about how options are processed?  In strict
     * mode, passing an undefined option will throw an exception; in
     * non-strict, it will not.
     * 
     * @var bool
     * 
     */
    protected $options_strict = Bull_Cli_Getopt::STRICT;
    
    /**
     * 
     * The positional (numeric) arguments passed at the command line.
     * 
     * @var array
     * 
     */
    protected $params = array();
    
    /**
     * 
     * Constructor.
     * 
     * @param Bull_Cli_Stdio $stdio Standard input/output streams.
     * 
     */
    public function __construct(array $options)
    {
        $this->stdio   = new Bull_Cli_Stdio();
        $this->context = new Bull_Cli_Context();
        $this->getopt  = new Bull_Cli_Getopt();

        $this->options = $options;
        
        $this->initGetopt();
        $this->initParams();
    }
    
    /**
     * 
     * Passes the Context arguments to `$getopt`.
     * 
     * @return void
     * 
     */
    protected function initGetopt()
    {
        $this->getopt->init($this->options, $this->options_strict);
        $this->getopt->load($this->context->getArgv());
    }
    
    /**
     * 
     * Loads `$params` from `$getopt`.
     * 
     * @return void
     * 
     */
    protected function initParams()
    {
        $this->params = $this->getopt->getParams();
    }

    public function getOpt()
    {
        return $this->getopt;
    }

    public function getStdio()
    {
        return $this->stdio;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getParams()
    {
        return $this->params;
    }
}