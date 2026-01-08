


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div x-data="chat" class="..." x-cloak>
    
        <!-- CHAT TOGGLE BUTTON -->
        <button 
            @click="isOpen = !isOpen"
            class="fixed bottom-4 right-4 bg-blue-600 text-white p-4 rounded-full shadow-lg z-50 hover:bg-blue-700 transition"
            title="Chat with us"
        >
            💬
        </button>
    
        <!-- CHAT PANEL -->
        <div x-show="isOpen" class="fixed bottom-20 right-4 w-96 h-[500px] bg-white rounded-lg shadow-xl border border-gray-300 flex flex-col z-50">
            <div class="bg-gray-800 flex justify-center p-4">
              <span class="text-white text-bold">FakeGPT</span>
            </div>
          
            <div class="w-full max-w-screen-lg flex-1 m-auto p-8 my-4 pb-20">
              <div class="flex flex-col">
          
                <template x-for="message in messages">
                  <div class="message rounded-lg py-2 px-6 mb-4" :class="message.role === 'assistant' ? 'assistant bg-blue-100 border-blue-100 self-start' : 'user bg-green-200 border-green-200 self-end'">
                    <span x-text="message.body"></span>
                    <template x-if="message.beingTyped">
                      <span class="w-2.5 bg-gray-600 h-4 inline-block -mb-0.5 align-baseline blink"></span>
                    </template>
                  </div>
                </template>
          
                <template x-if="showTyping">
                  <div class="message assistant rounded-lg py-2.5 px-6 mb-4 bg-blue-100 border-blue-100 self-start">
                    <div class="type-indicator">
                      <span>.</span><span>.</span><span>.</span>
                    </div>
                  </div>
                </template>
          
              </div>
          
            </div>
            <div class="fixed inset-x-0 bottom-0 bg-gray-200">
              <form class="max-w-screen-lg m-auto w-full p-4 flex space-x-4 justify-center items-center" @submit.prevent="sendMessage">
                <input id="message" type="text" autocomplete="off" class="border rounded-md p-2 flex-1 border-gray-300" x-model="newMessage" placeholder="Your message..." />
                <button class="bg-gray-800 text-white px-4 py-2 rounded-md" :class="{'opacity-50' : waitingOnResponse}">Send</button>
              </form>
            </div>
        </div>
    </div>

<!---Chat Board End-->

    {{-- <div id="chat-box"></div>
    <p id="typing">Typing...</p>

    <input type="text" id="user-input" placeholder="Type a message..." autocomplete="off">
    <button onclick="sendMessage()">Send</button>

    <script>
        function sendMessage() {
            let userInput = $("#user-input").val();
            if (!userInput) return;

            $("#chat-box").append("<p><b>You:</b> " + userInput + "</p>");
            $("#user-input").val("");
            $("#typing").show(); // Show typing indicator

            $.ajax({
                url: "{{ route('chatbot.response') }}",
                type: "POST",
                data: { message: userInput, _token: "{{ csrf_token() }}" },
                success: function(response) {
                    $("#typing").hide(); // Hide typing indicator
                    $("#chat-box").append("<p><b>Bot:</b> " + response.message + "</p>");
                }
            });
        }
    </script> --}}
    <script src="{{asset('js/alpine.js')}}" defer></script>

<script>
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
                this.newMessage = "";
                this.waitingOnResponse = true;
                this.showTyping = true;

                fetch(@json(route('chatbot.translate')), {
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
                    this.addBotMessage(data.message);
                })
                .catch(() => {
                    this.showTyping = false;
                    this.waitingOnResponse = false;
                    this.addBotMessage("⚠️ Sorry, something went wrong.");
                });
            },
            addBotMessage(message) {
                this.messages.push({ role: "assistant", body: message });
            }
        }));
    });
</script>

  
</body>
</html>
