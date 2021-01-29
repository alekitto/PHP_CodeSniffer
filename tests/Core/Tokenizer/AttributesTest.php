<?php
/**
 * Tests the support of PHP 8 attributes
 *
 * @author    Alessandro Chitolina <alekitto@gmail.com>
 * @copyright 2019 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Tests\Core\Tokenizer;

use PHP_CodeSniffer\Tests\Core\AbstractMethodUnitTest;

class AttributesTest extends AbstractMethodUnitTest
{


    /**
     * Test that attributes are parsed correctly.
     *
     * @param string $testMarker The comment which prefaces the target token in the test file.
     * @param int    $length     The number of tokens between opener and closer.
     *
     * @dataProvider dataAttribute
     * @covers       PHP_CodeSniffer\Tokenizers\PHP::tokenize
     * @covers       PHP_CodeSniffer\Tokenizers\PHP::parsePhpAttribute
     *
     * @return void
     */
    public function testAttribute($testMarker, $length)
    {
        $tokens = self::$phpcsFile->getTokens();

        $attribute = $this->getTargetToken($testMarker, T_ATTRIBUTE);
        $this->assertTrue(array_key_exists('attribute_closer', $tokens[$attribute]));
        $this->assertEquals(($attribute + $length), $tokens[$attribute]['attribute_closer']);

    }//end testAttribute()


    /**
     * Data provider.
     *
     * @see testAttribute()
     *
     * @return array
     */
    public function dataAttribute()
    {
        return [
            [
                '/* testAttribute */',
                2,
            ],
            [
                '/* testAttributeWithParams */',
                7,
            ],
            [
                '/* testAttributeWithNamedParam */',
                10,
            ],
            [
                '/* testAttributeOnFunction */',
                2,
            ],
            [
                '/* testAttributeOnFunctionWithParams */',
                17,
            ],
            [
                '/* testAttributeGrouping */',
                26,
            ],
            [
                '/* testAttributeMultiline */',
                31,
            ],
        ];

    }//end dataAttribute()


    /**
     * Test that multiple attributes on the same line are parsed correctly.
     *
     * @covers PHP_CodeSniffer\Tokenizers\PHP::tokenize
     * @covers PHP_CodeSniffer\Tokenizers\PHP::parsePhpAttribute
     *
     * @return void
     */
    public function testTwoAttributesOnTheSameLine()
    {
        $tokens = self::$phpcsFile->getTokens();

        $attribute = $this->getTargetToken('/* testTwoAttributeOnTheSameLine */', T_ATTRIBUTE);
        $this->assertTrue(array_key_exists('attribute_closer', $tokens[$attribute]));

        $closer = $tokens[$attribute]['attribute_closer'];
        $this->assertEquals(T_WHITESPACE, $tokens[($closer + 1)]['code']);
        $this->assertEquals(T_ATTRIBUTE, $tokens[($closer + 2)]['code']);
        $this->assertTrue(array_key_exists('attribute_closer', $tokens[($closer + 2)]));

    }//end testTwoAttributesOnTheSameLine()


    /**
     * Test that attribute followed by a line comment is parsed correctly.
     *
     * @covers PHP_CodeSniffer\Tokenizers\PHP::tokenize
     * @covers PHP_CodeSniffer\Tokenizers\PHP::parsePhpAttribute
     *
     * @return void
     */
    public function testAttributeAndLineComment()
    {
        $tokens = self::$phpcsFile->getTokens();

        $attribute = $this->getTargetToken('/* testAttributeAndCommentOnTheSameLine */', T_ATTRIBUTE);
        $this->assertTrue(array_key_exists('attribute_closer', $tokens[$attribute]));

        $closer = $tokens[$attribute]['attribute_closer'];
        $this->assertEquals(T_WHITESPACE, $tokens[($closer + 1)]['code']);
        $this->assertEquals(T_COMMENT, $tokens[($closer + 2)]['code']);

    }//end testAttributeAndLineComment()


    /**
     * Test that attribute followed by a line comment is parsed correctly.
     *
     * @param string $testMarker The comment which prefaces the target token in the test file.
     * @param int    $position   The token position (starting from T_FUNCTION) of T_ATTRIBUTE token.
     * @param int    $length     The number of tokens between opener and closer.
     *
     * @dataProvider dataAttributeOnParameters
     *
     * @covers PHP_CodeSniffer\Tokenizers\PHP::tokenize
     * @covers PHP_CodeSniffer\Tokenizers\PHP::parsePhpAttribute
     *
     * @return void
     */
    public function testAttributeOnParameters($testMarker, $position, $length)
    {
        $tokens = self::$phpcsFile->getTokens();

        $function  = $this->getTargetToken($testMarker, T_FUNCTION);
        $attribute = ($function + $position);

        $this->assertEquals(T_ATTRIBUTE, $tokens[$attribute]['code']);
        $this->assertTrue(array_key_exists('attribute_closer', $tokens[$attribute]));
        $this->assertEquals(($attribute + $length), $tokens[$attribute]['attribute_closer']);

        $closer = $tokens[$attribute]['attribute_closer'];
        $this->assertEquals(T_WHITESPACE, $tokens[($closer + 1)]['code']);
        $this->assertEquals(T_STRING, $tokens[($closer + 2)]['code']);
        $this->assertEquals('int', $tokens[($closer + 2)]['content']);

        $this->assertEquals(T_VARIABLE, $tokens[($closer + 4)]['code']);
        $this->assertEquals('$param', $tokens[($closer + 4)]['content']);

    }//end testAttributeOnParameters()


    /**
     * Data provider.
     *
     * @see testAttributeOnParameters()
     *
     * @return array
     */
    public function dataAttributeOnParameters()
    {
        return [
            [
                '/* testSingleAttributeOnParameter */',
                4,
                2,
            ],
            [
                '/* testMultipleAttributesOnParameter */',
                4,
                10,
            ],
            [
                '/* testMultilineAttributesOnParameter */',
                4,
                13,
            ],
        ];

    }//end dataAttributeOnParameters()


}//end class
