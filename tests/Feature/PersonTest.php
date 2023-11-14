<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = "Miftah";
        $person->last_name = "Fadilah";
        $person->save();

        self::assertEquals("MIFTAH Fadilah", $person->fullName);

        $person->fullName = "GADIS Syalwa";
        $person->save();

        self::assertEquals("GADIS", $person->first_name);
        self::assertEquals("Syalwa", $person->last_name);
    }

    public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = "Miftah";
        $person->last_name = "Fadilah";
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
    }

    public function testCustomCast()
    {
        $person = new Person();
        $person->first_name = "Miftah";
        $person->last_name = "Fadilah";
        $person->address = new Address("Jalan Belum Jadi", "Bogor", "Indonesia", "16680");
        $person->save();

        $person = Person::query()->find($person->id);
        self::assertNotNull($person->address);
        self::assertInstanceOf(Address::class, $person->address);
        self::assertEquals("Jalan Belum Jadi", $person->address->street);
        self::assertEquals("Bogor", $person->address->city);
        self::assertEquals("Indonesia", $person->address->country);
        self::assertEquals("16680", $person->address->postal_code);
    }
}
