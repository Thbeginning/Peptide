<?php
require_once 'db.php';

$images_dir = __DIR__ . '/../Peptide image 2.0';
$coa_dir = __DIR__ . '/../COA';

$images = scandir($images_dir);
$coas = scandir($coa_dir);

echo "Starting product seed from files...<br>";

foreach ($images as $img) {
    if ($img === '.' || $img === '..' || is_dir("$images_dir/$img") || $img === 'Logo.jpeg' || $img === 'about us.webp' || strpos($img, '.html') !== false) {
        continue;
    }
    
    // E.g. "BPC157+TB500.jpeg" -> "BPC157+TB500"
    $name = pathinfo($img, PATHINFO_FILENAME);
    
    // e.g. "bpc157-tb500"
    $id = strtolower(str_replace('+', '-', str_replace(' ', '-', $name)));
    $id = preg_replace('/[^a-z0-9\-]/', '', $id);
    
    $image_path = 'Peptide image 2.0/' . $img;
    
    // Find matching COAs
    $matched_coas = [];
    foreach ($coas as $coa) {
        if ($coa === '.' || $coa === '..' || is_dir("$coa_dir/$coa") || $coa === 'peptide product cover .jpeg') {
            continue;
        }
        
        // Strip out the (5mg), (10mg), COA parts, make simple match
        // Or simply check if the product name appears in the COA string (case insensitive)
        // Wait, "BPC157+TB500" does it match "BPC157 COA"? No.
        // But "BPC157" shouldn't match "BPC157+TB500".
        // Let's explode the name by '+' or spaces and see if all parts or the main part is in the COA name.
        $coa_clean = str_ireplace([' COA', '.webp', '.jpg', '.jpeg', '.png', ' (', ')'], ' ', $coa);
        
        // simple containment check: 
        // e.g. Reta is in Reta COA (10mg).webp!
        if (stripos($coa, $name) !== false) {
            $matched_coas[] = 'COA/' . $coa;
        } else {
            // Check matching ID (like 'bpc157')
            $coa_id_version = strtolower(str_replace('+', '-', str_replace(' ', '-', pathinfo($coa, PATHINFO_FILENAME))));
            if (stripos($coa_id_version, $id) !== false && strlen($id) > 2) {
                // To avoid 'mt-2' matching 'mt' which is too short, etc.
                 $matched_coas[] = 'COA/' . $coa;
            }
        }
    }
    
    // unique matches
    $matched_coas = array_unique($matched_coas);
    
    // Special defaults if name is "Klow" or something based on previous context
    $category = 'Peptide';
    $description = 'High-purity synthetic peptide designed for research studies.';
    $purity = '≥ 99%';
    $form = 'Lyophilized powder';
    
    if (stripos($name, 'klow') !== false || strtolower($id) === 'klow') {
        $description = 'KLOW peptide(BPC157+TB500+GHK-CU+KPV), high-purity peptide designed for skin health and rejuvenation studies. Ideal for beauty clinics, wellness centers, and peptide distributors.';
        $form = 'Lyophilized powder';
        $storage = 'Store at -20°C in a cool, dry place';
        $specification = '80mg/vial';
    } else {
        $storage = 'Store at -20°C';
        $specification = '';
    }
    
    $coa_paths_json = empty($matched_coas) ? null : json_encode(array_values($matched_coas));

    echo "Inserting $name ($id) | COAS: " . count($matched_coas) . "<br>";

    // Ensure it exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $exists = $stmt->fetch();
    
    if ($exists) {
        $stmt = $pdo->prepare("UPDATE products SET image_path=?, coa_paths=?, name=?, category=?, purity=?, description=?, form=?, storage=?, specification=? WHERE id=?");
        $stmt->execute([$image_path, $coa_paths_json, $name, $category, $purity, $description, $form, $storage, $specification, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (id, name, category, image_path, description, purity, form, storage, specification, coa_paths) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $name, $category, $image_path, $description, $purity, $form, $storage, $specification, $coa_paths_json]);
    }
}

echo "Seed complete!";
?>
