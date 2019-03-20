<?php namespace Tests\Feature;

use Tests\TestCase;
use Syscover\Market\Models\Section;

class MarketTest extends TestCase
{
    // Sections
    public function testGetSections(): void
    {
        $response = $this->json('GET', route('api.market_section'));

        $response
            ->assertStatus(200)
            ->assertJson([
                'status'        => 200,
                'statusText'    => 'success'
            ]);
    }

    public function testStoreSection(): void
    {
        $response = $this->json('POST', route('api.market_store_section'), [
            'id'        => 'unit-testing',
            'lang_id'   => base_lang(),
            'name'      => 'Unit Testing',
            'slug'      => 'unit-testing'
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'status'        => 201,
                'statusText'    => 'success'
            ]);
    }

    public function tearDown(): void
    {
        Section::where('id', 'unit-testing')->delete();
        parent::tearDown();
    }
}
