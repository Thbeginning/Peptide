const fs = require('fs');

const SMARTSUPP_SCRIPT = `
    <!-- Smartsupp Live Chat -->
    <script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '132cc1b3e5e9ccf2e543bc80ff228a2f2288e394';
    window.smartsupp||(function(d) {
      var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
      s=d.getElementsByTagName('script')[0];c=d.createElement('script');
      c.type='text/javascript';c.charset='utf-8';c.async=true;
      c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
    </script>
    <noscript>Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript>`;

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
        if (content.includes('Smartsupp')) {
            console.log('Already has Smartsupp: ' + file);
            continue;
        }
        content = content.replace('</body>', SMARTSUPP_SCRIPT + '\n</body>');
        fs.writeFileSync(file, content, 'utf8');
        console.log('Added Smartsupp to: ' + file);
    } catch (e) {
        console.log('Error with ' + file + ': ' + e.message);
    }
}
console.log('Done!');
