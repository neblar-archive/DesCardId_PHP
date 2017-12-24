<?php
/**
 *  @author Rijul Gupta <rijulg@neblar.com>
 */

namespace Neblar\DesCardId;

class CardNumberValidatorTest extends BaseTestCase{

    /**@var CardNumberValidator $validator*/
    private $validator;

    public function setUp(){
        $this->validator = new CardNumberValidator();
    }

    /**
     * providerCalculateProbabilityOfBeingACreditCard
     * data provider for the test testCalculateProbabilityOfBeingACreditCard
     *
     * the format of the data is as follows:
     *  [testName, testNumber, expectedResult]
     *
     * @return array
     */
    public function providerCalculateProbabilityOfBeingACreditCard(){
        return [
            ['1.1. Invalid number (too short)', '123', 0.0],
            ['1.2. Invalid number (too long)', '12345678901234567890', 0.0],
            ['2.1. Valid number (matches test numbers, passes LUHN, length checks and gets provider MasterCard)', '5555555555554444', 190.0],
        ];
    }

    /**
     * providerIsSurelyACreditCardNumber
     * data provider for the test testIsSurelyACreditCardNumber
     *
     * the format of the data is as follows:
     *  [testName, testNumber, testThreshold, expectedResult]
     *
     * @return array
     */
    public function providerIsSurelyACreditCardNumber(){
        return [
            ['1.1. Invalid number (too short), default threshold', '123', null, false],
            ['1.2. Invalid number (too short), 0 threshold', '123', 0, false],
            ['1.3. Invalid number (too long), default threshold', '12345678901234567890', null, false],
            ['1.4. Invalid number (too long), 0 threshold', '12345678901234567890', 0, false],
            ['2.1. Valid number (test number), default threshold', '5555555555554444', null, true],
            ['2.2. Valid number (test number), impossible threshold', '5555555555554444', 200, false],
            ['2.3. Valid number (test number), exact threshold', '5555555555554444', 190.0, false],
            ['2.4. Valid number (test number), just under threshold', '5555555555554444', 190.01, false],
            ['2.4. Valid number (test number), just over threshold', '5555555555554444', 189.99, true],
        ];
    }

    /**
     * providerSetProbabilities
     * data provider for the test testSetProbabilities
     *
     * the format of the data is as follows:
     *  [testName, testProbabilities, expectedResult]
     *
     * @return array
     */
    public function providerSetProbabilities(){
        return [
            ['1.1. Empty values being set', [], true],
            ['2.1. Invalid key being set', ['foo' => 123], false],
            ['2.2. Invalid key part of valid keys', ['LUHN' => 80, 'foo' => 123], false],
            ['3.1. Valid keys being set (single key)', ['LUHN' => 80], true],
            ['3.1. Valid keys being set (all keys)', ['LUHN' => 80, 'TEST_NUMBERS' => 91, 'PROVIDERS' => 19, 'LENGTH' => 5], true],
        ];
    }

    /**
     * testCalculateProbabilityOfBeingACreditCard
     * tests the calculateProbabilityOfBeingACreditCard function of CardNumberValidator class
     *
     * @param string $testName          the name of the test
     * @param string $testNumber        the number to be tested
     * @param double $expectedResult    the expected result
     *
     * @dataProvider providerCalculateProbabilityOfBeingACreditCard
     */
    public function testCalculateProbabilityOfBeingACreditCard($testName, $testNumber, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->calculateProbabilityOfBeingACreditCard($testNumber),$testName." failed");
    }

    /**
     * testIsPossibleToBeACreditCardNumber
     * tests the isPossibleToBeACreditCardNumber function of CardNumberValidator class
     *
     * @param string $testName          the name of the test
     * @param string $testNumber        the number to be tested
     * @param bool   $expectedResult    the expected result
     *
     * @dataProvider providerValidatePossibility
     */
    public function testIsPossibleToBeACreditCard($testName, $testNumber, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->isPossibleToBeACreditCard($testNumber),$testName." failed");
    }

    /**
     * testIsSurelyACreditCardNumber
     * tests the isSurelyACreditCardNumber function of CardNumberValidator class
     *
     * @param string $testName          the name of the test
     * @param string $testNumber        the number to be tested
     * @param string $testThreshold     the threshold of surety
     * @param bool   $expectedResult    the expected result
     *
     * @dataProvider providerIsSurelyACreditCardNumber
     */
    public function testIsSurelyACreditCardNumber($testName, $testNumber, $testThreshold, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->isSurelyACreditCardNumber($testNumber, $testThreshold),$testName." failed");
    }

    /**
     * testSetProbabilities
     * tests the setProbabilities function of CardNumberValidator class
     *
     * @param string    $testName           the name of the test
     * @param array     $testProbabilities  the probabilities to set
     * @param bool      $expectedResult   the expected result
     *
     * @dataProvider providerSetProbabilities
     */
    public function testSetProbabilities($testName, $testProbabilities, $expectedResult){
        $this->assertEquals($expectedResult,$this->validator->setProbabilities($testProbabilities),$testName." failed");

        if($expectedResult){
            $probabilities = $this->getPrivatePropertyValue( $this->validator, 'probabilities' );
            $this->assertArraySubset($testProbabilities, $probabilities);
        }
    }

}
