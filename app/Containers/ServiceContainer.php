<?php
namespace App\Containers;

class ServiceContainer
{
    private static $instance;


    private function __construct()
    {
        // Private constructor to prevent instantiation outside the class
    }
 
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

     /**
     * Array to store bindings for services.
     * 
     * @var array  
     */
    private array $bindings = [];
    /**
     * Array to store singleton instances.
     * 
     * @var array  
     */
    private array $singletons = [];

    public function bind(string $abstract, object|callable|string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, object|callable|string $concrete): void
    {
        $this->singletons[$abstract] = $concrete;
    }

    public function resolve(string $abstract): object
    {

        // Track resolved services to detect circular dependencies.
        static $resolving = [];

        if (isset($resolving[$abstract]))
        {
            throw new \Exception("Circular dependency detected: {$abstract}");
        }

        // Mark current class as being resolved.
        $resolving[$abstract] = true;

        // Check if it's singleton and return the existing instance.
        if (isset($this->singletons[$abstract]))
        {
            $this->singletons[$abstract] = $this->resolveBinding($this->singletons[$abstract]);

            unset($resolving[$abstract]);

            return  $this->singletons[$abstract];
        }


        // Check if it exists in bindings and return the instance. 
        else if (isset($this->bindings[$abstract]))
        {
            $instance = $this->resolveBinding($this->bindings[$abstract]);

            unset($resolving[$abstract]);

            return $instance;
        }

        // Default: attempt to build the class automatically.
        $instance = $this->build($abstract);

        // Remove the current class from the list of classes currently being resolved. 
        unset($resolving[$abstract]);

        return $instance;
    }

    /**
     * Automatically resolve and instantiate a class with its dependencies.
     * 
     * @param string $classname 
     * @return object
     * @throws \Exception
     */
    protected function build(string $classname): object
    {
        if (!class_exists($classname))
        {
            throw new \Exception("Cannot resolve class '{$classname}'.");
        }

        $reflector = new \ReflectionClass($classname);

        // If no constructor, create a new instance.
        if (!$reflector->getConstructor())
        {
            return $reflector->newInstance();
        }

        // Resolve dependencies for the constructor.
        $parameters = $reflector->getConstructor()->getParameters();


        // Loop over constructor parameter and create instances of them.
        $dependencies = array_map(function ($param) use ($classname)
        {
            $type = $param->getType();

            // Check if parameter is another class and resolve it.
            if ($type && !$type->isBuiltin())
            {
                return $this->resolve($type->getName());
            }

            // Check if parameter have default value and return it.
            if ($param->isDefaultValueAvailable())
            {
                return $param->getDefaultValue();
            }

            throw new \Exception("Cannot resolve parameter '{$param->getName()} of the class '{$classname}'.");
        }, $parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve a binding to an instance.
     *
     * @param callable|string|object $binding
     * @return object
     * @throws \Exception
     */
    private function resolveBinding(callable|string|object $binding): object
    {
        if (is_callable($binding))
        {
            return $binding($this);
        }

        if (is_object($binding))
        {
            return $binding;
        }

        if (is_string($binding))
        {
            return $this->build($binding);
        }


        throw new \Exception("Invalid binding provided.");
    }
}
