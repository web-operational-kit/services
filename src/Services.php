<?php

    namespace WOK\Services;

    /**
     * Service provider and dependency injection container
     *
    **/
    class Services {

        /**
         * @var array   $services       Services collection
        **/
        protected $services     = array();

        /**
         * @var array   $instances      Services instances collection
        **/
        protected $instances    = array();


        /**
         * @var array   $singleton      Services collection singleton
        **/
        static protected $singleton = array();


        /**
         * Generate a service collection singleton
         * @param   string      $name       Singleton name
        **/
        public function __construct($name = 'default') {

            self::$singleton[$name] = &$this;

        }


        /**
         * Retrieve a collection singleton
         * @param   string      $name       Singleton name
         * @return  Services    Returns the services collection
        **/
        static public function getInstance($name = 'default') {

            if(!isset(self::$singleton[$name])) {
                self::$singleton[$name] = new self();
            }

            return self::$singleton[$name];

        }
        

        /**
         * Register a new service
         * @param   string          $name                  Service's name
         * @param   mixed           $constructor           Service's constructor or definition
        **/
        public function addService($name, $constructor) {
            $this->services[$name] =  $constructor;
        }


        /**
         * Check whether a service has been defined or not
         * @param   string          $name                  Service's name
        **/
        public function hasService($name) {
            return isset($this->services[$name]);
        }


        /**
         * Get a service instance
         * @param   string              $name                  Service's name
         * @param   array               $parameters            Service's instanciation parameters
         * @throws  DomainException     Throws an exception if the service has not been defined
         * @return  mixed               Return the service instance
        **/
        public function getService($name, array $parameters = array()) {

            if(!$this->hasService($name)) {
                throw new \DomainException('Undefined service "'.$name.'"');
            }

            // Force instance generation if necessary
            $hash = md5(serialize($parameters));
            if(!isset($this->instances[$name][$hash])) {

                $constructor = $this->services[$name];
                if(is_callable($constructor)) {
                    $instance = call_user_func_array($constructor, $parameters);
                }
                else {
                    $instance = $constructor;
                }

                $this->instances[$name][$hash] = $instance;

            }

            return $this->instances[$name][$hash];

        }


        /**
         * Get a service new instance with custom parameters
         * @param   string              $name                  Service's name
        **/
        public function removeService($name) {

            if($this->hasService($name)) {
                unset($this->services[$name]);
            }

        }



    }
