<?php
/**
 *  @author Rijul Gupta <rijulg@neblar.com>
 */

namespace Neblar\DesCardId;


class TextManipulatorTest extends BaseTestCase{

    /**@var TextManipulator $manipulator*/
    private $manipulator;

    public function setUp(){
        $this->manipulator = new TextManipulator();
    }

    /**
     * providerExtractNumberFromText
     * data provider for the test testExtractNumberFromText
     *
     * the format of the data is as follows:
     *  [testName, testText, expectedResult]
     *
     * @return array
     */
    public function providerExtractNumberFromText(){
        return [
            ['1. Text without numbers', 'foo bar', null],
            ['2. Text with numbers (continuous)', 'foo 12345 bar', '12345'],
            ['3. Text with numbers (discontinuous, spaces)', 'foo 12345 67890 bar', '1234567890'],
            ['4. Text with numbers (discontinuous, dashes)', 'foo 123-45-678-90 bar', '1234567890'],
            ['5. Text with numbers (discontinuous, alpha mix)', 'foo 123-bla45-foo678-90 bar', '1234567890'],
            ['6. Text with numbers (discontinuous, alpha mix)', 'foo 123-bla45-foo678-90 bar', '1234567890'],
            ['7. Text with numbers (discontinuous, special chars)', 'foo 123-!@$bla45-fQ@%*(!oo678-90 bar', '1234567890'],
        ];
    }

    /**
     * providerExtractNumberFromText
     * data provider for the test testExtractNumberFromText
     *
     * the format of the data is as follows:
     *  [testName, testText, checkLevel, expectedResult]
     *
     * @return array
     */
    public function providerGetSuspectedFragments(){
        return [
            ['1.1. Default check level, Invalid numbers (none)', 'foo bar', null, []],
            ['1.2. Default check level, Valid numbers (continuous)', 'foo 12345 bar', null, ['12345']],
            ['1.3. Default check level, Valid numbers (discontinuous)', 'foo 213 567 bar', null, ['213', '567']],
            ['2.4. Default check level, Valid numbers (discontinuous)', 'foo 2.1.3 bar', null, ['2', '1', '3']],
            ['2.1. Check level 1, Invalid numbers (none)', 'foo bar', 1, []],
            ['2.2. Check level 1, Valid numbers (continuous)', 'foo 12345 bar', 1, ['12345']],
            ['2.3. Check level 1, Valid numbers (discontinuous)', 'foo 213 567 bar', 1, ['213', '567']],
            ['2.4. Check level 1, Valid numbers (discontinuous)', 'foo 2.1.3 bar', 1, ['2', '1', '3']],
            ['3.1. Check level 2, Invalid numbers (none)', 'foo bar', 2, []],
            ['3.2. Check level 2, Valid numbers (continuous)', 'foo 12345 bar', 2, ['12345']],
            ['3.3. Check level 2, Valid numbers (discontinuous, spaces)', 'foo 213 567 bar', 2, ['213 567']],
            ['3.4. Check level 2, Valid numbers (discontinuous, dashes)', 'foo 21-3-56-7 bar', 2, ['21-3-56-7']],
            ['3.5. Check level 2, Valid numbers (discontinuous, periods)', 'foo 21.3.56.7 bar', 2, ['21.3.56.7']],
            ['3.5. Check level 2, Valid numbers (discontinuous, mixed)', 'foo 21.3-56 7 bar', 2, ['21.3-56 7']],
        ];
    }

    /**
     * providerMarkFragments
     * data provider for the test testMarkFragments
     *
     * the format of the data is as follows:
     *  [testName, testText, fragment, marker, expectedResult]
     *
     * @return array
     */
    public function providerMarkFragment(){
        return [
            ['1.a. Empty fragment, empty marker', 'foo bar', '','', 'foo bar'],
            ['2.a. Non Empty fragment, empty marker', 'foo bar', 'foo','', '{{foo}[]} bar'],
            ['3.a. Empty fragment, non empty marker', 'foo bar', '', 'bar', 'foo bar'],
            ['4.a. Non Empty fragment, non empty marker (single instance)', 'foo bar', 'foo','bar', '{{foo}[bar]} bar'],
            ['4.b. Non Empty fragment, non empty marker (multiple instance)', 'foo bar foo bar', 'foo','bar', '{{foo}[bar]} bar {{foo}[bar]} bar'],
        ];
    }

    /**
     * testInspectText
     * tests the inspectText function of TextManipulator class
     *
     * @param string    $testName       the name of the test
     * @param string    $testText       the text to be tested
     * @param string    $expectedResult the expected result
     *
     * @dataProvider providerExtractNumberFromText
     */
    public function testExtractNumberFromText($testName, $testText, $expectedResult){
        $this->assertEquals($expectedResult, $this->manipulator->extractNumberFromText($testText), $testName." failed");
    }

    /**
     * testGetSuspectedFragments
     * tests the getSuspectedFragments function of TextManipulator class
     *
     * @param string    $testName       the name of the test
     * @param string    $testText       the text to be tested
     * @param int       $checkLevel     the level of check to be done
     * @param array     $expectedResult the expected result
     *
     * @dataProvider providerGetSuspectedFragments
     */
    public function testGetSuspectedFragments($testName, $testText, $checkLevel, $expectedResult){
        $this->assertEquals($expectedResult, $this->manipulator->getSuspectedFragments($testText, $checkLevel), $testName." failed");
    }

    /**
     * testMarkFragment
     * tests the markFragment function of TextManipulator class
     *
     * @param string    $testName       the name of the test
     * @param string    $testText       the text to be tested
     * @param string    $fragment       the fragment to be marked
     * @param string    $marker         the marker to be used for identification
     * @param string    $expectedResult the expected result
     *
     * @dataProvider providerMarkFragment
     */
    public function testMarkFragment($testName, $testText, $fragment, $marker, $expectedResult){
        $testText = $this->manipulator->markFragment($testText, $fragment, $marker);
        $this->assertEquals($expectedResult, $testText, $testName." failed");
    }

}
