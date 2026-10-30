<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingDateValidationTest extends TestCase
{
    public function test_user_cannot_book_past_date(): void
    {
        $user = User::where('role', User::ROLE_USER)->first() ?? User::factory()->create(['role' => User::ROLE_USER]);
        $court = Court::first() ?? Court::create(['name' => 'Court Test', 'location' => 'Indoor', 'price_per_hour' => 300000]);

        $yesterday = now()->subDay()->toDateString();

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'court_id'       => $court->id,
            'date'           => $yesterday,
            'start_time'     => '10:00',
            'duration_hours' => 1,
        ]);

        $response->assertSessionHasErrors('date');
    }

    public function test_user_can_book_today_or_future_date(): void
    {
        $user = User::where('role', User::ROLE_USER)->first() ?? User::factory()->create(['role' => User::ROLE_USER]);
        $court = Court::first() ?? Court::create(['name' => 'Court Test', 'location' => 'Indoor', 'price_per_hour' => 300000]);

        $tomorrow = now()->addDays(5)->toDateString();

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'court_id'       => $court->id,
            'date'           => $tomorrow,
            'start_time'     => '14:00',
            'duration_hours' => 1,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('bookings', [
            'user_id'  => $user->id,
            'court_id' => $court->id,
            'date'     => $tomorrow,
        ]);
    }

    public function test_user_dashboard_clamps_past_date_to_today(): void
    {
        $user = User::where('role', User::ROLE_USER)->first() ?? User::factory()->create(['role' => User::ROLE_USER]);

        $response = $this->actingAs($user)->get(route('dashboard', ['date' => '2020-01-01']));

        $response->assertStatus(200);
        $response->assertViewHas('date', now()->toDateString());
        $response->assertSee('min="' . now()->toDateString() . '"', false);
    }
}
