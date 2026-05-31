<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?string $createdManifest = null;

    protected function setUp(): void
    {
        parent::setUp();

        $manifestPath = public_path('build/manifest.json');
        if (!file_exists($manifestPath)) {
            @mkdir(public_path('build'), 0777, true);
            file_put_contents($manifestPath, json_encode(['resources/js/app.js' => ['file' => 'js/app.js', 'src' => 'resources/js/app.js', 'isEntry' => true]]));
            $this->createdManifest = $manifestPath;
        }
    }

    protected function tearDown(): void
    {
        if (isset($this->createdManifest) && file_exists($this->createdManifest)) {
            @unlink($this->createdManifest);
            $buildDir = dirname($this->createdManifest);
            if (is_dir($buildDir)) {
                @rmdir($buildDir);
            }
        }

        parent::tearDown();
    }
}
