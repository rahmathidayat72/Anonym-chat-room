<x-layout>
    <div style="max-width: 400px; margin: 0 auto; padding: 64px 24px;">
        <div style="background: var(--surface); padding: 24px; border-radius: 10px;">
            <h1 style="font-family: 'JetBrains Mono', monospace; font-size: 1.25rem; font-weight: 500; margin-bottom: 4px;">
                Join Room
            </h1>
            <p style="color: var(--secondary); font-size: 0.85rem; margin-bottom: 24px;">
                Code: <span style="font-family: 'JetBrains Mono', monospace; color: var(--tertiary);">{{ $room->code }}</span>
            </p>

            <form action="/room/{{ $room->code }}/join" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Your name (max 30)" maxlength="30" required
                       style="width: 100%; padding: 12px; background: var(--neutral); border: 1px solid var(--secondary); border-radius: 6px; color: var(--primary); font-size: 0.85rem; margin-bottom: 16px; outline: none;">
                <button type="submit" style="width: 100%; padding: 12px; background: var(--tertiary); color: var(--neutral); border: none; border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; font-weight: 500; cursor: pointer;">
                    Enter Room
                </button>
            </form>
        </div>
    </div>
</x-layout>
