<?php

declare(strict_types=1);

namespace Tests\Unit\core\Application;

use Core\Application\BaseInput;
use Core\Application\InputException;
use Tests\Unit\UnitTestCase;

class BaseInputTest extends UnitTestCase
{
    /** @var BaseInput|\ReflectionClass */
    protected $baseInput;

    /** @var \ReflectionMethod */
    protected $parseArrayAsJson;

    /** @var \ReflectionMethod */
    protected $validateArrayOfIntegers;

    /** @var \ReflectionMethod */
    protected $validateArrayOfStrings;

    public function setUp()
    {
        parent::setUp();
        $reflectionClass = new \ReflectionClass(BaseInput::class);
        $this->baseInput = $reflectionClass->newInstance();

        $this->parseArrayAsJson = $reflectionClass->getMethod('parseArrayAsJson');
        $this->parseArrayAsJson->setAccessible(true);

        $this->validateArrayOfIntegers = $reflectionClass->getMethod('validateArrayOfIntegers');
        $this->validateArrayOfIntegers->setAccessible(true);

        $this->validateArrayOfStrings = $reflectionClass->getMethod('validateArrayOfStrings');
        $this->validateArrayOfStrings->setAccessible(true);
    }

    /**
     * @test
     */
    public function parseArrayAsJson_throws_exception_when_invalid_json()
    {
        $array = 'some invalid json';
        $list = json_encode($array);
        $this->expectException(InputException::class);
        $this->parseArrayAsJson->invokeArgs($this->baseInput, [$list, true]);
    }

    /**
     * @test
     */
    public function parseArrayAsJson_throws_exception_when_empty_array_is_passed_and_it_is_not_allowed()
    {
        $array = [];
        $list = json_encode($array);
        $this->expectException(InputException::class);
        $this->parseArrayAsJson->invokeArgs($this->baseInput, [$list, false]);
    }

    /**
     * @test
     */
    public function parseArrayAsJson_works_when_empty_array_is_passed_and_it_is_allowed()
    {
        $array = [];
        $list = json_encode($array);
        $results = $this->parseArrayAsJson->invokeArgs($this->baseInput, [$list, true]);
        $this->assertEquals($array, $results);
    }

    /**
     * @test
     */
    public function validateArrayOfIntegers_throws_exception_when_one_item_is_not_integer()
    {
        $addonIds = [1, 2, 'test', 4];
        $this->expectException(InputException::class);
        $this->validateArrayOfIntegers->invoke($this->baseInput, [$addonIds]);
    }

    /**
     * @test
     */
    public function validateArrayOfIntegers_works_when_all_items_are_integer()
    {
        $addonIds = [1, 2, 3, 4];
        $results = $this->validateArrayOfIntegers->invoke($this->baseInput, $addonIds);
        $this->assertTrue($results);
    }

    /**
     * @test
     */
    public function validateArrayOfStrings_throws_exception_when_one_item_is_not_string()
    {
        $addonIds = ['first', 'second', 3, 'fourth'];
        $this->expectException(InputException::class);
        $this->validateArrayOfStrings->invoke($this->baseInput, $addonIds);
    }

    /**
     * @test
     */
    public function validateArrayOfStrings_works_when_all_items_are_string()
    {
        $addonIds = ['first', 'second', 'third', 'fourth'];
        $results = $this->validateArrayOfStrings->invoke($this->baseInput, $addonIds);
        $this->assertTrue($results);
    }
}
