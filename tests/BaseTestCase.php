<?php
/**
 *  @author Rijul Gupta <rijulg@neblar.com>
 */

namespace Neblar\DesCardId;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use ReflectionProperty;

class BaseTestCase extends PHPUnit_Framework_TestCase{

    const IDENTIFICATION_PATTERN_REGEX = "{{.*}\[.*\]}";

    /**
     * assertContainsNoIdentifications
     * this function is used to ensure that a given piece of code does not have any
     * identification patterns.
     *
     * @param string $testName          the name of the test
     * @param string $identifiedText    the text to be tested
     */
    public function assertContainsNoIdentifications($testName, $identifiedText){
        $this->assertEquals(0,preg_match(self::IDENTIFICATION_PATTERN_REGEX, $identifiedText),$testName.' failed');
    }

    /**
     * getPrivateProperty
     *
     * @author	Joe Sexton <joe@webtipblog.com>
     * @param 	string $className
     * @param 	string $propertyName
     * @return	ReflectionProperty
     */
    public function getPrivateProperty( $className, $propertyName ) {
        $reflector = new ReflectionClass( 'Neblar\\DesCardId\\'.$className );
        $property = $reflector->getProperty( $propertyName );
        $property->setAccessible( true );

        return $property;
    }

    /**
     * getPrivateProperty
     *
     * @param 	\stdClass   $object
     * @param 	string      $propertyName
     * @return	ReflectionProperty
     */
    public function getPrivatePropertyValue( $object, $propertyName ){
        $reflector = new ReflectionClass( $object );
        $property = $reflector->getProperty( $propertyName );
        $property->setAccessible( true );
        return $property->getValue($object);
    }

    /**
     * providerValidatePossibility
     * data provider for the test testValidatePossibility
     *
     * the format of the data is as follows:
     *  [testName, testNumber, expectedResult]
     *
     * @return array
     */
    public function providerValidatePossibility(){
        return [
            ['1.1. Number consisting of other characters', '1234 5678 1234 5678', false],
            ['1.2. Number consisting of other characters', '1234-5678-1234-5678', false],
            ['1.3. Number consisting of other characters', '1234.5678-1234 5678', false],
            ['2. Number shorter than minimum length', '123456', false],
            ['3. Number greater than maximum length', '12345678901234567890', false],
            ['4. Number equal to minimum length', '1234567', true],
            ['5. Number equal to maximum length', '1234567890123456789', true],
            ['6. Number between minimum and maximum length', '1234567890', true],
            ['7. Empty number', '', false],
        ];
    }
}