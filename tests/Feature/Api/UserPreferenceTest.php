<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can set preferences', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('/api/user/preferences', [
        'preferences' => [
            ['type' => 'source', 'value' => 'NewsAPI'],
            ['type' => 'category', 'value' => 'technology'],
        ]
    ])->assertStatus(200);

    $this->assertDatabaseHas('user_preferences', [
        'user_id' => $user->id,
        'type' => 'source',
        'value' => 'NewsAPI',
    ]);
});

test('can get preferences', function () {
    $user = User::factory()->create();
    $user->preferences()->create(['type' => 'source', 'value' => 'NewsAPI']);

    $this->actingAs($user)->getJson('/api/user/preferences')
        ->assertStatus(200)
        ->assertJsonFragment(['value' => 'NewsAPI']);
});
