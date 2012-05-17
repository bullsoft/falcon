<?php
/**
 * 
 * Wraps a callable specifically for the purpose of lazy-loading an object.
 * 
 */
class Bull_Di_Lazy
{
    /**
     * 
     * A callable to create an object instance.
     * 
     * @var callable
     * 
     */
    protected $callable;
    
    /**
     * 
     * Constructor.
     * 
     * @param callable $callable A callable to create an object instance.
     * 
     * @return void
     * 
     */
    public function __construct(Closure $callable)
    {
        if (!is_callable($callable)) {
            throw new Bull_Di_Exception("ERR_LAZY_NOT_CALLABLE");
        }

        $this->callable = $callable;
    }
    
    /**
     * 
     * Invokes the closure to create the instance.
     * 
     * @return object The object created by the closure.
     * 
     */
    public function __invoke()
    {
        $callable = $this->callable;
        return $callable();
    }
}
