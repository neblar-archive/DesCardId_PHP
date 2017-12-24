<?php
/**
 *  @author Rijul Gupta <rijulg@neblar.com>
 */

namespace Neblar\DesCardId;

class CardIdentifierTest extends BaseTestCase{

    /**
     * providerInspectText
     * data provider for the test testInspectText
     *
     * the format of the data is as follows:
     *  [testName, testText, expectedResult]
     *
     * @return array
     */
    public function providerInspectText(){
        return [
            ['1.1. Text contains invalid numbers (none)', 'foo bar bla bla', null],
            ['1.2. Text contains invalid numbers (continuous)', 'foo 12345 bla bla', null],
            ['1.3. Text contains invalid numbers (discontinuous)', 'foo 12 34 56 bla bla', null],
            ['2.1. Text contains valid numbers (continuous, test number)', 'foo 5555555555554444 bla bla', ['{{5555555555554444}[ALERT]}']],
            ['2.2. Text contains valid numbers (discontinuous, spaces, test number)', 'foo 5555 5555 5555 4444 bla bla', ['{{5555 5555 5555 4444}[ALERT]}']],
            ['2.3. Text contains valid numbers (discontinuous, dashes, test number)', 'foo 5555-5555-5555-4444 bla bla', ['{{5555-5555-5555-4444}[ALERT]}']],
            ['2.4. Text contains valid numbers (discontinuous, periods, test number)', 'foo 5555.5555.5555.4444 bla bla', ['{{5555.5555.5555.4444}[ALERT]}']],
            ['3.1. Text contains possible numbers (continuous)', 'foo 555555555555 bla bla', null],
            ['3.2. Text contains possible numbers (discontinuous, spaces)', 'foo 555 555 555 555 bla bla', null],
            ['3.3. Text contains possible numbers (discontinuous, dashes)', 'foo 555-555-555-555 bla bla', null],
            ['3.4. Text contains possible numbers (discontinuous, periods)', 'foo 555.555.555.555 bla bla', null],
        ];
    }

    /**
     * providerInspectTextWithNotices
     * data provider for the test testInspectTextWithNotices
     *
     * the format of the data is as follows:
     *  [testName, testText, expectedResult]
     *
     * @return array
     */
    public function providerInspectTextWithNotices(){
        return [
            ['1.1. Text contains invalid numbers (none)', 'foo bar bla bla', null],
            ['1.2. Text contains invalid numbers (continuous)', 'foo 12345 bla bla', null],
            ['1.3. Text contains invalid numbers (discontinuous)', 'foo 12 34 56 bla bla', null],
            ['1.4. Text contains invalid numbers (phone number)', 'foo 0423607729 bla bla', null],
            ['1.5. Text contains invalid numbers (ABN)', 'foo 53004085616 bla bla', null],
            ['2.1. Text contains valid numbers (continuous, test number)', 'foo 5555555555554444 bla bla', ['{{5555555555554444}[ALERT]}']],
            ['2.2. Text contains valid numbers (discontinuous, spaces, test number)', 'foo 5555 5555 5555 4444 bla bla', ['{{5555 5555 5555 4444}[ALERT]}']],
            ['2.3. Text contains valid numbers (discontinuous, dashes, test number)', 'foo 5555-5555-5555-4444 bla bla', ['{{5555-5555-5555-4444}[ALERT]}']],
            ['2.4. Text contains valid numbers (discontinuous, periods, test number)', 'foo 5555.5555.5555.4444 bla bla', ['{{5555.5555.5555.4444}[ALERT]}']],
            ['3.1. Text contains possible numbers (continuous)', 'foo 5555555555555555 bla bla', ['{{5555555555555555}[NOTICE]}']],
            ['3.2. Text contains possible numbers (discontinuous, spaces)', 'foo 5555 5555 5555 5555 bla bla', ['{{5555 5555 5555 5555}[NOTICE]}']],
            ['3.3. Text contains possible numbers (discontinuous, dashes)', 'foo 5555-5555-5555-5555 bla bla', ['{{5555-5555-5555-5555}[NOTICE]}']],
            ['3.4. Text contains possible numbers (discontinuous, periods)', 'foo 5555.5555.5555.5555 bla bla', ['{{5555.5555.5555.5555}[NOTICE]}']],
        ];
    }

    /**
     * providerInspectTextWithNoticesWithoutThreshold
     * data provider for the test testInspectTextWithNoticesWithoutThreshold
     *
     * the format of the data is as follows:
     *  [testName, testText, expectedResult]
     *
     * @return array
     */
    public function providerInspectTextWithNoticesWithoutThreshold(){
        return [
            ['1.1. Text contains invalid numbers (none)', 'foo bar bla bla', null],
            ['1.2. Text contains invalid numbers (continuous)', 'foo 12345 bla bla', null],
            ['1.3. Text contains invalid numbers (discontinuous)', 'foo 12 34 56 bla bla', null],
            ['1.4. Text contains invalid numbers (phone number)', 'foo 0423607729 bla bla', ['{{0423607729}[NOTICE]}']],
            ['1.5. Text contains invalid numbers (ABN)', 'foo 53004085616 bla bla', ['{{53004085616}[NOTICE]}']],
            ['2.1. Text contains valid numbers (continuous, test number)', 'foo 5555555555554444 bla bla', ['{{5555555555554444}[ALERT]}']],
            ['2.2. Text contains valid numbers (discontinuous, spaces, test number)', 'foo 5555 5555 5555 4444 bla bla', ['{{5555 5555 5555 4444}[ALERT]}']],
            ['2.3. Text contains valid numbers (discontinuous, dashes, test number)', 'foo 5555-5555-5555-4444 bla bla', ['{{5555-5555-5555-4444}[ALERT]}']],
            ['2.4. Text contains valid numbers (discontinuous, periods, test number)', 'foo 5555.5555.5555.4444 bla bla', ['{{5555.5555.5555.4444}[ALERT]}']],
            ['3.1. Text contains possible numbers (continuous)', 'foo 5555555555555555 bla bla', ['{{5555555555555555}[NOTICE]}']],
            ['3.2. Text contains possible numbers (continuous)', 'foo 555555555555 bla bla', ['{{555555555555}[NOTICE]}']],
            ['3.3. Text contains possible numbers (discontinuous, spaces)', 'foo 5555 5555 5555 5555 bla bla', ['{{5555 5555 5555 5555}[NOTICE]}']],
            ['3.4. Text contains possible numbers (discontinuous, dashes)', 'foo 5555-5555-5555-5555 bla bla', ['{{5555-5555-5555-5555}[NOTICE]}']],
            ['3.5. Text contains possible numbers (discontinuous, periods)', 'foo 5555.5555.5555.5555 bla bla', ['{{5555.5555.5555.5555}[NOTICE]}']],
        ];
    }

    /**
     * testInspectText
     * tests the inspectText function of CardIdentifier class
     *
     * @param string        $testName          the name of the test
     * @param string        $testText          the text to be tested
     * @param array|null    $expectedResults   the expected identifications
     *
     * @dataProvider providerInspectText
     */
    public function testInspectText($testName, $testText, $expectedResults){
        $identifier = new CardIdentifier();
        $identifiedText = $identifier->inspectText($testText);
        if($expectedResults === null){
            $this->assertContainsNoIdentifications($testName, $identifiedText);
        }
        else {
            foreach ($expectedResults as $expectedResult) {
                $this->assertContains($expectedResult, $identifiedText, $testName . " failed");
            }
        }
    }

    /**
     * testInspectText
     * tests the inspectTextWithNotices function of CardIdentifier class
     *
     * @param string        $testName          the name of the test
     * @param string        $testText          the text to be tested
     * @param array|null    $expectedResults   the expected identifications
     *
     * @dataProvider providerInspectTextWithNotices
     */
    public function testInspectTextWithNotices($testName, $testText, $expectedResults){
        $identifier = new CardIdentifier(85, 20);
        $identifiedText = $identifier->inspectTextWithNotices($testText);
        if($expectedResults === null){
            $this->assertContainsNoIdentifications($testName, $identifiedText);
        }
        else {
            foreach ($expectedResults as $expectedResult) {
                $this->assertContains($expectedResult, $identifiedText, $testName . " failed");
            }
        }
    }
    /**
     * testInspectTextWithNoticesWithoutThreshold
     * tests the inspectTextWithNoticesWithoutThreshold function of CardIdentifier class
     *
     * @param string        $testName          the name of the test
     * @param string        $testText          the text to be tested
     * @param array|null    $expectedResults   the expected identifications
     *
     * @dataProvider providerInspectTextWithNoticesWithoutThreshold
     */
    public function testInspectTextWithNoticesWithoutThreshold($testName, $testText, $expectedResults){
        $identifier = new CardIdentifier(85);
        $identifiedText = $identifier->inspectTextWithNotices($testText);
        if($expectedResults === null){
            $this->assertContainsNoIdentifications($testName, $identifiedText);
        }
        else {
            foreach ($expectedResults as $expectedResult) {
                $this->assertContains($expectedResult, $identifiedText, $testName . " failed");
            }
        }
    }

}
