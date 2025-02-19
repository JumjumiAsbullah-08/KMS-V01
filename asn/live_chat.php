<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/turn/turn.min.js"></script>

<style>
    .chat-box {
        /* max-height: 400px; */
        overflow-y: auto;
    }

    .chat-message {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
        max-width: 75%;
        word-wrap: break-word;
    }

    .chat-left {
        align-self: flex-start;
        background-color: #FFCDB2;
        color: #000;
        border-radius: 0 30px 30px 30px;
        padding: 10px;
    }

    .chat-right {
        align-self: flex-end;
        background-color: #FFCFCF;
        color: #000;
        border-radius: 30px 0 30px 30px;
        padding: 10px;
        margin-left: 100px;
    }

    .chat-meta {
        font-size: 12px;
        margin-top: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-meta span {
        margin-right: 5px;
    }

    .read-status {
        font-size: 14px;
        color: #007bff;
    }
    .suggestion-item {
    padding: 8px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:hover {
        background: #f0f0f0;
    }
    .chat-notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 50%;
            padding: 0.3em 0.5em;
            font-size: 0.75rem;
            min-width: 20px;
            text-align: center;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
            line-height: 1;
        }
        #context-menu {
            display: none;
            position: absolute;
            z-index: 10000;
            background: #fff;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            font-family: sans-serif;
        }
        #context-menu ul {
            list-style: none;
            margin: 0;
            padding: 5px 0;
        }
        #context-menu ul li {
            padding: 5px 15px;
            cursor: pointer;
            white-space: nowrap;
        }
        #context-menu ul li:hover {
            background-color: #f0f0f0;
        }
</style>

<!-- Live Chat Toggle -->
<div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2" onclick="toggleChat()">
        <i class="material-icons py-2">chat</i>
        <span id="chat-notif" class="chat-notif-badge bg-danger position-absolute" style="top: 5px; right: 5px; display: none;"></span>
    </a>

    <div class="card shadow-lg" id="chat-container">
        <div class="card-header pb-0 pt-3">
            <div class="float-start">
                <h5 class="mt-3 mb-0">Live Chat</h5>
            </div>
            <div class="float-end mt-4">
                <button class="btn btn-link text-dark p-0 fixed-plugin-close-button" onclick="toggleChat()">
                    <i class="material-icons">clear</i>
                </button>
            </div>
        </div>
        <audio id="chat-audio">
                <source src="../assets/audio/chat.mp3" type="audio/mpeg">
            </audio>
        
        <hr class="horizontal dark my-1">
        <div class="card-body chat-box p-3" id="chat-box">
            <!-- Pesan chat akan muncul di sini -->
        </div>
        <div class="card-footer">
            <div class="input-group input-group-outline d-flex">
            <div style="position: relative;">
                <div class="form-control" id="chat-box" style="
                    position: absolute; 
                    background: white; 
                    border: 1px solid #ccc; 
                    width: 100%; 
                    display: none; 
                    max-height: 150px; 
                    overflow-y: auto; 
                    z-index: 1000;
                    border-radius: 5px;
                    box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
                "></div>
                <div id="suggestion-box" style="position: absolute; background: white; border: 1px solid #ccc; width: 200px; display: none;"></div>
                <textarea id="message" style="height: 50px; width:210px !important;" placeholder="Ketik pesan..." autocomplete="off" class="form-control me-2"></textarea>
            </div>
                <button class="btn btn-primary" onclick="sendMessage()" style="margin-top:3px !important;">
                    <i class="material-icons">send</i>
                </button>
            </div>
        </div>
    </div>
     <!-- Custom Context Menu -->
     <div id="context-menu">
        <ul>
            <li id="edit-option">Edit</li>
            <li id="delete-option">Hapus</li>
        </ul>
    </div>
</div>

<script>
    
        // Variabel currentUserId di-set dari session
        var currentUserId = <?php echo $_SESSION['user_id']; ?>;
        // Untuk mendeteksi jika notifikasi bertambah (audio akan dimainkan)
        var prevNotifCount = 0;
        var audioContext, audioElement;
        var currentMessageElement = null; // Bubble chat yang sedang diklik kanan
        var editingChatId = null;         // ID pesan yang sedang diedit
        var chatActive = false;

        // Saat pengguna mengarahkan kursor ke chat container atau ketika container mendapatkan focus:
        $('#chat-container').on('mouseenter focusin', function() {
            chatActive = true;
        }).on('mouseleave focusout', function() {
            chatActive = false;
        });

        $(document).ready(function() {
            // console.log("Document ready, inisialisasi AudioContext...");
            try {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                // console.log("AudioContext berhasil dibuat. State: " + audioContext.state);
            } catch(e) {
                // console.error("Error saat membuat AudioContext:", e);
            }
            audioElement = document.getElementById('chat-audio');
            if (!audioElement) {
                // console.error("Audio element tidak ditemukan!");
            } else {
                // console.log("Audio element ditemukan:", audioElement);
            }
            
            // Pastikan volume audio diatur maksimal (1 = 100%)
            audioElement.volume = 1.0;

            // Tambahkan listener untuk mengetahui kapan file audio siap dimainkan
            audioElement.addEventListener('canplaythrough', function() {
                // console.log("Audio file sudah siap untuk dimainkan. Duration:", audioElement.duration);
            });

            let audioSourceNode = audioContext.createMediaElementSource(audioElement);
            audioSourceNode.connect(audioContext.destination);

            // Polling untuk update pesan dan notifikasi
            setInterval(function() {
                loadMessages();
                checkNotifications();
            }, 3000);
        });
        // Fungsi untuk menampilkan atau menyembunyikan chat
        function toggleChat() {
            $('#chat-container').toggle();
            if ($('#chat-container').is(':visible')) {
                // Saat chat dibuka, tandai pesan yang belum dibaca sebagai sudah dibaca
                $.ajax({
                    url: 'test/fetchMessages.php',
                    type: 'GET',
                    data: { markRead: 1 },
                    success: function() {
                        checkNotifications();
                        loadMessages();
                    }
                });
            }
        }

        // Fungsi untuk mengirim pesan
        function sendMessage() {
            var message = $('#message').val().trim();
            if (message == '') return;
            // Jika sedang dalam mode edit
            if (editingChatId !== null) {
                $.ajax({
                url: 'test/updateMessage.php',
                type: 'POST',
                data: { action: 'edit', chat_id: editingChatId, new_message: message },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                    // Perbarui tampilan bubble chat yang sedang diedit
                    $('.chat-message[data-chat-id="'+ editingChatId +'"]').find("div:first").text(response.newMessage);
                    // Reset mode edit
                    editingChatId = null;
                    $('#message').val('');
                    $('#send-btn').text("Kirim");
                    } else {
                    alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error saat mengedit pesan:", error);
                }
                });
            } else {
                // Kirim pesan baru
                $.ajax({
                url: 'test/send_message.php',
                type: 'POST',
                data: { message: message },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                    $('#message').val('');
                    loadMessages();
                    checkNotifications();
                    } else {
                    alert(response.message);
                    }
                }
                });
            }
        }

        // Fungsi untuk mengambil pesan dan menampilkan read status
        function loadMessages() {
            $.ajax({
                url: 'test/fetchMessages.php',
                type: 'GET',
                dataType: 'json',
                success: function(chats) {
                    var chatHtml = '';
                    $.each(chats, function(i, chat) {
                        // Tentukan posisi pesan: kanan jika pesan dikirim sendiri, kiri jika dari orang lain
                        var messageClass = (chat.sender_id == currentUserId) ? 'chat-right' : 'chat-left';
                        chatHtml += '<div class="chat-message ' + messageClass + '" data-chat-id="' + chat.id + '">';
                        chatHtml += '<div>' + chat.message + '</div>';
                        chatHtml += '<div class="chat-meta"><span>' + chat.username + '</span> <span>' + chat.time_only + '</span>';

                        // Tampilkan read status:
                        // - Jika pesan dikirim oleh current user, tampilkan centang (misal: done_all)
                        // - Jika pesan dari orang lain dan belum dibaca (is_read==0) tampilkan "Baru"
                        // - Jika pesan dari orang lain dan sudah dibaca (is_read==1) tampilkan centang (misal: done)
                        if (chat.sender_id == currentUserId) {
                            chatHtml += ' <span class="read-status"><i class="material-icons" style="font-size:14px; vertical-align:middle;">done_all</i></span>';
                        } else {
                            if (chat.is_read == 0) {
                                chatHtml += ' <span class="read-status">Baru</span>';
                            } else {
                                chatHtml += ' <span class="read-status"><i class="material-icons" style="font-size:14px; vertical-align:middle;">done_all</i></span>';
                            }
                        }
                        chatHtml += '</div></div>';
                    });
                    $('#chat-box').html(chatHtml);
                    // Scroll ke bawah
                    $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
                }
            });
        }

        // Fungsi untuk mengecek notifikasi pesan baru dan memainkan audio jika ada pesan baru
        function checkNotifications() {
            $.ajax({
                url: 'test/get_notifications.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // console.log("Jumlah notifikasi dari server:", data.count);
                    if (data.count > prevNotifCount && !chatActive) {
                        // console.log("Notifikasi baru terdeteksi. Chat container tidak terbuka.");
                        if (audioContext.state === 'suspended') {
                            // console.log("AudioContext dalam keadaan suspended, mencoba resume...");
                            audioContext.resume().then(function() {
                                // console.log("AudioContext telah resume. Memulai playback...");
                                audioElement.play().then(function() {
                                    // console.log("Playback audio berhasil.");
                                }).catch(function(error) {
                                    // console.error("Error saat playback audio:", error);
                                });
                            }).catch(function(error) {
                                // console.error("Gagal me-resume AudioContext:", error);
                            });
                        } else {
                            // console.log("AudioContext state:", audioContext.state, ". Memulai playback...");
                            audioElement.play().then(function() {
                                // console.log("Playback audio berhasil.");
                            }).catch(function(error) {
                                // console.error("Error saat playback audio:", error);
                            });
                        }
                    }
                    prevNotifCount = data.count;
                    if (data.count > 0) {
                        $('#chat-notif').text(data.count).show();
                    } else {
                        $('#chat-notif').hide();
                    }
                },
                error: function(xhr, status, error) {
                    // console.error("Error checkNotifications:", error);
                }
            });
        }
        // Polling periodik untuk update pesan dan notifikasi (misal setiap 3 detik)
        $(document).ready(function() {
            setInterval(function() {
                loadMessages();
                checkNotifications();
            }, 3000);
        });
    //     window.sendMessage = function () {
    //     let message = $('#message').val().trim();
    //     if (message === '') {
    //         alert('Pesan tidak boleh kosong!');
    //         return;
    //     }
    // }

    $(document).ready(function () {
        $('#message').on('input', function () {
            let text = $(this).val();
            let lastWord = text.split(" ").pop(); // Ambil kata terakhir

            if (lastWord.startsWith('@')) {
                let query = lastWord.substring(1); // Hilangkan '@'

                if (query.length > 0) {
                    $.ajax({
                        url: 'test/search_mentions.php',
                        type: 'GET',
                        data: { query: query },
                        success: function (response) {
                            let results = JSON.parse(response);
                            showSuggestions(results);
                        },
                        error: function (xhr, status, error) {
                            console.log("Error:", error);
                        }
                    });
                }
            } else {
                $('#suggestion-box').hide(); // Sembunyikan jika tidak ada '@'
            }
        });

        function showSuggestions(results) {
            let suggestionBox = $('#suggestion-box');
            suggestionBox.html('');

            if (results.length > 0) {
                results.forEach(item => {
                    let suggestion = `<div class="suggestion-item form-control" onclick="insertMention('${item.name}')">
                        ${item.type === 'user' ? '@' : '📄'} ${item.name}
                    </div>`;
                    suggestionBox.append(suggestion);
                });

                let inputOffset = $('#message').offset();
                let inputHeight = $('#message').outerHeight();
                let suggestionHeight = suggestionBox.outerHeight();

                suggestionBox.css({
                    top: `-${suggestionHeight + 5}px`, // Pindahkan ke atas input
                    left: "0px",
                    display: "block"
                });
            } else {
                suggestionBox.hide();
            }
        }


        window.insertMention = function (name) {
            let messageInput = $('#message');
            let text = messageInput.val();
            let words = text.split(" ");
            words[words.length - 1] = "@" + name; // Ganti kata terakhir dengan mention
            messageInput.val(words.join(" ") + " ");
            $('#suggestion-box').hide();
        };
    });
    // Event delegasi untuk menangani right-click pada bubble chat
    $(document).on("contextmenu", ".chat-message", function(e) {
      e.preventDefault();
      currentMessageElement = $(this);
      $("#context-menu").css({
        top: e.pageY + "px",
        left: e.pageX + "px"
      }).show();
    });

    // Sembunyikan context menu saat klik di luar
    $(document).click(function(e) {
      if (!$(e.target).closest("#context-menu").length) {
        $("#context-menu").hide();
      }
    });

    // Opsi "Hapus" via AJAX ke updateMessage.php
    $("#delete-option").click(function() {
      if (currentMessageElement) {
        var chatId = currentMessageElement.data("chat-id");
        $.ajax({
          url: 'test/updateMessage.php',
          type: 'POST',
          data: { action: 'delete', chat_id: chatId },
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              // Tampilkan pesan terhapus dalam format italic
              currentMessageElement.find("div:first").html('<em>' + response.deletedText + '</em>');
            } else {
              alert(response.message);
            }
          },
          error: function(xhr, status, error) {
            console.error("Error saat menghapus pesan:", error);
          }
        });
      }
      $("#context-menu").hide();
    });

    // Opsi "Edit" memuat isi pesan ke form textarea dan masuk ke mode edit
    $("#edit-option").click(function() {
      if (currentMessageElement) {
        var chatId = currentMessageElement.data("chat-id");
        var currentText = currentMessageElement.find("div:first").text();
        // Masukkan teks ke textarea untuk diedit
        $('#message').val(currentText);
        // Simpan ID pesan yang sedang diedit ke variabel global
        editingChatId = chatId;
        // Ubah label tombol kirim menjadi "Update"
        $('#send-btn').text("Update");
      }
      $("#context-menu").hide();
    });
    </script>

<!-- <script>
        // Pastikan variabel currentUserId di-set dari session
        var currentUserId = <?php echo $_SESSION['user_id']; ?>;

        // Fungsi untuk menampilkan atau menyembunyikan chat
        function toggleChat() {
            $('#chat-container').toggle();
            if ($('#chat-container').is(':visible')) {
                // Saat chat dibuka, tandai pesan yang belum dibaca sebagai sudah dibaca
                $.ajax({
                    url: 'test/fetchMessages.php',
                    type: 'GET',
                    data: { markRead: 1 },
                    success: function() {
                        checkNotifications();
                        loadMessages();
                    }
                });
            }
        }

        // Fungsi untuk mengirim pesan
        function sendMessage() {
            var message = $('#message').val().trim();
            if (message == '') return;
            $.ajax({
                url: 'test/send_message.php',
                type: 'POST',
                data: { message: message },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#message').val('');
                        loadMessages();
                        checkNotifications();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }

        // Fungsi untuk mengambil pesan
        function loadMessages() {
            $.ajax({
                url: 'test/fetchMessages.php',
                type: 'GET',
                dataType: 'json',
                success: function(chats) {
                    var chatHtml = '';
                    $.each(chats, function(i, chat) {
                        // Jika pesan dikirim oleh user yang sedang login, tampilkan di sebelah kanan
                        var messageClass = (chat.sender_id == currentUserId) ? 'chat-right' : 'chat-left';
                        chatHtml += '<div class="chat-message ' + messageClass + '">';
                        chatHtml += '<div>' + chat.message + '</div>';
                        chatHtml += '<div class="chat-meta"><span>' + chat.username + '</span> <span>' + chat.created_at + '</span>';
                        if (chat.recipient_id == currentUserId && chat.is_read == 0) {
                            chatHtml += '<span class="read-status">Baru</span>';
                        }
                        chatHtml += '</div></div>';
                    });
                    $('#chat-box').html(chatHtml);
                    // Scroll ke bawah
                    $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
                }
            });
        }

        // Fungsi untuk mengecek notifikasi pesan baru
        function checkNotifications() {
            $.ajax({
                url: 'test/get_notifications.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.count > 0) {
                        $('#chat-notif').text(data.count).show();
                    } else {
                        $('#chat-notif').hide();
                    }
                }
            });
        }

        // Polling secara periodik untuk update pesan dan notifikasi (misal setiap 3 detik)
        $(document).ready(function() {
            setInterval(function() {
                loadMessages();
                checkNotifications();
            }, 3000);
        });
</script> -->
