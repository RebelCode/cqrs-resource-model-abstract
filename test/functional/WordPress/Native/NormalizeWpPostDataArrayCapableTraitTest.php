<?php

namespace RebelCode\Storage\Resource\WordPress\Native\FuncTest;

use RebelCode\Storage\Resource\WordPress\Native\NormalizeWpPostDataArrayCapableTrait as TestSubject;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class NormalizeWpPostDataArrayCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Storage\Resource\WordPress\Native\NormalizeWpPostDataArrayCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        $methods = $this->mergeValues(
            $methods,
            [
                '_getWpPostDataFieldKeys',
                '_getWpPostDataMetaFieldName',
                '_containerGet',
                '_containerHas',
                '_normalizeArray',
                '_normalizeString',
            ]
        );

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods($methods)
                     ->getMockForTrait();

        $mock->method('_normalizeString')->willReturnArgument(0);
        $mock->method('_normalizeArray')->willReturnArgument(0);
        $mock->method('_containerGet')->willReturnCallback(
            function($c, $k) {
                return $c[$k];
            }
        );
        $mock->method('_containerHas')->willReturnCallback(
            function($c, $k) {
                return isset($c[$k]);
            }
        );

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the normalize post data array method to assert whether the output array is a correct normalized version
     * of the input array.
     *
     * @since [*next-version*]
     */
    public function testNormalizeWpPostDataArray()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $fieldNames = [
            $f1 = uniqid('field-'),
            $f2 = uniqid('field-'),
            $f3 = uniqid('field-'),
            $f4 = uniqid('field-'),
        ];
        $metaField = uniqid('meta-');
        $subject->method('_getWpPostDataFieldKeys')->willReturn($fieldNames);
        $subject->method('_getWpPostDataMetaFieldName')->willReturn($metaField);

        $m1 = uniqid('meta-key-');
        $m2 = uniqid('meta-key-');
        $m3 = uniqid('meta-key-');
        $input = [
            $f2 => $fv2 = uniqid('value-'),
            $m1 => $mv1 = uniqid('meta-value-'),
            $f3 => $fv3 = uniqid('value-'),
            $f1 => $fv1 = uniqid('value-'),
            $m3 => $mv3 = uniqid('meta-value-'),
            $m2 => $mv2 = uniqid('meta-value-'),
        ];

        $expected = [
            $f2        => $fv2,
            $f3        => $fv3,
            $f1        => $fv1,
            $metaField => [
                $m1 => $mv1,
                $m3 => $mv3,
                $m2 => $mv2,
            ],
        ];

        $result = $reflect->_normalizeWpPostDataArray($input);

        $this->assertEquals($expected, $result, 'Expected and retrieved post data arrays do not match.');
    }
}
