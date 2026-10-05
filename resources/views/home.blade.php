<x-layout>
    {{-- Animated background grid --}}
    <div style="
        position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
        background:
            linear-gradient(rgba(108,142,245,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(108,142,245,0.03) 1px, transparent 1px);
        background-size: 48px 48px;
    "></div>

    {{-- Glow orbs --}}
    <div style="position: fixed; top: -200px; right: -200px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(108,142,245,0.06) 0%, transparent 70%); pointer-events: none; z-index: 0;"></div>
    <div style="position: fixed; bottom: -200px; left: -200px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(139,92,246,0.05) 0%, transparent 70%); pointer-events: none; z-index: 0;"></div>

    <div style="position: relative; z-index: 1; max-width: 680px; margin: 0 auto; padding: 56px 24px 80px;">

        {{-- Logo / Header --}}
        <div style="margin-bottom: 52px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="
                    width: 40px; height: 40px; border-radius: 10px;
                    background: linear-gradient(135deg, var(--accent), var(--accent2));
                    display: flex; align-items: center; justify-content: center;
                    box-shadow: 0 0 20px rgba(108,142,245,0.35);
                ">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <h1 class="mono" style="font-size: 1.6rem; font-weight: 600; letter-spacing: -0.03em; background: linear-gradient(135deg, var(--primary) 60%, var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    EphemChat
                </h1>
            </div>
            <p style="color: var(--secondary); font-size: 0.9rem; padding-left: 52px; line-height: 1.6;">
                Temporary anonymous chat rooms. No sign-up. No trace.
            </p>
        </div>

        @if (session('error'))
            <div style="
                display: flex; align-items: center; gap: 10px;
                background: var(--red-bg); color: var(--red);
                padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;
                font-size: 0.85rem; border: 1px solid rgba(248,113,113,0.2);
            ">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Action Cards --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 32px;">

            {{-- Create Room --}}
            <div class="card" style="padding: 24px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--accent), var(--accent2));"></div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 18px;">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: rgba(108,142,245,0.12); display: flex; align-items: center; justify-content: center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </div>
                    <h2 style="font-size: 0.92rem; font-weight: 600; color: var(--primary);">Create Room</h2>
                </div>
                <form action="/room/create" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Your display name" maxlength="30" required class="input" style="margin-bottom: 10px;">
                    <label class="checkbox-label" style="margin-bottom: 16px;">
                        <input type="checkbox" name="is_private" value="1">
                        <span>Private room</span>
                    </label>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Create
                    </button>
                </form>
            </div>

            {{-- Join Room --}}
            <div class="card" style="padding: 24px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--accent2), var(--accent3));"></div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 18px;">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: rgba(6,214,160,0.1); display: flex; align-items: center; justify-content: center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent3)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                    </div>
                    <h2 style="font-size: 0.92rem; font-weight: 600; color: var(--primary);">Join Room</h2>
                </div>
                <form action="/room/search" method="GET" onsubmit="event.preventDefault(); window.location='/room/'+this.code.value.toUpperCase();">
                    <input type="text" name="code" placeholder="Room code (8 chars)" maxlength="8" required
                           class="input mono" style="margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.1em;">
                    <div style="height: 38px; margin-bottom: 16px;"></div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; background: linear-gradient(135deg, var(--accent2), var(--accent3)); box-shadow: 0 0 20px rgba(139,92,246,0.2);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        Join
                    </button>
                </form>
            </div>
        </div>

        {{-- Public Rooms --}}
        @if ($rooms->isNotEmpty())
            <div class="card" style="padding: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 28px; height: 28px; border-radius: 7px; background: rgba(52,211,153,0.1); display: flex; align-items: center; justify-content: center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <h2 style="font-size: 0.92rem; font-weight: 600; color: var(--primary);">Public Rooms</h2>
                    </div>
                    <span class="badge badge-green">
                        <span class="dot"></span>
                        {{ $rooms->count() }} active
                    </span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    @foreach ($rooms as $room)
                        <a href="/room/{{ $room->code }}" style="
                            display: flex; justify-content: space-between; align-items: center;
                            padding: 11px 14px; background: var(--surface2); border: 1px solid var(--border);
                            border-radius: 8px; text-decoration: none; color: var(--primary);
                            font-size: 0.85rem; transition: all 0.18s ease;
                        "
                        onmouseover="this.style.borderColor='var(--border2)';this.style.background='var(--bg2)';"
                        onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--surface2)';">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span class="dot"></span>
                                <span class="mono" style="font-size: 0.8rem; letter-spacing: 0.06em; color: var(--accent);">{{ $room->code }}</span>
                            </div>
                            <span class="badge badge-muted">{{ $room->users_count }} online</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 40px 0; color: var(--muted);">
                <div style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.4;">💬</div>
                <p style="font-size: 0.88rem;">No public rooms yet. Create one!</p>
            </div>
        @endif
    </div>
</x-layout>
