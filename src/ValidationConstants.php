<?php
/**
 * ValidationConstants
 * This class contains all the constants that are used for various
 * validations.
 *
 * @author      Rijul Gupta <rijulg@neblar.com>
 * @since       13 Dec 2017
 * @copyright   2017 Neblar Technologies
 * @license     MIT
*/

namespace Neblar\DesCardId;

class ValidationConstants{

    public $MIN_POSSIBLE_LENGTH = 7;
    public $MAX_POSSIBLE_LENGTH = 19;

    /*
     *These are probabilities based on the most common card lengths
    */
    public $PROBABILITIES_LENGTH = [
        16 => 100, /*This is the most common card number length*/
        15 => 100, /*American Express has cards of this length*/
        13 => 80, /*VISA sometimes makes cards of this length*/
    ];

    /*
     * If the identification fingerprints of a type of card are too few
     * i.e. if the regex patter is too short, which in turns means that
     * it might produce more false positives the probability assigned
     * to that particular regex is low.
    */
    public $PROBABILITIES_REGEX_PROVIDERS = [
         /*Regex to identify mastercard*/
        '/^(5[1-5][0-9]{5,}|222[1-9][0-9]{3,}|22[3-9][0-9]{4,}|2[3-6][0-9]{5,}|27[01][0-9]{4,}|2720[0-9]{3,})$/' => 100,
        /*Regex to identify american express*/
        '/^(3[47][0-9]{5,})$/' => 70,
        /*Regex to identify VISA*/
        '/^(4[0-9]{6,})$/' => 50,
        /*Regex to identify Diners Club*/
        '/^(3(?:0[0-5]|[68][0-9])[0-9]{4,})$/' => 85,
        /*Regex to identify Discover*/
        '/^(6(?:011|5[0-9]{2})[0-9]{3,})$/' => 80,
        /*Regex to identify JCB*/
        '/^((?:2131|1800|35[0-9]{3})[0-9]{3,})$/' => 85,
    ];

    /*
     * These are the test numbers that are openly provided by various providers
     * so that we can correctly identify if someone checks for any of These
     * with full certainty
    */
    public $KNOWN_TEST_NUMBERS = [
        '378282246310005',      /*American Express*/
        '371449635398431',      /*American Express*/
        '345436849253786',      /*American Express*/
        '344343597098739',      /*American Express*/
        '348195053148184',      /*American Express*/
        '346761128958196',      /*American Express*/
        '379983963916986',      /*American Express*/
        '376749501879009',      /*American Express*/
        '349204254634213',      /*American Express*/
        '376432510463566',      /*American Express*/
        '378734493671000',      /*American Express Corporate*/
        '5610591081018250',     /*Australian BankCard*/
        '30569309025904',       /*Diners Club*/
        '38520000023237',       /*Diners Club*/
        '30467323783394',       /*Diners Club (Carte Blanche)*/
        '30389589049437',       /*Diners Club (Carte Blanche)*/
        '30213469782901',       /*Diners Club (Carte Blanche)*/
        '36197365718891',       /*Diners Club (International)*/
        '36823785024749',       /*Diners Club (International)*/
        '36251701871102',       /*Diners Club (International)*/
        '5485157059278227',     /*Diners Club (North America)*/
        '5418199988362484',     /*Diners Club (North America)*/
        '5402093870675764',     /*Diners Club (North America)*/
        '6011111111111117',     /*Discover*/
        '6011000990139424',     /*Discover*/
        '6011540018341759',     /*Discover*/
        '6011052057723921',     /*Discover*/
        '6011277618211484585',  /*Discover*/
        '6011861286835722',     /*Discover*/
        '6011890376173660',     /*Discover*/
        '6011464247892518',     /*Discover*/
        '6011244758428047',     /*Discover*/
        '6011469345729306',     /*Discover*/
        '6382961806046593',     /*InstaPayment*/
        '6373413397497463',     /*InstaPayment*/
        '6375275217437369',     /*InstaPayment*/
        '3530111333300000',     /*JCB*/
        '3566002020360505',     /*JCB*/
        '3566111111111113',     /*JCB*/
        '3529844470994754',     /*JCB*/
        '3535754231437369',     /*JCB*/
        '3541031337467299722',  /*JCB*/
        '6762678941084830',     /*Maestro*/
        '5018131548158304',     /*Maestro*/
        '6304521934333993',     /*Maestro*/
        '50339619890917',       /*Maestro (International)*/
        '586824160825533338',   /*Maestro (International)*/
        '6759411100000008',     /*Maestro (UK Domestic)*/
        '6759560045005727054',  /*Maestro (UK Domestic)*/
        '5641821111166669',     /*Maestro (UK Domestic)*/
        '5555555555554444',     /*MasterCard*/
        '5105105105105100',     /*MasterCard*/
        '2222420000001113',     /*MasterCard*/
        '2222630000001125',     /*MasterCard*/
        '5246772059242294',     /*MasterCard*/
        '5365643412360922',     /*MasterCard*/
        '5310506475502852',     /*MasterCard*/
        '5192310560826646',     /*MasterCard*/
        '5174224924081487',     /*MasterCard*/
        '5353732311938484',     /*MasterCard*/
        '5203246075883952',     /*MasterCard*/
        '5186682476306626',     /*MasterCard*/
        '4111111111111111',     /*VISA*/
        '4012888888881881',     /*VISA*/
        '4222222222222',        /*VISA*/
        '4330954187429262',     /*VISA*/
        '4916861873042626',     /*VISA*/
        '4024007176658892119',  /*VISA*/
        '4485992558886887',     /*VISA*/
        '4556556689853209',     /*VISA*/
        '4532379342751077',     /*VISA*/
        '4024007153524987',     /*VISA*/
        '4485643204102613',     /*VISA*/
        '4508138079686538',     /*VISA (electron)*/
        '4026207140510119',     /*VISA (electron)*/
        '4508608593847550',     /*VISA (electron)*/
        '5019717010103742',     /*PBS*/
        '6331101999990016',     /*Paymentech*/
        '135412345678911',      /*UATP*/
    ];
}
