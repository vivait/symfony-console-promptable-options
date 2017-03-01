<?php

namespace Vivait\PromptableOptions\Tests\Transformer;

use Vivait\PromptableOptions\Transformer\IntegerTransformer;

class IntegerTransformerTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var IntegerTransformer
     */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new IntegerTransformer();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Console\Exception\LogicException
     * @expectedExceptionMessage A valid integer is required.
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
            ['72', 72],
            ['100.1', 100],
            ['-20', -20],
            ['-02', -2],
            ['0123', 123],
        ];
    }
}
