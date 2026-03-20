<?php
// === api/auth.php ===
// Handles Registration, Login, and Session Checking

session_start();
header('Content-Type: application/json');

// Security: Prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Security: Session timeout (2 hours)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
    session_destroy();
    echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
    exit;
}
$_SESSION['last_activity'] = time();

// Security: Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'db.php';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];
// Get raw JSON payload
$input = json_decode(file_get_contents('php://input'), true);

$action = $_GET['action'] ?? ($input['action'] ?? null);

if (!$action) {
    echo json_encode(['status' => 'error', 'message' => 'No action specified']);
    exit;
}

switch ($action) {
    case 'register':
        $name = filter_var($input['name'] ?? '', FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $input['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Email is already registered.']);
            exit;
        }

        // Default role is 'customer'. (To make an admin, you modify the DB directly or based on a specific email)
        $role = ($email === 'admin@qinglipeptide.com') ? 'admin' : 'customer';

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // last_login will automatically be CURRENT_TIMESTAMP by default DB behavior on insert
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password, $role]);
            
            // Automatically log them in
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;

            echo json_encode(['status' => 'success', 'message' => 'Registration successful.', 'user' => [
                'name' => $name, 'email' => $email, 'role' => $role
            ]]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed.']);
        }
        break;

    case 'login':
        $email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $input['password'] ?? '';

        // Proper authentication without hardcoded bypass
        if (empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Update last_login
            $update_stmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $update_stmt->execute([$user['id']]);

            // Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            echo json_encode(['status' => 'success', 'message' => 'Login successful.', 'user' => [
                'name' => $user['name'], 'email' => $user['email'], 'role' => $user['role']
            ]]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
        }
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Logged out.']);
        break;

    case 'check':
        if (isset($_SESSION['user_id'])) {
            echo json_encode([
                'status' => 'success',
                'logged_in' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'name' => $_SESSION['user_name'],
                    'email' => $_SESSION['user_email'],
                    'role' => $_SESSION['user_role']
                ]
            ]);
        } else {
            echo json_encode(['status' => 'success', 'logged_in' => false]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
