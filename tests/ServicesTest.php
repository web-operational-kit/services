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
         * Test services parameters
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

        /**
         * Test if constructors are callable
        **/
        public function testCallability() {

            $services = new Services();

            $services->addService('callableFunction',           'callableFunction');
            $services->addService('callableClosure',            function() { return 'callableClosure-OK'; });
            $services->addService('callableObject',             $callableObject = new callableObject());
            $services->addService('callableClassMethod',        [callableObject::class,'callableMethod']);
            $services->addService('notCallableObject',          new notCallableObject());
            $services->addService('callableClassConstructor',          callableClassConstructor::class);

            $this->assertEquals('callableFunction-OK',      $services->getService('callableFunction'));
            $this->assertEquals('callableClosure-OK',       $services->getService('callableClosure'));
            $this->assertEquals('callableInvokeObject-OK',  $services->getService('callableObject'));
            $this->assertEquals('callableClassMethod-OK',   $services->getService('callableClassMethod'));
            $this->assertFalse(
                ($services->getService('callableClassConstructor') instanceof callableClassConstructor)
            );
            $this->assertNotEquals('notCallableObject-OK',   $services->getService('notCallableObject'));

        }

    }


    /**
     * Required Types
     * @see above testCallability
    **/
    function callableFunction() {
        return 'callableFunction-OK';
    }

    class callableClassConstructor {}

    class callableObject {

        public function callableMethod() {
            return 'callableClassMethod-OK';
        }

        public function __invoke() {
            return 'callableInvokeObject-OK';
        }
    }

    class notCallableObject {}
