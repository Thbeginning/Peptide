const fs = require('fs');

const files = [
    'd:/Peptide/index.html',
    'd:/Peptide/product.html',
    'd:/Peptide/products.html',
    'd:/Peptide/refund-policy.html',
    'd:/Peptide/privacy-policy.html',
    'd:/Peptide/submit_review.html',
    'd:/Peptide/thank_you.html',
];

// New floating wrapper: WhatsApp only
const NEW_WRAPPER = `    <!-- Floating Contact Actions -->
    <div class="floating-contact-wrapper">
        <a href="https://wa.me/14239122216" target="_blank" class="floating-chat-item floating-chat-whatsapp">
            <i class="fa-brands fa-whatsapp text-white"></i>
            <span class="floating-chat-tooltip glass-panel">Inquire on WhatsApp</span>
        </a>
    </div>`;

for (const file of files) {
    try {
        let content = fs.readFileSync(file, 'utf8');

        // Replace the entire floating-contact-wrapper block
        // Match from the comment through to the closing </div>
        const regex = /[ \t]*<!--\s*Floating Contact Actions\s*-->[\s\S]*?<\/div>\s*(?=\n\s*(?:<!--|<div|<script|$))/;
        
        if (regex.test(content)) {
            content = content.replace(regex, NEW_WRAPPER);
            fs.writeFileSync(file, content, 'utf8');
            console.log('Updated: ' + file);
        } else {
            console.log('No match found in: ' + file);
        }
    } catch (e) {
        console.log('Error: ' + file + ' - ' + e.message);
    }
}
console.log('Done!');
