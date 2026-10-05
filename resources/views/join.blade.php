<x-layout>
    <div style="
        min-height: 100vh; display: flex; align-items: center; justify-content: center;
        padding: 24px;
        background:
            radial-gradient(ellipse 80% 60% at 50% -10%, rgba(108,142,245,0.08) 0%, transparent 70%),
            var(--bg);
    ">
        <div style="width: 100%; max-width: 400px;">

            {{-- Back --}}
            <a href="/" style="
                display: inline-flex; align-items: center; gap: 6px; color: var(--secondary);
                font-size: 0.82rem; text-decoration: none; margin-bottom: 28px;
                transition: color 0.18s;
            " onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--secondary)'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Back to home
            </a>

            {{-- Card --}}
            <div class="card" style="padding: 32px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--accent), var(--accent2));"></div>

                {{-- Icon --}}
                <div style="
                    width: 52px; height: 52px; border-radius: 14px; margin-bottom: 20px;
                    background: linear-gradient(135deg, rgba(108,142,245,0.15), rgba(139,92,246,0.15));
                    border: 1px solid rgba(108,142,245,0.2);
                    display: flex; align-items: center; justify-content: center;
                ">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                </div>

                <h1 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 6px;">Join Room</h1>
                <p style="color: var(--secondary); font-size: 0.85rem; margin-bottom: 24px; line-height: 1.5;">
                    Room:
                    <span class="mono text-accent" style="
                        font-size: 0.82rem; letter-spacing: 0.08em;
                        background: rgba(108,142,245,0.1); padding: 2px 8px; border-radius: 4px;
                    ">{{ $room->code }}</span>
                </p>

                <form action="/room/{{ $room->code }}/join" method="POST">
                    @csrf
                    <label style="display: block; font-size: 0.8rem; color: var(--secondary); margin-bottom: 6px; font-weight: 500;">Display name</label>
                    <input type="text" name="name" placeholder="What should we call you?" maxlength="30" required
                           class="input" style="margin-bottom: 20px;" autofocus>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px 20px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        Enter Room
                    </button>
                </form>

                <p style="font-size: 0.78rem; color: var(--muted); text-align: center; margin-top: 20px; line-height: 1.5;">
                    By entering, you agree to keep things respectful.<br>Rooms auto-delete after expiry.
                </p>
            </div>
        </div>
    </div>
</x-layout>
