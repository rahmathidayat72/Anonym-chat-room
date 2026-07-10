<x-layout>
    <div id="app" style="display: flex; flex-direction: column; height: 100vh; max-width: 768px; margin: 0 auto; padding: 0 16px;">
        {{-- Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid var(--surface);">
            <div>
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: var(--tertiary);">{{ $room->code }}</span>
                <span id="countdown" style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--secondary); margin-left: 12px;"></span>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <span id="online-count" style="font-size: 0.75rem; color: var(--emerald); font-family: 'JetBrains Mono', monospace;"></span>
                <button onclick="copyLink()" style="background: none; border: 1px solid var(--secondary); color: var(--secondary); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">Copy</button>
                <button onclick="shareWA()" style="background: none; border: 1px solid var(--secondary); color: var(--secondary); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">WA</button>
                <button onclick="shareTG()" style="background: none; border: 1px solid var(--secondary); color: var(--secondary); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">TG</button>
                <button id="sound-toggle" onclick="toggleSound()" style="background: none; border: 1px solid var(--secondary); color: var(--secondary); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">Sound</button>
                <form action="/room/{{ $room->code }}/leave" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: 1px solid #7F1D1D; color: #FCA5A5; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">Leave</button>
                </form>
            </div>
        </div>

        {{-- Messages --}}
        <div id="messages" style="flex: 1; overflow-y: auto; padding: 16px 0; display: flex; flex-direction: column; gap: 8px;">
            <div id="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--secondary);">
                <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.3;">💬</div>
                <p style="font-size: 0.92rem;">No messages yet</p>
                <p style="font-size: 0.8rem; margin-top: 4px;">Be the first to say something</p>
            </div>
        </div>

        {{-- Typing indicator --}}
        <div id="typing-indicator" style="font-size: 0.75rem; color: var(--secondary); padding: 4px 0; min-height: 24px;"></div>

        {{-- Input --}}
        <form id="chat-form" style="display: flex; gap: 8px; padding: 12px 0; border-top: 1px solid var(--surface);">
            @csrf
            <input id="message-input" type="text" placeholder="Type a message..." maxlength="500" autocomplete="off"
                   style="flex: 1; padding: 12px; background: var(--surface); border: 1px solid var(--secondary); border-radius: 6px; color: var(--primary); font-size: 0.85rem; outline: none;">
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
                    div.innerHTML = '<div style="background:var(--violet);color:#fff;padding:8px 14px;border-radius:10px 10px 2px 10px;font-size:0.85rem;word-break:break-word;">' + escapeHtml(msg.message) + '</div><span style="font-size:0.7rem;color:var(--secondary);text-align:right;margin-top:2px;">' + escapeHtml(msg.name) + ' ' + msg.time + '</span>';
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
                renderMessages(res.data.messages);
                updateSidebar(res.data);
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
            btn.style.borderColor = soundEnabled ? 'var(--secondary)' : '#7F1D1D';
            btn.style.color = soundEnabled ? 'var(--secondary)' : '#FCA5A5';
        }

        // Start polling
        poll();
        setInterval(poll, 2000);
    </script>
</x-layout>
