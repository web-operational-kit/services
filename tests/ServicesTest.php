<?php

    use PHPUnit\Framework\TestCase;

    use \WOK\Services\Services;


    class ServicesTest extends TestCase {


        public function __construct() {

            $this->services = new \WOK\Services\Services;

        }

        /**
         * Test adding services
        **/
        public function testAddService() {

            $instance = new \StdClass();
            $instance->addService = 'addService';

            $this->services->addService('addedService', function() use($instance) {
                return $instance;
            });

            $this->assertEquals($instance, $this->services->getService('addedService'));

        }

        /**
         * Test checking if a service has been defined
        **/
        public function testHasService() {

            $this->services->addService('addedService', function() {
                return new \StdClass();
            });

            $this->assertTrue($this->services->hasService('addedService'));

        }

        /**
         * Test getting a service
        **/
        public function testGetService() {

            $instance = new \StdClass();
            $instance->getService = 'getService';

            $this->services->addService('gettableService', function() use ($instance) {
                return $instance;
            });

            $this->assertEquals($instance, $this->services->getService('gettableService'));

        }

        /**
         * Test removable service
        **/
        public function testRemoveService() {

            $this->services->addService('removable', function() {
                return new StdClass();
            });

            $this->services->removeService('removable');

            $this->assertTrue(!$this->services->hasService('removable'));

        }


        /**
         * Test removable services
        **/
        public function testServiceWithParameters() {

            $this->services->addService('serviceWithParameters', function(array $parameters) {
                return new ArrayObject($parameters);
            }, true);

            $parameters = array(
                'a' => 'b',
                'c' => 'd',
                'e' => 'f'
            );

            $instance = $this->services->getService('serviceWithParameters', [$parameters]);

            foreach($parameters as $parameter => $value) {
                $this->assertEquals($value, $instance[$parameter]);
            }

        }

    }
