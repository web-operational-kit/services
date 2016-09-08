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
        protected $services = array();


        /**
         * Register a new service
         * @param   string          $name                  Service's name
         * @param   Closure         $constructor           Service's constructor
         * @param   boolean         $autoreset             Force service reinstanciation at each call
        **/
        public function addService($name, \Closure $constructor, $autoreset = false) {

            $this->services[$name] = (object) array(
                'instance'      => null,
                'constructor'   => $constructor,
                'autoreset'     => $autoreset
            );

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
         * @param   boolean             $newinstance           Force service new instance
         * @throws  DomainException     Throws an exception if the service has not been defined
         * @return  mixed               Return the service instance
        **/
        public function getService($name, array $parameters = array(), $newinstance = false) {

            if(!$this->hasService($name)) {
                throw new \DomainException('Undefined service "'.$name.'"');
            }

            $service = &$this->services[$name];

            // Instanciate service with it's constructor
            if($newinstance || $service->autoreset) {
                return $this->getInstance($service->constructor, $parameters);
            }
            elseif(is_null($service->instance)) {
                $service->instance = $this->getInstance($service->constructor, $parameters);
            }

            return $service->instance;

        }


        /**
         * Get a service new instance with custom parameters
         * @param   Closure     $constructor        Service constructor
         * @param   array       $parameters         Service instance parameters
         * @return  mixed       Returns service instance
        **/
        protected function getInstance(\Closure $constructor, array $parameters = array()) {

            return call_user_func_array($constructor, $parameters);

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
