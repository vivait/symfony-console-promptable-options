<?php

namespace Vivait\PromptableOptions\Transformer;

use Symfony\Component\Console\Exception\LogicException;

class IntegerTransformer extends  ValueTransformer
{

    /**
     * {@inheritdoc}
     *
     * @throws LogicException
     */
    public function transform($value)
    {
        // Not numeric and isn't blank (a value was actually provided)
        if ( ! is_numeric($value) && ! $this->isBlank($value)) {
            throw new LogicException("A valid integer is required.");
        }

        if ($value !== null) {
            return (int) $value;
        }

        return $value;
    }
}
