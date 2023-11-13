<?php

namespace Tests\Feature;

use App\Models\Person;
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
}
