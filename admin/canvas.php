<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>html2canvas Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .capture-area {
            width: 300px;
            height: 200px;
            padding: 20px;
            border: 1px solid #000;
            background-color: #f0f0f0;
        }
        .capture-area h1 {
            font-size: 24px;
            color: #333;
        }
        .capture-area p {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="capture-area">
        <h1>Title</h1>
        <p>This is some sample text to capture.</p>
    </div>
    <button id="capture-btn">Capture</button>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        document.getElementById('capture-btn').addEventListener('click', function() {
            html2canvas(document.querySelector('.capture-area')).then(canvas => {
                document.body.appendChild(canvas);
            });
        });
    </script>
</body>
</html>
