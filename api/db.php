<?php
// === api/db.php ===
// Database Connection Setup

// Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; img-src 'self' data: https://images.unsplash.com; connect-src 'self';");

// Adjust these based on your local XAMPP environment
$host = 'localhost';
$dbname = 'qingli_db';
$user = 'root'; // default XAMPP user
$password = ''; // default XAMPP password is empty

try {
    // 1. Connect without specifying the database
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fetch objects by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 2. Ensure database exists and select it
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");

    // 3. Auto-setup tables if they do not exist
    $tables = $pdo->query("SHOW TABLES LIKE 'users'")->rowCount();
    if ($tables === 0) {
        $sqlFile = __DIR__ . '/../database.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $pdo->exec($sql);
        }
    }

    // 4. Auto-update schema for new features (fail silently if columns already exist)
    try { $pdo->exec("ALTER TABLE users ADD COLUMN last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE quote_requests ADD COLUMN research_only_confirmed BOOLEAN DEFAULT 0"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE products ADD COLUMN specification VARCHAR(255) DEFAULT NULL"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE products ADD COLUMN form VARCHAR(255) DEFAULT NULL"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE products ADD COLUMN storage VARCHAR(255) DEFAULT NULL"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE products ADD COLUMN overview TEXT DEFAULT NULL"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE products ADD COLUMN applications TEXT DEFAULT NULL"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE products ADD COLUMN target_users TEXT DEFAULT NULL"); } catch(PDOException $e) {}
    try { $pdo->exec("ALTER TABLE products ADD COLUMN coa_paths TEXT DEFAULT NULL"); } catch(PDOException $e) {}

    // 5. Dynamic Products System Migration
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id VARCHAR(100) PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        category VARCHAR(100) NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        description TEXT,
        purity VARCHAR(50),
        coa_path VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 6. Quote System Migration
    $pdo->exec("CREATE TABLE IF NOT EXISTS quote_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        shipping_city VARCHAR(100) NOT NULL,
        shipping_country VARCHAR(100) NOT NULL,
        contact_method ENUM('email', 'whatsapp') DEFAULT 'email',
        contact_detail VARCHAR(255) NOT NULL,
        message TEXT,
        research_only_confirmed BOOLEAN DEFAULT TRUE,
        status ENUM('Pending', 'Under Review', 'Paid', 'Shipped', 'Cancelled') DEFAULT 'Pending',
        internal_notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS quote_request_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quote_id INT NOT NULL,
        product_id VARCHAR(100) NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        quantity INT DEFAULT 1,
        FOREIGN KEY (quote_id) REFERENCES quote_requests(id) ON DELETE CASCADE
    )");

    // 7. Settings System Migration
    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        setting_key VARCHAR(50) PRIMARY KEY,
        setting_value VARCHAR(255) NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Seed default settings
    $settingsCount = $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn();
    if ($settingsCount == 0) {
        $pdo->exec("INSERT INTO settings (setting_key, setting_value) VALUES ('contact_email', 'admin@qinglipeptide.com')");
        $pdo->exec("INSERT INTO settings (setting_key, setting_value) VALUES ('contact_whatsapp', '+1234567890')");
    }

    // 8. Site Reviews System Migration
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        display_name VARCHAR(255) NOT NULL,
        overall_rating INT NOT NULL CHECK (overall_rating BETWEEN 1 AND 5),
        communication_professional BOOLEAN DEFAULT 0,
        shipping_discreet_timely BOOLEAN DEFAULT 0,
        product_lab_standards BOOLEAN DEFAULT 0,
        review_text TEXT NOT NULL,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Seed products table if empty (initial migration from hardcoded JS)
    $prodCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($prodCount == 0) {
        $initialProducts = [
            ['aod9604', 'AOD9604', 'Peptide', 'Peptide image 2.0/AOD9604.jpeg', 'Fat loss peptide fragment derived from hGH.', '>99.0%'],
            ['ara290', 'Ara290', 'Peptide', 'Peptide image 2.0/Ara290.jpeg', 'Synthetic peptide designed to target the erythropoietin receptor.', '>98.5%'],
            ['botox', 'BOTOX', 'Cosmetic', 'Peptide image 2.0/BOTOX.jpeg', 'Botulinum toxin type A research grade.', '>99.1%'],
            ['bpc-tb-blend', 'BPC157 + TB500', 'Peptide Blend', 'Peptide image 2.0/BPC157+TB500.jpeg', 'Synergistic tissue repair and angiogenesis blend.', '>99.0%'],
            ['bpc-157', 'BPC-157', 'Peptide', 'Peptide image 2.0/BPC157.jpeg', 'Body Protection Compound. Synthetically produced sequence utilized extensively for tissue repair research.', '>99.2%'],
            ['cjc-dac', 'CJC-1295 DAC', 'Peptide', 'Peptide image 2.0/CJC-1295-DAC.jpeg', 'Long-acting GHRH analog with Drug Affinity Complex.', '>98.8%'],
            ['cjc-nodac', 'CJC-1295 NO DAC', 'Peptide', 'Peptide image 2.0/CJC-1295-NO-DAC.jpeg', 'Shorter acting GHRH analog (Mod GRF 1-29).', '>99.0%'],
            ['cjc-ipa', 'CJC1295 NO DAC + IPA', 'Peptide Blend', 'Peptide image 2.0/CJC1295 NO DAC+IPA.jpeg', 'Standard growth hormone secretagogue blend.', '>99.0%'],
            ['cagrilin', 'Cagrilintide', 'Amylin Analog', 'Peptide image 2.0/Cagrilin.jpeg', 'Long-acting amylin analog for metabolic research.', '>99.2%'],
            ['cerebrolysin', 'Cerebrolysin', 'Nootropic', 'Peptide image 2.0/Cerebrolysim.jpeg', 'Peptide mixture with neurotrophic factors.', '>98.5%'],
            ['dsip', 'DSIP', 'Peptide', 'Peptide image 2.0/DSIP.jpeg', 'Delta Sleep-Inducing Peptide.', '>98.9%'],
            ['epithalon', 'Epithalon', 'Peptide', 'Peptide image 2.0/Epithalon.jpeg', 'Synthetic pineal peptide derivative currently studied for telomerase activation and anti-aging.', '>99.0%'],
            ['ghk-cu', 'GHK-CU', 'Copper Peptide', 'Peptide image 2.0/GHK-CU.jpeg', 'Copper peptide naturally occurring and used in cosmetic/wound healing research.', '>99.3%'],
            ['ghrp-6', 'GHRP-6', 'Peptide', 'Peptide image 2.0/GHRP-6.jpeg', 'First-generation growth hormone releasing hexapeptide.', '>98.7%'],
            ['glow', 'GLOW', 'Cosmetic Blend', 'Peptide image 2.0/GLOW.jpeg', 'Specialized dermal research blend.', '>99.0%'],
            ['glutathione', 'Glutathione', 'Antioxidant', 'Peptide image 2.0/Glutathione.jpeg', 'Master antioxidant for cellular defense studies.', '>99.5%'],
            ['hcg', 'HCG', 'Hormone', 'Peptide image 2.0/HCG.jpeg', 'Human Chorionic Gonadotropin research grade.', '>99.0%'],
            ['hgh', 'HGH', 'Hormone', 'Peptide image 2.0/HGH.jpeg', 'Somatropin (Human Growth Hormone).', '>99.5%'],
            ['hmg', 'HMG', 'Hormone', 'Peptide image 2.0/HMG.jpeg', 'Human Menopausal Gonadotropin.', '>99.0%'],
            ['igf-lr3', 'IGF-1 LR3', 'Peptide', 'Peptide image 2.0/IGF-1LR3.jpeg', 'Long-acting insulin-like growth factor 1 analog.', '>98.8%'],
            ['igf-des', 'IGF-1 DES', 'Peptide', 'Peptide image 2.0/IGF-DES.jpeg', 'Truncated, highly potent insulin-like growth factor 1.', '>98.5%'],
            ['ipamorelin', 'Ipamorelin', 'Peptide', 'Peptide image 2.0/Ipamorelin.jpeg', 'Selective growth hormone secretagogue and ghrelin receptor agonist for longevity models.', '>98.8%'],
            ['klow', 'KLOW', 'Specialty', 'Peptide image 2.0/KLOW.jpeg', 'Proprietary research compound.', '>99.0%'],
            ['kpv', 'KPV', 'Peptide', 'Peptide image 2.0/KPV.jpeg', 'Anti-inflammatory tripeptide derived from alpha-MSH.', '>99.2%'],
            ['ll37', 'LL-37', 'Peptide', 'Peptide image 2.0/LL37.jpeg', 'Cathelicidin antimicrobial peptide.', '>98.5%'],
            ['lemon-bottle', 'Lemon Bottle', 'Lipolytic', 'Peptide image 2.0/Lemon Bottle.jpeg', 'Advanced lipolysis research solution.', '>99.0%'],
            ['mots-c', 'MOTS-C', 'Mitochondrial Peptide', 'Peptide image 2.0/MOTS-C.jpeg', 'Mitochondrial-derived peptide linked to metabolic regulation.', '>99.1%'],
            ['mt-2', 'Melanotan II', 'Peptide', 'Peptide image 2.0/MT-2.jpeg', 'Synthetic analog of alpha-melanocyte-stimulating hormone.', '>99.0%'],
            ['nad-plus', 'NAD+', 'Coenzyme', 'Peptide image 2.0/NAD+.jpeg', 'Nicotinamide adenine dinucleotide for cellular respiration studies.', '>99.5%'],
            ['pnc-27', 'PNC-27', 'Peptide', 'Peptide image 2.0/PNC-27.jpeg', 'Anti-cancer research peptide targeting HDM-2.', '>98.5%'],
            ['pt-141', 'Bremelanotide (PT-141)', 'Peptide', 'Peptide image 2.0/PT-141.jpeg', 'Melanocortin receptor agonist.', '>99.0%'],
            ['pinealon', 'Pinealon', 'Peptide', 'Peptide image 2.0/Pinealon.jpeg', 'Short peptide for brain function research.', '>98.8%'],
            ['retatrutide', 'Retatrutide', 'Triple Agonist', 'Peptide image 2.0/Reta.jpeg', 'GIP, GLP-1, and glucagon receptor triple agonist.', '>99.2%'],
            ['slu-pp-332', 'SLU-PP-332', 'ERR Agonist', 'Peptide image 2.0/SLU-PP-332.jpeg', 'Estrogen-related receptor agonist for metabolic studies.', '>98.5%'],
            ['ss-31', 'Elamipretide (SS-31)', 'Mitochondrial Peptide', 'Peptide image 2.0/SS-31.jpeg', 'Targets inner mitochondrial membrane.', '>99.0%'],
            ['selank', 'Selank', 'Peptide', 'Peptide image 2.0/Selank.jpeg', 'Synthetic analog of the human tetrapeptide tuftsin.', '>98.9%'],
            ['semaglutide', 'Semaglutide', 'GLP-1', 'Peptide image 2.0/Sema.jpeg', 'Long-acting GLP-1 receptor agonist. A staple compound in in-vitro metabolic and glycemic studies.', '>99.1%'],
            ['semax', 'Semax', 'Peptide', 'Peptide image 2.0/Semax.jpeg', 'Heptapeptide utilized for neuroprotection research.', '>98.8%'],
            ['snap-8', 'Snap-8', 'Cosmetic Peptide', 'Peptide image 2.0/Snap-8.jpeg', 'Octapeptide mimicking N-terminal end of SNAP-25.', '>99.0%'],
            ['survodutide', 'Survodutide', 'Dual Agonist', 'Peptide image 2.0/Survodutide.jpeg', 'Glucagon/GLP-1 receptor dual agonist.', '>99.1%'],
            ['tb-500', 'TB-500', 'Peptide', 'Peptide image 2.0/TB500.jpeg', 'Thymosin Beta-4 synthetic analog. Focus of cellular repair, angiogenesis, and recovery studies.', '>98.5%'],
            ['tesamorelin', 'Tesamorelin', 'Peptide', 'Peptide image 2.0/Tesam.jpeg', 'Synthetic GHRH analog for lipodystrophy research.', '>99.2%'],
            ['thymalin', 'Thymalin', 'Peptide', 'Peptide image 2.0/Thymalin.jpeg', 'Thymic peptide for immunoregulation studies.', '>98.8%'],
            ['ta1', 'Thymosin Alpha-1', 'Peptide', 'Peptide image 2.0/Thymosin-Alpha-1.jpeg', 'Major component of Thymosin Fraction 5.', '>99.0%'],
            ['tirzepatide', 'Tirzepatide', 'GLP-1/GIP', 'Peptide image 2.0/Tirz.jpeg', 'Novel dual GIP and GLP-1 receptor agonist highly sought after for advanced metabolic research.', '>99.5%'],
            ['vip', 'VIP', 'Peptide', 'Peptide image 2.0/VIP.jpeg', 'Vasoactive Intestinal Peptide.', '>98.5%']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO products (id, name, category, image_path, description, purity) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($initialProducts as $p) {
            $stmt->execute($p);
        }
    }

} catch (PDOException $e) {
    // Send a JSON error instead of exposing full DB credentials if it fails
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed. Ensure MySQL is running on your local server.',
        'error_details' => $e->getMessage()
    ]);
    exit;
}
