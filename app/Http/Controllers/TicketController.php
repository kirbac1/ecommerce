<?php

namespace App\Http\Controllers;

use App\TicketMessage;
use App\User;
use App\TicketThread;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        return TicketThread::with(['messages','messages.user'])->take($limit)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find(1);
//        $user = Auth::user();
        if ($request->get('ticket_id', null)) {
            $thread = TicketThread::find($request->get('ticket_id'));
            $thread->status = $request->get('status');
            if ($request->get('status')) $thread->status = $request->get('status');
            if ($request->get('content') !== '') {
                $message = new TicketMessage;
                $message->sentBySupport = $user->superAdmin;
                $message->thread_id = $request->get('ticket_id');
                $message->user_id = $user->id;
                $message->content = $request->get('content');
                $message->save();
            }
            $thread->save();
            $thread = TicketThread::with(['messages','messages.user'])->findOrFail($thread->id);
            return $thread;
        } else {
            $thread = new TicketThread;
            $thread->user_id = $user->id;
            $thread->subject = $request->get('subject', '');
            $thread->department = 'technical';
            if ($request->get('status')) $thread->status = $request->get('status');
            $thread->save();
            if ($request->get('content')) {
                $message = new TicketMessage;
                $message->thread_id = $thread->id;
                $message->sentBySupport = $user->superAdmin;
                $message->content = $request->get('content');
                $message->user_id = $user->id;
                $message->save();
            }

            $thread->save();
            $thread = TicketThread::with(['messages','messages.user'])->findOrFail($thread->id);
            Cache::forget('tickets');
            return $thread;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  TicketThread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(TicketThread $thread)
    {
        return $thread;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        Cache::forget('tickets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketThread $thread)
    {
        $thread->delete();
        Cache::forget('tickets');
        return response([
            'status' => 'success'
        ], 200);
    }
}
