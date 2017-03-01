<?php

namespace Vivait\PromptableOptions\Tests\Transformer;

use Vivait\PromptableOptions\Transformer\StringTransformer;

class StringTransformerTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var StringTransformer
     */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new StringTransformer();
    }

    /**
     * @test
     * @dataProvider providerForTheTransformerWillReturnAnyStringItIsGiven
     *
     * @param string $value
     */
    public function theTransformerWillReturnAnyStringItIsGiven($value)
    {
        $actual = $this->transformer->transform($value);

        $this->assertEquals($value, $actual);
    }

    /**
     * @return array
     */
    public function providerForTheTransformerWillReturnAnyStringItIsGiven()
    {
        return [
            ['test'],
            ['string'],
            ['       '],
            [''],
            [""],
            ["\n"],
            ["∂"],
        ];
    }

    /**
     * @test
     * @dataProvider providerForTheTransformerWillReturnNullForAnythingThatIsNotAString
     *
     * @param mixed $value
     */
    public function theTransformerWillReturnNullForAnythingThatIsNotAString($value)
    {
        $actual = $this->transformer->transform($value);

        $this->assertEquals(null, $actual);
    }

    /**
     * @return array
     */
    public function providerForTheTransformerWillReturnNullForAnythingThatIsNotAString()
    {
        return [
            [null],
            [22],
            [23.4],
            [false],
            [true],
            [function() {}]
        ];
    }
}
