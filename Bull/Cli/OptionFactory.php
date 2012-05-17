<?php
/**
 * 
 * A factory to create Option objects.
 * 
 * @package Bull.Cli
 * 
 */
class Bull_Cli_OptionFactory
{
    /**
     * 
     * An array of default parameters for Option objects.
     * 
     * @var array
     * 
     */
    protected $params = array(
        'name'    => null,
        'long'    => null,
        'short'   => null,
        'param'   => null,
        'multi'   => null,
        'default' => null,
    );
    
    /**
     * 
     * Creates and returns a new Option object.
     * 
     * @param array $params An array of key-value pairs corresponding to
     * Option constructor params.
     * 
     * @return Bull_Cli_Option
     * 
     */
    public function newInstance(array $params)
    {
        $params = array_merge($this->params, $params);
        return new Bull_Cli_Option(
            $params['name'],
            $params['long'],
            $params['short'],
            $params['param'],
            $params['multi'],
            $params['default']
        );
    }
}
