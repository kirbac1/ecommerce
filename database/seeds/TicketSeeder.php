<?php

namespace database\seeds;

use App\TicketMessage;
use App\TicketThread;
use App\Type;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run()
    {
        for($i=0; $i<4; $i++) {
            $ticketThread = factory(TicketThread::class)->create();
            $ticketMessage = factory(TicketMessage::class)->create([
                'thread_id' => $ticketThread->id,
                'user_id' => 1,
            ], 10);
            $ticketMessage = factory(TicketMessage::class)->create([
                'thread_id' => $ticketThread->id,
                'user_id' => 1,
            ], 10);
            $ticketMessage = factory(TicketMessage::class)->create([
                'thread_id' => $ticketThread->id,
                'user_id' => 1,
            ], 10);
        }
    }
}