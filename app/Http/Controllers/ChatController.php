<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $rooms = Room::active()
            ->where('is_private', false)
            ->withCount(['users' => fn ($q) => $q->online()])
            ->latest()
            ->get();

        return view('home', compact('rooms'));
    }

    public function createRoom(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:30',
            'is_private' => 'sometimes|boolean',
        ]);

        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (Room::where('code', $code)->exists());

        $room = Room::create([
            'code' => $code,
            'is_private' => $data['is_private'] ?? false,
            'expired_at' => now()->addHours(24),
        ]);

        $name = $this->resolveDuplicateName($room->id, $data['name']);

        $user = User::create([
            'room_id' => $room->id,
            'session_id' => session()->getId(),
            'name' => $name,
            'last_seen' => now(),
        ]);

        session(['user_name' => $name, 'room_code' => $room->code, 'user_id' => $user->id]);

        Message::create([
            'room_id' => $room->id,
            'user_id' => null,
            'message' => "{$name} joined the room",
            'is_system' => true,
        ]);

        return redirect("/room/{$room->code}");
    }

    public function joinByCode($code)
    {
        $room = Room::where('code', $code)->firstOrFail();

        if ($room->expired_at <= now()) {
            $room->delete();

            return redirect('/')->with('error', 'Room has expired.');
        }

        if (! session('user_name')) {
            return view('join', compact('room'));
        }

        $user = User::where('session_id', session()->getId())
            ->where('room_id', $room->id)
            ->first();

        if (! $user) {
            return view('join', compact('room'));
        }

        $user->update(['last_seen' => now()]);

        return view('room', compact('room'));
    }

    public function registerUser(Request $request, $code)
    {
        $room = Room::where('code', $code)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:30',
        ]);

        $name = $this->resolveDuplicateName($room->id, $data['name']);

        $user = User::create([
            'room_id' => $room->id,
            'session_id' => session()->getId(),
            'name' => $name,
            'last_seen' => now(),
        ]);

        session(['user_name' => $name, 'room_code' => $room->code, 'user_id' => $user->id]);

        Message::create([
            'room_id' => $room->id,
            'user_id' => null,
            'message' => "{$name} joined the room",
            'is_system' => true,
        ]);

        return redirect("/room/{$room->code}");
    }

    public function sync($code)
    {
        $room = Room::where('code', $code)->firstOrFail();

        $messages = Message::where('room_id', $room->id)
            ->with('user:id,name')
            ->oldest()
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'user_id' => $m->user_id,
                'name' => $m->is_system ? null : optional($m->user)->name,
                'message' => $m->message,
                'is_system' => $m->is_system,
                'time' => $m->created_at->format('H:i'),
            ]);

        $onlineUsers = User::where('room_id', $room->id)
            ->online()
            ->pluck('name');

        $typingUsers = User::where('room_id', $room->id)
            ->typing()
            ->where('session_id', '!=', session()->getId())
            ->pluck('name');

        $countdown = max(0, now()->diffInSeconds($room->expired_at, false));

        $userId = User::where('session_id', session()->getId())
            ->where('room_id', $room->id)
            ->value('id');

        return response()->json([
            'messages' => $messages,
            'online_users' => $onlineUsers,
            'typing_users' => $typingUsers,
            'countdown' => $countdown,
            'user_id' => $userId,
        ]);
    }

    public function sendMessage(Request $request, $code, RateLimiter $limiter)
    {
        $key = 'message:'.session()->getId();
        $maxAttempts = 1;
        $decaySeconds = 1;

        if ($limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json(['error' => 'Too many messages'], 429);
        }

        $limiter->hit($key, $decaySeconds);

        $room = Room::where('code', $code)->firstOrFail();

        $data = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $user = User::where('session_id', session()->getId())
            ->where('room_id', $room->id)
            ->first();

        if (! $user) {
            return response()->json(['error' => 'Not in room'], 403);
        }

        $message = Message::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'message' => strip_tags($data['message']),
            'is_system' => false,
        ]);

        $user->update(['last_seen' => now()]);

        return response()->json(['id' => $message->id]);
    }

    public function typing(Request $request, $code)
    {
        $room = Room::where('code', $code)->firstOrFail();

        User::where('session_id', session()->getId())
            ->where('room_id', $room->id)
            ->update(['typing_at' => now(), 'last_seen' => now()]);

        return response()->noContent();
    }

    public function leaveRoom($code)
    {
        $room = Room::where('code', $code)->firstOrFail();

        $user = User::where('session_id', session()->getId())
            ->where('room_id', $room->id)
            ->first();

        if ($user) {
            Message::create([
                'room_id' => $room->id,
                'user_id' => null,
                'message' => "{$user->name} left the room",
                'is_system' => true,
            ]);

            $user->delete();
        }

        session()->forget(['user_name', 'room_code', 'user_id']);

        return redirect('/');
    }

    private function resolveDuplicateName($roomId, $name)
    {
        $existing = User::where('room_id', $roomId)
            ->where('name', 'LIKE', "{$name}%")
            ->pluck('name');

        if (! $existing->contains($name)) {
            return $name;
        }

        $suffix = 2;
        while ($existing->contains("{$name} ({$suffix})")) {
            $suffix++;
        }

        return "{$name} ({$suffix})";
    }
}
