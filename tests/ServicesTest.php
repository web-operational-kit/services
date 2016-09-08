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

        /**
         * Test reinstanciable services
         * A. Test no reset
         * B. Test auto reset
         * C. Test force reset
        **/
        public function testReinstanciableServices() {

            // A. Test no reset
            $this->services->addService('autoreset', function(array $parameters) {
                return $parameters;
            }, false);

            $pa = ['a'=>'b'];
            $a = $this->services->getService('autoreset', [$pa]);
            $this->assertEquals($pa, $a);

            $pb = ['c'=>'d'];
            $b = $this->services->getService('autoreset', [$pb]);
            $this->assertNotEquals($pb, $b);


            // B. Test auto reset
            $this->services->addService('autoreset', function(array $parameters) {
                return $parameters;
            }, true);

            $pa = ['a'=>'b'];
            $a = $this->services->getService('autoreset', [$pa]);
            $this->assertEquals($pa, $a);

            $pb = ['c'=>'d'];
            $b = $this->services->getService('autoreset', [$pb]);
            $this->assertEquals($pb, $b);

            // C. Test force reset
            $this->services->addService('autoreset', function(array $parameters) {
                return $parameters;
            }, false);

            $pa = ['a'=>'b'];
            $a = $this->services->getService('autoreset', [$pa]);
            $this->assertEquals($pa, $a);

            $pb = ['c'=>'d'];
            $b = $this->services->getService('autoreset', [$pb], true);
            $this->assertEquals($pb, $b);

        }

    }
