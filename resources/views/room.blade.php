    <x-layout>
    <div id="app" style="display: flex; flex-direction: column; height: 100vh; padding: 0 16px;">
        {{-- Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid var(--surface);">
            <div>
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: var(--tertiary);">{{ $room->code }}</span>
                <span id="countdown" style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--secondary); margin-left: 12px;"></span>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <span id="online-count" style="font-size: 0.75rem; color: var(--emerald); font-family: 'JetBrains Mono', monospace;"></span>
                <button onclick="copyLink()" title="Copy link" style="background: none; border: 1px solid var(--secondary); color: var(--secondary); padding: 6px 8px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                </button>
                <button onclick="shareWA()" title="Share via WhatsApp" style="background: none; border: 1px solid var(--secondary); color: var(--secondary); padding: 6px 8px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </button>
                <button onclick="shareTG()" title="Share via Telegram" style="background: none; border: 1px solid var(--secondary); color: var(--secondary); padding: 6px 8px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
                <button id="sound-toggle" onclick="toggleSound()" title="Toggle sound" style="background: none; border: 1px solid var(--secondary); color: var(--secondary); padding: 6px 8px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                    <svg id="sound-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                </button>
                <form action="/room/{{ $room->code }}/leave" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" title="Leave room" style="background: none; border: 1px solid #7F1D1D; color: #FCA5A5; padding: 6px 8px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Messages --}}
        <div id="messages" style="flex: 1; overflow-y: auto; padding: 16px 16px; display: flex; flex-direction: column; gap: 8px;">
            <div id="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--secondary);">
                <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.3;">💬</div>
                <p style="font-size: 0.92rem;">No messages yet</p>
                <p style="font-size: 0.8rem; margin-top: 4px;">Be the first to say something</p>
            </div>
        </div>

        {{-- Typing indicator --}}
        <div id="typing-indicator" style="font-size: 0.75rem; color: var(--secondary); padding: 4px 16px; min-height: 24px;"></div>

        {{-- Input --}}
        <form id="chat-form" style="display: flex; gap: 8px; padding: 12px 0; border-top: 1px solid var(--surface);">
            @csrf
            <div style="display: flex; flex: 1; position: relative;">
                <button type="button" id="emoji-btn" onclick="toggleEmojiPicker()"
                        style="background: none; border: 1px solid var(--secondary); border-right: none; color: var(--secondary); padding: 6px 10px; border-radius: 6px 0 0 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                </button>
                <input id="message-input" type="text" placeholder="Type a message..." maxlength="500" autocomplete="off"
                       style="flex: 1; padding: 12px 12px 12px 8px; background: var(--surface); border: 1px solid var(--secondary); border-left: none; border-radius: 0 6px 6px 0; color: var(--primary); font-size: 0.85rem; outline: none;">
                <div id="emoji-picker" style="display: none; position: absolute; bottom: calc(100% + 8px); left: 0; background: var(--surface); border: 1px solid var(--secondary); border-radius: 8px; padding: 8px; width: 272px; max-height: 200px; overflow-y: auto; flex-wrap: wrap; gap: 2px; z-index: 100; align-content: flex-start;"></div>
            </div>
            <button type="submit" style="padding: 12px 20px; background: var(--tertiary); color: var(--neutral); border: none; border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; cursor: pointer;">Send</button>
        </form>
    </div>

    <script>
        const roomCode = '{{ $room->code }}';
        const syncUrl = '/room/' + roomCode + '/sync';
        const typingUrl = '/room/' + roomCode + '/typing';
        let userId = null;
        let soundEnabled = true;
        let typingTimer = null;
        let lastMessageId = 0;

        function scrollToBottom(force) {
            const el = document.getElementById('messages');
            const threshold = 100;
            const atBottom = el.scrollHeight - el.scrollTop - el.clientHeight < threshold;
            if (force || atBottom) {
                el.scrollTop = el.scrollHeight;
            }
        }

        function renderMessages(messages) {
            const container = document.getElementById('messages');
            const emptyState = document.getElementById('empty-state');

            if (messages.length === 0) {
                if (!emptyState) {
                    const es = document.createElement('div');
                    es.id = 'empty-state';
                    es.style.cssText = 'display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:var(--secondary);';
                    es.innerHTML = '<div style="font-size:3rem;margin-bottom:16px;opacity:0.3;">💬</div><p style="font-size:0.92rem;">No messages yet</p><p style="font-size:0.8rem;margin-top:4px;">Be the first to say something</p>';
                    container.appendChild(es);
                }
                return;
            }

            if (emptyState) emptyState.remove();

            messages.forEach(msg => {
                if (msg.id <= lastMessageId) return;
                lastMessageId = msg.id;

                const div = document.createElement('div');
                div.style.cssText = 'display:flex;flex-direction:column;max-width:75%;';

                if (msg.is_system) {
                    div.style.cssText += 'align-items:center;max-width:100%;';
                    div.innerHTML = '<span style="font-size:0.75rem;color:var(--secondary);padding:4px 12px;">' + escapeHtml(msg.message) + '</span>';
                } else if (msg.user_id === userId) {
                    div.style.cssText += 'align-self:flex-end;';
                    div.innerHTML = '<div style="background:var(--bubble);color:#fff;padding:8px 14px;border-radius:10px 10px 2px 10px;font-size:0.85rem;word-break:break-word;">' + escapeHtml(msg.message) + '</div><span style="font-size:0.7rem;color:var(--secondary);text-align:right;margin-top:2px;">' + escapeHtml(msg.name) + ' ' + msg.time + '</span>';
                } else {
                    div.style.cssText += 'align-self:flex-start;';
                    div.innerHTML = '<div style="background:var(--surface);color:var(--primary);padding:8px 14px;border-radius:10px 10px 10px 2px;font-size:0.85rem;word-break:break-word;">' + escapeHtml(msg.message) + '</div><span style="font-size:0.7rem;color:var(--secondary);margin-top:2px;">' + escapeHtml(msg.name) + ' ' + msg.time + '</span>';
                }

                container.appendChild(div);
            });

            scrollToBottom(false);
        }

        function escapeHtml(text) {
            const d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        function updateSidebar(data) {
            const count = document.getElementById('online-count');
            count.textContent = data.online_users.length + ' online';

            const typing = document.getElementById('typing-indicator');
            if (data.typing_users.length > 0) {
                typing.textContent = data.typing_users.join(', ') + (data.typing_users.length === 1 ? ' is' : ' are') + ' typing...';
            } else {
                typing.textContent = '';
            }

            if (!userId && data.user_id) {
                userId = data.user_id;
            }
        }

        function updateCountdown(seconds) {
            const el = document.getElementById('countdown');
            if (seconds <= 0) {
                el.textContent = 'Expired';
                el.style.color = '#FCA5A5';
                return;
            }
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            el.textContent = h.toString().padStart(2,'0') + ':' + m.toString().padStart(2,'0') + ':' + s.toString().padStart(2,'0');
            if (seconds < 3600) {
                el.style.color = '#FCA5A5';
            } else {
                el.style.color = 'var(--secondary)';
            }
        }

        function poll() {
            axios.get(syncUrl).then(res => {
                updateSidebar(res.data);
                renderMessages(res.data.messages);
                updateCountdown(res.data.countdown);
            });
        }

        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const msg = input.value.trim();
            if (!msg) return;

            axios.post('/room/' + roomCode + '/message', { message: msg })
                .then(() => {
                    input.value = '';
                    poll();
                })
                .catch(err => {
                    if (err.response && err.response.status === 429) {
                        showToast('Too fast! Wait a second.');
                    }
                });
        });

        document.getElementById('message-input').addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                axios.post(typingUrl);
            }, 300);
        });

        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => showToast('Link copied!'));
        }

        function shareWA() {
            window.open('https://wa.me/?text=' + encodeURIComponent('Join my EphemChat room: ' + window.location.href), '_blank');
        }

        function shareTG() {
            window.open('https://t.me/share/url?url=' + encodeURIComponent(window.location.href) + '&text=Join%20my%20EphemChat%20room!', '_blank');
        }

        function toggleSound() {
            soundEnabled = !soundEnabled;
            const btn = document.getElementById('sound-toggle');
            const icon = document.getElementById('sound-icon');
            btn.style.borderColor = soundEnabled ? 'var(--secondary)' : '#7F1D1D';
            btn.style.color = soundEnabled ? 'var(--secondary)' : '#FCA5A5';
            icon.innerHTML = soundEnabled
                ? '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>'
                : '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line>';
        }

        // Emoji picker
        const EMOJIS = [
            '😀','😃','😄','😁','😅','😂','🤣','😊',
            '😇','🙂','😉','😍','🥰','😘','😋','😛',
            '😜','🤪','🤔','😏','😒','🙄','🥺','😢',
            '😭','😤','😡','🤬','👍','👎','🤞','✌️',
            '👌','💪','🔥','✨','💯','🎉','❤️','💜',
        ];

        function buildEmojiPicker() {
            const picker = document.getElementById('emoji-picker');
            EMOJIS.forEach(e => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = e;
                btn.style.cssText = 'background:none;border:none;cursor:pointer;font-size:1.3rem;padding:4px;border-radius:4px;line-height:1;transition:background 0.15s;';
                btn.onmouseenter = () => btn.style.background = 'var(--secondary)';
                btn.onmouseleave = () => btn.style.background = 'none';
                btn.onclick = () => insertEmoji(e);
                picker.appendChild(btn);
            });
        }

        function toggleEmojiPicker() {
            const picker = document.getElementById('emoji-picker');
            picker.style.display = picker.style.display === 'none' || picker.style.display === '' ? 'flex' : 'none';
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

        // Start polling
        poll();
        setInterval(poll, 2000);
    </script>
</x-layout>
