<?php
// === api/products.php ===
// Handles fetching, adding, editing, and deleting products
session_start();
header('Content-Type: application/json');
require_once 'db.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? null);

if (!$action) {
    // If no action, default to fetching all (for public storefront)
    $action = 'fetch_all';
}

switch ($action) {
    case 'fetch_all':
        try {
            $stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
            $products = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'products' => $products]);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch products.']);
        }
        break;

    case 'add_product':
        // Require Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        $id = filter_var($_POST['id'] ?? '', FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
        $category = filter_var($_POST['category'] ?? '', FILTER_SANITIZE_STRING);
        $description = $_POST['description'] ?? '';
        $purity = filter_var($_POST['purity'] ?? '', FILTER_SANITIZE_STRING);
        
        $specification = $_POST['specification'] ?? '';
        $form = $_POST['form'] ?? '';
        $storage = $_POST['storage'] ?? '';
        $overview = $_POST['overview'] ?? '';
        $applications = $_POST['applications'] ?? '';
        $target_users = $_POST['target_users'] ?? '';
        
        if(empty($id) || empty($name) || empty($category)) {
            echo json_encode(['status' => 'error', 'message' => 'ID, Name, and Category are required.']);
            exit;
        }

        // File Upload Handling
        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $image_path = 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&q=80&w=400'; // Fallback
        $coa_paths = [];

        // Image Upload with validation
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedImg = ['jpg','jpeg','png','gif','webp'];
            if (!in_array($ext, $allowedImg)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid image file type. Allowed: jpg, jpeg, png, gif, webp']);
                exit;
            }
            $targetPath = $upload_dir . time() . '_' . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image_path = 'uploads/' . basename($targetPath);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
                exit;
            }
        }

        // COA Upload (Multiple) with validation
        if (isset($_FILES['coa'])) {
            $files = $_FILES['coa'];
            $allowedCoa = ['pdf','jpg','jpeg','png','gif','webp'];
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $fileName = basename($files['name'][$i]);
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (!in_array($ext, $allowedCoa)) {
                            echo json_encode(['status' => 'error', 'message' => 'Invalid COA file type: ' . $fileName . '. Allowed: pdf, jpg, jpeg, png, gif, webp']);
                            exit;
                        }
                        $targetPath = $upload_dir . time() . '_COA_' . $i . '_' . $fileName;
                        if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                            $coa_paths[] = 'uploads/' . basename($targetPath);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Failed to upload COA file: ' . $fileName]);
                            exit;
                        }
                    }
                }
            } else {
                if ($files['error'] === UPLOAD_ERR_OK) {
                    $fileName = basename($files['name']);
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowedCoa)) {
                        echo json_encode(['status' => 'error', 'message' => 'Invalid COA file type: ' . $fileName . '. Allowed: pdf, jpg, jpeg, png, gif, webp']);
                        exit;
                    }
                    $targetPath = $upload_dir . time() . '_COA_' . $fileName;
                    if (move_uploaded_file($files['tmp_name'], $targetPath)) {
                        $coa_paths[] = 'uploads/' . basename($targetPath);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to upload COA file: ' . $fileName]);
                        exit;
                    }
                }
            }
        }

        $coa_paths_json = empty($coa_paths) ? null : json_encode($coa_paths);

        try {
            $stmt = $pdo->prepare("INSERT INTO products (id, name, category, image_path, description, purity, coa_paths, specification, form, storage, overview, applications, target_users) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id, $name, $category, $image_path, $description, $purity, $coa_paths_json, $specification, $form, $storage, $overview, $applications, $target_users]);
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error (ID might already exist).']);
        }
        break;

    case 'delete_product':
        // Require Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }
        
        $json = json_decode(file_get_contents('php://input'), true);
        $id = $json['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Product ID required.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Product deleted.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
        }
        break;

    case 'edit_product':
        // Require Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        $old_id = $_POST['old_id'] ?? '';
        $id = filter_var($_POST['id'] ?? '', FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
        $category = filter_var($_POST['category'] ?? '', FILTER_SANITIZE_STRING);
        $description = $_POST['description'] ?? '';
        $purity = filter_var($_POST['purity'] ?? '', FILTER_SANITIZE_STRING);
        
        $specification = $_POST['specification'] ?? '';
        $form = $_POST['form'] ?? '';
        $storage = $_POST['storage'] ?? '';
        $overview = $_POST['overview'] ?? '';
        $applications = $_POST['applications'] ?? '';
        $target_users = $_POST['target_users'] ?? '';
        
        if(empty($old_id) || empty($id) || empty($name) || empty($category)) {
            echo json_encode(['status' => 'error', 'message' => 'ID, Name, and Category are required.']);
            exit;
        }

        // Handle existing product fetching
        $stmt = $pdo->prepare("SELECT image_path, coa_paths, coa_path FROM products WHERE id = ?");
        $stmt->execute([$old_id]);
        $existing = $stmt->fetch();
        if (!$existing) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
            exit;
        }

        $image_path = $existing['image_path'];
        $coa_paths_json = $existing['coa_paths'];
        
        $existing_coas = [];
        if ($coa_paths_json) {
            $existing_coas = json_decode($coa_paths_json, true) ?: [];
        } elseif ($existing['coa_path']) {
            $existing_coas = [$existing['coa_path']];
        }

        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Image Upload with validation
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedImg = ['jpg','jpeg','png','gif','webp'];
            if (!in_array($ext, $allowedImg)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid image file type. Allowed: jpg, jpeg, png, gif, webp']);
                exit;
            }
            $targetPath = $upload_dir . time() . '_' . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image_path = 'uploads/' . basename($targetPath);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
                exit;
            }
        }

        // COA Upload (Multiple) with validation
        if (isset($_FILES['coa'])) {
            $files = $_FILES['coa'];
            $allowedCoa = ['pdf','jpg','jpeg','png','gif','webp'];
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $fileName = basename($files['name'][$i]);
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (!in_array($ext, $allowedCoa)) {
                            echo json_encode(['status' => 'error', 'message' => 'Invalid COA file type: ' . $fileName . '. Allowed: pdf, jpg, jpeg, png, gif, webp']);
                            exit;
                        }
                        $targetPath = $upload_dir . time() . '_COA_' . $i . '_' . $fileName;
                        if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                            $existing_coas[] = 'uploads/' . basename($targetPath);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Failed to upload COA file: ' . $fileName]);
                            exit;
                        }
                    }
                }
            } else {
                if ($files['error'] === UPLOAD_ERR_OK) {
                    $fileName = basename($files['name']);
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowedCoa)) {
                        echo json_encode(['status' => 'error', 'message' => 'Invalid COA file type: ' . $fileName . '. Allowed: pdf, jpg, jpeg, png, gif, webp']);
                        exit;
                    }
                    $targetPath = $upload_dir . time() . '_COA_' . $fileName;
                    if (move_uploaded_file($files['tmp_name'], $targetPath)) {
                        $existing_coas[] = 'uploads/' . basename($targetPath);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to upload COA file: ' . $fileName]);
                        exit;
                    }
                }
            }
        }

        // Clear COAs if user requested it
        if (isset($_POST['clear_coas']) && $_POST['clear_coas'] === 'true') {
            $existing_coas = [];
        }

        $coa_paths_json = empty($existing_coas) ? null : json_encode($existing_coas);

        try {
            $stmt = $pdo->prepare("UPDATE products SET id=?, name=?, category=?, image_path=?, description=?, purity=?, coa_paths=?, specification=?, form=?, storage=?, overview=?, applications=?, target_users=? WHERE id=?");
            $stmt->execute([$id, $name, $category, $image_path, $description, $purity, $coa_paths_json, $specification, $form, $storage, $overview, $applications, $target_users, $old_id]);
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error during update.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
