<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraphs(3, true),
            'author' => $this->faker->name,
            'source' => $this->faker->company,
            'category' => $this->faker->word,
            'url' => $this->faker->unique()->url,
            'image_url' => $this->faker->imageUrl(),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
