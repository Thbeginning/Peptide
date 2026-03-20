<?php
require_once 'db.php';

$raw = file_get_contents('raw_products.txt');
// Split by numbers like '1) ', '2) '
$blocks = preg_split('/(^|\n)\d+\)\s+/', $raw, -1, PREG_SPLIT_NO_EMPTY);

echo "Found " . count($blocks) . " products to process.<br>";

foreach ($blocks as $block) {
    if (trim($block) === '') continue;

    $lines = explode("\n", $block);
    $firstLine = trim($lines[0]);

    // Initialize fields
    $p_name = '';
    $p_spec = '';
    $p_purity = '≥ 99%';
    $p_form = 'Lyophilized powder';
    $p_storage = 'Store at -20°C in a cool, dry place';
    $p_overview = '';
    $p_apps = '';
    $p_users = '';

    $current_section = '';

    foreach ($lines as $line) {
        $l = trim($line);
        if (stripos($l, 'Product Name:') === 0) {
            $p_name = trim(str_ireplace('Product Name:', '', $l));
        } elseif (stripos($l, 'Specification:') === 0) {
            $p_spec = trim(str_ireplace('Specification:', '', $l));
        } elseif (stripos($l, 'Purity:') === 0) {
            $p_purity = trim(str_ireplace('Purity:', '', $l));
        } elseif (stripos($l, 'Form:') === 0) {
            $p_form = trim(str_ireplace('Form:', '', $l));
        } elseif (stripos($l, 'Storage:') === 0) {
            $p_storage = trim(str_ireplace('Storage:', '', $l));
        } elseif (strpos($l, 'Overview:') !== false) {
            $current_section = 'overview';
        } elseif (strpos($l, 'Typical Research Applications:') !== false) {
            $current_section = 'applications';
        } elseif (strpos($l, 'Target Users:') !== false) {
            $current_section = 'users';
        } else {
            // Append to current section
            if ($current_section === 'overview' && $l !== '') {
                $p_overview .= $l . " ";
            } elseif ($current_section === 'applications' && $l !== '') {
                $p_apps .= $l . "\n";
            } elseif ($current_section === 'users' && $l !== '') {
                // Stop at random notes
                if (stripos($l, 'Note:') !== false || stripos($l, 'For laboratory') !== false) {
                    $current_section = '';
                } else {
                    $p_users .= $l . "\n";
                }
            }
        }
    }

    $p_overview = trim($p_overview);
    $p_apps = trim($p_apps);
    $p_users = trim($p_users);
    
    // Normalize first line to an ID
    $id = strtolower(str_replace(['+', ' '], '-', $firstLine));
    $id = preg_replace('/[^a-z0-9\-]/', '', $id);
    
    $clean_p_name = str_ireplace(' Peptide', '', $p_name);

    echo "<b>Processing -> ID: $id</b> | Name: $p_name <br>";
    
    // Let's attempt to match based on ID or Name
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id=? OR name LIKE ? OR id LIKE ? LIMIT 1");
    $stmt->execute([$id, "%$clean_p_name%", "%$id%"]);
    $row = $stmt->fetch();
    
    if ($row) {
        $real_id = $row['id'];
        $update = $pdo->prepare("UPDATE products SET specification=?, purity=?, form=?, storage=?, overview=?, applications=?, target_users=? WHERE id=?");
        $update->execute([$p_spec, $p_purity, $p_form, $p_storage, $p_overview, $p_apps, $p_users, $real_id]);
        echo "&nbsp;&nbsp;&nbsp; -> Updated successfully in DB ($real_id)<br>";
    } else {
        echo "&nbsp;&nbsp;&nbsp; -> <b style='color:red;'>WARNING: Could not find DB match for '$id' ($firstLine)</b><br>";
    }
}
echo "<br><b>Full data seed completed!</b>";
?>
