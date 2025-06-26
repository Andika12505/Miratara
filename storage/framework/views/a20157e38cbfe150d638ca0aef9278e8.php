<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Service - MiraTara</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet"> 
    <style>
        body { background-color: #f0f2f5; font-family: 'Arial', sans-serif; }
        .chat-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 600px; /* Tinggi minimal chatbot */
        }
        .chat-header {
            background-color: #ffc0cb; /* MiraTara Pink */
            color: white;
            padding: 15px 20px;
            font-size: 1.2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .chat-header .back-button {
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .chat-header .back-button:hover {
            transform: translateX(-3px);
        }
        .chat-body {
            padding: 20px;
            flex-grow: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-end; /* Pesan baru muncul dari bawah */
        }
        .message-bubble {
            background-color: #e9ecef;
            border-radius: 10px;
            padding: 10px 15px;
            margin-bottom: 10px;
            max-width: 80%;
            align-self: flex-start; /* Default untuk pesan bot */
        }
        .message-bubble.user {
            background-color: #d1e7dd; /* Light green for user messages */
            align-self: flex-end; /* Untuk pesan user */
        }
        .message-bubble.bot {
            background-color: #e9ecef; /* Light gray for bot messages */
            align-self: flex-start;
        }
        .options-container {
            margin-top: 15px;
        }
        .option-button {
            display: block;
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 8px;
            border: 1px solid #ffc0cb;
            border-radius: 8px;
            background-color: #fff;
            color: #ffc0cb;
            text-align: left;
            transition: all 0.2s ease;
            cursor: pointer;
            font-weight: 500;
        }
        .option-button:hover {
            background-color: #ffc0cb;
            color: white;
            transform: translateY(-2px);
        }
        .chat-input-area {
            border-top: 1px solid #e9ecef;
            padding: 15px 20px;
            display: flex;
            gap: 10px;
            background-color: #f8f9fa;
        }
        .chat-input-area input {
            flex-grow: 1;
            border-radius: 20px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
        }
        .chat-input-area button {
            border-radius: 20px;
            padding: 10px 15px;
            background-color: #ffc0cb;
            border-color: #ffc0cb;
            color: white;
        }
        .chat-input-area button:hover {
            background-color: #ff8fab;
            border-color: #ff8fab;
        }
        .live-chat-form {
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        .live-chat-messages {
            border: 1px solid #eee;
            min-height: 150px;
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .live-chat-messages .chat-message {
            margin-bottom: 5px;
            padding: 5px;
            border-radius: 5px;
        }
        .live-chat-messages .chat-message.user {
            text-align: right;
            background-color: #d1e7dd;
        }
        .live-chat-messages .chat-message.agent {
            text-align: left;
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

    <div class="chat-container">
        <div class="chat-header">
            <i class="fas fa-arrow-left back-button" id="backButton" style="display: none;"></i>
            <span id="chatTitle">Customer Service MiraTara</span>
        </div>
        <div class="chat-body" id="chatBody">
            <!-- Messages and options will be rendered here by JavaScript -->
        </div>

        <!-- Input area for chat messages or initial question -->
        <div class="chat-input-area" id="initialInput" style="display: flex;">
            <input type="text" id="initialQuestionInput" placeholder="Tulis pertanyaan Anda atau pilih opsi..." />
            <button id="sendInitialQuestion"><i class="fas fa-paper-plane"></i></button>
        </div>

        <!-- Live Chat Form (Hidden by default) -->
        <div class="live-chat-form" id="liveChatForm" style="display: none;">
            <div id="liveChatMessages" class="live-chat-messages">
                <!-- Live chat messages will appear here -->
            </div>
            <div class="input-group">
                <input type="text" id="liveChatMessageInput" class="form-control" placeholder="Ketik pesan Anda..." />
                <button class="btn btn-primary" id="sendLiveChatMessage"><i class="fas fa-paper-plane"></i> Kirim</button>
            </div>
        </div>

        <!-- Submit Ticket Form (Hidden by default) -->
        <div class="live-chat-form" id="ticketForm" style="display: none;">
            <p>Mohon isi formulir di bawah untuk mengajukan tiket dukungan. Kami akan merespons melalui email Anda.</p>
            <form id="submitTicketForm">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="ticketSubject" class="form-label">Subjek</label>
                    <input type="text" class="form-control" id="ticketSubject" name="subject" required />
                </div>
                <div class="mb-3">
                    <label for="ticketDescription" class="form-label">Deskripsi Lengkap Masalah</label>
                    <textarea class="form-control" id="ticketDescription" name="description" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-ticket-alt"></i> Ajukan Tiket</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables for JavaScript
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        const GET_ARTICLES_URL = "<?php echo e(route('cs.get_articles')); ?>";
        const START_LIVE_CHAT_URL = "<?php echo e(route('cs.start_live_chat')); ?>";
        const SUBMIT_TICKET_URL = "<?php echo e(route('cs.submit_ticket')); ?>";
        const CURRENT_USER_ID = <?php echo e(Auth::check() ? Auth::id() : 'null'); ?>;
        const IS_USER_LOGGED_IN = <?php echo e(Auth::check() ? 'true' : 'false'); ?>;
    </script>
    <script src="<?php echo e(asset('js/customer/cs_chatbot.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\Miratara\resources\views/customer/cs/chatbot.blade.php ENDPATH**/ ?>