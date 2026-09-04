<?php

namespace Database\Seeders;

use App\TicketMessage;
use App\TicketThread;
use App\Type;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run()
    {
        for($i=0; $i<4; $i++) {
            $ticketThread = TicketThread::factory()->create();
            $ticketMessage = TicketMessage::factory()->create([
                'thread_id' => $ticketThread->id,
                'user_id' => 1,
            ]);
            $ticketMessage = TicketMessage::factory()->create([
                'thread_id' => $ticketThread->id,
                'user_id' => 1,
            ]);
            $ticketMessage = TicketMessage::factory()->create([
                'thread_id' => $ticketThread->id,
                'user_id' => 1,
            ]);
        }
    }
}