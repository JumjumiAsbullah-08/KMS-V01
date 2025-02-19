<?php
// flipbook.php
$file = isset($_GET['file']) ? $_GET['file'] : null;

if (!$file || !file_exists("uploads/" . $file)) {
    die("File tidak ditemukan.");
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flipbook PDF</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Turn.js -->
    <script src="../assets/turn/turn.min.js"></script>
    <!-- Include PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
    <style type="text/css">
        body {
            background: #ccc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        #magazine {
            width: 800px;
            height: 500px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }
        #magazine .turn-page {
            background-color: #ccc;
            background-size: 100% 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #magazine img {
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>
<body>

<div id="magazine">
    <!-- Cover -->
    <div class="hard">Cover</div>
    <!-- PDF Pages will be inserted here -->
    <!-- <div class="hard">Back</div> -->
</div>

<script type="text/javascript">
    $(window).ready(function() {
        const url = "uploads/<?php echo htmlspecialchars($file); ?>"; // Path ke file PDF

        // Load sound effects
        const startFlipSound = new Audio('../assets/sounds/start-flip.mp3');
        const endFlipSound = new Audio('../assets/sounds/end-flip.mp3');

        // Load PDF
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            const totalPages = pdf.numPages;
            let pagesLoaded = 0;

            for (let i = 1; i <= totalPages; i++) {
                pdf.getPage(i).then(function(page) {
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale: scale });

                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    page.render(renderContext).promise.then(function() {
                        const img = new Image();
                        img.src = canvas.toDataURL();
                        const pageDiv = document.createElement('div');
                        pageDiv.style.backgroundImage = `url(${img.src})`;
                        $('#magazine').append(pageDiv);

                        pagesLoaded++;
                        if (pagesLoaded === totalPages) {
                            // Inisialisasi flipbook setelah semua halaman dimuat
                            $('#magazine').turn({
                                display: 'single',
                                acceleration: true,
                                gradients: true,
                                elevation: 50,
                                when: {
                                    turning: function(e, page, view) {
                                        // Memutar suara saat mulai membalik halaman
                                        startFlipSound.play();
                                    },
                                    turned: function(e, page) {
                                        // Memutar suara saat selesai membalik halaman
                                        endFlipSound.play();
                                        console.log('Current view: ', $(this).turn('view'));
                                    }
                                }
                            });
                        }
                    });
                });
            }
        });
    });

    // Navigasi dengan tombol keyboard
    $(window).bind('keydown', function(e) {
        if (e.keyCode == 37) // Tombol kiri
            $('#magazine').turn('previous');
        else if (e.keyCode == 39) // Tombol kanan
            $('#magazine').turn('next');
    });
</script>

</body>
</html>