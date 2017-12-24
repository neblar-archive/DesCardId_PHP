<?php
/**
 *  @author Rijul Gupta <rijulg@neblar.com>
 */

namespace Neblar\DesCardId;

class ValidationFunctionsTest extends BaseTestCase{

    /**@var ValidationFunctions $validator*/
    private $validator;

    public function setUp(){
        $this->validator = new ValidationFunctions();
    }

    /**
     * providerValidateKnownTestNumbers
     * data provider for the test testValidateKnownTestNumbers
     *
     * the format of the data is as follows:
     *  [testName, testNumber, expectedResult]
     *
     * @return array
     */
    public function providerValidateKnownTestNumbers(){
        return [
            ['1. Number exists in constants class','5555555555554444',true],
            ['2. Number does not exist in constants class','123456789123456',false]
        ];
    }

    /**
     * providerValidateLength
     * data provider for the test testValidateLength
     *
     * the format of the data is as follows:
     *  [testName, testNumber, expectedResult]
     *
     * @return array
     */
    public function providerValidateLength(){
        return [
            ['1. Number equal to most common card length (16)', '1234567812345678', 100],
            ['2. Number equal to amex length (15)', '123456781234567', 100],
            ['3. Number equal to occasional VISA (13)', '1234567812345', 80],
            ['4. Unusual number length 8', '12345678', 0],
            ['5. Unusual number length 19', '12345678912345678123', 0],
            ['6. Unusual number length 14', '12345678123456', 0]
        ];
    }

    /**
     * providerValidateLUHN
     * data provider for the test testValidateLUHN
     *
     * the format of the data is as follows:
     *  [testName, testNumber, expectedResult]
     *
     * @return array
     */
    public function providerValidateLUHN(){
        return [
            ['1.1. Valid credit card number (AMEX)', '378282246310005', true],
            ['1.2. Valid credit card number (AMEX Corporate)', '378734493671000', true],
            ['1.3. Valid credit card number (Australian Bank Card)', '5610591081018250', true],
            ['1.4. Valid credit card number (Diners Club)', '30569309025904', true],
            ['1.5. Valid credit card number (Diners Club, Carte Blanche)', '30467323783394', true],
            ['1.6. Valid credit card number (Diners Club, International)', '36197365718891', true],
            ['1.7. Valid credit card number (Diners Club, North America)', '5557962570909157', true],
            ['1.8. Valid credit card number (Discover)', '6011111111111117', true],
            ['1.9. Valid credit card number (InstaPayment)', '6382961806046593', true],
            ['1.10. Valid credit card number (JCB)', '3530111333300000', true],
            ['1.11. Valid credit card number (Maestro)', '6762678941084830', true],
            ['1.12. Valid credit card number (Maestro, International)', '50339619890917', true],
            ['1.13. Valid credit card number (Maestro, UK Domestic)', '6759411100000008', true],
            ['1.14. Valid credit card number (MasterCard)', '5555555555554444', true],
            ['1.15. Valid credit card number (VISA)', '4111111111111111', true],
            ['1.16. Valid credit card number (VISA, electron)', '4508138079686538', true],
            ['1.17 Valid credit card number (PBS)', '5019717010103742', true],
            ['1.18. Valid credit card number (Paymentech)', '6331101999990016', true],
            ['1.19. Valid credit card number (UATP)', '135412345678911', true],
            ['2.1. Invalid credit card number', '123456781234567', false],
            ['2.2. Invalid credit card number', '1234567', false],
        ];
    }

    /**
     * providerValidateProvider
     * data provider for the test testValidateProvider
     *
     * the format of the data is as follows:
     *  [testName, testNumber, expectedResult]
     *
     * @return array
     */
    public function providerValidateProvider(){
        return [
            ['1.1. Valid credit card number (AMEX)', '378282246310005', 70],
            ['1.2. Valid credit card number (AMEX Corporate)', '378734493671000', 70],
            ['1.3. Valid credit card number (Australian Bank Card)', '5610591081018250', 0],
            ['1.4. Valid credit card number (Diners Club)', '30569309025904', 85],
            ['1.5. Valid credit card number (Diners Club, Carte Blanche)', '30467323783394', 85],
            ['1.6. Valid credit card number (Diners Club, International)', '36197365718891', 85],
            ['1.7. Valid credit card number (Diners Club, North America)', '5485157059278227', 100], /*This is actually masterCard's pattern*/
            ['1.8. Valid credit card number (Discover)', '6011111111111117', 80],
            ['1.9. Valid credit card number (InstaPayment)', '6382961806046593', 0],
            ['1.10. Valid credit card number (JCB)', '3530111333300000', 85],
            ['1.11. Valid credit card number (Maestro)', '6762678941084830', 0],
            ['1.12. Valid credit card number (Maestro, International)', '50339619890917', 0],
            ['1.13. Valid credit card number (Maestro, UK Domestic)', '6759411100000008', 0],
            ['1.14. Valid credit card number (MasterCard)', '5555555555554444', 100],
            ['1.15. Valid credit card number (VISA)', '4111111111111111', 50],
            ['1.16. Valid credit card number (VISA, electron)', '4508138079686538', 50],
            ['1.17. Valid credit card number (PBS)', '5019717010103742', 0],
            ['1.18. Valid credit card number (Paymentech)', '6331101999990016', 0],
            ['1.19. Valid credit card number (UATP)', '135412345678911', 0],
            ['2.1. Invalid credit card number', '123456781234568', 0],
            ['2.2. Invalid credit card number', '1234567', 0],
        ];
    }

    /**
     * testValidateKnownTestNumbers
     * tests the validateKnownTestNumbers function of ValidationFunctions class
     *
     * @param string $testName          the name of the test
     * @param string $testNumber        the number to be tested
     * @param bool   $expectedResult    the expected result
     *
     * @dataProvider providerValidateKnownTestNumbers
     */
    public function testValidateKnownTestNumbers($testName, $testNumber, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->validateKnownTestNumbers($testNumber),$testName." failed");
    }

    /**
     * testValidateLength
     * tests the validateLength function of ValidationFunctions class
     *
     * @param string $testName          the name of the test
     * @param string $testNumber        the number to be tested
     * @param int    $expectedResult    the expected result
     *
     * @dataProvider providerValidateLength
     */
    public function testValidateLength($testName, $testNumber, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->validateLength($testNumber),$testName." failed");
    }

    /**
     * testValidateLUHN
     * tests the validateLUHN function of ValidationFunctions class
     *
     * @param string $testName          the name of the test
     * @param string $testNumber        the number to be tested
     * @param string $expectedResult    the expected result
     *
     * @dataProvider providerValidateLUHN
     */
    public function testValidateLUHN($testName, $testNumber, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->validateLUHN($testNumber),$testName." failed");
    }

    /**
     * testValidatePossibility
     * tests the validatePossibility function of ValidationFunctions class
     *
     * @param string $testName          the name of the test
     * @param string $testNumber        the number to be tested
     * @param bool   $expectedResult    the expected result
     *
     * @dataProvider providerValidatePossibility
     */
    public function testValidatePossibility($testName, $testNumber, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->validatePossibility($testNumber),$testName." failed");
    }

    /**
     * testValidateProvider
     * tests the validateProvider function of ValidationFunctions class
     *
     * @param string $testName          the name of the test
     * @param string $testNumber        the number to be tested
     * @param double $expectedResult    the expected result
     *
     * @dataProvider providerValidateProvider
     */
    public function testValidateProvider($testName, $testNumber, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->validateProvider($testNumber),$testName." failed");
    }

  
}