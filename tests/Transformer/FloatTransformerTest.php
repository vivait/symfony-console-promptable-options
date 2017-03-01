<?php

namespace Vivait\PromptableOptions\Tests\Transformer;

use Vivait\PromptableOptions\Transformer\FloatTransformer;

class FloatTransformerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FloatTransformer
     */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new FloatTransformer();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Console\Exception\LogicException
     * @expectedExceptionMessage A valid float value is required.
     */
    public function nonNumericValuesAreHandledWithAnError()
    {
        $this->transformer->transform('hello');
    }

    /**
     * @test
     * @dataProvider providerForNonNumericValuesWillReturnNullIfTheInputIsBlank
     *
     * @param mixed $value
     */
    public function nonNumericValuesWillReturnNullIfTheInputIsBlank($value)
    {
        $actual = $this->transformer->transform($value);

        $this->assertEquals(null, $actual);
    }

    /**
     * @return array
     */
    public function providerForNonNumericValuesWillReturnNullIfTheInputIsBlank()
    {
        return [
            [null],
            [''],
            [""],
            ["       "],
            ["\n"],
        ];
    }

    /**
     * @test
     * @dataProvider providerForNumericValuesWillBeCorrectlyTransformed
     *
     * @param string $value
     * @param int    $expectedOutput
     */
    public function numericValuesWillBeCorrectlyTransformed($value, $expectedOutput)
    {
        $actual = $this->transformer->transform($value);

        $this->assertEquals($expectedOutput, $actual);
    }

    /**
     * @return array
     */
    public function providerForNumericValuesWillBeCorrectlyTransformed()
    {
        return [
            ['72.3', 72.3],
            ['100.1', 100.1],
            ['-20.3333333', -20.3333333],
            ['-02', -2.0],
            ['0123.0123', 123.0123],
        ];
    }
}
