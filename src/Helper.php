<?php

namespace ResponsivePagination;

class Helper
{
    /**
     * Return string representation of an RGBA object color
     *
     * @param array $rgbaColor
     * @return void
     */
    public static function colorToString($rgbaColor)
    {
        return sprintf('rgba(%d, %d, %d, %d)', $rgbaColor['r'], $rgbaColor['g'], $rgbaColor['b'], $rgbaColor['a']);
    }

    public static function stringToKebabCase($string)
    {
        preg_match_all(
            '/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/',
            trim(strtolower($string)),
            $words
        );
        return implode('-', $words[0]);
    }

    /**
     * Recursively normalize POST content
     * This process includes :
     * - convert boolean string into boolean (i.e: 'true' become true, 'false' become false)
     * - trim whitespace
     *
     * @param mixed $variable
     * @return void
     */
    public static function normalizeFormData($variable, $original_type = '')
    {
        // If form data is empty array or empty object, it'll likely be automatically converted into null by the server.
        // Null form Data can cause error. So we need to restore it to array.
        // This is mainly aimed for the root level of form-data
        if (is_null($variable) && ( 'array' === $original_type || 'object' === $original_type )) {
            return array();
        }

        if (is_array($variable)) {
            $array = array();
            foreach ($variable as $key => $variable_item) {
                $array[ $key ] = self::normalizeFormData($variable_item);
            }
            return $array;
        } elseif (is_string($variable)) {
            $variable = trim($variable);
            
            if (in_array(strtolower($variable), ['true', 'false', 'yes', 'no'])) {
                // Boolean value
                return in_array(strtolower($variable), ['true', 'yes']) ? true : false;
            } elseif (is_numeric($variable)) {
                // Possible numeric value, so we check
                $intValue = intval($variable);
                $stringInt = strval($intValue);
                if ($variable === $stringInt) {
                    return $intValue;
                }
            }
        }
        
        // If Not a boolean nor integer, return as it is
        return $variable;
    }
}
