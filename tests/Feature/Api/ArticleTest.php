<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);

test('can fetch articles', function () {
    Article::factory()->count(5)->create();

    $this->getJson('/api/articles')
        ->assertStatus(200)
        ->assertJsonCount(5, 'data');
});

test('can search articles', function () {
    Article::factory()->create(['title' => 'Laravel is awesome']);
    Article::factory()->create(['title' => 'PHP is great']);

    $this->getJson('/api/articles?keyword=Laravel')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'Laravel is awesome']);
});

test('can filter by source', function () {
    Article::factory()->create(['source' => 'NewsAPI']);
    Article::factory()->create(['source' => 'Guardian']);

    $this->getJson('/api/articles?source=NewsAPI')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['source' => 'NewsAPI']);
});
