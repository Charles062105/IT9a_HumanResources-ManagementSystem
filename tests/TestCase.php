<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // /**
    //  * Use an in-memory database and run migrations once per test case.
    //  *
    //  * Some tests are failing with: "table employees already exists".
    //  * Ensuring we let Laravel manage migrations via RefreshDatabase
    //  * (and not do any extra manual setup) prevents double-creation.
    //  */
    protected function setUp(): void
    {
        parent::setUp();
    }
}

