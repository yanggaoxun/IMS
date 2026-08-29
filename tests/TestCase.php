<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // CI 环境无前端构建产物，禁用 Vite
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}
