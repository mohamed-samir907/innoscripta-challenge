<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('feed returns personalized content', function () {
    $user = User::factory()->create();
    $user->preferences()->create(['type' => 'source', 'value' => 'NewsAPI']);

    Article::factory()->create(['title' => 'Relevant Article', 'source' => 'NewsAPI']);
    Article::factory()->create(['title' => 'Irrelevant Article', 'source' => 'OtherSource']);

    $this->actingAs($user)->getJson('/api/feed')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'Relevant Article'])
        ->assertJsonMissing(['title' => 'Irrelevant Article']);
});

test('feed returns empty message if no preferences', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->getJson('/api/feed')
        ->assertStatus(200)
        ->assertJsonFragment(['message' => 'No preferences set. Please add some preferences to see your feed.']);
});
