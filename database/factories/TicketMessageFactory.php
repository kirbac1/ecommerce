<?php

namespace Database\Factories;

use App\TicketMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketMessageFactory extends Factory
{
    protected $model = TicketMessage::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'content' => $this->faker->paragraph(4),
            'sentBySupport' => $this->faker->boolean(),
        ];
    }
}
