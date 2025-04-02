<?php

namespace App\Parsers\Base;

class BaseParser
{
    protected $parseFields = [];

    /**
     * Gets the field mapping for the parser.
     *
     * @return array Field mapping (e.g. ['teamID' => 'team_id']).
     */
    protected function getParseFields() {
        return $this->parseFields;
    }

   
    /**
     * Returns the fields of a given type.
     * 
     * This method takes a type parameter and returns the fields of that type.
     * If the type is not found, it defaults to the 'always' type.
     * 
     * @param string $type The type of fields to retrieve.
     * @return array The fields of the given type.
     */
    protected function getFieldsByType($type) {
        return $this->parseFields[$type] ?? $this->parseFields['always'];
    }


    /**
     * Applies field mapping from-to in the data array.
     *
     * @param array $data Data to be transformed.
     * @param string $type Type of conversion.
     * @return array Transformed data.
     */
    public function parseFields(array $data, string $type = 'always'): array
    {
        $parsedData = [];
        $fieldMap = $this->getFieldsByType($type);

        foreach ($fieldMap as $key => $mappedKey) {
            if (is_int($key)) {
                if (array_key_exists($mappedKey, $data)) {
                    $parsedData[$mappedKey] = $data[$mappedKey];
                }
            } elseif (array_key_exists($key, $data)) {
                $parsedData[$mappedKey] = $data[$key];
            }
        }

        return $parsedData;
    }
}
