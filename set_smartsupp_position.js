const fs = require('fs');

const OLD_SCRIPT = `var _smartsupp = _smartsupp || {};
    _smartsupp.key = '132cc1b3e5e9ccf2e543bc80ff228a2f2288e394';`;

const NEW_SCRIPT = `var _smartsupp = _smartsupp || {};
    _smartsupp.key = '132cc1b3e5e9ccf2e543bc80ff228a2f2288e394';
    _smartsupp.position = 'bl';`;

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
];

for (const file of files) {
    try {
        let content = fs.readFileSync(file, 'utf8');
        if (content.includes(OLD_SCRIPT)) {
            content = content.replace(OLD_SCRIPT, NEW_SCRIPT);
            fs.writeFileSync(file, content, 'utf8');
            console.log('Updated position: ' + file);
        } else if (content.includes('_smartsupp.position')) {
            console.log('Already set: ' + file);
        } else {
            console.log('Script not found: ' + file);
        }
    } catch (e) {
        console.log('Error: ' + file + ' - ' + e.message);
    }
}
console.log('Done!');
