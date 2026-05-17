<?php

namespace Database\Factories;

use App\Models\Alternatif;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlternatifFactory extends Factory
{
    protected $model = Alternatif::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik'                => $this->faker->unique()->numerify('################'), // 16 digits
            'nama'               => $this->faker->name(),
            'alamat'             => $this->faker->address(),
            'periode_bantuan_id' => null, // set when calling
            'user_id'            => null,
        ];
    }
}
