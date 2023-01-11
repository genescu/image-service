<?php declare(strict_types=1);

namespace Images\App;

use \Exception as Exception;
use InvalidArgumentException;

/**
 * Class ValidatorHandler
 * @package Images\App
 * Provide validator rules for Parameters
 */
class ValidatorHandler
{

    public function required($label, $haystack)
    {
        if (!isset($haystack[$label])) {

            $this->getErrorMessage(sprintf("Parameter %s is required", $label));
        }
    }

    public function pathFileExists($label, $haystack)
    {
        if (!file_exists($haystack)) {
            $this->getErrorMessage(sprintf("File '%s' could not be found", $label));
        }
        return $this;

    }

    public function integer($haystack)
    {
        if (!is_int($haystack)) {
            $this->getErrorMessage(sprintf("Parameter '%s' must be integer", $haystack));
        }

        return $this;
    }

    public function minChar($label, $needle, $haystack)
    {
        if (strlen(trim($needle)) <= $haystack) {
            $this->getErrorMessage(sprintf("Parameter '%s' must have minimum %s characters", $label, $haystack));
        }

        return $this;
    }


    public function minValue($label, $needle, $haystack)
    {
        if ((float)$needle < (float)$haystack) {
            $this->getErrorMessage(sprintf("Parameter '%s' must be min %s", $label, $haystack));
        }

        return $this;
    }

    public function getErrorMessage($message)
    {
        try {
            throw new InvalidArgumentException($message);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    // and so on, all this function shall be called dynamically via a validator function, with a validate() function
}