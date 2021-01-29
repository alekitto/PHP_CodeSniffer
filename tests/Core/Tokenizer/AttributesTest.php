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
        ];

    }//end dataAttribute()


}//end class
