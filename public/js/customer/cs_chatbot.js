// JavaScript untuk Chatbot Customer Service
// File: public/js/customer/cs_chatbot.js

document.addEventListener("DOMContentLoaded", function () {
    const chatBody = document.getElementById('chatBody');
    const backButton = document.getElementById('backButton');
    const chatTitle = document.getElementById('chatTitle');
    const initialInputArea = document.getElementById('initialInput');
    const initialQuestionInput = document.getElementById('initialQuestionInput');
    const sendInitialQuestionButton = document.getElementById('sendInitialQuestion');

    const liveChatForm = document.getElementById('liveChatForm');
    const ticketForm = document.getElementById('ticketForm');
    const liveChatMessagesContainer = document.getElementById('liveChatMessages');
    const liveChatMessageInput = document.getElementById('liveChatMessageInput');
    const sendLiveChatMessageButton = document.getElementById('sendLiveChatMessage');
    const submitTicketForm = document.getElementById('submitTicketForm');

    let currentParentId = null;
    let chatHistory = []; // Untuk melacak riwayat navigasi (parent_id)

    function addBotMessage(text) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message-bubble', 'bot');
        messageElement.innerHTML = text; // Gunakan innerHTML untuk mendukung HTML dasar jika ada
        chatBody.appendChild(messageElement);
        chatBody.scrollTop = chatBody.scrollHeight; // Scroll ke bawah
    }

    function addUserMessage(text) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message-bubble', 'user');
        messageElement.textContent = text;
        chatBody.appendChild(messageElement);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function renderOptions(articles) {
        // Hapus opsi lama dan input area (jika ada)
        let existingOptions = chatBody.querySelector('.options-container');
        if (existingOptions) {
            existingOptions.remove();
        }
        initialInputArea.style.display = 'none'; // Sembunyikan input teks standar

        const optionsContainer = document.createElement('div');
        optionsContainer.classList.add('options-container');

        articles.forEach(article => {
            const button = document.createElement('button');
            button.classList.add('option-button');
            button.textContent = article.question;
            button.dataset.id = article.id;
            button.dataset.answer = article.answer || ''; // Simpan jawaban di dataset
            button.dataset.hasChildren = article.has_children;
            button.dataset.isChatOption = article.is_chat_option;

            button.addEventListener('click', function () {
                addUserMessage(article.question); // Tampilkan pilihan user sebagai pesan
                handleArticleSelection(article);
            });
            optionsContainer.appendChild(button);
        });
        chatBody.appendChild(optionsContainer);
        chatBody.scrollTop = chatBody.scrollHeight; // Scroll ke bawah
    }

    async function fetchArticles(parentId = null) {
        addBotMessage('Mohon tunggu...');
        try {
            const response = await fetch(GET_ARTICLES_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ parent_id: parentId })
            });
            const result = await response.json();
            if (result.success) {
                // Hapus pesan "Mohon tunggu..."
                const lastBotMessage = chatBody.lastChild;
                if (lastBotMessage && lastBotMessage.textContent === 'Mohon tunggu...') {
                    lastBotMessage.remove();
                }
                renderOptions(result.articles);
                if (parentId !== null) {
                    backButton.style.display = 'block'; // Tampilkan tombol kembali jika bukan root
                } else {
                    backButton.style.display = 'none';
                }
            } else {
                addBotMessage('Maaf, terjadi kesalahan saat memuat pilihan. Silakan coba lagi.');
            }
        } catch (error) {
            console.error('Error fetching articles:', error);
            addBotMessage('Maaf, terjadi kesalahan jaringan. Silakan coba lagi.');
        }
    }

    function handleArticleSelection(article) {
        // Jika artikel memiliki jawaban, tampilkan jawaban
        if (article.answer) {
            addBotMessage(article.answer);
            // Jika ini opsi "Chat dengan Admin", aktifkan form live chat
            if (article.is_chat_option) {
                if (IS_USER_LOGGED_IN) {
                    // Panggil API untuk memulai live chat session
                    startLiveChatSession(article.question);
                } else {
                    addBotMessage('Untuk menggunakan fitur chat langsung, Anda perlu masuk terlebih dahulu. Silakan <a href="/masuk">Masuk</a> atau <a href="/daftar">Daftar</a>.');
                    // Opsi: Sembunyikan form live chat
                    liveChatForm.style.display = 'none';
                    ticketForm.style.display = 'none';
                }
            } else {
                // Jika ini jawaban akhir, tampilkan opsi untuk kembali atau ajukan tiket
                showFinalOptions();
            }
        } else if (article.has_children) {
            // Jika tidak ada jawaban tapi ada anak, berarti ini node percabangan
            chatHistory.push(currentParentId); // Simpan parent_id saat ini untuk navigasi kembali
            currentParentId = article.id;
            fetchArticles(currentParentId);
        } else if (article.is_chat_option) { // Jika hanya merupakan opsi chat (tidak ada jawaban langsung)
             if (IS_USER_LOGGED_IN) {
                startLiveChatSession(article.question);
            } else {
                addBotMessage('Untuk menggunakan fitur chat langsung, Anda perlu masuk terlebih dahulu. Silakan <a href="/masuk">Masuk</a> atau <a href="/daftar">Daftar</a>.');
                liveChatForm.style.display = 'none';
                ticketForm.style.display = 'none';
            }
        } else {
            // Fallback jika tidak ada jawaban dan tidak ada anak (tidak seharusnya terjadi)
            addBotMessage('Maaf, tidak ada informasi lebih lanjut untuk pilihan ini.');
        }
    }

    function showFinalOptions() {
        // Setelah jawaban akhir, berikan opsi untuk kembali ke awal atau ajukan tiket
        const optionsContainer = document.createElement('div');
        optionsContainer.classList.add('options-container');

        const backToStartButton = document.createElement('button');
        backToStartButton.classList.add('option-button');
        backToStartButton.textContent = 'Kembali ke Pilihan Utama';
        backToStartButton.addEventListener('click', () => {
            currentParentId = null;
            chatHistory = []; // Reset riwayat
            chatBody.innerHTML = ''; // Kosongkan chat
            addBotMessage('Selamat datang! Apa yang bisa saya bantu?');
            fetchArticles();
        });
        optionsContainer.appendChild(backToStartButton);

        const submitTicketButton = document.createElement('button');
        submitTicketButton.classList.add('option-button');
        submitTicketButton.textContent = 'Ajukan Tiket (jika masalah belum terselesaikan)';
        submitTicketButton.addEventListener('click', () => {
            showTicketForm();
        });
        optionsContainer.appendChild(submitTicketButton);

        chatBody.appendChild(optionsContainer);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function showTicketForm() {
        liveChatForm.style.display = 'none'; // Sembunyikan live chat jika aktif
        ticketForm.style.display = 'block'; // Tampilkan form tiket
        initialInputArea.style.display = 'none'; // Sembunyikan input umum
        backButton.style.display = 'block'; // Pastikan tombol kembali terlihat
        addBotMessage('Anda bisa mengisi formulir tiket di bawah ini.');
    }

    async function startLiveChatSession(initialQuestion) {
        addBotMessage('Memulai sesi chat langsung...');
        try {
            const response = await fetch(START_LIVE_CHAT_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ initial_question: initialQuestion })
            });
            const result = await response.json();
            if (result.success) {
                addBotMessage('Anda telah terhubung dengan agen. Silakan mulai chat Anda.');
                // Simpan session_id jika perlu untuk real-time chat (akan diimplementasikan nanti)
                // currentLiveChatSessionId = result.session_id; 
                liveChatForm.style.display = 'block'; // Tampilkan form chat
                ticketForm.style.display = 'none'; // Sembunyikan form tiket
                initialInputArea.style.display = 'none'; // Sembunyikan input umum
                liveChatMessagesContainer.innerHTML = ''; // Bersihkan pesan chat lama
                liveChatMessageInput.focus(); // Fokus ke input chat
                backButton.style.display = 'block'; // Pastikan tombol kembali terlihat
                // Di sini Anda akan menginisialisasi koneksi WebSocket (Pusher/Echo)
            } else {
                addBotMessage('Maaf, gagal memulai sesi chat langsung: ' + (result.message || 'Error tidak diketahui.'));
            }
        } catch (error) {
            console.error('Error starting live chat:', error);
            addBotMessage('Terjadi kesalahan jaringan saat memulai chat langsung.');
        }
    }

    // --- Event Listeners ---
    backButton.addEventListener('click', function () {
        liveChatForm.style.display = 'none'; // Sembunyikan live chat form
        ticketForm.style.display = 'none'; // Sembunyikan ticket form

        if (chatHistory.length > 0) {
            currentParentId = chatHistory.pop();
            chatBody.innerHTML = ''; // Kosongkan chat
            fetchArticles(currentParentId);
        } else {
            // Kembali ke root
            currentParentId = null;
            chatBody.innerHTML = '';
            addBotMessage('Selamat datang! Apa yang bisa saya bantu?');
            fetchArticles();
        }
    });

    sendInitialQuestionButton.addEventListener('click', function() {
        const question = initialQuestionInput.value.trim();
        if (question) {
            addUserMessage(question);
            initialQuestionInput.value = '';
            // Anda bisa mengimplementasikan pencarian fuzzy atau LLM di sini
            // Untuk saat ini, kita akan selalu kembali ke pilihan utama jika user mengetik
            addBotMessage('Maaf, saya belum bisa memproses pertanyaan teks secara langsung. Mohon pilih dari opsi yang tersedia.');
            currentParentId = null; // Kembali ke root
            chatHistory = [];
            fetchArticles();
        }
    });

    initialQuestionInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendInitialQuestionButton.click();
        }
    });

    submitTicketForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const subject = document.getElementById('ticketSubject').value.trim();
        const description = document.getElementById('ticketDescription').value.trim();

        if (!subject || !description) {
            alert('Subjek dan Deskripsi harus diisi.'); // Gunakan showAlert jika ada styling
            return;
        }

        if (!IS_USER_LOGGED_IN) {
            addBotMessage('Untuk mengajukan tiket, Anda perlu masuk terlebih dahulu. Silakan <a href="/masuk">Masuk</a> atau <a href="/daftar">Daftar</a>.');
            return;
        }
        
        try {
            const response = await fetch(SUBMIT_TICKET_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ subject: subject, description: description })
            });
            const result = await response.json();
            if (result.success) {
                addBotMessage('Tiket Anda berhasil diajukan! Kami akan segera menghubungi Anda.');
                submitTicketForm.reset(); // Reset form
                ticketForm.style.display = 'none'; // Sembunyikan form
                initialInputArea.style.display = 'flex'; // Tampilkan kembali input awal
                currentParentId = null; // Kembali ke root
                chatHistory = [];
                fetchArticles(); // Muat ulang pilihan utama
            } else {
                addBotMessage('Maaf, gagal mengajukan tiket: ' + (result.message || 'Error tidak diketahui.'));
            }
        } catch (error) {
            console.error('Error submitting ticket:', error);
            addBotMessage('Terjadi kesalahan jaringan saat mengajukan tiket.');
        }
    });

    sendLiveChatMessageButton.addEventListener('click', function() {
        const message = liveChatMessageInput.value.trim();
        if (message) {
            addUserMessage(message); // Tampilkan pesan user di UI
            liveChatMessageInput.value = '';
            // Di sini Anda akan mengirim pesan ke server (WebSocket)
            // Contoh: Echo.channel('chat-session-' + currentLiveChatSessionId).whisper('message', { message: message });
            // Untuk saat ini, hanya placeholder
            addBotMessage('Pesan Anda telah terkirim. Mohon menunggu balasan dari agen.');
        }
    });
    
    liveChatMessageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendLiveChatMessageButton.click();
        }
    });


    // Initial load of root articles
    addBotMessage('Selamat datang! Apa yang bisa saya bantu?');
    fetchArticles();
});
