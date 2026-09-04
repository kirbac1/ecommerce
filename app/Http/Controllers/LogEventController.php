<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\LogEvent;
use Illuminate\Http\Request;

class LogEventController extends Controller
{
    public function __construct()
    {
        // Only allow admins
        $this->middleware('admins');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 40);
        $limit = $limit > 40 ? 40 : $limit;
        $start = $request->get('start', 0);
        $count = LogEvent::count();
        $result = LogEvent::skip($start)->take($limit)->get();
        return [
            'count' => $count,
            'result' => $result,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param LogEvent $logEvent
     * @return \Illuminate\Http\Response
     */
    public function show(LogEvent $logEvent)
    {
        return $logEvent;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogEvent $logEvent)
    {
        $logEvent->delete();
        return [
            'success' => true
        ];
    }
}
