<!-- AI Chatbot Component (Ollama-powered) -->
<div x-data="chatbot()" x-init="init()">
    <!-- Floating Chat Button -->
    <button 
        x-show="!isOpen"
        @click="isOpen = true"
        class="fixed bottom-6 right-6 z-40 bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-full shadow-2xl hover:shadow-blue-500/50 hover:scale-110 transition-all duration-300 group"
    >
        <i data-lucide="message-circle" class="w-6 h-6"></i>
        <span class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white animate-pulse"></span>
        
        <!-- Tooltip -->
       <div class="absolute bottom-20 right-0 mb-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
        AI Chatbot is ready to help you!
    </div>
    </button>

    <!-- Chat Panel -->
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-50">
        <!-- Backdrop -->
        <div
            class="absolute inset-0 bg-black/40"
            x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="isOpen = false"
        ></div>

        <!-- Chat Panel -->
        <div
            class="fixed right-0 top-0 bottom-0 w-full sm:w-[400px] bg-white shadow-2xl flex flex-col"
            x-transition:enter="transform transition ease-out duration-200"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
        >
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <img src="{{ asset('images/ollama.png') }}" alt="SK Logo" class="w-8 h-7">
                        </div>
                        <div>
                            <h3 class="font-semibold">Ken</h3>
                            <p class="text-xs text-blue-100">Powered by Ollama</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button
                            @click="clearHistory()"
                            class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors"
                            title="Clear History"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        <button
                            @click="isOpen = false"
                            class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors"
                        >
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" x-ref="messagesContainer">
                    <template x-for="message in messages" :key="message.id">
                        <div :class="message.type === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div 
                                :class="message.type === 'user' 
                                    ? 'bg-blue-600 text-white' 
                                    : 'bg-white text-gray-800 shadow-sm border border-gray-100'"
                                class="max-w-[85%] rounded-2xl px-4 py-3"
                            >
                                <!-- AI Header -->
                                <template x-if="message.type === 'ai'">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <i data-lucide="sparkles" class="w-4 h-4 text-blue-600"></i>
                                        <span class="text-xs text-gray-500">Ken</span>
                                    </div>
                                </template>
                                
                                <!-- Message Content -->
                                <div class="whitespace-pre-wrap" x-html="message.content"></div>
                                
                                <!-- Timestamp -->
                                <div 
                                    :class="message.type === 'user' ? 'text-blue-100' : 'text-gray-400'"
                                    class="text-xs mt-2"
                                    x-text="message.time"
                                ></div>
                            </div>
                        </div>
                    </template>

                    <!-- Loading Indicator -->
                    <template x-if="isLoading">
                        <div class="flex justify-start">
                            <div class="bg-white rounded-2xl px-4 py-3 shadow-sm border border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <i data-lucide="loader-2" class="w-4 h-4 text-blue-600 animate-spin"></i>
                                    <span class="text-sm text-gray-600">
                                        Ken is thinking
                                        <span class="chatbot-typing-dots" aria-hidden="true">
                                            <span>.</span><span>.</span><span>.</span>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-white border-t border-gray-200">
                    <form @submit.prevent="sendMessage()">
                        <div class="flex items-center space-x-2">
                            <input
                                type="text"
                                x-model="input"
                                placeholder="Ask anything about members, events..."
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                :disabled="isLoading"
                            />
                            <button
                                type="submit"
                                :disabled="!input.trim() || isLoading"
                                class="flex-shrink-0 inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                aria-label="Send message"
                            >
                                <i data-lucide="send" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Quick Suggestions -->
                    <div class="mt-3 flex flex-wrap gap-2">
                        <template x-for="suggestion in quickSuggestions">
                            <button
                                @click="input = suggestion"
                                x-text="suggestion"
                                :disabled="isLoading"
                                class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition-colors"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chatbot-typing-dots span {
        display: inline-block;
        opacity: 0.2;
        animation: chatbotDots 1.2s infinite;
    }

    .chatbot-typing-dots span:nth-child(2) { animation-delay: 0.2s; }
    .chatbot-typing-dots span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes chatbotDots {
        0%, 20% { opacity: 0.2; }
        50% { opacity: 1; }
        100% { opacity: 0.2; }
    }
</style>
@endpush

@push('scripts')
<script>
function chatbot() {
    return {
        isOpen: false,
        messages: [],
        input: '',
        isLoading: false,
        quickSuggestions: ['How many members?', 'Most active purok?', 'Upcoming events?'],
        
        init() {
            this.messages = [{
                id: 1,
                type: 'ai',
                content: "Hi! I'm Ken, your AI assistant powered by Ollama. Ask me anything about your members, events, attendance, or analytics! 🎯",
                time: this.getCurrentTime()
            }];

            // Ensure icons render for initial content
            this.refreshIcons();

            // Re-render icons when opening/closing (modal content is dynamic)
            this.$watch('isOpen', (value) => {
                if (value) {
                    this.refreshIcons();
                }
            });
        },

        refreshIcons() {
            // Lucide replaces <i data-lucide="..."> with SVG.
            // Because parts of the chat render dynamically (x-show / x-for / x-if),
            // we need to refresh icons after DOM updates.
            this.$nextTick(() => {
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            });
        },
        
        async sendMessage() {
            if (!this.input.trim()) return;
            
            const userMessage = {
                id: Date.now(),
                type: 'user',
                content: this.input,
                time: this.getCurrentTime()
            };
            
            this.messages.push(userMessage);
            const query = this.input;
            this.input = '';
            this.isLoading = true;

            this.refreshIcons();
            
            this.scrollToBottom();
            
            try {
                // Call Laravel API endpoint
                const response = await fetch('/api/chatbot/ask', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ query: query })
                });
                
                const data = await response.json();
                
                setTimeout(() => {
                    this.messages.push({
                        id: Date.now(),
                        type: 'ai',
                        content: data.response,
                        time: this.getCurrentTime()
                    });
                    
                    this.isLoading = false;
                    this.refreshIcons();
                    this.scrollToBottom();
                }, 1000);
                
            } catch (error) {
                console.error('Chatbot error:', error);
                this.messages.push({
                    id: Date.now(),
                    type: 'ai',
                    content: 'Sorry, I encountered an error. Please try again.',
                    time: this.getCurrentTime()
                });
                this.isLoading = false;
                this.refreshIcons();
            }
        },
        
        clearHistory() {
            if (confirm('Are you sure you want to clear the chat history?')) {
                this.init();
            }
        },
        
        getCurrentTime() {
            return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        },
        
        scrollToBottom() {
            setTimeout(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 100);
        }
    }
}
</script>
@endpush
