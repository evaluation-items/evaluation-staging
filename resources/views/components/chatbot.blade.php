<div x-data="chat" x-cloak>
    <!-- CHAT TOGGLE BUTTON -->
    <button 
        @click="isOpen = !isOpen"
        class="fixed bottom-4 right-4 bg-blue-600 text-white p-4 rounded-full shadow-lg z-50 hover:bg-blue-700 transition"
        title="Chat with us"
    >
        💬
    </button>

    <!-- CHAT BOX PANEL -->
    <div x-show="isOpen" class="fixed bottom-20 right-4 w-96 h-[500px] bg-white rounded-lg shadow-xl border border-gray-300 flex flex-col z-50">
      

        {{-- Header --}}
            <div class="bg-gray-800 flex justify-center p-4">
                Evaluation Search
            </div>

            <div class="w-full max-w-screen-lg m-auto p-4 overflow-y-auto">
                <div class="flex-1 overflow-y-auto p-4 space-y-2 text-sm" x-ref="chatContainer">
                        <template x-for="message in messages" :key="message.body">
                            <div class="message rounded-lg py-2 px-6"
                                :class="message.role === 'assistant' ? 'bg-blue-100 self-start' : 'bg-green-200 self-end'">
                                <span x-text="message.body" class="text-black"></span>
                            </div>
                        </template>

                        <template x-if="showTyping">
                            <div class="message assistant rounded-lg py-2.5 px-6 mb-4 bg-blue-100 self-start text-black">
                                <div class="type-indicator">
                                    <span>.</span><span>.</span><span>.</span>
                                </div>
                            </div>
                        </template>
                        <div x-show="chartData" class="mt-4 w-full max-w-xl">
                            <canvas id="myChart" style="max-height: 300px; width: 100%;"></canvas>
                        
                            <template x-if="chartData">
                                <button 
                                    @click="downloadChartImage()" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2">
                                    Download Chart
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                {{-- <div class="flex-1 overflow-y-auto p-4 space-y-2" x-ref="chatContainer">
                    
                    <template x-for="message in messages">
                        <div class="message rounded-lg py-2 px-6 mb-4" :class="message.role === 'assistant' ? 'assistant bg-blue-100 border-blue-100 self-start' : 'user bg-green-200 border-green-200 self-end'">
                          <span class="text-black" x-text="message.body"></span>
                          <template x-if="message.beingTyped">
                            <span class="w-2.5 bg-gray-600 h-4 inline-block -mb-0.5 align-baseline blink"></span>
                          </template>
                        </div>
                      </template>
                  
                    <template x-if="showTyping">
                        <div class="message assistant rounded-lg py-2.5 px-6 mb-4 bg-blue-100 self-start text-black">
                            <div class="type-indicator">
                                <span>.</span><span>.</span><span>.</span>
                            </div>
                        </div>
                    </template>
                    <div x-show="chartData" class="mt-4 w-full max-w-xl">
                        <canvas id="myChart" style="max-height: 300px; width: 100%;"></canvas>
                    
                        <template x-if="chartData">
                            <button 
                                @click="downloadChartImage()" 
                                class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2">
                                Download Chart
                            </button>
                        </template>
                    </div>
                </div> --}}
         

            {{-- Input Area --}}
            <div class="bg-gray-100 p-4">
                <form @submit.prevent="sendMessage" class="flex space-x-2">
                    <textarea 
                        x-model="newMessage" 
                        rows="2"
                        class="flex-1 p-2 border border-gray-400 rounded text-black bg-white placeholder-gray-500"
                        placeholder="Type your message... (Shift+Enter for new line)"
                        @keydown.enter.prevent="if (!$event.shiftKey) { sendMessage(); } else { newMessage += '\n' }"
                    ></textarea>
                
                    <button 
                        class="bg-gray-800 text-white px-4 py-2 rounded-md" 
                        :class="{'opacity-50': waitingOnResponse}"> Send
                    </button>
                </form>
                
            </div>
    </div>
</div>


{{-- <script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("chat", () => ({
            isOpen: false,
            newMessage: "",
            showTyping: false,
            waitingOnResponse: true,
            messages: [],
            init() {
                this.mockResponse("Hello there. How can i help you?");
                this.waitingOnResponse = false;
            },
            sendMessage() {
                if (this.waitingOnResponse) return;
                this.waitingOnResponse = true;
                this.messages.push({ role: "user", body: this.newMessage });
                this.newMessage = "";
                this.mockResponse();
            },
            typeOutResponse(message) {
                this.messages.push({ role: "assistant", body: "", beingTyped: true });
                let responseMessage = this.messages[this.messages.length - 1];
                let i = 0;
                let interval = setInterval(() => {
                    responseMessage.body += message.charAt(i);
                    i++;
                    if (i >= message.length) {
                        this.waitingOnResponse = false;
                        delete responseMessage.beingTyped;
                        clearInterval(interval);
                    }
                }, 30);
            },
            mockResponse(message) {
                setTimeout(() => {
                    this.showTyping = true;
                }, 1500);
                setTimeout(() => {
                    this.showTyping = false;
                    let responseMessage = message ?? "Thanks for sending me: " + this.messages[this.messages.length - 1].body;
                    this.typeOutResponse(responseMessage);
                }, 3000);
            }
        }));
    });
</script> --}}
<script>
    var chartData = null;
    document.addEventListener("alpine:init", () => {
        Alpine.data("chat", () => ({
            isOpen: false,
            newMessage: "",
            showTyping: false,
            waitingOnResponse: false,
            messages: [],
            init() {
                this.addBotMessage("Hello there. I am FakeGPT. Ask me anything!");
            },
            sendMessage() {
                if (!this.newMessage || this.waitingOnResponse) return;

                const userMessage = this.newMessage;
                this.messages.push({ role: "user", body: userMessage });
                this.scrollToBottom(); // <--- ADD THIS
                this.newMessage = "";
                this.waitingOnResponse = true;
                this.showTyping = true;
                this.chartData = null;

                fetch("{{ route('chatbot.translate') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: userMessage })
                })
                .then(res => res.json())
                .then(data => {
                    this.showTyping = false;
                    this.waitingOnResponse = false;
                    this.addBotMessage(data.translated);

                    if (data.chart) {
                        this.chartData = data.chart;
                        this.$nextTick(() => {
                            this.renderChart();
                            this.scrollToBottom(); // Optional, scroll after chart too
                        });
                    }
                });
            },
            renderChart() {
                const canvas = document.getElementById("myChart");
                if (!canvas) return;

                const ctx = canvas.getContext("2d");

                // Destroy previous chart instance if exists
                if (window.myChart instanceof Chart) {
                    window.myChart.destroy();
                }

                // Create new chart
                window.myChart = new Chart(ctx, {
                    type: this.chartData.type || 'bar',
                    data: {
                        labels: this.chartData.labels || [],
                        datasets: [{
                            label: 'Values',
                            data: this.chartData.data || [],
                            backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#E91E63'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.chatContainer;
                    container.scrollTop = container.scrollHeight;
                });
            },
            addBotMessage(message) {
                this.messages.push({ role: "assistant", body: message });
                this.scrollToBottom();
            }
        }));
    });
    function downloadChartImage() {
    if (window.myChart) {
        const image = window.myChart.toBase64Image();
        const link = document.createElement('a');
        link.href = image;
        link.download = 'chart.png';
        link.click();
    }
}

</script>


