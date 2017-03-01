<?php

namespace Vivait\PromptableOptions\Tests\Transformer;

use Vivait\PromptableOptions\Transformer\BooleanTransformer;

class BooleanTransformerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BooleanTransformer
     */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new BooleanTransformer();
    }

    /**
     * @test
     */
    public function nullInputWillReturnNull()
    {
        $actual = $this->transformer->transform(null);

        $this->assertEquals(null, $actual);
    }

    /**
     * @test
     * @dataProvider providerForTheTransformerIsNotCaseSensitive
     *
     * @param string $value
     * @param bool   $expectedOutput
     */
    public function theTransformerIsNotCaseSensitive($value, $expectedOutput)
    {
        $actual = $this->transformer->transform($value);

        $this->assertEquals($expectedOutput, $actual);
    }

    /**
     * @return array
     */
    public function providerForTheTransformerIsNotCaseSensitive()
    {
        return [
            ['FaLSE', false],
            ['false', false],
            ['FALSE', false],
            ['falsE', false],
            ['truE', true],
            ['TRUE', true],
            ['true', true],
            ['TRUe', true],
            ['TrUe', true]
        ];
    }

    /**
     * @test
     * @dataProvider providerForTheTransformerWillNotAcceptValuesThatCantBeMappedToABooleanValue
     * @expectedException \Symfony\Component\Console\Exception\LogicException
     * @expectedExceptionMessage A boolean value must be provided (`0` or `false`, `1` or `true`).
     * 
     * @param mixed $value
     */
    public function theTransformerWillNotAcceptValuesThatCantBeMappedToABooleanValue($value)
    {
        $this->transformer->transform($value);
    }

    /**
     * @return array
     */
    public function providerForTheTransformerWillNotAcceptValuesThatCantBeMappedToABooleanValue()
    {
        return [
            ['test'],
            ['345345'],
            [32453],
            [333.333],
            ["---"],
            ["ß"],
        ];
    }

    /**
     * @test
     * @dataProvider providerForTheTransformerWillCorrectlyTransformValues
     *
     * @param mixed $value
     * @param bool  $expectedOutput
     */
    public function theTransformerWillCorrectlyTransformValues($value, $expectedOutput)
    {
        $actual = $this->transformer->transform($value);

        $this->assertEquals($expectedOutput, $actual);
    }

    /**
     * @return array
     */
    public function providerForTheTransformerWillCorrectlyTransformValues()
    {
        return [
            ['true', true],
            ['false', false],
            [0, false],
            [1, true],
            ['1', true],
            ['0', false],
        ];
    }
}
