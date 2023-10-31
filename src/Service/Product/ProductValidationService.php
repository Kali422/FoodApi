<?php

namespace App\Service\Product;

use stdClass;

class ProductValidationService
{
    private const SCHEMA = [
        'name' => ['validation' => 'is_string', 'required' => true],
        'kcal' => ['validation' => 'is_numeric', 'required' => true],
        'carbohydrates' => [
            'validation' => 'is_array',
            'required' => true,
            'subfields' => [
                'total' => ['required' => true, 'validation' => 'is_numeric'],
                'sugars' => ['required' => false, 'validation' => 'is_numeric']
            ]
        ],
        'fat' => [
            'validation' => 'is_array',
            'required' => true,
            'subfields' => [
                'total' => ['required' => true, 'validation' => 'is_numeric'],
                'saturated' => ['required' => false, 'validation' => 'is_numeric'],
                'monounsaturated' => ['required' => false, 'validation' => 'is_numeric'],
                'omega3' => ['required' => false, 'validation' => 'is_numeric'],
                'omega6' => ['required' => false, 'validation' => 'is_numeric']
            ]
        ],
        'protein' => [
            'validation' => 'is_array',
            'required' => true,
            'subfields' => [
                'total' => ['required' => true, 'validation' => 'is_numeric'],
                'animalBased' => ['required' => false, 'validation' => 'is_numeric'],
                'plantBased' => ['required' => false, 'validation' => 'is_numeric']
            ]
        ]
    ];

    public function validateAdd(array $rawData): array
    {
        $errors = [];
        foreach (self::SCHEMA as $key => $field) {
            if (isset($field['subfields'])) {
                foreach ($field['subfields'] as $subkey => $subfield) {
                    if (($subfield['required'] && !isset($rawData[$key][$subkey])) || (isset($rawData[$key][$subkey]) && !$subfield['validation'](
                                $rawData[$key][$subkey]
                            ))) {
                        $errors[] = "Wrong or missing {$key}:{$subkey} field";
                    }
                }
            } else {
                if (($field['required'] && !isset($rawData[$key])) || (isset($rawData[$key]) && !$field['validation'](
                            $rawData[$key]
                        ))) {
                    $errors[] = "Wrong or missing {$key} field";
                }
            }
        }
        return $errors;
    }

    public function validateUpdate(array $rawData): array
    {
        $errors = [];
        foreach ($rawData as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    if (!isset(self::SCHEMA[$key]['subfields'][$subKey])) {
                        $errors[] = "Unexpected {$key}:{$subKey} field";
                    }
                    else if (!self::SCHEMA[$key]['subfields'][$subKey]['validation']($subValue)) {
                        $errors[] = "Wrong {$key}:{$subKey} field";
                    }
                }
            } else {
                if (!isset(self::SCHEMA[$key])) {
                    $errors[] = "Unexpected {$key} field";
                }
                else if (!self::SCHEMA[$key]['validation']($value)) {
                    $errors[] = "Wrong {$key} field";
                }
            }
        }
        return $errors;
    }
}