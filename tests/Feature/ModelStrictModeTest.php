<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class ModelStrictModeTest extends TestCase
{
    /**
     * It should prevent lazy loading of relations.
     */
    public function test_lazy_loading_is_prevented(): void
    {
        $this->assertTrue(Model::preventsLazyLoading());
    }

    /**
     * It should prevent silently discarding attributes.
     */
    public function test_silently_discarding_attributes_is_prevented(): void
    {
        $this->assertTrue(Model::preventsSilentlyDiscardingAttributes());
    }

    /**
     * It should prevent accessing missing attributes.
     */
    public function test_accessing_missing_attributes_is_prevented(): void
    {
        $this->assertTrue(Model::preventsAccessingMissingAttributes());
    }
}
