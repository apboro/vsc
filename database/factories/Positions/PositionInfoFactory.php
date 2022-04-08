<?php

namespace Database\Factories\Positions;

use App\Models\Positions\PositionInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionInfoFactory extends Factory
{
    protected $model = PositionInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {

        return [
            'work_phone' => $this->faker->numerify('+7 (###) ###-##-##'),
        ];
    }
}
