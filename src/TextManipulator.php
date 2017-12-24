<?php
/**
 * TextManipulator
 * This class provides with the functions that are used to break the text into fragments,
 * and perform other text manipulations.
 *
 * @author      Rijul Gupta <rijulg@neblar.com>
 * @since       16 Dec 2017
 * @copyright   2017 Neblar Technologies
 * @license     MIT
 */

namespace Neblar\DesCardId;

class TextManipulator{

    /**@var int $maxCardLength*/
    private $maxCardLength;

    /**@var int $minCardLength*/
    private $minCardLength;

    /**
     * TextManipulator constructor.
     * The constructor allows to set the minimum and maximum card lengths, these are
     * used to break the text into fragments.
     *
     * @param int $maxCardLength the maximum length of card to detect, this is used to break the text into fragments only
     * @param int $minCardLength the minimum length of card to detect, this is used to break the text into fragments only
     */
    public function __construct($maxCardLength = null, $minCardLength = null){
        $this->maxCardLength = ($maxCardLength === null) ? 20 : $maxCardLength;
        $this->minCardLength = ($minCardLength === null) ? 7 : $minCardLength;
    }

    /**
     * extractNumberFromText
     * extracts a number from the provided text
     *
     * @param  string $text the text from which to extract the number
     * @return string|null the extracted number or null
     */
    public function extractNumberFromText($text){
        $number =  preg_replace('/[^0-9]/', '', $text);
        if(strlen($number) == 0){
            return null;
        }
        return $number;
    }

    /**
     * getContinuousNumbers
     * returns the numbers that appear continuously in given text. For ex:
     * " bla bla 123456 bla bla" will return ["123456"]
     *
     * @param string $text the text from which to extract the numbers
     * @return array the array of numbers extracted
     */
    private function getContinuousNumbers($text){
        preg_match_all('([0-9]+)', $text, $matches);
        return $matches[0];
    }

    /**
     * getDiscontinuousNumbers
     * returns the numbers that appear continuously in given text. For ex:
     * " bla bla 123 456 bla 321-4 5 bla 9876" will return ["123 456", "321-4 5", "9876"]
     *
     * @param string $text the text from which to extract the numbers
     * @return array the array of numbers extracted
     */
    private function getDiscontinuousNumbers($text){
        preg_match_all('([0-9]+(((?![a-zA-Z]))([^a-zA-Z]+))[0-9])', $text, $matches);
        return $matches[0];
    }

    /**
     * getSuspectedFragments
     * breaks the given text into suspected fragments which contain a number.
     * This number will further be inspected for accessing whether it belongs to
     * a credit card or not.
     *
     * @param string $text the string from which to extract the fragments
     * @param int $checkLevel the level of check used to inspect the text and identify numbers [1-2]
     * @return array the array of suspected fragments
     */
    public function getSuspectedFragments($text, $checkLevel){

        switch($checkLevel){
            case 2  :   $fragments = $this->getDiscontinuousNumbers($text);
                        break;
            case 1  :
            default :   $fragments = $this->getContinuousNumbers($text);
        }

        return array_unique($fragments);
    }

    /**
     * markFragment
     * marks the specified fragment in given text with the provided marker
     * The main text is passed by reference, so the original text gets changed.
     *
     * @param string $text      the text in which to do the marking
     * @param string $fragment  the fragment that needs to be marked
     * @param string $marker    the marker used to identify the mark
     *
     * @return string the marked text
     */
    public function markFragment(&$text, $fragment, $marker){
        $replacement = "{{".$fragment."}[".$marker."]}";
        return str_replace($fragment,$replacement,$text);
    }

}