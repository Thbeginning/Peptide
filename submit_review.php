<?php
// === submit_review.php ===
// QingLi Brand Ratings Submission Form
session_start();
header('Content-Type: text/html; charset=UTF-8');

// Optional: Check for a token parameter for security
// $token = $_GET['token'] ?? '';
// if (empty($token) || $token !== 'your_secret_token') {
//     die('Access denied.');
// }

require_once 'api/db.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $display_name = trim($_POST['display_name'] ?? '');
    $overall_rating = (int)($_POST['overall_rating'] ?? 0);
    $communication_professional = isset($_POST['communication_professional']) ? 1 : 0;
    $shipping_discreet_timely = isset($_POST['shipping_discreet_timely']) ? 1 : 0;
    $product_lab_standards = isset($_POST['product_lab_standards']) ? 1 : 0;
    $review_text = trim($_POST['review_text'] ?? '');

    if (empty($display_name) || $overall_rating < 1 || $overall_rating > 5 || empty($review_text)) {
        $message = 'Please fill in all required fields correctly.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO site_reviews (display_name, overall_rating, communication_professional, shipping_discreet_timely, product_lab_standards, review_text) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$display_name, $overall_rating, $communication_professional, $shipping_discreet_timely, $product_lab_standards, $review_text]);
            $success = true;
            $message = 'Thank you! Your review has been submitted and is pending verification by our team.';
        } catch (PDOException $e) {
            $message = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QingLi Brand Ratings | Qingli Peptide</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0f1a 0%, #1a1f2e 100%);
            color: #e2e8f0;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .review-form {
            max-width: 700px;
            width: 100%;
            background: rgba(10, 15, 26, 0.8);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 1rem;
            padding: 3rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        .form-header p {
            font-size: 1.125rem;
            color: #94a3b8;
        }
        .form-group {
            margin-bottom: 2rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 0.75rem;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            transition: all 0.3s ease;
        }
        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
            background: rgba(255, 255, 255, 0.08);
        }
        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .rating-stars input[type="radio"] {
            display: none;
        }
        .rating-stars label {
            font-size: 2.5rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            filter: drop-shadow(0 0 5px rgba(6, 182, 212, 0.3));
        }
        .rating-stars input[type="radio"]:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #fbbf24;
            filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.5));
        }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #374151, #4b5563);
            transition: .4s;
            border-radius: 34px;
            border: 1px solid rgba(6, 182, 212, 0.2);
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        input:checked + .slider {
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
        }
        input:checked + .slider:before {
            transform: translateX(26px);
            background: #ffffff;
        }
        .toggle-label {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 500;
            color: #e2e8f0;
        }
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            border: none;
            border-radius: 0.75rem;
            color: #ffffff;
            font-size: 1.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        }
        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
        }
        .submit-btn:disabled {
            background: linear-gradient(135deg, #64748b, #475569);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
        .message {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 0.75rem;
            backdrop-filter: blur(10px);
        }
        .message.error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .message.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body>
    <div class="review-form">
        <div class="form-header">
            <div class="brand-logo" style="text-align: center; margin-bottom: 1.5rem;">
                <img src="Peptide image 2.0/Logo.jpeg" alt="Qingli Peptide Logo" style="width: 60px; height: 60px; border-radius: 12px; margin-bottom: 0.5rem; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                <div class="logo-text" style="font-size: 1.5rem; font-weight: 700; background: linear-gradient(135deg, #06b6d4, #2563eb); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Qingli <span style="color: #06b6d4;">Peptide</span>
                </div>
            </div>
            <h1>QingLi Brand Ratings</h1>
            <p>Help us improve our services by sharing your experience</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" id="reviewForm">
            <div class="form-group">
                <label for="display_name">Display Name *</label>
                <input type="text" id="display_name" name="display_name" placeholder="e.g., Researcher Name or Lab Name" required>
            </div>

            <div class="form-group">
                <label>Overall Experience *</label>
                <div class="rating-stars">
                    <input type="radio" id="star5" name="overall_rating" value="5" required>
                    <label for="star5"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star4" name="overall_rating" value="4">
                    <label for="star4"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star3" name="overall_rating" value="3">
                    <label for="star3"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star2" name="overall_rating" value="2">
                    <label for="star2"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star1" name="overall_rating" value="1">
                    <label for="star1"><i class="fas fa-star"></i></label>
                </div>
            </div>

            <div class="form-group">
                <label>Service Categories</label>
                <div class="toggle-label">
                    <label class="toggle-switch">
                        <input type="checkbox" name="communication_professional" value="1">
                        <span class="slider"></span>
                    </label>
                    Was the communication professional?
                </div>
                <div class="toggle-label" style="margin-top: 12px;">
                    <label class="toggle-switch">
                        <input type="checkbox" name="shipping_discreet_timely" value="1">
                        <span class="slider"></span>
                    </label>
                    Was the shipping discreet and timely?
                </div>
                <div class="toggle-label" style="margin-top: 12px;">
                    <label class="toggle-switch">
                        <input type="checkbox" name="product_lab_standards" value="1">
                        <span class="slider"></span>
                    </label>
                    Did the product meet laboratory standards?
                </div>
            </div>

            <div class="form-group">
                <label for="review_text">Your Review *</label>
                <textarea id="review_text" name="review_text" rows="6" placeholder="Share your detailed experience and feedback..." required></textarea>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">Submit Review</button>
        </form>
        <?php else: ?>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="index.html" class="submit-btn" style="text-decoration: none; display: inline-block;">Return to Storefront</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Anti-spam: Disable submit button after click
        document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        });
    </script>
</body>
</html>