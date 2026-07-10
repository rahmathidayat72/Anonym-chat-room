<x-layout>
    <div style="max-width: 640px; margin: 0 auto; padding: 64px 24px;">
        <h1 style="font-family: 'JetBrains Mono', monospace; font-size: 2rem; font-weight: 500; letter-spacing: -0.02em; margin-bottom: 8px;">
            EphemChat
        </h1>
        <p style="color: var(--secondary); margin-bottom: 48px; font-size: 0.92rem;">
            Temporary anonymous chat rooms. No sign-up required.
        </p>

        @if (session('error'))
            <div style="background: #7F1D1D; color: #FCA5A5; padding: 12px 16px; border-radius: 6px; margin-bottom: 24px; font-size: 0.85rem;">
                {{ session('error') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 48px;">
            <div style="background: var(--surface); padding: 24px; border-radius: 10px;">
                <h2 style="font-family: 'JetBrains Mono', monospace; font-size: 1rem; font-weight: 500; margin-bottom: 16px; color: var(--primary);">
                    Create Room
                </h2>
                <form action="/room/create" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Your name (max 30)" maxlength="30" required
                           style="width: 100%; padding: 12px; background: var(--neutral); border: 1px solid var(--secondary); border-radius: 6px; color: var(--primary); font-size: 0.85rem; margin-bottom: 12px; outline: none;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--secondary); margin-bottom: 16px; cursor: pointer;">
                        <input type="checkbox" name="is_private" value="1">
                        Private Room
                    </label>
                    <button type="submit" style="width: 100%; padding: 12px; background: var(--tertiary); color: var(--neutral); border: none; border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; font-weight: 500; cursor: pointer;">
                        Create
                    </button>
                </form>
            </div>

            <div style="background: var(--surface); padding: 24px; border-radius: 10px;">
                <h2 style="font-family: 'JetBrains Mono', monospace; font-size: 1rem; font-weight: 500; margin-bottom: 16px; color: var(--primary);">
                    Join Room
                </h2>
                <form action="/room/search" method="GET" onsubmit="event.preventDefault(); window.location='/room/'+this.code.value;">
                    @csrf
                    <input type="text" name="code" placeholder="Room code (8 chars)" maxlength="8" required
                           style="width: 100%; padding: 12px; background: var(--neutral); border: 1px solid var(--secondary); border-radius: 6px; color: var(--primary); font-size: 0.85rem; margin-bottom: 12px; outline: none; text-transform: uppercase;">
                    <button type="submit" style="width: 100%; padding: 12px; background: var(--tertiary); color: var(--neutral); border: none; border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; font-weight: 500; cursor: pointer;">
                        Join
                    </button>
                </form>
            </div>
        </div>

        @if ($rooms->isNotEmpty())
            <div style="background: var(--surface); padding: 24px; border-radius: 10px;">
                <h2 style="font-family: 'JetBrains Mono', monospace; font-size: 1rem; font-weight: 500; margin-bottom: 16px; color: var(--primary);">
                    Public Rooms
                </h2>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach ($rooms as $room)
                        <a href="/room/{{ $room->code }}" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: var(--neutral); border-radius: 6px; text-decoration: none; color: var(--primary); font-size: 0.85rem;">
                            <span style="font-family: 'JetBrains Mono', monospace;">{{ $room->code }}</span>
                            <span style="color: var(--secondary);">{{ $room->users_count }} online</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layout>
