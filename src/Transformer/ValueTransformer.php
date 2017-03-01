<?php

namespace Vivait\PromptableOptions\Transformer;

abstract class ValueTransformer
{

    /**
     * @param string $value
     *
     * @return mixed
     */
    abstract public function transform($value);

    /**
     * Check if the value is blank or not.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isBlank($value)
    {
        // Be very sure!
        if ($value === null || strlen($value) === 0 || trim($value) === '') {
            return true;
        }

        return false;
    }
}
