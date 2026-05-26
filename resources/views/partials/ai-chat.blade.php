{{-- AI customer-support chat widget (powered by OpenAI via /ai/chat) --}}
<style>
    #aiChatBtn{position:fixed;bottom:22px;right:22px;z-index:900;width:60px;height:60px;border:none;border-radius:50%;
        background:var(--green-700,#2e7d32);color:#fff;font-size:1.6rem;cursor:pointer;box-shadow:0 8px 24px rgba(0,0,0,.25);
        transition:transform .2s ease}
    #aiChatBtn:hover{transform:scale(1.08)}
    #aiChatPanel{position:fixed;bottom:94px;right:22px;z-index:900;width:350px;max-width:calc(100vw - 32px);height:480px;
        max-height:calc(100vh - 130px);background:#fff;border-radius:16px;box-shadow:0 16px 48px rgba(0,0,0,.28);
        display:none;flex-direction:column;overflow:hidden}
    #aiChatPanel.open{display:flex}
    .aic-head{background:var(--green-700,#2e7d32);color:#fff;padding:14px 16px;display:flex;justify-content:space-between;align-items:center}
    .aic-head b{font-size:1rem}
    .aic-head small{opacity:.85;display:block;font-size:.72rem}
    .aic-head button{background:none;border:none;color:#fff;font-size:1.2rem;cursor:pointer}
    .aic-body{flex:1;overflow-y:auto;padding:14px;background:#f4f7f2;display:flex;flex-direction:column;gap:10px}
    .aic-msg{max-width:82%;padding:9px 12px;border-radius:14px;font-size:.9rem;line-height:1.4;white-space:pre-line}
    .aic-msg.bot{background:#fff;align-self:flex-start;border-bottom-left-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
    .aic-msg.user{background:var(--green-700,#2e7d32);color:#fff;align-self:flex-end;border-bottom-right-radius:4px}
    .aic-typing{align-self:flex-start;color:#6b7d6b;font-size:.85rem;font-style:italic}
    .aic-foot{display:flex;gap:6px;padding:10px;border-top:1px solid #eef2ec;background:#fff}
    .aic-foot input{flex:1;border:1px solid #d8e0d4;border-radius:20px;padding:9px 14px;font-size:.9rem;outline:none}
    .aic-foot input:focus{border-color:var(--green-700,#2e7d32)}
    .aic-foot button{border:none;background:var(--green-700,#2e7d32);color:#fff;border-radius:20px;padding:0 16px;cursor:pointer;font-weight:600}
    .aic-foot button:disabled{opacity:.5;cursor:not-allowed}
</style>

<button id="aiChatBtn" aria-label="Chat with us" title="Need help? Chat with us">💬</button>

<div id="aiChatPanel" role="dialog" aria-label="Herbal Roots Assistant">
    <div class="aic-head">
        <div><b>🌿 Herbal Assistant</b><small>Ask about products, orders &amp; more</small></div>
        <button id="aiChatClose" aria-label="Close">✕</button>
    </div>
    <div class="aic-body" id="aicBody">
        <div class="aic-msg bot">Assalam-o-Alaikum! 🌿 I'm your Herbal Roots assistant. Tell me what you need — e.g. "I can't sleep well" — and I'll suggest the right products.</div>
    </div>
    <form class="aic-foot" id="aicForm">
        <input type="text" id="aicInput" placeholder="Type your message…" autocomplete="off" maxlength="1000" required>
        <button type="submit" id="aicSend">Send</button>
    </form>
</div>

<script>
(function () {
    const btn = document.getElementById('aiChatBtn'),
          panel = document.getElementById('aiChatPanel'),
          closeBtn = document.getElementById('aiChatClose'),
          body = document.getElementById('aicBody'),
          form = document.getElementById('aicForm'),
          input = document.getElementById('aicInput'),
          send = document.getElementById('aicSend'),
          endpoint = "{{ route('ai.chat') }}",
          csrf = "{{ csrf_token() }}";
    const history = [];

    btn.addEventListener('click', () => { panel.classList.toggle('open'); if (panel.classList.contains('open')) input.focus(); });
    closeBtn.addEventListener('click', () => panel.classList.remove('open'));

    function addMsg(text, who) {
        const el = document.createElement('div');
        el.className = 'aic-msg ' + who;
        el.textContent = text;
        body.appendChild(el);
        body.scrollTop = body.scrollHeight;
        return el;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = input.value.trim();
        if (!msg) return;

        addMsg(msg, 'user');
        input.value = '';
        send.disabled = true;

        const typing = document.createElement('div');
        typing.className = 'aic-typing';
        typing.textContent = 'Assistant is typing…';
        body.appendChild(typing);
        body.scrollTop = body.scrollHeight;

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ message: msg, history: history.slice(-8) })
            });
            const data = await res.json();
            typing.remove();
            const reply = data.reply || 'Sorry, something went wrong. Please try again.';
            addMsg(reply, 'bot');
            history.push({ role: 'user', content: msg });
            history.push({ role: 'assistant', content: reply });
        } catch (err) {
            typing.remove();
            addMsg('Connection error. Please check your internet and try again.', 'bot');
        } finally {
            send.disabled = false;
            input.focus();
        }
    });
})();
</script>
