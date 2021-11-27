<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EvaluationTest extends TestCase
{
    /**
     * Test empty response
     *
     * @return void
     */
    public function test_get_evaluation_empty()
    {
        $response = $this->get('/evaluations/fake-company');

        $response->assertStatus(200)
                    ->assertJsonCount(0, 'data');
    }

    /**
     * Test response
     *
     * @return void
     */
    public function test_get_evaluation()
    {
        $company = (string) Str::uuid();
        $evaluations = Evaluation::factory()->count(6)->create([
            'company' => $company
        ]);
        $response = $this->get("/evaluations/{$company}");

        $response->assertStatus(200)
                    ->assertJsonCount(6, 'data');
    }

    /**
     * Test error store
     *
     * @return void
     */
    public function test_error_store_evaluation()
    {
        $company = (string) Str::uuid();
        $evaluations = Evaluation::factory()->count(6)->create([
            'company' => $company
        ]);
        $response = $this->postJson("/evaluations/{$company}", []);

        $response->assertStatus(422);
    }

     /**
     * Test store
     *
     * @return void
     */
    public function test_store_evaluation()
    {
        $company = 'fake-company';
        $response = $this->postJson("/evaluations/{$company}", [
            'company' =>  (string) Str::uuid(),
            'comment' => 'New comment',
            'stars' => 5
        ]);

        $response->assertStatus(404);
    }
}
