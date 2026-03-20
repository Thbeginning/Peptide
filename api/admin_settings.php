<?php
// === api/admin_settings.php ===
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Fetch user data to check role
$stmt = $pdo->prepare("SELECT id, role, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized: Admins only.']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'];

switch($action) {
    case 'update_content':
        updateContent();
        break;
    case 'get_content':
        getContent();
        break;
    case 'add_communication':
        addCommunication();
        break;
    case 'get_communications':
        getCommunications();
        break;
    case 'add_customer':
        addCustomer();
        break;
    case 'get_crm':
        getCRM();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

function updateContent() {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Create table if not exists
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS site_content (
                id INT AUTO_INCREMENT PRIMARY KEY,
                hero_headline TEXT,
                hero_subheadline TEXT,
                hero_cta TEXT,
                hero_cta2 TEXT,
                hero_rating DECIMAL(3,1),
                about_title TEXT,
                about_description TEXT,
                about_mission TEXT,
                calc_title TEXT,
                calc_description TEXT,
                calc_default_water DECIMAL(3,1),
                quality_title TEXT,
                quality_description TEXT,
                quality_purity DECIMAL(4,1),
                seo_title TEXT,
                seo_description TEXT,
                seo_keywords TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_content (id)
            )
        ");
        
        // Store content in database
        $stmt = $pdo->prepare("
            INSERT INTO site_content (hero_headline, hero_subheadline, hero_cta, hero_cta2, hero_rating, 
                                   about_title, about_description, about_mission, 
                                   calc_title, calc_description, calc_default_water,
                                   quality_title, quality_description, quality_purity,
                                   seo_title, seo_description, seo_keywords, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE
            hero_headline = VALUES(hero_headline),
            hero_subheadline = VALUES(hero_subheadline),
            hero_cta = VALUES(hero_cta),
            hero_cta2 = VALUES(hero_cta2),
            hero_rating = VALUES(hero_rating),
            about_title = VALUES(about_title),
            about_description = VALUES(about_description),
            about_mission = VALUES(about_mission),
            calc_title = VALUES(calc_title),
            calc_description = VALUES(calc_description),
            calc_default_water = VALUES(calc_default_water),
            quality_title = VALUES(quality_title),
            quality_description = VALUES(quality_description),
            quality_purity = VALUES(quality_purity),
            seo_title = VALUES(seo_title),
            seo_description = VALUES(seo_description),
            seo_keywords = VALUES(seo_keywords),
            updated_at = NOW()
        ");
        
        $stmt->execute([
            $data['heroHeadline'],
            $data['heroSubheadline'],
            $data['heroCTA'],
            $data['heroCTA2'],
            $data['heroRating'],
            $data['aboutTitle'],
            $data['aboutDescription'],
            $data['aboutMission'],
            $data['calcTitle'],
            $data['calcDescription'],
            $data['calcDefaultWater'],
            $data['qualityTitle'],
            $data['qualityDescription'],
            $data['qualityPurity'],
            $data['seoTitle'],
            $data['seoDescription'],
            $data['seoKeywords']
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Content updated successfully']);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getContent() {
    try {
        // Create table if not exists
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS site_content (
                id INT AUTO_INCREMENT PRIMARY KEY,
                hero_headline TEXT,
                hero_subheadline TEXT,
                hero_cta TEXT,
                hero_cta2 TEXT,
                hero_rating DECIMAL(3,1),
                about_title TEXT,
                about_description TEXT,
                about_mission TEXT,
                calc_title TEXT,
                calc_description TEXT,
                calc_default_water DECIMAL(3,1),
                quality_title TEXT,
                quality_description TEXT,
                quality_purity DECIMAL(4,1),
                seo_title TEXT,
                seo_description TEXT,
                seo_keywords TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Get content from database
        $stmt = $pdo->prepare("SELECT * FROM site_content ORDER BY updated_at DESC LIMIT 1");
        $stmt->execute();
        $content = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'content' => $content ?: [
                'hero_headline' => 'Premium Research Peptides',
                'hero_subheadline' => 'Global supplier of 98%+ purity research peptides',
                'hero_cta' => 'Get Started',
                'hero_cta2' => 'View Catalog',
                'hero_rating' => '4.9',
                'about_title' => 'About Qingli Peptide',
                'about_description' => 'Premium supplier of high-purity peptides and chemical reagents for global biotechnology sector. Empowering research excellence since 2019.',
                'about_mission' => 'To provide researchers worldwide with the highest quality peptides, backed by comprehensive analytical verification and exceptional customer service.',
                'calc_title' => 'Peptide Reconstitution Calculator',
                'calc_description' => 'Achieve 100% accurate research measurements with our clinical-grade reconstitution tool. Designed for precise laboratory calculations across all standard syringe specifications.',
                'calc_default_water' => '2',
                'quality_title' => 'Uncompromising Analytical Verification',
                'quality_description' => 'In the research chemical sector, trust is built on verifiable data. Every single batch undergoes rigorous independent analysis via HPLC and Mass Spectrometry before entering our global inventory.',
                'quality_purity' => '99.9',
                'seo_title' => 'Qingli Peptide | Premium Research Peptides',
                'seo_description' => 'Global supplier of 98%+ purity research peptides. Third-party tested, designed for clinical and laboratory research use only.',
                'seo_keywords' => 'research peptides, peptide synthesis, laboratory chemicals, HPLC tested, 99% purity'
            ]
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function addCommunication() {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Create table if not exists
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS communications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                customer_name VARCHAR(255),
                message TEXT,
                type ENUM('email', 'whatsapp', 'phone', 'support', 'meeting'),
                status ENUM('open', 'in-progress', 'resolved', 'follow-up') DEFAULT 'open',
                date DATE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                created_by VARCHAR(100)
            )
        ");
        
        $stmt = $pdo->prepare("
            INSERT INTO communications (customer_name, message, type, status, date, created_by)
            VALUES (?, ?, ?, ?, CURDATE(), ?)
        ");
        
        $stmt->execute([
            $data['customer_name'],
            $data['message'],
            $data['type'],
            $_SESSION['user_name'] ?? 'Admin'
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Communication added successfully']);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getCommunications() {
    try {
        // Create table if not exists
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS communications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                customer_name VARCHAR(255),
                message TEXT,
                type ENUM('email', 'whatsapp', 'phone', 'support', 'meeting'),
                status ENUM('open', 'in-progress', 'resolved', 'follow-up') DEFAULT 'open',
                date DATE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                created_by VARCHAR(100)
            )
        ");
        
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $status = $_GET['status'] ?? '';
        
        $sql = "SELECT * FROM communications WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (customer_name LIKE ? OR message LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if (!empty($type)) {
            $sql .= " AND type = ?";
            $params[] = $type;
        }
        
        if (!empty($dateFrom)) {
            $sql .= " AND date >= ?";
            $params[] = $dateFrom;
        }
        
        if (!empty($dateTo)) {
            $sql .= " AND date <= ?";
            $params[] = $dateTo;
        }
        
        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT 50";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $communications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'communications' => $communications
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function addCustomer() {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Create table if not exists
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS customers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                email VARCHAR(255),
                stage ENUM('new', 'qualified', 'quote_sent', 'negotiation', 'won') DEFAULT 'new',
                value DECIMAL(10,2) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                created_by VARCHAR(100)
            )
        ");
        
        $stmt = $pdo->prepare("
            INSERT INTO customers (name, email, stage, value, created_by)
            VALUES (?, ?, 'new', ?, ?)
        ");
        
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['value'] ?? 0,
            $_SESSION['user_name'] ?? 'Admin'
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Customer added successfully']);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getCRM() {
    try {
        // Create tables if not exist
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS customers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                email VARCHAR(255),
                stage ENUM('new', 'qualified', 'quote_sent', 'negotiation', 'won') DEFAULT 'new',
                value DECIMAL(10,2) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                created_by VARCHAR(100)
            )
        ");
        
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS communications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                customer_name VARCHAR(255),
                message TEXT,
                type ENUM('email', 'whatsapp', 'phone', 'support', 'meeting'),
                status ENUM('open', 'in-progress', 'resolved', 'follow-up') DEFAULT 'open',
                date DATE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                created_by VARCHAR(100)
            )
        ");
        
        // Get CRM stats
        $statsStmt = $pdo->prepare("
            SELECT 
                COUNT(*) as total_customers,
                SUM(CASE WHEN stage = 'quote_sent' THEN value ELSE 0 END) as active_deals,
                (SELECT COUNT(*) FROM communications WHERE status = 'open') as open_conversations
            FROM customers
        ");
        $statsStmt->execute();
        $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
        
        // Get pipeline data
        $pipelineStmt = $pdo->prepare("
            SELECT stage, COUNT(*) as count, GROUP_CONCAT(
                JSON_OBJECT('id', id, 'name', name, 'email', email, 'value', COALESCE(value, 0))
                ORDER BY id DESC
                LIMIT 5
            SEPARATOR '|'
            ) as customers_json
            FROM customers 
            GROUP BY stage
        ");
        $pipelineStmt->execute();
        $pipelineData = $pipelineStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $pipeline = [
            'new' => [],
            'qualified' => [],
            'quote_sent' => [],
            'negotiation' => [],
            'won' => []
        ];
        
        foreach ($pipelineData as $row) {
            $stage = $row['stage'];
            $customersJson = $row['customers_json'];
            $customers = array_filter(explode('|', $customersJson));
            $pipeline[$stage] = array_map(function($json) {
                return json_decode($json, true);
            }, $customers);
        }
        
        echo json_encode([
            'status' => 'success',
            'stats' => $stats,
            'pipeline' => $pipeline
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Read JSON payload
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['action'])) {
        echo json_encode(['status' => 'error', 'message' => 'Action required.']);
        exit;
    }

    $action = $data['action'];

    if ($action === 'update_login') {
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
            exit;
        }

        try {
            // Check if email already exists for another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user['id']]);
            if ($stmt->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'Email is already in use by another account.']);
                exit;
            }

            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET email = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$email, $hash, $user['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
                $stmt->execute([$email, $user['id']]);
            }

            echo json_encode(['status' => 'success', 'message' => 'Admin login details updated successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
