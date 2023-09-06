<?php

namespace Tests\Feature;

use Tests\TestCase;

class CollectionTest extends TestCase
{
    public function testCreateCollection()
    {
        $collection = collect([1, 2, 3, 4]);
        $this->assertEqualsCanonicalizing([1, 2, 3, 4], $collection->all());
    }

    public function testForeach()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        foreach ($collection as $key => $value)
            $this->assertEquals($key + 1, $value);
    }

    public function testCrud()
    {
        $collection = collect([]);
        $collection->push(1, 2, 3, 4, 5);
        $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5], $collection->all());

        $result = $collection->pop();
        $this->assertEquals(5, $result);
        $this->assertEqualsCanonicalizing([1, 2, 3, 4], $collection->all());
    }

}