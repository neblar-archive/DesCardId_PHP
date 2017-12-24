<?php
/**
 * CardNumberValidator
 * This class provides with the functions that can be used to validate a
 * card number.
 *
 * You should first ensure that a number can possibly be a credit card number
 * by calling "isImpossibleToBeACreditCard" function and checking that it is
 * false, after that you can check if we can surely say that a number is a
 * credit card number or not by calling "isSurelyACreditCardNumber".
 * The level of threshold for check can be set and should be decided based
 * on how sure you want to be.
 *
 * @author      Rijul Gupta <rijulg@neblar.com>
 * @since       12 Dec 2017
 * @copyright   2017 Neblar Technologies
 * @license     MIT
*/

namespace Neblar\DesCardId;

class CardNumberValidator{

    const DEFAULT_THRESHOLD = 85;

    /**@var array $probabilities*/
    private $probabilities;

    /**@var ValidationFunctions $validator*/
    private $validator;

    /**
     * CardNumberValidator constructor.
     * the constructor allows us to customize the probabilities used for the validation check as well as
     * define the constants that should be used to conduct the validation
     *
     * @param array                 $probabilities  the probabilities assigned to various validations in ValidationFunctions
     * @param ValidationConstants   $constantsClass the constants used for validation
     */
    public function __construct($probabilities = null, $constantsClass = null){
        if($probabilities === null){
            $this->probabilities = [
                'LUHN'          => 60,
                'TEST_NUMBERS'  => 100,
                'PROVIDERS'     => 15,
                'LENGTH'        => 15,
            ];
        }
        else{
            $this->probabilities = $probabilities;
        }

        if($constantsClass === null){
            $this->validator = new ValidationFunctions();
        }
        else{
            $this->validator = new ValidationFunctions($constantsClass);
        }
    }

    /**
     * calculateProbabilityOfBeingACreditCard
     * This function calculates the probability of a number being associated
     * with a credit card.
     * The probability associated with each check is declared separately in
     * a constants class
     *
     * @param  string $number the number to be checked
     * @return double total probability of a number being a credit card number
    */
    public function calculateProbabilityOfBeingACreditCard($number){

        $probability = 0;

        if($this->validator->validateKnownTestNumbers($number)){
            $probability += $this->probabilities['TEST_NUMBERS'];
        }

        if($this->validator->validateLUHN($number)){
            $probability += $this->probabilities['LUHN'];
        }

        $probability += ( $this->validator->validateProvider($number) * ($this->probabilities['PROVIDERS'] / 100) );

        $probability += ( $this->validator->validateLength($number) * ($this->probabilities['LENGTH'] / 100) );

        return $probability;
    }

    /**
     * isPossibleToBeACreditCard
     * wrapper function to check whether the number can possibly be a credit
     * card number or not.
     *
     * @param  string $number the number to be checked
     * @return bool   true if number can be a credit card, false otherwise
    */
    public function isPossibleToBeACreditCard($number){
        return $this->validator->validatePossibility($number);
    }

    /**
     * isSurelyACreditCardNumber
     * compares the probability of given number being a credit card number
     * to specified threshold. If the threshold is not specified, it gets
     * defaulted to the defaults set in the constants class
     *
     * @param  string $number    the number to be checked
     * @param  double $threshold the tolerable level of uncertainty
     * @return bool   true if we are sure, false otherwise
    */
    public function isSurelyACreditCardNumber($number, $threshold = null){
        if($threshold === null){
            $threshold = self::DEFAULT_THRESHOLD;
        }

        $probability = $this->calculateProbabilityOfBeingACreditCard($number);

        return ($probability > $threshold);
    }

    /**
     * setProbabilities
     * This is a helper function that helps set the probabilities for
     * calculation. This allows the users to customize the calculation for
     * their needs.
     * If the setup fails on any step it reverts to the original settings.
     *
     * @param    array $keyValuePairs array of key => value pairs to be set
     * @return   bool  false if fails on any step, true otherwise
    */
    public function setProbabilities($keyValuePairs){
        $originalProbabilities = $this->probabilities;
        foreach ($keyValuePairs as $key => $value) {
            if(isset($this->probabilities[$key])){
                $this->probabilities[$key] = $value;
            }
            else{
                $this->probabilities = $originalProbabilities;
                return false;
            }
        }
        return true;
    }

}
