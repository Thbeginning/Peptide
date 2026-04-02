const fs = require('fs');

const FLOATING_HTML = `
    <!-- Floating Contact Actions -->
    <div class="floating-contact-wrapper">
        <button onclick="if(typeof smartsupp==='function'){smartsupp('chat:open')}" class="floating-chat-item floating-chat-email" style="border:none;cursor:pointer;" title="Live Chat">
            <i class="fa-solid fa-comment-dots text-white"></i>
            <span class="floating-chat-tooltip glass-panel">Live Chat Support</span>
        </button>
        <a href="https://t.me/Qinglishagmoa" target="_blank" class="floating-chat-item floating-chat-telegram">
            <i class="fa-brands fa-telegram text-white"></i>
            <span class="floating-chat-tooltip glass-panel">Contact on Telegram</span>
        </a>
        <a href="https://wa.me/14239122216" target="_blank" class="floating-chat-item floating-chat-whatsapp">
            <i class="fa-brands fa-whatsapp text-white"></i>
            <span class="floating-chat-tooltip glass-panel">Inquire on WhatsApp</span>
        </a>
    </div>`;

// Pages that DON'T already have it
const files = [
    'd:/Peptide/products.html',
    'd:/Peptide/refund-policy.html',
    'd:/Peptide/privacy-policy.html',
    'd:/Peptide/submit_review.html',
    'd:/Peptide/thank_you.html',
];

for (const file of files) {
    try {
        let content = fs.readFileSync(file, 'utf8');
        if (content.includes('floating-contact-wrapper')) {
            console.log('Already has floating wrapper: ' + file);
            continue;
        }
        // Inject just before the Smartsupp script (which is just before </body>)
        content = content.replace('<!-- Smartsupp Live Chat -->', FLOATING_HTML + '\n    <!-- Smartsupp Live Chat -->');
        fs.writeFileSync(file, content, 'utf8');
        console.log('Added floating buttons to: ' + file);
    } catch (e) {
        console.log('Error: ' + file + ' - ' + e.message);
    }
}
console.log('Done!');
