<?php
// === api/settings.php ===
// Handles getting and updating general site settings (Contact Info)

session_start();
header('Content-Type: application/json');

require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// GET Settings (Public - for frontend display)
if ($method === 'GET' && $action === 'fetch') {
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        // Add marquee_messages as array split by newlines if contact_marquee exists
        if (isset($settings['contact_marquee'])) {
            $lines = preg_split('/\r?\n/', $settings['contact_marquee']);
            $marquee_messages = array_filter(array_map('trim', $lines), function($v) { return $v !== ''; });
            $settings['marquee_messages'] = array_values($marquee_messages);
        } else {
            $settings['marquee_messages'] = [];
        }
        echo json_encode(['status' => 'success', 'settings' => $settings]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch settings']);
    }
    exit;
}

// POST Settings (Admin only - for updating)
if ($method === 'POST') {
    // Check Authentication and Admin Role
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $action_post = $input['action'] ?? '';

    if ($action_post === 'update') {
        $email = $input['email'] ?? null;
        $whatsapp = $input['whatsapp'] ?? null;
        $marquee = $input['marquee'] ?? null;
        $marquee_speed = $input['marquee_speed'] ?? null;
        $marquee_color = $input['marquee_color'] ?? null;

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            
            if ($email !== null) {
                $stmt->execute(['contact_email', $email, $email]);
            }
            if ($whatsapp !== null) {
                $stmt->execute(['contact_whatsapp', $whatsapp, $whatsapp]);
            }
            if ($marquee !== null) {
                $stmt->execute(['contact_marquee', $marquee, $marquee]);
            }
            if ($marquee_speed !== null) {
                $stmt->execute(['marquee_speed', $marquee_speed, $marquee_speed]);
            }
            if ($marquee_color !== null) {
                $stmt->execute(['marquee_color', $marquee_color, $marquee_color]);
            }
            
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Settings updated successfully']);
        } catch(PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Failed to update settings.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
}
?>
