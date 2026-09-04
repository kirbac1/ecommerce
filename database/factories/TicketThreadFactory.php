<?php

namespace Database\Factories;

use App\TicketThread;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketThreadFactory extends Factory
{
    protected $model = TicketThread::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'subject' => $this->faker->sentence(),
            'department' => 1,
            'status' => 'open',
        ];
    }
}
