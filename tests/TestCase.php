<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case for the application.
 * Ensures the Laravel application is bootstrapped for Feature tests.
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // RefreshDatabase is used in individual tests as needed.
}
