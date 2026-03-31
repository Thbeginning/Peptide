// upload_assets.js
// Uploads all peptide images and COA files to Supabase Storage,
// then updates image_path and coa_path in the products table.

const { createClient } = require('@supabase/supabase-js');
const fs = require('fs');
const path = require('path');

const SUPABASE_URL = 'https://ujwmzcdpilmgaiwrligq.supabase.co';
// Anon key is fine here — bucket INSERT policy is open to public
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVqd216Y2RwaWxtZ2Fpd3JsaWdxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQ1MzY0ODgsImV4cCI6MjA5MDExMjQ4OH0.-NwyaFNcAYS8lCiz_ofuV9KlsWyz-sWnxVCeGR992Rk';

const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

// Local directories
const IMAGES_DIR = path.join(__dirname, 'Peptide images');
const COA_DIR    = path.join(__dirname, 'COA');

// Supabase Storage bucket
const BUCKET = 'products';

// ─── helpers ────────────────────────────────────────────────────────────────

function getMimeType(filePath) {
  const ext = path.extname(filePath).toLowerCase();
  const map = {
    '.webp':  'image/webp',
    '.jpeg':  'image/jpeg',
    '.jpg':   'image/jpeg',
    '.jfif':  'image/jpeg',
    '.png':   'image/png',
    '.gif':   'image/gif',
    '.pdf':   'application/pdf',
  };
  return map[ext] || 'application/octet-stream';
}

async function uploadFile(localPath, storagePath) {
  const fileBuffer = fs.readFileSync(localPath);
  const contentType = getMimeType(localPath);
  const { error } = await supabase.storage
    .from(BUCKET)
    .upload(storagePath, fileBuffer, { contentType, upsert: true });
  if (error) {
    console.error(`  ❌  Upload failed: ${storagePath} — ${error.message}`);
    return null;
  }
  const { data } = supabase.storage.from(BUCKET).getPublicUrl(storagePath);
  return data.publicUrl;
}

// ─── image → product-id mapping ─────────────────────────────────────────────
// Key = filename without extension (lowercase), Value = product id in DB
const IMAGE_TO_PRODUCT = {
  'aod9604-400x400':                'aod9604-peptide',
  'ara-290-400x400':                'ara-290-peptide',
  'bpc-157-400x400':                'bpc-157-peptide',
  'bpc157-tb500-400x400':           'bpc-157-plus-tb500-peptide',
  'botulinum-toxin-400x400':        'botox-peptide',
  'cjc-1295-dac-400x400':           'cjc-1295-dac-peptide',
  'cjc-1295-no-dac-400x400':        'cjc-1295-no-dac-peptide',
  'cjc1295-no-dac-ipa-400x400':     'cjc-1295-no-dac-plus-ipamorelin-peptide',
  'dsip-400x400':                   'dsip-peptide',
  'epithalon-400x400':              'epithalon-peptide',
  'ghk-cu-400x400':                 'ghk-cu-peptide',
  'ghrp-6-400x400':                 'ghrp-6-peptide',
  'glow-400x400':                   'glow-peptide',
  'glutathione-400x400':            'glutathione-peptide',
  'hcg-400x400':                    'hcg-peptide',
  'hgh-400x400':                    'hgh-peptide',
  'hmg-400x400':                    'hmg-peptide',
  'igf-1lr3-400x400':               'igf-1-lr3-peptide',
  'igf-des-400x400 (1)':            'igf-des-peptide',
  'ipamorelin-400x400 (1)':         'ipamorelin-peptide',
  'klow-80mg-600x600':              'klow-peptide',
  'kpv-400x400 (1)':                'kpv-peptide',
  'll37-400x400':                   'll37-peptide',
  'lemon-bottle-400x400':           'lemon-bottle-peptide',
  'mots-c-400x400':                 'mots-c-peptide',
  'mt-2-400x400':                   'melanotan-ii-mt-2-peptide',
  'nad-400x400':                    'nadplus-peptide',
  'pnc-27-400x400':                 'pnc-27-peptide',
  'pt-141-400x400':                 'pt-141-peptide',
  'pinealon-400x400':               'pinealon-peptide',
  'reta-600x600':                   'reta-peptide',
  'slu-pp-332-400x400':             'slu-pp-332-peptide',
  'ss-31-400x400':                  'ss-31-peptide',
  'selank-400x400':                 'selank-peptide',
  'sema-400x400':                   'sema-peptide',
  'semax-400x400':                  'semax-peptide',
  'snap-8-400x400':                 'snap-8-peptide',
  'survodutide-400x400':            'survodutide-peptide',
  'tb500-400x400':                  'tb-500-peptide',
  'tesam-400x400':                  'tesam-peptide',
  'thymalin-400x400':               'thymalin-peptide',
  'thymosin-alpha-1-400x400':       'thymosin-alpha-1-peptide',
  'tirz-600x600':                   'tirz-peptide',
  'vip-400x400':                    'vip-peptide',
  'cagrilin-400x400':               'cagr-peptide',
  'cerebrolysim-400x400':           'cerebrolysin-peptide',
};

// ─── COA → product-id mapping ─────────────────────────────────────────────
const COA_TO_PRODUCT = {
  'aod9604 coa':                    'aod9604-peptide',
  'bpc157 coa (10mg)':              'bpc-157-peptide',
  'cjc-1295-no-dac coa (5mg)':      'cjc-1295-no-dac-peptide',
  'cjc1295 no dac+ipa coa':         'cjc-1295-no-dac-plus-ipamorelin-peptide',
  'cagrilin coa (10mg)':            'cagr-peptide',
  'dsip coa (5mg)':                 'dsip-peptide',
  'epithalon coa (10mg)':           'epithalon-peptide',
  'ghrp-6 coa (10mg)':              'ghrp-6-peptide',
  'glow coa (70mg)':                'glow-peptide',
  'glutathione coa (1500mg)':       'glutathione-peptide',
  'hcg coa (5000iu)':               'hcg-peptide',
  'igf-1lr3 coa (1mg)':             'igf-1-lr3-peptide',
  'ipamorelin coa (10mg)':          'ipamorelin-peptide',
  'klow coa':                       'klow-peptide',
  'mots-c coa (10mg)':              'mots-c-peptide',
  'mt-2 coa (10mg)':                'melanotan-ii-mt-2-peptide',
  'nad+ coa (500mg)':               'nadplus-peptide',
  'pinealon coa (5mg)':             'pinealon-peptide',
  'reta coa (10mg)':                'reta-peptide',
  'ss-31 coa (10mg)':               'ss-31-peptide',
  'selank coa (10mg)':              'selank-peptide',
  'sema coa (10mg)':                'sema-peptide',
  'semax coa (10mg)':               'semax-peptide',
  'tb500 coa (10mg)':               'tb-500-peptide',
  'tesam coa (10mg)':               'tesam-peptide',
  'tirz coa (30.54mg)':             'tirz-peptide',
};

// ─── main ────────────────────────────────────────────────────────────────────
async function main() {
  console.log('\n=== QingLi Peptide — Asset Upload & DB Update ===\n');

  // Bucket 'products' already exists and is public — no need to create it
  console.log(`ℹ️  Using existing storage bucket '${BUCKET}'\n`);

  // ── Upload product images ───────────────────────────────────────────────
  console.log('--- Uploading product images ---');
  const imageFiles = fs.readdirSync(IMAGES_DIR);
  const imageUpdates = {}; // productId → publicUrl

  for (const filename of imageFiles) {
    const key = path.basename(filename, path.extname(filename)).toLowerCase();
    const productId = IMAGE_TO_PRODUCT[key];
    if (!productId) {
      console.log(`  ⚠️  No mapping for image: ${filename}`);
      continue;
    }
    const storagePath = `products/${filename}`;
    const localPath   = path.join(IMAGES_DIR, filename);
    process.stdout.write(`  Uploading ${filename} … `);
    const url = await uploadFile(localPath, storagePath);
    if (url) {
      imageUpdates[productId] = url;
      console.log('✅');
    }
  }

  // ── Upload COA files ────────────────────────────────────────────────────
  console.log('\n--- Uploading COA files ---');
  const coaFiles = fs.readdirSync(COA_DIR);
  const coaUpdates = {}; // productId → publicUrl

  for (const filename of coaFiles) {
    const ext = path.extname(filename);
    // Skip non-image/non-pdf files (e.g. Logo.jfif, cover images)
    if (!['.webp','.jpg','.jpeg','.png','.pdf'].includes(ext.toLowerCase())) continue;
    // Skip known non-COA files
    if (filename.toLowerCase().includes('peptide product cover') ||
        filename.toLowerCase().includes('logo')) continue;

    const key = path.basename(filename, ext).toLowerCase().trim();
    const productId = COA_TO_PRODUCT[key];
    if (!productId) {
      console.log(`  ⚠️  No mapping for COA: ${filename}`);
      continue;
    }
    const storagePath = `coa-files/${filename}`;
    const localPath   = path.join(COA_DIR, filename);
    process.stdout.write(`  Uploading ${filename} … `);
    const url = await uploadFile(localPath, storagePath);
    if (url) {
      coaUpdates[productId] = url;
      console.log('✅');
    }
  }

  // ── Update database rows ────────────────────────────────────────────────
  console.log('\n--- Updating database ---');
  const allProductIds = new Set([
    ...Object.keys(imageUpdates),
    ...Object.keys(coaUpdates),
  ]);

  let successCount = 0;
  for (const productId of allProductIds) {
    const patch = {};
    if (imageUpdates[productId]) patch.image_path = imageUpdates[productId];
    if (coaUpdates[productId])   patch.coa_path   = coaUpdates[productId];

    const { error } = await supabase
      .from('products')
      .update(patch)
      .eq('id', productId);

    if (error) {
      console.error(`  ❌  Failed to update ${productId}: ${error.message}`);
    } else {
      console.log(`  ✅  Updated: ${productId}`);
      successCount++;
    }
  }

  console.log(`\n=== Done! Updated ${successCount} / ${allProductIds.size} products ===\n`);
}

main().catch(err => {
  console.error('Fatal error:', err);
  process.exit(1);
});
