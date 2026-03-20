<?php
require_once 'api/db.php';

$content = file_get_contents('raw_products.txt');
$blocks = preg_split('/(?=\n\d+\)\s)/', "\n" . $content);

$nameMap = [
    'KLOW' => ['KLOW'],
    'Tirz' => ['Tirzepatide'],
    'Reta' => ['Retatrutide'],
    'Tesam' => ['Tesamorelin'],
    'BPC157' => ['BPC-157', 'BPC157'],
    'TB500' => ['TB-500', 'TB500'],
    'BPC157+TB500' => ['BPC157 + TB500', 'BPC157+TB500'],
    'GLOW' => ['GLOW'],
    'BOTOX' => ['BOTOX'],
    'AOD9604' => ['AOD9604'],
    'NAD+' => ['NAD+'],
    'Epithalon' => ['Epithalon'],
    'Cagrilin' => ['Cagrilin'],
    'MT-2' => ['MT-2'],
    'MOTS-c' => ['MOTS-C'],
    'Pinealon' => ['Pinealon'],
    'Sema' => ['Semaglutide'],
    'VIP' => ['VIP'],
    'Thymosin-Alpha-1' => ['Thymosin Alpha-1', 'Thymosin-Alpha-1'],
    'Thymalin' => ['Thymalin'],
    'Survodutide' => ['Survodutide'],
    'SS-31' => ['SS-31'],
    'Snap-8' => ['Snap-8'],
    'SLU-PP-332' => ['SLU-PP-332'],
    'Semax' => ['Semax'],
    'Selank' => ['Selank'],
    'PT-141' => ['PT-141'],
    'PNC-27' => ['PNC-27'],
    'LL37' => ['LL37'],
    'Lemon Bottle' => ['Lemon Bottle'],
    'KPV' => ['KPV'],
    'Ipamorelin' => ['Ipamorelin'],
    'IGF-DES' => ['IGF-DES'],
    'IGF-1LR3' => ['IGF-1 LR3', 'IGF-1LR3'],
    'HMG' => ['HMG'],
    'HGH' => ['HGH'],
    'HCG' => ['HCG'],
    'Glutathione' => ['Glutathione'],
    'GHRP-6' => ['GHRP-6'],
    'GHK-CU' => ['GHK-CU'],
    'DSIP' => ['DSIP'],
    'CJC1295 NO DAC+IPA' => ['CJC1295 NO DAC + IPA', 'CJC1295 NO DAC+IPA'],
    'CJC-1295-NO-DAC' => ['CJC-1295-NO-DAC', 'CJC-1295 NO DAC'],
    'CJC-1295-DAC' => ['CJC-1295-DAC', 'CJC-1295 DAC'],
    'Cerebrolysim' => ['Cerebrolysin']
];

foreach ($blocks as $block) {
    if (trim($block) === '') continue;
    
    preg_match('/^\n?\d+\)\s*(.+)/', $block, $matches);
    $baseName = isset($matches[1]) ? trim($matches[1]) : '';
    if (!$baseName) continue;
    
    preg_match('/Specification:\s*(.+)/i', $block, $matches);
    $spec = isset($matches[1]) ? trim($matches[1]) : '';
    
    preg_match('/Purity:\s*(.+)/i', $block, $matches);
    $purity = isset($matches[1]) ? trim($matches[1]) : '';
    
    preg_match('/Form:\s*(.+)/i', $block, $matches);
    $form = isset($matches[1]) ? trim($matches[1]) : '';
    
    preg_match('/Storage:\s*(.+)/i', $block, $matches);
    $storage = isset($matches[1]) ? trim($matches[1]) : '';
    
    preg_match('/^\n?\d+\)\s*.*?\n(.*?)(?=\nCategories:)/s', $block, $matches);
    $shortDesc = isset($matches[1]) ? trim(str_replace("\n", " ", $matches[1])) : '';
    
    preg_match('/Overview:\s*\n(.*?)(?=\n💡|\n👥|💡|👥)/s', $block, $matches);
    $overview = isset($matches[1]) ? trim($matches[1]) : '';
    
    preg_match('/Applications:\s*\n(.*?)(?=\n👥|👥|\z)/s', $block, $matches);
    $applications = isset($matches[1]) ? trim($matches[1]) : '';
    
    preg_match('/Target Users:\s*\n(.*)/s', $block, $matches);
    $targetUsers = isset($matches[1]) ? trim($matches[1]) : '';
    
    // Clean up targeted sections
    $applications = preg_replace('/⚠️.*/s', '', $applications);
    $targetUsers = preg_replace('/⚠️.*/s', '', $targetUsers);

    $targetNames = $nameMap[$baseName] ?? [$baseName];
    
    foreach ($targetNames as $searchName) {
        $stmt = $pdo->prepare("UPDATE products SET description=?, specification=?, purity=?, form=?, storage=?, overview=?, applications=?, target_users=? WHERE name LIKE ?");
        $stmt->execute([$shortDesc, $spec, $purity, $form, $storage, $overview, $applications, $targetUsers, "%{$searchName}%"]);
    }
}
echo "Database Update Complete.\n";
?>
