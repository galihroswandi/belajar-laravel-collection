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

    public function testZip()
    {
        $collection1 = collect([1, 2, 3]);
        $collection2 = collect([4, 5, 6]);
        $collection3 = $collection1->zip($collection2);

        $this->assertEquals([
            collect([1, 4]),
            collect([2, 5]),
            collect([3, 6]),
        ], $collection3->all());
    }

    public function testConcat()
    {
        $collection1 = collect([1, 2, 3]);
        $collection2 = collect([4, 5, 6]);
        $collection3 = $collection1->concat($collection2);

        $this->assertEquals([
            1,
            2,
            3,
            4,
            5,
            6
        ], $collection3->all());
    }

    public function testCombine()
    {
        $collection1 = collect(["name", "country"]);
        $collection2 = collect(["Jhon", "Indonesia"]);
        $collection3 = $collection1->combine($collection2);

        $this->assertEqualsCanonicalizing([
            "name" => "Jhon",
            "country" => "Indonesia"
        ], $collection3->all());
    }

    public function testCollapse()
    {
        $collection = collect([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9]
        ]);
        $result = $collection->collapse();
        $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5, 6, 7, 8, 9], $result->all());
    }

    public function testFlatMap()
    {
        $collection = collect([
            [
                "name" => "Jhon",
                "hobbies" => ["Coding", "Gaming"]
            ],
            [
                "name" => "Doe",
                "hobbies" => ["Reading", "Writting"]
            ]
        ]);
        $result = $collection->flatMap(function ($item) {
            return $item["hobbies"];
        });
        $this->assertEqualsCanonicalizing(["Coding", "Gaming", "Reading", "Writting"], $result->all());
    }

    public function testStringRepresentation()
    {
        $collection = collect(["Jhon", "Doe", "Setiawan"]);
        $this->assertEqualsCanonicalizing("Jhon-Doe-Setiawan", $collection->join("-"));
        $this->assertEqualsCanonicalizing("Jhon-Doe_Setiawan", $collection->join("-", "_"));
        $this->assertEqualsCanonicalizing("Jhon, Doe and Setiawan", $collection->join(", ", " and "));
    }

    public function testFilter()
    {
        $collection = collect([
            "Jhon" => 100,
            "Doe" => 80,
            "Setiawan" => 90
        ]);
        $result = $collection->filter(function ($value, $key) {
            return $value >= 90;
        });

        $this->assertEqualsCanonicalizing([
            "Jhon" => 100,
            "Setiawan" => 90
        ], $result->all());
    }

    public function testFilterIndex()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $result = $collection->filter(function ($value, $key) {
            return $value % 2 == 0;
        });

        $this->assertEqualsCanonicalizing([2, 4, 6, 8, 10], $result->all());
    }

    public function testPartition()
    {
        $collection = collect([
            "Jhon" => 100,
            "Doe" => 80,
            "Setiawan" => 90
        ]);
        [$result1, $result2] = $collection->partition(function ($value, $key) {
            return $value >= 90;
        });

        $this->assertEquals([
            "Jhon" => 100,
            "Setiawan" => 90
        ], $result1->all());
        $this->assertEquals([
            "Doe" => 80
        ], $result2->all());
    }

    public function testTesting()
    {
        $collection = collect(["Jhon", "Doe", "Setiawan"]);

        $this->assertTrue($collection->contains("Jhon"));
        $this->assertTrue($collection->contains(function ($value, $key) {
            return $value === "Jhon";
        }));
    }

    public function testGrouping()
    {
        $collection = collect([
            [
                "name" => "Jhon",
                "departement" => "IT",
            ],
            [
                "name" => "Doe",
                "departement" => "IT",
            ],
            [
                "name" => "Budi",
                "departement" => "HR"
            ]
        ]);
        $result = $collection->groupBy("departement");

        $this->assertEquals([
            "IT" => collect([
                [
                    "name" => "Jhon",
                    "departement" => "IT"
                ],
                [
                    "name" => "Doe",
                    "departement" => "IT"
                ]
            ]),
            "HR" => collect([
                [
                    "name" => "Budi",
                    "departement" => "HR"
                ]
            ])
        ], $result->all());

        $result2 = $collection->groupBy(function ($value, $key) {
            return strtolower($value["departement"]);
        });

        $this->assertEquals([
            "it" => collect([
                [
                    "name" => "Jhon",
                    "departement" => "IT"
                ],
                [
                    "name" => "Doe",
                    "departement" => "IT"
                ]
            ]),
            "hr" => collect([
                [
                    "name" => "Budi",
                    "departement" => "HR"
                ]
            ])
        ], $result2->all());
    }

    public function testSlicing()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collection->slice(3);
        $this->assertEqualsCanonicalizing([4, 5, 6, 7, 8, 9], $result->all());

        $result = $collection->slice(3, 2);
        $this->assertEqualsCanonicalizing([4, 5], $result->all());
    }

    public function testTake()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collection->take(3);
        $this->assertEquals([1, 2, 3], $result->all());

        $result2 = $collection->takeUntil(function ($value) {
            return $value === 3;
        });
        $this->assertEquals([1, 2], $result2->all());

        $result3 = $collection->takeWhile(function ($value) {
            return $value < 5;
        });
        $this->assertEquals([1, 2, 3, 4], $result3->all());
    }

    public function testSkip()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collection->skip(3);
        $this->assertEqualsCanonicalizing([4, 5, 6, 7, 8, 9], $result->all());

        $result2 = $collection->skipUntil(function ($value) {
            return $value === 3;
        });
        $this->assertEqualsCanonicalizing([3, 4, 5, 6, 7, 8, 9], $result2->all());

        $result3 = $collection->skipWhile(function ($value, $key) {
            return $value < 5;
        });
        $this->assertEqualsCanonicalizing([5, 6, 7, 8, 9], $result3->all());
    }

    public function testChunk()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $result = $collection->chunk(3);
        $this->assertEqualsCanonicalizing([1, 2, 3], $result->all()[0]->all());
        $this->assertEqualsCanonicalizing([4, 5, 6], $result->all()[1]->all());
        $this->assertEqualsCanonicalizing([7, 8, 9], $result->all()[2]->all());
        $this->assertEqualsCanonicalizing([10], $result->all()[3]->all());
    }

    public function testFirst()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collection->first();
        $this->assertEquals(1, $result);

        $result2 = $collection->first(function ($value, $key) {
            return $value > 5;
        });
        $this->assertEquals(6, $result2);
    }

    public function testLast()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collection->last();
        $this->assertEquals(9, $result);

        $result2 = $collection->last(function ($value, $key) {
            return $value < 5;
        });
        $this->assertEquals(4, $result2);
    }

    public function testRandom()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collection->random();
        $this->assertTrue(in_array($result, [1, 2, 3, 4, 5, 6, 7, 8, 9]));

        // $result2 = $collection->random(5);
        // $this->assertEquals([1, 2, 3, 4, 5], $result2->all());
    }

    public function testExistence()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $this->assertTrue($collection->isNotEmpty());
        $this->assertFalse($collection->isEmpty());
        $this->assertTrue($collection->contains(1));
        $this->assertFalse($collection->contains(10));
        $this->assertTrue($collection->contains(function ($value, $key) {
            return $value === 9;
        }));
    }
}