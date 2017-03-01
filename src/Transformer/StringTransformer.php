<?php

namespace Vivait\PromptableOptions\Transformer;

use Symfony\Component\Console\Exception\LogicException;

class StringTransformer extends  ValueTransformer
{

    /**
     * {@inheritdoc}
     *
     * @throws LogicException
     */
    public function transform($value)
    {
        return is_string($value) ? $value : null;
    }
}
