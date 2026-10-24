<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tour;
use App\Models\Availability;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatChoTest extends TestCase
{
    public function test_nha_truong_co_the_giu_cho_tour_thanh_cong()
    {
        // Tạo dữ liệu giả lập test
        $user = User::factory()->create(['role' => 'school']);
        $school = School::factory()->create(['user_id' => $user->id]);
        $tour = Tour::factory()->create(['status' => 'published']);
        $tomorrow = now()->addDays(5)->format('Y-m-d');
        
        Availability::create([
            'tour_id' => $tour->id,
            'service_date' => $tomorrow,
            'seats_total' => 100,
            'seats_held' => 0,
            'seats_sold' => 0,
        ]);

        // Gửi request đặt tour qua API
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/bookings', [
            'tour_id' => $tour->id,
            'service_date' => $tomorrow,
            'pax' => 40,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['status', 'message', 'data']);
    }
}