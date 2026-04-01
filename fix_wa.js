const fs = require('fs');
const path = require('path');

const OLD = '85292434470';
const NEW = '14239122216';

const files = [
    'd:/Peptide/index.html',
    'd:/Peptide/privacy-policy.html',
    'd:/Peptide/product.html',
    'd:/Peptide/products.html',
    'd:/Peptide/refund-policy.html',
    'd:/Peptide/submit_review.html',
    'd:/Peptide/thank_you.html',
    'd:/Peptide/admin/index.html',
    'd:/Peptide/admin/login.html',
    'd:/Peptide/script.js',
];

for (const file of files) {
    try {
        let content = fs.readFileSync(file, 'utf8');
        if (content.includes(OLD)) {
            const updated = content.split(OLD).join(NEW);
            fs.writeFileSync(file, updated, 'utf8');
            console.log('Updated: ' + file);
        }
    } catch (e) {
        console.log('Skipped (not found): ' + file);
    }
}
console.log('Done!');
