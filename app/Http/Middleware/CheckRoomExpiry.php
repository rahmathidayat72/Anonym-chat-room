<?php

namespace App\Http\Middleware;

use App\Models\Room;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoomExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        $code = $request->route('code');

        if ($code) {
            $room = Room::where('code', $code)->first();

            if ($room && $room->expired_at <= now()) {
                $room->delete();

                return redirect('/')->with('error', 'Room has expired.');
            }
        }

        return $next($request);
    }
}
