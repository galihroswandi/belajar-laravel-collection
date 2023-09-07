<?php

namespace Tests\Feature;

use App\Data\Person;
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

    public function testMap()
    {
        $collection = collect([1, 2, 3]);
        $result = $collection->map(function ($item) {
            return $item * 2;
        });

        self::assertEquals([2, 4, 6], $result->all());
    }

    public function testMapInto()
    {
        $collection = collect(["Jhon"]);
        $result = $collection->mapInto(Person::class);

        $this->assertEquals([new Person("Jhon")], $result->all());
    }

    public function testMapSpread()
    {
        $collection = collect([
            ["Jhon", "Doe"],
            ["Budi", "Setiawan"]
        ]);
        $result = $collection->mapSpread(function ($firstName, $lastName) {
            $fullName = "$firstName $lastName";
            return new Person($fullName);
        });

        $this->assertEquals([
            new Person("Jhon Doe"),
            new Person("Budi Setiawan")
        ], $result->all());
    }

    public function testMapToGroup()
    {
        $collection = collect([
            [
                "name" => "Jhon",
                "departement" => "IT"
            ],
            [
                "name" => "Doe",
                "departement" => "IT"
            ],
            [
                "name" => "Budi",
                "departement" => "HR"
            ]
        ]);
        $result = $collection->mapToGroups(function ($item) {
            return [
                $item["departement"] => $item["name"]
            ];
        });

        $this->assertEquals([
            "IT" => collect(["Jhon", "Doe"]),
            "HR" => collect(["Budi"])
        ], $result->all());
    }
}