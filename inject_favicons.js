// inject_favicons.js — add/update favicon <link> tags in all HTML files
const fs = require('fs');
const path = require('path');

const HTML_FILES = [
  'index.html',
  'products.html',
  'product.html',
  'refund-policy.html',
  'privacy-policy.html',
  'submit_review.html',
  'thank_you.html',
  path.join('admin', 'index.html'),
];

const FAVICON_BLOCK = `
    <!-- Favicon / Site Icon -->
    <link rel="icon" type="image/png" sizes="192x192" href="/Logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
    <link rel="shortcut icon" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">`;

// Patterns that might already exist
const OLD_FAVICON_PATTERNS = [
  /<link[^>]*rel=["'](?:shortcut )?icon["'][^>]*>/gi,
  /<link[^>]*rel=["']apple-touch-icon["'][^>]*>/gi,
  /<!-- Favicon[^>]*-->/gi,
];

let updated = 0;
for (const file of HTML_FILES) {
  const fullPath = path.join('d:\\Peptide', file);
  if (!fs.existsSync(fullPath)) { console.log(`⚠ Skipping (not found): ${file}`); continue; }

  let html = fs.readFileSync(fullPath, 'utf8');
  
  // Remove old favicon tags
  for (const pattern of OLD_FAVICON_PATTERNS) {
    html = html.replace(pattern, '');
  }

  // Insert after <head> or after <meta charset>
  if (html.includes('<meta charset')) {
    html = html.replace(/(<meta charset[^>]+>)/i, `$1${FAVICON_BLOCK}`);
  } else {
    html = html.replace(/<head>/i, `<head>${FAVICON_BLOCK}`);
  }

  fs.writeFileSync(fullPath, html, 'utf8');
  console.log(`✅ Updated: ${file}`);
  updated++;
}
console.log(`\nDone! Updated ${updated} files.`);
