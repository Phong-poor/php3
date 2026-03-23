<script setup>
import { ref, nextTick, watch } from 'vue'

const isOpen = ref(true)
const isMinimized = ref(false)
const inputText = ref('')
const messagesEnd = ref(null)
const isTyping = ref(false)

const quickReplies = ['Tư vấn máy tính', 'Tra cứu đơn hàng', 'Khuyến mãi mới nhất']

const messages = ref([
    {
        id: 1,
        from: 'bot',
        text: 'Chào bạn! NextGen Laptop có thể giúp gì cho bạn trong việc lựa chọn thiết bị công nghệ hôm nay?',
        time: '10:45 AM',
        showQuick: true,
    }
])

const scrollToBottom = async () => {
    await nextTick()
    if (messagesEnd.value) {
        messagesEnd.value.scrollIntoView({ behavior: 'smooth' })
    }
}

watch(messages, scrollToBottom, { deep: true })

const botReplies = {
    'tư vấn máy tính': 'Bạn đang tìm laptop cho mục đích gì? Làm việc văn phòng, đồ họa hay gaming? Tôi sẽ gợi ý sản phẩm phù hợp nhất cho bạn! 💻',
    'tra cứu đơn hàng': 'Vui lòng cung cấp mã đơn hàng (VD: #VT-2026-001) để tôi kiểm tra trạng thái ngay cho bạn nhé! 📦',
    'khuyến mãi mới nhất': 'Hiện VinaTech đang có chương trình:\n🔥 Giảm 15% MacBook Pro M3\n🔥 Tặng tai nghe khi mua iPhone 17\n🔥 Trả góp 0% 12 tháng cho tất cả sản phẩm!',
}

const getReply = (text) => {
    const key = Object.keys(botReplies).find(k => text.toLowerCase().includes(k))
    return key ? botReplies[key] : 'Cảm ơn bạn đã liên hệ! Nhân viên tư vấn sẽ phản hồi trong thời gian sớm nhất. Bạn có thể để lại số điện thoại để được hỗ trợ nhanh hơn nhé! 😊'
}

const now = () => new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })

const sendMessage = async (text) => {
    const msg = (text || inputText.value).trim()
    if (!msg) return
    inputText.value = ''

    messages.value.push({ id: Date.now(), from: 'user', text: msg, time: now() })

    isTyping.value = true
    await new Promise(r => setTimeout(r, 1000 + Math.random() * 600))
    isTyping.value = false

    messages.value.push({
        id: Date.now() + 1,
        from: 'bot',
        text: getReply(msg),
        time: now(),
    })
}

const onKeydown = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault()
        sendMessage()
    }
}
</script>

<template>
    <!-- CHAT BUBBLE (khi đóng) -->
    <button v-if="!isOpen" class="chat-bubble" @click="isOpen = true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        <span class="bubble-dot"></span>
    </button>

    <!-- CHAT WINDOW -->
    <div v-else class="chat-window" :class="{ minimized: isMinimized }">

        <!-- HEADER -->
        <div class="chat-header">
            <div class="header-left">
                <div class="bot-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                        <rect x="3" y="11" width="18" height="10" rx="2"/>
                        <path d="M12 2v4M8 11V7a4 4 0 0 1 8 0v4"/>
                        <circle cx="9" cy="16" r="1" fill="currentColor"/>
                        <circle cx="15" cy="16" r="1" fill="currentColor"/>
                    </svg>
                </div>
                <div>
                    <b>NextGen Laptop</b>
                    <span class="status-row">
                        <span class="status-dot"></span>
                        ĐANG HOẠT ĐỘNG
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <button class="hdr-btn" @click="isMinimized = !isMinimized" :title="isMinimized ? 'Mở rộng' : 'Thu nhỏ'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                </button>
                <button class="hdr-btn close-btn" @click="isOpen = false" title="Đóng">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- BODY (ẩn khi minimized) -->
        <template v-if="!isMinimized">

            <!-- MESSAGES -->
            <div class="chat-body">
                <div v-for="msg in messages" :key="msg.id" class="msg-group" :class="msg.from">

                    <!-- BOT AVATAR nhỏ -->
                    <div v-if="msg.from === 'bot'" class="msg-bot-avatar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <rect x="3" y="11" width="18" height="10" rx="2"/>
                            <path d="M12 2v4M8 11V7a4 4 0 0 1 8 0v4"/>
                            <circle cx="9" cy="16" r="1" fill="currentColor"/>
                            <circle cx="15" cy="16" r="1" fill="currentColor"/>
                        </svg>
                    </div>

                    <div class="msg-content">
                        <div class="bubble" :class="msg.from">{{ msg.text }}</div>

                        <div class="msg-meta">
                            <span class="msg-time">{{ msg.time }}</span>
                            <span v-if="msg.from === 'bot'" class="msg-arrow">›</span>
                        </div>

                        <!-- QUICK REPLIES -->
                        <div v-if="msg.showQuick" class="quick-replies">
                            <button
                                v-for="q in quickReplies" :key="q"
                                class="quick-btn"
                                @click="sendMessage(q)"
                            >{{ q }}</button>
                        </div>
                    </div>

                </div>

                <!-- TYPING INDICATOR -->
                <div v-if="isTyping" class="msg-group bot">
                    <div class="msg-bot-avatar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <rect x="3" y="11" width="18" height="10" rx="2"/>
                            <path d="M12 2v4M8 11V7a4 4 0 0 1 8 0v4"/>
                        </svg>
                    </div>
                    <div class="msg-content">
                        <div class="bubble bot typing-bubble">
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                    </div>
                </div>

                <div ref="messagesEnd"></div>
            </div>

            <!-- INPUT -->
            <div class="chat-input-wrap">
                <div class="input-row">
                    <input
                        v-model="inputText"
                        @keydown="onKeydown"
                        placeholder="Nhập tin nhắn..."
                        :disabled="isTyping"
                    />
                    <button class="send-btn" @click="sendMessage()" :disabled="!inputText.trim() || isTyping">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </div>
                <p class="input-note">AI cung cấp thông tin để tham khảo</p>
            </div>

        </template>

    </div>
</template>

<style scoped>
/* BUBBLE BUTTON */
.chat-bubble {
    position: fixed; bottom: 28px; right: 28px;
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 8px 24px rgba(37,99,235,0.4); transition: transform 0.2s, box-shadow 0.2s;
    z-index: 999;
}
.chat-bubble svg { width: 24px; height: 24px; color: white; }
.chat-bubble:hover { transform: scale(1.08); box-shadow: 0 12px 32px rgba(37,99,235,0.5); }
.bubble-dot {
    position: absolute; top: 8px; right: 8px;
    width: 12px; height: 12px; border-radius: 50%;
    background: #22c55e; border: 2px solid white;
}

/* CHAT WINDOW */
.chat-window {
    position: fixed; bottom: 28px; right: 28px;
    width: 360px; border-radius: 20px;
    background: white; box-shadow: 0 20px 60px rgba(15,23,42,0.18);
    overflow: hidden; display: flex; flex-direction: column;
    z-index: 999; font-family: sans-serif;
    animation: popIn 0.25s cubic-bezier(.22,1,.36,1);
}
.chat-window.minimized { height: auto; }

@keyframes popIn {
    from { opacity: 0; transform: scale(0.92) translateY(16px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

/* HEADER */
.chat-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 16px; background: white;
    border-bottom: 1px solid #f1f5f9;
}
.header-left { display: flex; align-items: center; gap: 10px; }
.bot-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    color: white;
}
.bot-avatar svg { width: 20px; height: 20px; }
.header-left b { display: block; font-size: 14px; font-weight: 700; color: #0f172a; }
.status-row { display: flex; align-items: center; gap: 5px; font-size: 10px; font-weight: 700; color: #16a34a; letter-spacing: 0.06em; }
.status-dot { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; flex-shrink: 0; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }

.header-actions { display: flex; gap: 4px; }
.hdr-btn {
    width: 28px; height: 28px; border-radius: 7px; border: none;
    background: transparent; cursor: pointer; display: flex;
    align-items: center; justify-content: center; color: #94a3b8; transition: all 0.2s;
}
.hdr-btn svg { width: 14px; height: 14px; }
.hdr-btn:hover { background: #f1f5f9; color: #475569; }
.close-btn:hover { background: #fee2e2; color: #ef4444; }

/* BODY */
.chat-body {
    flex: 1; overflow-y: auto; padding: 16px;
    display: flex; flex-direction: column; gap: 14px;
    background: #f8fafc; min-height: 320px; max-height: 400px;
    scroll-behavior: smooth;
}
.chat-body::-webkit-scrollbar { width: 4px; }
.chat-body::-webkit-scrollbar-track { background: transparent; }
.chat-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }

/* MESSAGES */
.msg-group { display: flex; gap: 8px; align-items: flex-end; }
.msg-group.user { flex-direction: row-reverse; }

.msg-bot-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: white;
}
.msg-bot-avatar svg { width: 14px; height: 14px; }

.msg-content { display: flex; flex-direction: column; gap: 4px; max-width: 80%; }
.msg-group.user .msg-content { align-items: flex-end; }

.bubble {
    padding: 12px 15px; border-radius: 16px;
    font-size: 14px; line-height: 1.55; white-space: pre-wrap;
    word-break: break-word;
}
.bubble.bot {
    background: white; color: #1e293b;
    border-radius: 4px 16px 16px 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.bubble.user {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white; border-radius: 16px 16px 4px 16px;
}

.msg-meta { display: flex; align-items: center; gap: 6px; padding: 0 2px; }
.msg-time { font-size: 10px; color: #94a3b8; }
.msg-arrow { font-size: 14px; color: #cbd5e1; }

/* QUICK REPLIES */
.quick-replies { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 6px; }
.quick-btn {
    padding: 7px 13px; border-radius: 20px;
    border: 1.5px solid #2563eb; background: white;
    font-size: 12px; font-weight: 600; color: #2563eb;
    cursor: pointer; transition: all 0.2s; white-space: nowrap;
}
.quick-btn:hover { background: #2563eb; color: white; }

/* TYPING */
.typing-bubble {
    display: flex; gap: 5px; align-items: center;
    padding: 12px 16px; min-width: 56px;
}
.dot {
    width: 7px; height: 7px; border-radius: 50%; background: #94a3b8;
    animation: bounce 1.2s infinite;
}
.dot:nth-child(2) { animation-delay: 0.2s; }
.dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes bounce {
    0%,60%,100% { transform: translateY(0); }
    30% { transform: translateY(-6px); }
}

/* INPUT */
.chat-input-wrap {
    padding: 12px 14px 10px; background: white;
    border-top: 1px solid #f1f5f9;
}
.input-row { display: flex; gap: 8px; align-items: center; margin-bottom: 6px; }
.input-row input {
    flex: 1; padding: 10px 14px; border-radius: 24px;
    border: 1px solid #e2e8f0; font-size: 13px; color: #0f172a;
    outline: none; background: #f8fafc; transition: border-color 0.2s;
}
.input-row input:focus { border-color: #2563eb; background: white; }
.input-row input::placeholder { color: #94a3b8; }
.input-row input:disabled { opacity: 0.6; cursor: not-allowed; }

.send-btn {
    width: 38px; height: 38px; border-radius: 50%; border: none;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white; cursor: pointer; display: flex;
    align-items: center; justify-content: center; flex-shrink: 0;
    transition: opacity 0.2s, transform 0.2s;
}
.send-btn svg { width: 15px; height: 15px; }
.send-btn:hover:not(:disabled) { opacity: 0.9; transform: scale(1.05); }
.send-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

.input-note { font-size: 10px; color: #94a3b8; text-align: center; margin: 0; }

/* MOBILE */
@media (max-width: 420px) {
    .chat-window { right: 12px; bottom: 12px; width: calc(100vw - 24px); }
    .chat-bubble { right: 16px; bottom: 16px; }
}
</style>