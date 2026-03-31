const fs = require('fs');
const path = require('path');
const { createClient } = require('@supabase/supabase-js');

const SUPABASE_URL = 'https://ujwmzcdpilmgaiwrligq.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVqd216Y2RwaWxtZ2Fpd3JsaWdxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQ1MzY0ODgsImV4cCI6MjA5MDExMjQ4OH0.-NwyaFNcAYS8lCiz_ofuV9KlsWyz-sWnxVCeGR992Rk';
const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

const mdPath = path.join(__dirname, 'product description.md');
const content = fs.readFileSync(mdPath, 'utf8');

// Regex or manual splitting to find products
// It starts with e.g. "1) KLOW"
const blocks = content.split(/(?=\d+\)\s+[A-Za-z0-9\-\+]+)/);

// Map of names to product IDs from previous script
const PRODUCT_MAPPING = {
  'klow': 'klow-peptide',
  'tirz': 'tirz-peptide',
  'reta': 'reta-peptide',
  'sema': 'sema-peptide',
  'bpc-157': 'bpc-157-peptide',
  'bpc157': 'bpc-157-peptide',
  'bpc157+tb500': 'bpc-157-plus-tb500-peptide',
  'tb500': 'tb-500-peptide',
  'aod9604': 'aod9604-peptide',
  'cjc-1295 no dac': 'cjc-1295-no-dac-peptide',
  'cjc-1295 dac': 'cjc-1295-dac-peptide',
  'cjc1295 no dac+ipa': 'cjc-1295-no-dac-plus-ipamorelin-peptide',
  'ipamorelin': 'ipamorelin-peptide',
  'ghrp-6': 'ghrp-6-peptide',
  'igf-1lr3': 'igf-1-lr3-peptide',
  'igf-des': 'igf-des-peptide',
  'epithalon': 'epithalon-peptide',
  'mots-c': 'mots-c-peptide',
  'ss-31': 'ss-31-peptide',
  'pt-141': 'pt-141-peptide',
  'mt-2': 'melanotan-ii-mt-2-peptide',
  'dsip': 'dsip-peptide',
  'selank': 'selank-peptide',
  'semax': 'semax-peptide',
  'pinealon': 'pinealon-peptide',
  'pnc-27': 'pnc-27-peptide',
  'll37': 'll37-peptide',
  'kpv': 'kpv-peptide',
  'ara-290': 'ara-290-peptide',
  'vip': 'vip-peptide',
  'ghk-cu': 'ghk-cu-peptide',
  'snap-8': 'snap-8-peptide',
  'hcg': 'hcg-peptide',
  'hmg': 'hmg-peptide',
  'thymosin alpha-1': 'thymosin-alpha-1-peptide',
  'thymalin': 'thymalin-peptide',
  'nad+': 'nadplus-peptide',
  'glutathione': 'glutathione-peptide',
  'lemon bottle': 'lemon-bottle-peptide',
  'glow': 'glow-peptide',
  'botulinum toxin': 'botox-peptide',
  'cagrilin': 'cagr-peptide',
  'slu-pp-332': 'slu-pp-332-peptide',
  'survodutide': 'survodutide-peptide',
  'tesam': 'tesam-peptide',
  'hgh': 'hgh-peptide',
  'cerebrolysin': 'cerebrolysin-peptide',
};

function extractSection(text, titleStart, titleEndTokens) {
  const indexStart = text.indexOf(titleStart);
  if (indexStart === -1) return null;
  const contentStart = indexStart + titleStart.length;
  
  let indexEnd = text.length;
  if (titleEndTokens) {
    for (const token of titleEndTokens) {
      const idx = text.indexOf(token, contentStart);
      if (idx !== -1 && idx < indexEnd) {
        indexEnd = idx;
      }
    }
  }
  return text.substring(contentStart, indexEnd).trim();
}

async function run() {
  for (const block of blocks) {
    if (!block.trim()) continue;
    
    // e.g. "1) KLOW"
    const firstLineMatch = block.match(/^\d+\)\s+([^\n\r]+)/);
    if (!firstLineMatch) continue;
    
    let rawName = firstLineMatch[1].trim().toLowerCase();
    let productId = PRODUCT_MAPPING[rawName];
    if (!productId) {
      console.log('Skipping unmatched block: ' + rawName);
      continue;
    }

    const spec = extractSection(block, 'Specification:', ['\n', '\r']) || '';
    const purity = extractSection(block, 'Purity:', ['\n', '\r']) || '';
    const form = extractSection(block, 'Form:', ['\n', '\r']) || '';
    const storage = extractSection(block, 'Storage:', ['\n', '\r']) || '';

    const overview = extractSection(block, 'overview:', ['💡 Typical Research Applications:', '💡 Typical research application:', '💡 Research Application:', '👥 Target Users:']) || 
                     extractSection(block, 'Overview:', ['💡 Typical Research Applications:', '💡 Typical research application:', '💡 Research Application:', '👥 Target Users:']) || '';
    
    const apps = extractSection(block, '💡 Typical Research Applications:', ['👥 Target Users:']) || 
                 extractSection(block, '💡 Typical research application:', ['👥 Target Users:']) || 
                 extractSection(block, '💡 Research Application:', ['👥 Target Users:']) || '';
                 
    const targetUsers = extractSection(block, '👥 Target Users:', ['\n\n\n', '\n\n\d+\)', '---']) || '';

    console.log(`Processing: ${productId}`);

    const updatePlayload = {};
    if (spec) updatePlayload.specification = spec;
    if (purity) updatePlayload.purity = purity;
    if (form) updatePlayload.form = form;
    if (storage) updatePlayload.storage = storage;
    if (overview) updatePlayload.overview = overview;
    if (apps) updatePlayload.applications = apps;
    if (targetUsers) updatePlayload.target_users = targetUsers;

    console.log("Updating:", updatePlayload);
    const { error } = await supabase.from('products').update(updatePlayload).eq('id', productId);
    if (error) {
      console.error('Error updating ' + productId, error);
    } else {
      console.log('Updated ' + productId);
    }
  }
}

run();
