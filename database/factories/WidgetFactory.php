<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Widget;
use Illuminate\Database\Eloquent\Factories\Factory;

class WidgetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Widget::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'user_id' => null,
            'team_id' => null,
            'name' => $this->faker->company,
            'settings' => null,
        ];
    }

}
