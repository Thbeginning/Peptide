<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You | Qingli Peptide</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0f1a 0%, #1a1f2e 100%);
            color: #e2e8f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .thank-you-container {
            max-width: 600px;
            width: 100%;
            padding: 3rem;
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 1rem;
            background: rgba(10, 15, 26, 0.8);
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .thank-you-container h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }
        .thank-you-container p {
            font-size: 1.125rem;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 2rem;
        }
        .back-link {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            color: #ffffff;
            text-decoration: none;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        }
        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <h1>Thank You</h1>
        <p id="thankYouMessage">Thank you for your feedback. Your review has been submitted for verification. We appreciate your contribution to the research community.</p>
        <a href="index.html" class="back-link">Return to Homepage</a>
    </div>

    <script>
        // Get the client name from URL parameter and update the message
        const urlParams = new URLSearchParams(window.location.search);
        const clientName = urlParams.get('name');
        if (clientName) {
            document.getElementById('thankYouMessage').textContent = `Thank you, ${decodeURIComponent(clientName)}. Your review has been submitted for verification. We appreciate your contribution to the research community.`;
        }
    </script>
</body>
</html>