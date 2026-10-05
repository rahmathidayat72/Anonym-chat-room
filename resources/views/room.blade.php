    <x-layout>
    <div id="app" style="display: flex; flex-direction: column; height: 100vh;">

        {{-- ══════════ HEADER ══════════ --}}
        <header style="
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 20px; height: 56px; flex-shrink: 0;
            background: var(--bg2); border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        ">
            {{-- Left: logo + room info --}}
            <div style="display: flex; align-items: center; gap: 14px;">
                <a href="/" style="
                    display: flex; align-items: center; gap: 6px; color: var(--secondary);
                    text-decoration: none; font-size: 0.78rem; transition: color 0.18s;
                " title="Back to home"
                onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--secondary)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <div style="width: 1px; height: 18px; background: var(--border);"></div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="mono text-accent" style="font-size: 0.82rem; letter-spacing: 0.1em; background: rgba(108,142,245,0.08); padding: 3px 10px; border-radius: 6px; border: 1px solid rgba(108,142,245,0.15);">{{ $room->code }}</span>
                    <span id="countdown" class="mono" style="font-size: 0.72rem; color: var(--secondary);"></span>
                </div>
            </div>

            {{-- Right: controls --}}
            <div style="display: flex; gap: 6px; align-items: center;">
                <span id="online-count" style="
                    display: inline-flex; align-items: center; gap: 5px;
                    font-size: 0.72rem; color: var(--green); margin-right: 4px;
                ">
                    <span class="dot"></span>
                    <span>0 online</span>
                </span>

                {{-- Copy link --}}
                <button onclick="copyLink()" title="Copy link" class="btn btn-ghost" style="padding: 6px 8px; border-radius: 7px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                </button>

                {{-- WhatsApp --}}
                <button onclick="shareWA()" title="Share via WhatsApp" class="btn btn-ghost" style="padding: 6px 8px; border-radius: 7px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.13 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </button>

                {{-- Telegram --}}
                <button onclick="shareTG()" title="Share via Telegram" class="btn btn-ghost" style="padding: 6px 8px; border-radius: 7px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>

                {{-- Sound --}}
                <button id="sound-toggle" onclick="toggleSound()" title="Toggle sound" class="btn btn-ghost" style="padding: 6px 8px; border-radius: 7px;">
                    <svg id="sound-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                </button>

                {{-- Leave --}}
                <form action="/room/{{ $room->code }}/leave" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" title="Leave room" class="btn btn-danger" style="padding: 6px 8px; border-radius: 7px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- ══════════ MESSAGES ══════════ --}}
        <div id="messages" style="
            flex: 1; overflow-y: auto; padding: 20px 24px;
            display: flex; flex-direction: column; gap: 10px;
        ">
            <div id="empty-state" style="
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                height: 100%; color: var(--muted); gap: 10px;
            ">
                <div style="
                    width: 64px; height: 64px; border-radius: 16px;
                    background: rgba(108,142,245,0.06); border: 1px solid var(--border);
                    display: flex; align-items: center; justify-content: center; font-size: 1.8rem;
                ">💬</div>
                <p style="font-size: 0.88rem; color: var(--secondary);">No messages yet</p>
                <p style="font-size: 0.78rem; color: var(--muted);">Be the first to say something</p>
            </div>
        </div>

        {{-- ══════════ TYPING INDICATOR ══════════ --}}
        <div id="typing-indicator" style="
            padding: 4px 24px; min-height: 22px; font-size: 0.73rem;
            color: var(--secondary); font-style: italic;
        "></div>

        {{-- ══════════ INPUT AREA ══════════ --}}
        <div style="padding: 10px 16px 16px; background: var(--bg2); border-top: 1px solid var(--border); flex-shrink: 0;">
            <form id="chat-form" style="display: flex; gap: 8px; align-items: flex-end; position: relative;">
                @csrf

                {{-- Emoji button --}}
                <button type="button" id="emoji-btn" onclick="toggleEmojiPicker()"
                        class="btn btn-ghost" style="padding: 10px; border-radius: 8px; flex-shrink: 0; height: 44px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                </button>

                {{-- Emoji picker --}}
                <div id="emoji-picker" style="
                    display: none; position: absolute; bottom: calc(100% + 12px); left: 0;
                    background: var(--surface2); border: 1px solid var(--border2);
                    border-radius: 12px; padding: 10px; width: 280px; max-height: 200px;
                    overflow-y: auto; z-index: 100; flex-wrap: wrap; gap: 2px;
                    box-shadow: 0 16px 48px rgba(0,0,0,0.5); align-content: flex-start;
                "></div>

                {{-- Text input --}}
                <input id="message-input" type="text" placeholder="Type a message…" maxlength="500" autocomplete="off"
                       class="input" style="flex: 1; height: 44px; border-radius: 8px;">

                {{-- Send --}}
                <button type="submit" class="btn btn-primary" style="height: 44px; padding: 0 20px; flex-shrink: 0; border-radius: 8px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    <span style="font-size: 0.85rem;">Send</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        const roomCode = '{{ $room->code }}';
        const syncUrl  = '/room/' + roomCode + '/sync';
        const typingUrl = '/room/' + roomCode + '/typing';
        let userId = null;
        let soundEnabled = true;
        let typingTimer = null;
        let lastMessageId = 0;

        // ── Scroll ──────────────────────────────────────────
        function scrollToBottom(force) {
            const el = document.getElementById('messages');
            const atBottom = el.scrollHeight - el.scrollTop - el.clientHeight < 120;
            if (force || atBottom) el.scrollTop = el.scrollHeight;
        }

        // ── Escape HTML ──────────────────────────────────────
        function escapeHtml(text) {
            const d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        // ── Render Messages ──────────────────────────────────
        function renderMessages(messages) {
            const container = document.getElementById('messages');
            const emptyState = document.getElementById('empty-state');

            if (messages.length === 0) return;
            if (emptyState) emptyState.remove();

            messages.forEach(msg => {
                if (msg.id <= lastMessageId) return;
                lastMessageId = msg.id;

                const wrap = document.createElement('div');

                if (msg.is_system) {
                    // System message
                    wrap.style.cssText = 'display:flex;justify-content:center;';
                    wrap.innerHTML = `
                        <span style="
                            font-size:0.72rem; color:var(--muted); background:var(--surface);
                            border:1px solid var(--border); padding:3px 12px; border-radius:20px;
                        ">${escapeHtml(msg.message)}</span>
                    `;
                } else if (msg.user_id === userId) {
                    // My message
                    wrap.style.cssText = 'display:flex;flex-direction:column;align-items:flex-end;max-width:70%;align-self:flex-end;';
                    wrap.innerHTML = `
                        <div style="
                            background:linear-gradient(135deg, var(--accent), var(--accent2));
                            color:#fff; padding:9px 14px; border-radius:12px 12px 3px 12px;
                            font-size:0.875rem; word-break:break-word; line-height:1.45;
                            box-shadow:0 2px 12px rgba(108,142,245,0.25);
                        ">${escapeHtml(msg.message)}</div>
                        <span style="font-size:0.68rem; color:var(--muted); margin-top:4px; padding-right:2px;">
                            ${escapeHtml(msg.name)} · ${msg.time}
                        </span>
                    `;
                } else {
                    // Other message
                    wrap.style.cssText = 'display:flex;flex-direction:column;align-items:flex-start;max-width:70%;align-self:flex-start;';
                    wrap.innerHTML = `
                        <div style="
                            background:var(--surface2); color:var(--primary);
                            padding:9px 14px; border-radius:12px 12px 12px 3px;
                            font-size:0.875rem; word-break:break-word; line-height:1.45;
                            border:1px solid var(--border);
                        ">${escapeHtml(msg.message)}</div>
                        <span style="font-size:0.68rem; color:var(--muted); margin-top:4px; padding-left:2px;">
                            ${escapeHtml(msg.name)} · ${msg.time}
                        </span>
                    `;
                }

                container.appendChild(wrap);
            });

            scrollToBottom(false);
        }

        // ── Sidebar / online users ───────────────────────────
        function updateSidebar(data) {
            const countEl = document.getElementById('online-count');
            countEl.innerHTML = `<span class="dot"></span><span>${data.online_users.length} online</span>`;

            const typing = document.getElementById('typing-indicator');
            if (data.typing_users.length > 0) {
                typing.textContent = data.typing_users.join(', ')
                    + (data.typing_users.length === 1 ? ' is' : ' are')
                    + ' typing…';
            } else {
                typing.textContent = '';
            }

            if (!userId && data.user_id) userId = data.user_id;
        }

        // ── Countdown ───────────────────────────────────────
        function updateCountdown(seconds) {
            const el = document.getElementById('countdown');
            if (seconds <= 0) {
                el.textContent = 'Expired';
                el.style.color = 'var(--red)';
                return;
            }
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            el.textContent = h.toString().padStart(2,'0') + ':' + m.toString().padStart(2,'0') + ':' + s.toString().padStart(2,'0');
            el.style.color = seconds < 3600 ? 'var(--red)' : 'var(--secondary)';
        }

        // ── Poll ─────────────────────────────────────────────
        function poll() {
            axios.get(syncUrl).then(res => {
                updateSidebar(res.data);
                renderMessages(res.data.messages);
                updateCountdown(res.data.countdown);
            });
        }

        // ── Send message ─────────────────────────────────────
        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const msg = input.value.trim();
            if (!msg) return;

            const btn = this.querySelector('button[type=submit]');
            btn.disabled = true;

            axios.post('/room/' + roomCode + '/message', { message: msg })
                .then(() => { input.value = ''; poll(); })
                .catch(err => {
                    if (err.response && err.response.status === 429) {
                        showToast('Too fast! Wait a second.');
                    }
                })
                .finally(() => btn.disabled = false);
        });

        // ── Typing ───────────────────────────────────────────
        document.getElementById('message-input').addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => axios.post(typingUrl), 300);
        });

        // ── Share ─────────────────────────────────────────────
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => showToast('🔗 Link copied!'));
        }
        function shareWA() {
            window.open('https://wa.me/?text=' + encodeURIComponent('Join my EphemChat room: ' + window.location.href), '_blank');
        }
        function shareTG() {
            window.open('https://t.me/share/url?url=' + encodeURIComponent(window.location.href) + '&text=Join%20my%20EphemChat%20room!', '_blank');
        }

        // ── Sound ────────────────────────────────────────────
        function toggleSound() {
            soundEnabled = !soundEnabled;
            const btn = document.getElementById('sound-toggle');
            const icon = document.getElementById('sound-icon');
            btn.style.color = soundEnabled ? 'var(--secondary)' : 'var(--red)';
            btn.style.borderColor = soundEnabled ? 'var(--border)' : 'rgba(248,113,113,0.3)';
            icon.innerHTML = soundEnabled
                ? '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>'
                : '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line>';
        }

        // ── Emoji picker ─────────────────────────────────────
        const EMOJIS = [
            '😀','😃','😄','😁','😅','😂','🤣','😊',
            '😇','🙂','😉','😍','🥰','😘','😋','😛',
            '😜','🤪','🤔','😏','😒','🙄','🥺','😢',
            '😭','😤','😡','🤬','👍','👎','🤞','✌️',
            '👌','💪','🔥','✨','💯','🎉','❤️','💜',
            '😎','🥳','🤩','🫶','🙌','👏','💥','⚡',
        ];

        function buildEmojiPicker() {
            const picker = document.getElementById('emoji-picker');
            EMOJIS.forEach(e => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = e;
                btn.style.cssText = 'background:none;border:none;cursor:pointer;font-size:1.3rem;padding:5px;border-radius:6px;line-height:1;transition:background 0.12s;';
                btn.onmouseenter = () => btn.style.background = 'var(--surface)';
                btn.onmouseleave = () => btn.style.background = 'none';
                btn.onclick = () => insertEmoji(e);
                picker.appendChild(btn);
            });
        }

        function toggleEmojiPicker() {
            const picker = document.getElementById('emoji-picker');
            picker.style.display = (picker.style.display === 'none' || !picker.style.display) ? 'flex' : 'none';
        }

        function insertEmoji(emoji) {
            const input = document.getElementById('message-input');
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const val = input.value;
            input.value = val.substring(0, start) + emoji + val.substring(end);
            input.selectionStart = input.selectionEnd = start + emoji.length;
            input.focus();
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#emoji-btn') && !e.target.closest('#emoji-picker')) {
                document.getElementById('emoji-picker').style.display = 'none';
            }
        });

        buildEmojiPicker();
        poll();
        setInterval(poll, 2000);
    </script>
</x-layout>
