<?php
/**
 * ValidationFunctions
 * This class contains all the various algorithms we employ in determining
 * that a given number belongs to a credit card or not.
 *
 * Only a valid, formatted number should be passed to these functions.
 * Formatted here implies that there should be no spaces or special
 * characters between the provided number.
 *
 * The functions do not have an extra layer of number validation because
 * that particular check should happen before you access any of these.
 *
 * @author      Rijul Gupta <rijulg@neblar.com>
 * @since       13 Dec 2017
 * @copyright   2017 Neblar Technologies
 * @license     MIT
*/

namespace Neblar\DesCardId;

class ValidationFunctions{

    /**@var ValidationConstants $constants*/
    private $constants;

    /**
     * ValidationFunctions constructor.
     * The constructor allows us to customize the constants that will be used to conduct the validations.
     *
     * @param ValidationConstants   $constantsClass the constants used for validation
     */
    public function __construct($constantsClass = null){
        $this->constants = ($constantsClass === null) ? new ValidationConstants() : $constantsClass;
    }

    /**
     * validateKnownTestNumbers
     * checks if the given number matches any of the disclosed test numbers
     * provided by the major companies
     *
     * @param  string $number the number to be checked
     * @return bool   true if number matches a known test number, false otherwise
    */
    public function validateKnownTestNumbers($number){
        return in_array($number,$this->constants->KNOWN_TEST_NUMBERS,true);
    }

    /**
     * validateLength
     * returns the likelihood of a number being a credit card number based
     * just on it's length. So a common length like 16 would return 100,
     * an uncommon one like 18 would return 60 while others would return 0.
     *
     * @param  string $number the number to be checked
     * @return int likelihood associated with length of number
    */
    public function validateLength($number){
        $length = strlen($number);
        if(isset($this->constants->PROBABILITIES_LENGTH[$length])){
            return $this->constants->PROBABILITIES_LENGTH[$length];
        }
        return 0;
    }

    /**
     * validateLUHN
     * performs a LUHN check on the number
     *
     * @param  string $number the number to be checked
     * @return bool   true if passes the check, false otherwise
    */
    public function validateLUHN($number){
        $sum = 0;
        $length = strlen($number);
        $lastDigit = $number[$length-1];
        $number = substr($number,0,$length-1);
        $number = strrev($number);
        for($i=0; $i<$length-1; $i++){
            $digit = $number[$i];

            if($i%2 == 0){
                $digit *= 2;
                if ($digit > 9){
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }
        return ((($sum+$lastDigit)%10)==0);
    }

    /**
     * validatePossibility
     * determines whether it is at least possible for a given number
     * to be a credit card or not.
     *
     * @param  string $number the number to be checked
     * @return bool   true if it is possible, false otherwise
    */
    public function validatePossibility($number){

        if(ctype_digit($number) === false){
            return false;
        }

        $length = strlen($number);

        if($length < $this->constants->MIN_POSSIBLE_LENGTH){
            return false;
        }

        if($length > $this->constants->MAX_POSSIBLE_LENGTH){
            return false;
        }

        return true;
    }

    /**
     * validateProvider
     * checks if a provider can be identified for the given card number
     * and returns the certainty of identification mapped from 0 to 100
     *
     * @param  string $number the number to be checked
     * @return double probability of surety of identification of a provider
    */
    public function validateProvider($number){
        foreach($this->constants->PROBABILITIES_REGEX_PROVIDERS as $regex => $probability){
            if(preg_match($regex, $number)){
                return $probability;
            }
        }
        return 0;
    }

}
