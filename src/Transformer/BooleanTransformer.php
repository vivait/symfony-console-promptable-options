<?php

namespace Vivait\PromptableOptions\Transformer;

use Symfony\Component\Console\Exception\LogicException;

class BooleanTransformer extends  ValueTransformer
{

    /**
     * @var array
     */
    private $conversions = [
        'false' => false,
        '0'     => false,
        0       => false,
        'true'  => true,
        '1'     => true,
        1       => true
    ];

    /**
     * {@inheritdoc}
     *
     * @throws LogicException
     */
    public function transform($value)
    {
        if ($value === null) {
            return $value;
        }

        $realValue = strtolower(trim($value));
        if ( ! array_key_exists($realValue, $this->conversions)) {
            throw new LogicException("A boolean value must be provided (`0` or `false`, `1` or `true`).");
        }

        return $this->conversions[$realValue];
    }
}
