# DesCardId_PHP
DesCardId (of Card Identification)
is an open source library written in JavaScript and PHP that can be used to identify credit card numbers in any given text with certain degree of probability.

## How it works
The library follows the following process to identify credit card numbers in a given text:
 1. Identify fragments of text which have numbers in them. These are fragments without alphabets but may contain other characters for example spaces, dashes etc. The idea here is that if people write down a credit card number they would generally not include alphabets in between the number unless they are deliberately trying to bypass checks. For example, "Lorem ipsum 123 456 foo bar", would result in extraction of the fragment " 123 456 ", while "Lorem 123 ipsum 456 foo 789 bar" would result in extraction of " 123 ", " 456 ", " 789 " as separate fragments.
 2. These fragments are then stripped of all special characters, leaving behind only the number that they contain. For example, a fragment like "1234 - - 567 .. 89" would then be converted into "123456789".
 3. This number is then tested to check if it could be a credit card or not, this follows a separate sub process outlined here:
    1. Check if the number can possibly be a credit card number. This check is used to weed out numbers that are too short or too long to be credit card numbers, and check for any other basic characteristics which a credit card number must absolutely have.
    2. Calculate the probability of the number actually being a credit card number. This involves performing a check sum aka LUHN check, a validation based on length to match with popular card lengths, trying to match the number with a popular card provider and matching the number to known set of test numbers.
    3. The total probability assigned to a number is then compared with a threshold, if the probability is over the threshold then we are sure that it is a credit card number otherwise we are not.   

## Examples
Upon use of the main class's (CardIdentifier) inspectText function we get the following results:

**Provided text** ``foo 5555555555554444 bla bla``

**Resulting text** ``foo {{5555555555554444}[ALERT]} bla bla``
     
[**Interactive example**](http://neblar.com/softwareDevelopment/desCardId/desCardId_php)    

## How to Use
1. Include the library either by any of the following methods:
    * **Composer dependency**
    
        You can include the library as a composer dependency and then use it as follows:
         
        ```php     
            use Neblar\DesCardId\CardIdentifier;
	        ...
	        $identifier = new CardIdentifier(85);
	        $renderText = $identifier->inspectTextWithNotices($testText);
            }
        ```
    * **Manual includes**
    
        If your project does not make use of composer you can manually include the folder `src` and the file `DesCardId.php` in your project and use the library as follows: 
        
        ```php     
            use Neblar\DesCardId\CardIdentifier;
	        require_once('assets/php/DesCardId.php');
	        ...
	        $identifier = new CardIdentifier(85);
	        $renderText = $identifier->inspectTextWithNotices($testText);
            }
        ``` 
2. Initialize an object of the `CardIdentifier` class as

    ```php
       $identifier = new CardIdentifier();
    ``` 
3. Pass the text you want to inspect to function `inspectText` of this object as

    ```php
       $inspectedText = $identifier.inspectText("foo bar");
    ```

## Customization
The library has room for customization at different stages. Here are some of the common customizations that you can perform:

### Changing the markers for Alert and Notice
You can change the marker that is used to identify the numbers by passing the desired text to the constructor of `CardIdentifier` by
    
```php
    $identifier = new CardIdentifier(null, null, null, "ALERT TEXT", "NOTICE TEXT");
```
    
### Changing the thresholds
You can change the threshold for setting Alerts and Notices as follows

```php
    $thresholdAlert = 80;
    $thresholdNotice = 20;
    $identifier = new CardIdentifier(thresholdAlert, thresholdNotice);
```

### Changing the check level
The library has multiple check levels (2 at present) which can be chosen by setting the `checkLevel`
The check levels work as follows:
* Level 1
    This check gets number that are typed in continuously without breakage with special characters. Using this check would result in a text such as `foo 123 456 bar` returning two suspected fragments `123` and `456`.
* Level 2
    This check interprets numbers separated by special characters as one block and only breaks the numbers when they are separated by alphabets, for example `foo 123-45 bar 123 456 bar` would result in the fragments `12345` and `123456`
    
You can set the desired level of check as follows

```php
    $checkLevel = 1;
    $identier = new CardIdentifier(null, null, checkLevel);
```  

### Changing the probability weight of different checks
You can change the weight assigned to different checks of the process as follows:

```php
    $probabilities = {
        'LUHN':60,
        'TEST_NUMBERS':100,
        'PROVIDERS':15,
        'LENGTH':15,
    };
    $identifier = new CardIdentifier(null, null, null, null, null, null, null, probabilities);
```
Note that you have to provide a value for all the different checks if you want to change the probabilities. 

### Changing the constants used for performing checks
You can change the constants used for performing the checks. You can do the following by this:
* Change the minimum and maximum possible card lengths
* Change probability weight of different card lengths
* Add/Edit Regex checks used to identify cards as well as their probability weight
* Add new known test cards 

This can be achieved as follows:
```php
    $constants = new ValidationConstants();
    $constants->MIN_POSSIBLE_LENGTH = 4;
    $identifier = new CardIdentifier(null, null, null, null, null, null, null, null, constants);
```

## References
 * http://www.dirigodev.com/blog/ecommerce/anatomy-of-a-credit-card-number/
 * https://stackoverflow.com/questions/72768/how-do-you-detect-credit-card-type-based-on-number
 
## Copyright
Published under MIT license.
Copyright &copy; 2017 Neblar Technologies 
