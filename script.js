// ==== PROFESSIONAL MARQUEE SYSTEM ====
class ProfessionalMarquee {
    constructor() {
        this.marquee = document.getElementById('customMarquee');
        this.marqueeInner = document.getElementById('customMarqueeInner');
        this.messages = [];
        this.settings = {
            speed: 30,
            color: '#0bbed6',
            theme: 'cyan',
            pauseOnHover: true,
            autoRefresh: true,
            refreshInterval: 10000
        };
        this.isLoading = false;
        this.refreshTimer = null;
        
        this.init();
    }
    
    init() {
        this.showLoading();
        this.fetchMarqueeData();
        this.setupAutoRefresh();
        this.setupKeyboardControls();
    }
    
    showLoading() {
        this.isLoading = true;
        this.marquee?.classList.add('loading');
    }
    
    hideLoading() {
        this.isLoading = false;
        this.marquee?.classList.remove('loading');
    }
    
    async fetchMarqueeData() {
        try {
            const { data: rows, error } = await supabaseClient
                .from('settings')
                .select('setting_key, setting_value');

            if (!error && rows) {
                const settings = {};
                rows.forEach(r => settings[r.setting_key] = r.setting_value);

                if (settings.contact_marquee) {
                    this.messages = settings.contact_marquee
                        .split(/\r?\n/)
                        .map(line => line.trim())
                        .filter(line => line.length > 0);
                }
                if (settings.marquee_speed) this.settings.speed = parseInt(settings.marquee_speed) || 30;
                if (settings.marquee_color) {
                    this.settings.color = settings.marquee_color;
                    this.settings.theme = this.getColorTheme(settings.marquee_color);
                }
            }

            if (this.messages.length === 0) {
                this.messages = [
                    '• GLOBAL EXPORT QUALITY',
                    '• GUARANTEED CUSTOMS CLEARANCE',
                    '• UNMATCHED PURITY STANDARDS',
                    '• 24/7 TECHNICAL SUPPORT',
                    '• DISCREET WORLDWIDE SHIPPING'
                ];
            }

            this.render();
            this.hideLoading();
        } catch (error) {
            console.error('Failed to fetch marquee data:', error);
            this.messages = [
                '• GLOBAL EXPORT QUALITY',
                '• GUARANTEED CUSTOMS CLEARANCE',
                '• UNMATCHED PURITY STANDARDS'
            ];
            this.render();
            this.hideLoading();
        }
    }
    
    getColorTheme(color) {
        const colorMap = {
            '#0bbed6': 'cyan',
            '#ffd700': 'gold',
            '#ff4444': 'red',
            '#44ff44': 'green'
        };
        return colorMap[color] || 'cyan';
    }
    
    render() {
        if (!this.marquee || !this.marqueeInner) return;
        
        // Hide if no messages
        if (this.messages.length === 0) {
            this.marquee.style.display = 'none';
            return;
        }
        
        // Clear existing content
        this.marqueeInner.innerHTML = '';
        
        // Apply theme classes
        this.marquee.className = 'custom-marquee';
        this.marquee.classList.add(`theme-${this.settings.theme}`);
        
        // Apply speed class
        if (this.settings.speed <= 15) {
            this.marquee.classList.add('very-fast');
        } else if (this.settings.speed <= 25) {
            this.marquee.classList.add('fast');
        } else if (this.settings.speed >= 45) {
            this.marquee.classList.add('slow');
        }
        
        // Set animation duration
        this.marqueeInner.style.animationDuration = `${this.settings.speed}s`;
        
        // Set color
        this.marqueeInner.style.color = this.settings.color;
        
        // Build HTML with enhanced formatting
        const html = this.messages.map((message, index) => {
            // Add icons for special messages
            let icon = '';
            if (message.includes('QUALITY')) icon = '🏆 ';
            else if (message.includes('SHIPPING')) icon = '📦 ';
            else if (message.includes('SUPPORT')) icon = '💬 ';
            else if (message.includes('CLEARANCE')) icon = '✅ ';
            
            return `<span class="custom-marquee-message" data-index="${index}">${icon}${message}</span>`;
        }).join('<span class="custom-marquee-sep"></span>');
        
        // Duplicate for continuous scrolling
        this.marqueeInner.innerHTML = html + html + html;
        
        // Show marquee
        this.marquee.style.display = 'flex';
        
        // Setup hover pause
        if (this.settings.pauseOnHover) {
            this.setupHoverPause();
        }
        
        // Add click handlers for messages
        this.setupMessageClickHandlers();
    }
    
    setupHoverPause() {
        this.marquee.addEventListener('mouseenter', () => {
            this.marqueeInner.style.animationPlayState = 'paused';
        });
        
        this.marquee.addEventListener('mouseleave', () => {
            this.marqueeInner.style.animationPlayState = 'running';
        });
    }
    
    setupMessageClickHandlers() {
        const messages = this.marqueeInner.querySelectorAll('.custom-marquee-message');
        messages.forEach(message => {
            message.addEventListener('click', () => {
                this.handleMessageClick(message.textContent);
            });
            message.style.cursor = 'pointer';
        });
    }
    
    handleMessageClick(messageText) {
        // Show message details in a subtle notification
        this.showNotification(messageText.replace(/[•🏆📦💬✅]/g, '').trim());
    }
    
    showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'marquee-notification glass-panel';
        notification.innerHTML = `
            <div style="padding: 1rem; text-align: center;">
                <i class="fa-solid fa-info-circle text-accent mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        notification.style.cssText = `
            position: fixed; top: 120px; right: 20px; z-index: 10000;
            max-width: 300px; animation: slideIn 0.3s ease;
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }
    
    setupAutoRefresh() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
        }
        
        if (this.settings.autoRefresh) {
            this.refreshTimer = setInterval(() => {
                this.fetchMarqueeData();
            }, this.settings.refreshInterval);
        }
    }
    
    setupKeyboardControls() {
        document.addEventListener('keydown', (e) => {
            // Admin controls (Ctrl+Shift+M)
            if (e.ctrlKey && e.shiftKey && e.key === 'M') {
                e.preventDefault();
                this.openAdminPanel();
            }
            
            // Pause/Play (Space when hovering)
            if (e.code === 'Space' && this.marquee.matches(':hover')) {
                e.preventDefault();
                const isPaused = this.marqueeInner.style.animationPlayState === 'paused';
                this.marqueeInner.style.animationPlayState = isPaused ? 'running' : 'paused';
            }
        });
    }
    
    openAdminPanel() {
        // Quick admin panel for marquee settings
        const panel = document.createElement('div');
        panel.className = 'marquee-admin-panel glass-panel-darker';
        panel.innerHTML = `
            <div style="padding: 2rem; max-width: 600px; margin: 0 auto;">
                <h3 style="color: white; margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-bullhorn text-accent mr-2"></i>
                    Marquee Admin Panel
                </h3>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="color: #aaa; display: block; margin-bottom: 0.5rem;">Messages (one per line):</label>
                    <textarea id="adminMarqueeMessages" style="width: 100%; height: 120px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 1rem; color: white; resize: vertical;">${this.messages.join('\n')}</textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="color: #aaa; display: block; margin-bottom: 0.5rem;">Speed (seconds):</label>
                        <input type="number" id="adminMarqueeSpeed" value="${this.settings.speed}" min="5" max="120" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 0.5rem; color: white;">
                    </div>
                    <div>
                        <label style="color: #aaa; display: block; margin-bottom: 0.5rem;">Color:</label>
                        <select id="adminMarqueeColor" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 0.5rem; color: white;">
                            <option value="#0bbed6" ${this.settings.color === '#0bbed6' ? 'selected' : ''}>Cyan</option>
                            <option value="#ffd700" ${this.settings.color === '#ffd700' ? 'selected' : ''}>Gold</option>
                            <option value="#ff4444" ${this.settings.color === '#ff4444' ? 'selected' : ''}>Red</option>
                            <option value="#44ff44" ${this.settings.color === '#44ff44' ? 'selected' : ''}>Green</option>
                        </select>
                    </div>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button onclick="this.closest('.marquee-admin-panel').remove()" style="padding: 0.5rem 1.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; color: white; cursor: pointer;">Cancel</button>
                    <button onclick="professionalMarquee.saveAdminSettings()" style="padding: 0.5rem 1.5rem; background: var(--accent-cyan); border: none; border-radius: 8px; color: black; font-weight: bold; cursor: pointer;">Save Changes</button>
                </div>
            </div>
        `;
        
        panel.style.cssText = `
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
            background: rgba(0,0,0,0.8); backdrop-filter: blur(10px); 
            display: flex; align-items: center; justify-content: center; 
            z-index: 10000; animation: fadeIn 0.3s ease;
        `;
        
        document.body.appendChild(panel);
    }
    
    async saveAdminSettings() {
        const messages = document.getElementById('adminMarqueeMessages').value
            .split('\n')
            .map(line => line.trim())
            .filter(line => line.length > 0);

        const speed = parseInt(document.getElementById('adminMarqueeSpeed').value) || 30;
        const color = document.getElementById('adminMarqueeColor').value;

        try {
            const upserts = [
                { setting_key: 'contact_marquee', setting_value: messages.join('\n') },
                { setting_key: 'marquee_speed', setting_value: String(speed) },
                { setting_key: 'marquee_color', setting_value: color }
            ];
            const { error } = await supabaseClient.from('settings').upsert(upserts, { onConflict: 'setting_key' });

            if (!error) {
                this.messages = messages;
                this.settings.speed = speed;
                this.settings.color = color;
                this.settings.theme = this.getColorTheme(color);
                this.render();
                document.querySelector('.marquee-admin-panel')?.remove();
                this.showNotification('Marquee settings saved successfully!');
            } else {
                alert('Error saving settings: ' + error.message);
            }
        } catch (err) {
            console.error('Error saving marquee settings:', err);
            alert('Error saving settings. Please try again.');
        }
    }
    
    // Public methods for external control
    updateMessages(messages) {
        this.messages = messages;
        this.render();
    }
    
    updateSpeed(speed) {
        this.settings.speed = speed;
        this.render();
    }
    
    updateColor(color) {
        this.settings.color = color;
        this.settings.theme = this.getColorTheme(color);
        this.render();
    }
    
    pause() {
        this.marqueeInner.style.animationPlayState = 'paused';
    }
    
    play() {
        this.marqueeInner.style.animationPlayState = 'running';
    }
    
    destroy() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
        }
    }
}

// Initialize professional marquee
let professionalMarquee;

// Interactive Effects for Calculator
function initInteractiveEffects() {
    console.log('Initializing interactive effects...');
    const featureCards = document.querySelectorAll('.feature-card');
    console.log('Found feature cards:', featureCards.length);
    
    featureCards.forEach((card, index) => {
        console.log(`Setting up card ${index + 1}`);
        
        // Mouse tracking for spotlight effect
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            
            card.style.setProperty('--mouse-x', `${x}%`);
            card.style.setProperty('--mouse-y', `${y}%`);
        });
        
        // Reset on mouse leave
        card.addEventListener('mouseleave', () => {
            card.style.setProperty('--mouse-x', '50%');
            card.style.setProperty('--mouse-y', '50%');
        });
        
        // Add particle burst on click
        card.addEventListener('click', (e) => {
            console.log('Card clicked - creating particle burst');
            createParticleBurst(e.clientX, e.clientY);
        });
    });
}

// Create particle burst effect
function createParticleBurst(x, y) {
    for (let i = 0; i < 12; i++) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            left: ${x}px;
            top: ${y}px;
            width: 4px;
            height: 4px;
            background: radial-gradient(circle, rgba(11, 190, 214, 0.8) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            animation: particleBurst ${1 + Math.random()}s ease-out forwards;
        `;
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 2000);
    }
}

// Add particle burst animation
const particleStyle = document.createElement('style');
particleStyle.textContent = `
    @keyframes particleBurst {
        0% {
            opacity: 1;
            transform: translate(0, 0) scale(0);
        }
        50% {
            opacity: 0.8;
            transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) scale(1);
        }
        100% {
            opacity: 0;
            transform: translate(${Math.random() * 200 - 100}px, ${Math.random() * 200 - 100}px) scale(0.5);
        }
    }
`;
document.head.appendChild(particleStyle);

// ====== EVENT LISTENERS ======
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded - starting initialization...');
    
    // 1. MUST RUN FIRST: Check Age Verification Status
    checkAgeVerification();
    
    professionalMarquee = new ProfessionalMarquee();
    
    // Force background immediately
    forceCinematicBackground();
    
    // Force background every 500ms
    setInterval(forceCinematicBackground, 500);
    
    // Also force on window resize
    window.addEventListener('resize', forceCinematicBackground);
    
    // Force on scroll with throttling
    let bgScrollTicking = false;
    window.addEventListener('scroll', () => {
        if (!bgScrollTicking) {
            window.requestAnimationFrame(() => {
                forceCinematicBackground();
                bgScrollTicking = false;
            });
            bgScrollTicking = true;
        }
    }, { passive: true });
    
    // Initialize interactive effects immediately
    initInteractiveEffects();
    
    // Also try again after a short delay
    setTimeout(() => {
        console.log('Delayed initialization of interactive effects...');
        initInteractiveEffects();
    }, 1000);
    
    // And again after content loads
    setTimeout(() => {
        console.log('Final initialization of interactive effects...');
        initInteractiveEffects();
    }, 2000);
});

// ====== Product Catalog Data ======
// Integrating real product images from Peptide image 2.0 folder
let products = [];

// Global State
let currentUser = null;
let cartItems = [];

// ====== Authentication & Client Portal Modal ======
function openAuthModal(tab = 'login') {
    const modal = document.getElementById('authModal');
    if (modal) {
        modal.classList.add('active');
        document.getElementById('loginError').style.display = 'none';
        document.getElementById('regError').style.display = 'none';
        switchAuthTab(tab);
    }
}

function closeAuthModal() {
    const modal = document.getElementById('authModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function switchAuthTab(tab) {
    document.querySelectorAll('.auth-tabs .auth-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('registerForm').style.display = 'none';
    
    if (tab === 'login') {
        document.querySelector('.auth-tabs .auth-tab:nth-child(1)').classList.add('active');
        document.getElementById('loginForm').style.display = 'block';
    } else {
        document.querySelector('.auth-tabs .auth-tab:nth-child(2)').classList.add('active');
        document.getElementById('registerForm').style.display = 'block';
    }
}

async function handleLogin(e) {
    e.preventDefault();
    const btn = document.getElementById('loginBtn');
    const btnText = document.getElementById('loginBtnText');
    const errDiv = document.getElementById('loginError');
    
    // Loading state
    if (btn) { btn.disabled = true; btn.style.opacity = '0.7'; }
    if (btnText) btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Authenticating...';
    if (errDiv) errDiv.style.display = 'none';

    try {
        const email = document.getElementById('loginEmail').value.trim();
        const password = document.getElementById('loginPassword').value;
        const { data, error } = await supabaseClient.auth.signInWithPassword({ email, password });

        if (error) {
            if (errDiv) { errDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-2"></i>${error.message}`; errDiv.style.display = 'block'; }
        } else {
            const role = data.user?.user_metadata?.role;
            if (role === 'admin') {
                window.location.href = 'admin/index.html';
            } else {
                closeAuthModal();
                checkAuthSession();
            }
        }
    } catch(err) {
        console.error(err);
        if (errDiv) { errDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-2"></i>' + (err.message || 'Connection error.'); errDiv.style.display = 'block'; }
    }

    // Reset button
    if (btn) { btn.disabled = false; btn.style.opacity = '1'; }
    if (btnText) btnText.innerHTML = 'SIGN IN';
}

async function handleRegister(e) {
    e.preventDefault();
    const btn = document.getElementById('regBtn');
    const btnText = document.getElementById('regBtnText');
    const errDiv = document.getElementById('regError');
    
    if (btn) { btn.disabled = true; btn.style.opacity = '0.7'; }
    if (btnText) btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Creating Account...';
    if (errDiv) errDiv.style.display = 'none';

    try {
        const name = document.getElementById('regName').value.trim();
        const email = document.getElementById('regEmail').value.trim();
        const password = document.getElementById('regPassword').value;

        // Step 1: Sign up the user
        const { data: signUpData, error: signUpError } = await supabaseClient.auth.signUp({
            email, password,
            options: { data: { name } }
        });

        if (signUpError) {
            // Handle duplicate email gracefully
            const msg = signUpError.message.toLowerCase();
            if (msg.includes('already registered') || msg.includes('user already registered') || msg.includes('already been registered')) {
                if (errDiv) { errDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-2"></i>This email is already registered. Please log in instead.'; errDiv.style.display = 'block'; }
            } else {
                if (errDiv) { errDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-2"></i>${signUpError.message}`; errDiv.style.display = 'block'; }
            }
            if (btn) { btn.disabled = false; btn.style.opacity = '1'; }
            if (btnText) btnText.innerHTML = 'CREATE ACCOUNT';
            return;
        }

        const userId = signUpData?.user?.id;

        // Step 2: Auto-confirm via Edge Function and insert into CRM arrays
        if (userId) {
            // Push to Client Directory
            try {
                await supabaseClient.from('users').upsert({ id: userId, name: name, email: email, last_login: new Date().toISOString() });
            } catch(e) { console.warn('Error syncing to users table:', e); }

            // Push to CRM Pipeline
            try {
                // Check if exists first to avoid duplicate CRM entry if user registers again
                const { data: existingCustomer } = await supabaseClient.from('customers').select('id').eq('email', email).single();
                if (!existingCustomer) {
                    await supabaseClient.from('customers').insert({ name: name, email: email, stage: 'new', value: 0 });
                }
            } catch(e) { console.warn('Error syncing to CRM:', e); }

            if (btnText) btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Setting up account...';
            try {
                await fetch('https://ujwmzcdpilmgaiwrligq.supabase.co/functions/v1/auto-confirm-user', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ userId })
                });
                // Give Postgres/Gotrue a moment to reflect the confirmed status
                await new Promise(r => setTimeout(r, 1500));
            } catch (fnErr) {
                console.warn('Auto-confirm function error (non-fatal):', fnErr);
            }
        }

        // Step 3: Sign in immediately — no email verification prompt
        if (btnText) btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Signing you in...';
        let loginAttempt = await supabaseClient.auth.signInWithPassword({ email, password });

        // Retry once if it fails due to propagation delay
        if (loginAttempt.error) {
            await new Promise(r => setTimeout(r, 2000));
            loginAttempt = await supabaseClient.auth.signInWithPassword({ email, password });
        }

        const loginError = loginAttempt.error;

        if (loginError) {
            // If sign-in fails (e.g., confirmation still pending), show success but ask to log in
            if (errDiv) {
                errDiv.innerHTML = '<i class="fa-solid fa-circle-check mr-2"></i>Account created! Please log in to continue.';
                errDiv.style.background = 'rgba(16,185,129,0.1)';
                errDiv.style.borderColor = 'rgba(16,185,129,0.35)';
                errDiv.style.color = '#34d399';
                errDiv.style.display = 'block';
            }
            switchAuthTab('login');
            // pre-fill the login email
            const loginEmailInput = document.getElementById('loginEmail');
            if (loginEmailInput) loginEmailInput.value = email;
        } else {
            // Success — close modal and update UI
            closeAuthModal();
            await checkAuthSession(); // Wait for session to be fully loaded
        }

    } catch(err) {
        console.error('Registration Exception:', err);
        if (errDiv) { errDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-2"></i>' + (err.message || 'Connection error.'); errDiv.style.display = 'block'; }
    }

    if (btn) { btn.disabled = false; btn.style.opacity = '1'; }
    if (btnText) btnText.innerHTML = 'CREATE ACCOUNT';
}


function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    const icon = btn.querySelector('i');
    if (icon) { icon.className = isPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'; }
}



async function logoutUser() {
    try {
        await supabaseClient.auth.signOut();
        currentUser = null;
        cartItems = [];
        renderCartUI();
        checkAuthSession();
    } catch(err) {
        console.error('Logout error', err);
    }
}
// ===============================================

// Provide products grid
let showingAllProducts = false;
let currentCategoryFilter = 'All';
let currentSearchQuery = '';
let currentPage = 1;
const itemsPerPage = 9;

async function fetchProducts() {
    const grid = document.getElementById('productGrid');
    if (grid) {
        grid.innerHTML = Array(6).fill('<div class="skeleton-card"></div>').join('');
    }

        // Contact info is now hardcoded in HTML. No dynamic overwrite needed.
        window.globalContactWhatsapp = '14239122216';
        window.globalSupportEmail = 'quiglipeptide@gmail.com';

    try {
        const { data, error } = await supabaseClient
            .from('products')
            .select('*')
            .order('name');

        if (error) throw error;

        products = data || [];
        console.log('Products loaded from Supabase:', products.length);

        setTimeout(() => {
            if (document.getElementById('productGrid') || document.getElementById('fullProductGrid')) {
                if (window.isCatalogPage) {
                    renderCatalogGrid();
                } else {
                    renderProducts(6);
                }
            }
            if (document.getElementById('dynamicProductGallery')) {
                renderProductDetail();
            }
        }, 500);
    } catch(e) {
        console.error('Error fetching products:', e);
        // Fallback to local hardcoded products for local preview before Supabase is configured
        products = [
            { id: "aod9604", name: "AOD9604", category: "Peptide", image_path: "COA/peptide product cover .jpeg", description: "Fat loss peptide fragment derived from hGH.", purity: ">99.0%" },
            { id: "bpc-tb-blend", name: "BPC 157 + TB500", category: "Peptide Blend", image_path: "COA/peptide product cover .jpeg", description: "Synergistic tissue repair and angiogenesis blend.", purity: ">99.0%" },
            { id: "bpc-157", name: "BPC-157", category: "Peptide", image_path: "COA/peptide product cover .jpeg", description: "Body Protection Compound. Synthetically produced sequence utilized extensively for tissue repair research.", purity: ">99.2%" },
            { id: "cjc-dac", name: "CJC-1295 DAC", category: "Peptide", image_path: "COA/peptide product cover .jpeg", description: "Long-acting GHRH analog with Drug Affinity Complex.", purity: ">98.8%" },
            { id: "cjc-nodac", name: "CJC-1295 NO DAC", category: "Peptide", image_path: "COA/peptide product cover .jpeg", description: "Shorter acting GHRH analog (Mod GRF 1-29).", purity: ">99.0%" },
            { id: "cjc-ipa", name: "CJC1295 NO DAC + IPA", category: "Peptide Blend", image_path: "COA/peptide product cover .jpeg", description: "Standard growth hormone secretagogue blend.", purity: ">99.0%" }
        ];
        
        setTimeout(() => {
            if (document.getElementById('productGrid') || document.getElementById('fullProductGrid')) {
                if (window.isCatalogPage) {
                    renderCatalogGrid();
                } else {
                    renderProducts(6);
                }
            }
            if (document.getElementById('dynamicProductGallery')) {
                renderProductDetail();
            }
        }, 500);
    }
}

// ====== Render Catalog Grid (Homepage & products.html) ======
function renderProducts(limit = null) {
    const grid = document.getElementById('productGrid');
    if (!grid) return;

    let displayProducts = products;
    if (limit) {
        displayProducts = products.slice(0, limit);
    }

    grid.innerHTML = displayProducts.map(product => `
        <article class="product-card glass-panel" data-id="${product.id}">
            <a href="product.html?id=${product.id}" class="product-img-wrapper" style="display:block;">
                <div class="product-badge">RUO</div>
                <img src="${product.image_path || product.image}" alt="${product.name}" class="product-img" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&q=80&w=400';">
            </a>
            <div class="product-content">
                <span class="product-category">${product.category}</span>
                <a href="product.html?id=${product.id}" style="text-decoration:none;"><h3 class="product-title">${product.name}</h3></a>
                <p class="product-desc text-muted text-sm" style="margin-bottom: 1.5rem;">${product.description || product.desc}</p>
                <div class="product-actions mt-auto" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <a href="product.html?id=${product.id}" class="btn glass-input" style="text-decoration: none; text-align: center; border: 1px solid var(--accent-blue); color: var(--accent-blue); padding: 0.5rem; transition: all 0.3s ease;">
                        Read more <i class="fa-solid fa-chevron-right ml-1" style="font-size: 0.75rem;"></i>
                    </a>
                    <button class="btn btn-primary glow-btn" style="padding: 0.5rem;" onclick="addToCart('${product.id}', '${product.name.replace(/'/g, "\\'")}')">
                        <i class="fa-solid fa-list-check mr-1"></i> Add
                    </button>
                </div>
            </div>
        </article>
    `).join('');

    if (products.length > limit) {
        grid.insertAdjacentHTML('afterend', `
            <div class="text-center mt-8">
                <a href="products.html" class="btn btn-outline glow-btn-outline btn-lg glass-panel mt-6" style="padding: 1rem 3rem;">
                    View All Products <i class="fa-solid fa-chevron-right ml-2"></i>
                </a>
            </div>
        `);
    }
}

function renderCatalogGrid() {
    const grid = document.getElementById('fullProductGrid');
    if (!grid) return; // If not on products page

    let filtered = products;

    // Apply Category Filter
    if (currentCategoryFilter !== 'All') {
        filtered = filtered.filter(p => p.category === currentCategoryFilter);
    }

    // Apply Search Filter
    if (currentSearchQuery) {
        const q = currentSearchQuery.toLowerCase();
        filtered = filtered.filter(p => 
            p.name.toLowerCase().includes(q) || 
            (p.description && p.description.toLowerCase().includes(q)) ||
            (p.id && p.id.toLowerCase().includes(q))
        );
    }

    if (filtered.length === 0) {
        grid.innerHTML = '<div style="grid-column: 1/-1; padding: 3rem; text-align: center; color: #64748b;">No products found matching your criteria.</div>';
        renderPagination(0);
        return;
    }

    const totalPages = Math.ceil(filtered.length / itemsPerPage);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

    const startIndex = (currentPage - 1) * itemsPerPage;
    const paginatedItems = filtered.slice(startIndex, startIndex + itemsPerPage);

    grid.innerHTML = paginatedItems.map(product => `
        <article class="product-card glass-panel" data-id="${product.id}">
            <a href="product.html?id=${product.id}" class="product-img-wrapper" style="display:block;">
                <div class="product-badge">RUO</div>
                <img src="${product.image_path || product.image}" alt="${product.name}" class="product-img" onerror="this.src='https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&q=80&w=400';">
            </a>
            <div class="product-content">
                <span class="product-category">${product.category}</span>
                <a href="product.html?id=${product.id}" style="text-decoration:none;"><h3 class="product-title">${product.name}</h3></a>
                <p class="product-desc text-muted text-sm" style="margin-bottom: 1.5rem;">${product.description || product.desc}</p>
                <div class="product-actions mt-auto" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <a href="product.html?id=${product.id}" class="btn glass-input" style="text-decoration: none; text-align: center; border: 1px solid var(--accent-blue); color: var(--accent-blue); padding: 0.5rem; transition: all 0.3s ease;">
                        Read more <i class="fa-solid fa-chevron-right ml-1" style="font-size: 0.75rem;"></i>
                    </a>
                    <button class="btn btn-primary glow-btn" style="padding: 0.5rem;" onclick="addToCart('${product.id}', '${product.name.replace(/'/g, "\\'")}')">
                        <i class="fa-solid fa-list-check mr-1"></i> Add
                    </button>
                </div>
            </div>
        </article>
    `).join('');

    renderPagination(totalPages);
}

function filterCategory(category) {
    if(event) {
        event.preventDefault();
        document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
        event.target.classList.add('active');
    }
    currentCategoryFilter = category;
    currentPage = 1; // Reset to page 1 on filter
    renderCatalogGrid();
}

function filterCatalog(query) {
    currentSearchQuery = query;
    currentPage = 1; // Reset to page 1 on search
    renderCatalogGrid();
}

function renderPagination(totalPages) {
    const paginationEl = document.getElementById('catalogPagination');
    if (!paginationEl) return;
    
    if (totalPages <= 1) {
        paginationEl.innerHTML = '';
        return;
    }

    let html = '';
    
    // Prev button
    if (currentPage > 1) {
        html += `<button class="page-btn" onclick="changePage(${currentPage - 1})"><i class="fa-solid fa-chevron-left text-sm mr-1"></i> Prev</button>`;
    }
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
    }
    
    // Next button
    if (currentPage < totalPages) {
        html += `<button class="page-btn" onclick="changePage(${currentPage + 1})">Next <i class="fa-solid fa-chevron-right text-sm ml-1"></i></button>`;
    }
    
    paginationEl.innerHTML = html;
}

function changePage(page) {
    currentPage = page;
    renderCatalogGrid();
    const container = document.querySelector('.catalog-container');
    if (container) {
        window.scrollTo({ top: container.offsetTop - 100, behavior: 'smooth' });
    }
}

// ====== Product Detail Page Logic ======
function renderProductDetail() {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');
        
        // Normalize string search safely
        const product = products.find(p => String(p.id).toLowerCase() === String(productId).toLowerCase() || String(p.name).toLowerCase() === String(productId).toLowerCase());

        if (!product) {
            const gallery = document.getElementById('dynamicProductGallery');
            const info = document.getElementById('dynamicProductInfo');
            if (gallery) gallery.innerHTML = '';
            if (info) info.innerHTML = '<div class="text-center py-8"><h2>Product not found in Catalog.</h2><a href="index.html#products" class="btn btn-primary mt-4">Back to Catalog</a></div>';
            return;
        }

        // Extract COAs safely
        let coas = [];
        try {
            if (product.coa_paths) {
                coas = JSON.parse(product.coa_paths) || [];
            } else if (product.coa_path) {
                coas = [product.coa_path];
            }
        } catch(e) { console.error('COA parse error'); }

        const mainImage = product.image_path || product.image || 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&q=80&w=400';

        // Build thumbnails mapping
        let thumbsHtml = `
            <div class="thumbnail-wrapper active" onclick="updateMainImage(this, '${mainImage.replace(/'/g, "\\'")}')">
                <img src="${mainImage}" alt="${product.name} Main">
            </div>
        `;

        coas.forEach((coa, index) => {
            if (typeof coa === 'string' && coa.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
                thumbsHtml += `
                <div class="thumbnail-wrapper" onclick="updateMainImage(this, '${coa.replace(/'/g, "\\'")}')">
                    <img src="${coa}" alt="COA ${index + 1}">
                </div>`;
            }
        });

        const galleryEl = document.getElementById('dynamicProductGallery');
        if (galleryEl) {
            galleryEl.innerHTML = `
                <div class="main-image-wrapper reveal-item">
                    <img id="mainImage" src="${mainImage}" alt="${product.name}" style="cursor: zoom-in;" onclick="window.open(this.src, '_blank')" title="Click to view full image">
                </div>
                <div class="thumbnail-row reveal-item delay-1">
                    ${thumbsHtml}
                </div>
            `;
        }

        // Right side layout building
        // Prepare blocks defensively
        const nameEscapedForCart = (product.name || '').replace(/'/g, "\\'");
        const purityStr = product.purity || '';
        const formStr = product.form || '';
        const specStr = product.specification || '';
        const storageStr = product.storage || '';
        const descStr = product.description || '';
        const overviewStr = (product.overview || '').replace(/\n/g, '<br>');
        
        let appsHtml = '';
        if (product.applications && typeof product.applications === 'string') {
            appsHtml = product.applications.split('\n').filter(l => l.trim().length > 0).map(l => l.startsWith('•') ? l : `• ${l}`).join('<br>');
        }
        
        let usersHtml = '';
        if (product.target_users && typeof product.target_users === 'string') {
            usersHtml = product.target_users.split('\n').filter(l => l.trim().length > 0).map(l => l.startsWith('✔️') || l.startsWith('✔') ? l : `✔️ ${l}`).join('<br>');
        }
        
        const contactPhone = window.globalContactWhatsapp;
        const waLink = `https://wa.me/${contactPhone}?text=${encodeURIComponent("Hello QingLi Team, I would like a wholesale quote for " + product.name)}`;

        const infoEl = document.getElementById('dynamicProductInfo');
        if (infoEl) {
            infoEl.innerHTML = `
                <div class="product-badges reveal-item delay-1">
                    <span class="badge badge-ruo">Research Use Only</span>
                    <span class="badge badge-instock">Categories: ${product.category || 'Peptide'}</span>
                </div>

                <h1 class="reveal-item delay-2">${product.name}</h1>

                <p class="product-short-desc reveal-item delay-3">
                    ${descStr}
                </p>

                <div class="product-action-area reveal-item delay-4">
                    <a href="${waLink}" target="_blank" class="quote-action-btn primary-quote-btn">
                        <i class="fa-brands fa-whatsapp btn-icon"></i> 
                        <span>Request Wholesale Quote</span>
                    </a>
                    
                    <button class="quote-action-btn secondary-quote-btn" onclick="addToCart('${product.id}', '${nameEscapedForCart}')">
                        <i class="fa-solid fa-cart-plus btn-icon"></i> 
                        <span>Add to Quote List</span>
                    </button>

                    <a href="index.html" class="quote-action-btn secondary-quote-btn home-link-btn">
                        <i class="fa-solid fa-arrow-left btn-icon"></i> 
                        <span>Back to Home</span>
                    </a>
                </div>
            `;
        }
        
        const fullDescEl = document.getElementById('dynamicProductFullDesc');
        if (fullDescEl) {
            let hasContent = overviewStr || appsHtml || usersHtml || specStr || purityStr || formStr || storageStr;
            if (hasContent) {
                fullDescEl.style.display = 'block';
                fullDescEl.innerHTML = `
                    <div class="reveal-item">
                        <h3 style="font-size: 2.5rem; color: var(--text-main); margin-bottom: 3rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.5rem; font-weight: 800; background: linear-gradient(135deg, #fff 0%, #94a3b8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Technical Specifications</h3>
                        
                        <div class="desc-grid">
                            <div class="desc-card">
                                <h4><i class="fa-solid fa-list-check"></i> Product Profile</h4>
                                <ul style="list-style: none; padding: 0; margin: 0; color: var(--text-muted); font-size: 1rem; line-height: 2;">
                                    <li class="flex justify-between border-b border-glass pb-2 mb-2"><strong>Name:</strong> <span class="text-white">${product.name}</span></li>
                                    ${specStr ? `<li class="flex justify-between border-b border-glass pb-2 mb-2"><strong>Specification:</strong> <span class="text-white">${specStr}</span></li>` : ''}
                                    ${purityStr ? `<li class="flex justify-between border-b border-glass pb-2 mb-2"><strong>Purity:</strong> <span class="text-white">${purityStr}</span></li>` : ''}
                                    ${formStr ? `<li class="flex justify-between border-b border-glass pb-2 mb-2"><strong>Form:</strong> <span class="text-white">${formStr}</span></li>` : ''}
                                    ${storageStr ? `<li class="flex justify-between"><strong>Storage:</strong> <span class="text-white">${storageStr}</span></li>` : ''}
                                </ul>
                            </div>

                            <div class="desc-card">
                                <h4><i class="fa-solid fa-microscope"></i> Overview</h4>
                                <p style="color: var(--text-muted); line-height: 1.8; font-size: 1rem; margin: 0;">${overviewStr || 'Technical data sheet for this compound is available upon request for verified research institutions.'}</p>
                            </div>

                            ${appsHtml ? `
                            <div class="desc-card">
                                <h4><i class="fa-solid fa-lightbulb"></i> Research Applications</h4>
                                <div style="color: var(--text-muted); line-height: 1.8; font-size: 1rem;">${appsHtml.replace(/•/g, '<i class="fa-solid fa-flask-vial text-accent mr-2" style="font-size: 0.8rem;"></i>')}</div>
                            </div>
                            ` : ''}

                            ${usersHtml ? `
                            <div class="desc-card">
                                <h4><i class="fa-solid fa-user-doctor"></i> Target Users</h4>
                                <div style="color: #10b981; font-weight: 500; line-height: 1.8; font-size: 1rem;">${usersHtml.replace(/✔️/g, '<i class="fa-solid fa-check-double mr-2"></i>')}</div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            } else {
                fullDescEl.style.display = 'none';
            }
        }

        document.title = `${product.name} - Qingli Peptide`;
    } catch (err) {
        console.error("Critical error in renderProductDetail:", err);
        const info = document.getElementById('dynamicProductInfo');
        if (info) {
            info.innerHTML = '<div class="text-center py-8 text-danger"><h2>Failed to load product details due to an internal error.</h2><p class="text-muted mt-4">Please contact support.</p></div>';
        }
    }
}

function updateMainImage(thumbEl, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail-wrapper').forEach(t => t.classList.remove('active'));
    thumbEl.classList.add('active');
}

function toggleReadMore() {
    const sec = document.getElementById('fullDescriptionSection');
    const btn = document.getElementById('readMoreBtn');
    if (sec.style.display === 'none') {
        sec.style.display = 'block';
        btn.innerHTML = 'Show Less <i class="fa-solid fa-chevron-up ml-2"></i>';
    } else {
        sec.style.display = 'none';
        btn.innerHTML = 'Read More <i class="fa-solid fa-chevron-down ml-2"></i>';
    }
}

async function checkAuthSession() {
    try {
        const { data: { session } } = await supabaseClient.auth.getSession();

        if (session && session.user) {
            const user = session.user;
            currentUser = {
                id: user.id,
                name: user.user_metadata?.name || user.email.split('@')[0],
                email: user.email,
                role: user.user_metadata?.role || 'customer'
            };

            const authGuest = document.getElementById('authGuest');
            const authLogged = document.getElementById('authLogged');
            if (authGuest) authGuest.style.display = 'none';
            if (authLogged) authLogged.style.display = 'flex';

            const navUserName = document.getElementById('navUserName');
            if (navUserName) navUserName.innerText = currentUser.name;

            const adminLink = document.getElementById('adminDashboardLink');
            if (adminLink) adminLink.style.display = currentUser.role === 'admin' ? 'block' : 'none';

            const mobGuest = document.getElementById('mobileAuthGuest');
            const mobLogged = document.getElementById('mobileAuthLogged');
            const mobUserName = document.getElementById('mobileNavUserName');
            if (mobGuest) mobGuest.style.display = 'none';
            if (mobLogged) mobLogged.style.display = 'block';
            if (mobUserName) mobUserName.innerText = currentUser.name;

            const sbGuest = document.getElementById('cartSidebarGuest');
            const sbLogged = document.getElementById('cartSidebarLogged');
            const sbUserName = document.getElementById('cartSidebarUserName');
            if (sbGuest) sbGuest.style.display = 'none';
            if (sbLogged) sbLogged.style.display = 'flex';
            if (sbUserName) sbUserName.innerText = currentUser.name;

            fetchCart();
        } else {
            currentUser = null;
            const authGuest = document.getElementById('authGuest');
            const authLogged = document.getElementById('authLogged');
            if (authGuest) authGuest.style.display = 'flex';
            if (authLogged) authLogged.style.display = 'none';

            const mobGuest = document.getElementById('mobileAuthGuest');
            const mobLogged = document.getElementById('mobileAuthLogged');
            if (mobGuest) mobGuest.style.display = 'block';
            if (mobLogged) mobLogged.style.display = 'none';

            const sbGuest = document.getElementById('cartSidebarGuest');
            const sbLogged = document.getElementById('cartSidebarLogged');
            if (sbGuest) sbGuest.style.display = 'block';
            if (sbLogged) sbLogged.style.display = 'none';
        }
    } catch (e) {
        console.error('Error checking auth:', e);
        currentUser = null;
    }
}

// Note: handleLogin is defined above near line 533 and used for both login forms.
// This duplicate definition is intentionally removed — the active one is above.

// Note: handleRegister is defined above near line 566. This duplicate is removed.

// logoutUser is defined above near line 600. This duplicate is removed.

// ====== CART LOGIC ======
async function addToCart(productId, productName) {
    if (!currentUser) { openAuthModal(); return; }

    try {
        // Check if item already in cart
        const { data: existing } = await supabaseClient
            .from('cart_items')
            .select('id, quantity')
            .eq('user_id', currentUser.id)
            .eq('product_id', productId)
            .single();

        if (existing) {
            await supabaseClient.from('cart_items').update({ quantity: existing.quantity + 1 }).eq('id', existing.id);
        } else {
            await supabaseClient.from('cart_items').insert({
                user_id: currentUser.id,
                product_id: productId,
                product_name: productName,
                quantity: 1
            });
        }

        fetchCart();

        // 1. Give visual feedback on the general button if in catalog
        const btn = document.querySelector(`.product-card[data-id="${productId}"] .btn-primary`);
        if (btn) {
            const standardText = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-check mr-2"></i> Added`;
            btn.style.background = 'var(--success-green)';
            setTimeout(() => { btn.innerHTML = standardText; btn.style.background = ''; }, 1500);
        }

        // 2. Also automatically open the cart so the user can verify
        document.getElementById('cartSidebar').classList.add('active');
        document.getElementById('cartOverlay').classList.add('active');
        
        // Ensure body scrolling is disabled when sidebar is open
        document.body.style.overflow = 'hidden';

    } catch (e) { console.error(e); }
}

async function fetchCart() {
    if (!currentUser) return;
    try {
        const { data, error } = await supabaseClient
            .from('cart_items')
            .select('*')
            .eq('user_id', currentUser.id)
            .order('added_at');
        if (!error) { cartItems = data || []; renderCartUI(); }
    } catch (e) { console.error(e); }
}

async function removeFromCart(itemId) {
    try {
        const { error } = await supabaseClient.from('cart_items').delete().eq('id', itemId);
        if (!error) fetchCart();
    } catch (e) { console.error(e); }
}

async function updateCartQuantity(itemId, newQty) {
    if (newQty < 1) { await removeFromCart(itemId); return; }
    try {
        const { error } = await supabaseClient.from('cart_items').update({ quantity: newQty }).eq('id', itemId);
        if (!error) fetchCart();
    } catch (e) { console.error(e); }
}

function renderCartUI() {
    document.getElementById('cartCount').innerText = cartItems.length;
    
    const container = document.getElementById('cartItemsContainer');
    if (cartItems.length === 0) {
        container.innerHTML = `<div class="text-center text-muted mt-8"><i class="fa-solid fa-list-check fa-3x mb-4 opacity-50"></i><p>Your quote list is empty.</p></div>`;
        return;
    }
    
    container.innerHTML = cartItems.map(item => {
        // find image from master array
        const product = products.find(p => p.id === item.product_id);
        const img = product ? (product.image_path || product.image) : 'https://images.unsplash.com/photo-1532094349884-543bc11b234d';
        
        return `
        <div class="cart-item">
            <img src="${img}" class="cart-item-img">
            <div class="cart-item-details">
                <h4 class="cart-item-title">${item.product_name}</h4>
                <div class="flex-between mt-2">
                    <div class="qty-controls">
                        <button class="qty-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})"><i class="fa-solid fa-minus"></i></button>
                        <span class="qty-display">${item.quantity}</span>
                        <button class="qty-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <button class="cart-item-remove" onclick="removeFromCart(${item.id})"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>
        `;
    }).join('');
}

function checkoutCart() {
    if(cartItems.length === 0) { return alert("Quote list is empty."); }
    document.getElementById('inqName').value = currentUser?.name || '';
    document.getElementById('cartSidebar').classList.remove('active');
    document.getElementById('cartOverlay').classList.remove('active');
    document.getElementById('inquiryModal').classList.add('active');
}

function closeInquiryModal() {
    document.getElementById('inquiryModal').classList.remove('active');
}

async function submitInquiry(e) {
    e.preventDefault();
    if (!currentUser) { openAuthModal(); return; }

    const btn = e.target.querySelector('button[type="submit"]');
    const oldText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
    btn.disabled = true;

    try {
        const nameVal = document.getElementById('inqName').value || currentUser.name || "A customer";
        const cityVal = document.getElementById('inqCity').value;
        const countryVal = document.getElementById('inqCountry').value;

        // 1. Create the quote request
        const { data: quote, error: qErr } = await supabaseClient
            .from('quote_requests')
            .insert({
                user_id: currentUser.id,
                customer_name: nameVal,
                shipping_city: cityVal,
                shipping_country: countryVal,
                contact_method: document.getElementById('inqContactMethod').value,
                contact_detail: document.getElementById('inqContactDetail').value,
                message: document.getElementById('quoteMessage').value,
                research_only_confirmed: document.getElementById('researchDisclaimer').checked,
                status: 'Pending'
            })
            .select()
            .single();

        if (qErr) throw qErr;

        // 2. Insert quote items
        if (cartItems.length > 0) {
            const items = cartItems.map(item => ({
                quote_id: quote.id,
                product_id: item.product_id,
                product_name: item.product_name,
                quantity: item.quantity
            }));
            await supabaseClient.from('quote_request_items').insert(items);
        }

        // 3. Clear the cart
        await supabaseClient.from('cart_items').delete().eq('user_id', currentUser.id);

        document.getElementById('inquiryModal').classList.remove('active');
        const successMsg = document.getElementById('successMessageText');
        if (successMsg) successMsg.innerText = 'Your quote request has been submitted! Redirecting to WhatsApp...';
        document.getElementById('successModal').classList.add('active');
        e.target.reset();
        fetchCart();
        
        // 4. Redirect to WhatsApp
        let waNumber = window.globalContactWhatsapp || '14239122216';
        waNumber = waNumber.replace(/[^0-9]/g, '');
        const customMessage = `Hello Qingli Peptide! My name is ${nameVal} from ${cityVal}, ${countryVal}. I have just submitted a quote request on the website. Please check your admin dashboard!`;
        const waUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent(customMessage)}`;
        
        setTimeout(() => {
            window.location.href = waUrl;
        }, 1500);

    } catch(err) {
        console.error(err);
        alert('Error submitting quote: ' + (err.message || 'Unknown error'));
    } finally {
        btn.innerHTML = oldText;
        btn.disabled = false;
    }
}

// ====== MODAL UI LOGIC ======
function toggleCart() {
    document.getElementById('cartSidebar').classList.toggle('active');
    document.getElementById('cartOverlay').classList.toggle('active');
}

function toggleMobileMenu() {
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('mobileOverlay');
    const trigger = document.getElementById('mobileTrigger');
    
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    if (trigger) trigger.classList.toggle('active');
    
    // Lock body scroll
    if (sidebar.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// ====== CUSTOMER DASHBOARD / QUOTES ======
function openMyAccount() {
    if (!currentUser) return openAuthModal();
    document.getElementById('accountModal').classList.add('active');
    fetchUserQuotes();
}

async function fetchUserQuotes() {
    const container = document.getElementById('accountQuotesContainer');
    if (!currentUser) return;
    try {
        const { data: quotes, error } = await supabaseClient
            .from('quote_requests')
            .select('*, quote_request_items(*)')
            .eq('user_id', currentUser.id)
            .order('created_at', { ascending: false });

        if (error) throw error;

        const data = { status: 'success', quotes: (quotes || []).map(q => ({ ...q, items: q.quote_request_items || [] })) };

        if (data.status === 'success') {
            if (data.quotes.length === 0) {
                container.innerHTML = `<div class="text-center text-muted py-8"><i class="fa-solid fa-folder-open fa-3x mb-4 opacity-50"></i><p>You have no past quote requests.</p></div>`;
            } else {
                container.innerHTML = data.quotes.map(q => {
                    const d = new Date(q.created_at).toLocaleDateString();
                    // Colors
                    let statusColor = 'var(--text-muted)';
                    if(q.status === 'Under Review' || q.status === 'Pending') statusColor = 'var(--accent-blue)';
                    if(q.status === 'Paid' || q.status === 'Shipped' || q.status === 'Contacted') statusColor = 'var(--success-green)';
                    if(q.status === 'Cancelled') statusColor = 'var(--danger-red)';

                    // Items snippet
                    const itemsHtml = q.items.map(i => `<div class="text-sm text-muted">- ${i.product_name} (<span class="text-accent">x${i.quantity}</span>)</div>`).join('');

                    return `
                    <div class="glass-panel p-4 mb-4" style="background: rgba(0,0,0,0.3); border-color: var(--border-glass-light);">
                        <div class="flex-between mb-4" style="border-bottom: 1px solid var(--border-glass-light); padding-bottom: 0.5rem;">
                            <h4 class="m-0 text-white">Quote #${q.id}</h4>
                            <span class="badge text-sm" style="background: rgba(255,255,255,0.05); padding: 0.25rem 0.75rem; border-radius: 1rem; color: ${statusColor}; border: 1px solid ${statusColor}; font-weight: bold;">${q.status}</span>
                        </div>
                        <div class="mb-4">
                            <strong class="text-sm text-white mb-2 block">Requested Items:</strong>
                            ${itemsHtml}
                        </div>
                        <div class="flex-between mt-4">
                            <span class="text-xs text-muted"><i class="fa-solid fa-clock mr-1"></i> Submitted: ${d}</span>
                            <button class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.85rem;" onclick="duplicateQuote(${q.id})"><i class="fa-solid fa-copy mr-2"></i> Duplicate Quote</button>
                        </div>
                    </div>
                    `;
                }).join('');
            }
        } else {
            container.innerHTML = `<div class="text-center text-danger py-8">Failed to load history.</div>`;
        }
    } catch(e) {
        console.error(e);
        container.innerHTML = `<div class="text-center text-danger py-8">Server Error.</div>`;
    }
}

async function duplicateQuote(quoteId) {
    if (!confirm("Add exactly these items to your active Quote List? It will merge with any existing items you have.")) return;
    if (!currentUser) return;

    try {
        const { data: items, error } = await supabaseClient
            .from('quote_request_items')
            .select('*')
            .eq('quote_id', quoteId);

        if (error) throw error;

        if (items && items.length > 0) {
            const cartInserts = items.map(i => ({
                user_id: currentUser.id,
                product_id: i.product_id,
                product_name: i.product_name,
                quantity: i.quantity
            }));
            await supabaseClient.from('cart_items').insert(cartInserts);
        }

        document.getElementById('accountModal').classList.remove('active');
        fetchCart();
        setTimeout(() => toggleCart(), 300);
    } catch(e) { console.error(e); }
}

function openContactModal(e) {
    if(e) e.preventDefault();
    document.getElementById('contactModal').classList.add('active');
}

// ====== Calculator Logic ======
function calculateDose() {
    const syringeSizeType = parseFloat(document.getElementById('syringeSize').value);
    const peptideMg = parseFloat(document.getElementById('peptideAmount').value);
    const waterMl = parseFloat(document.getElementById('waterAmount').value);
    const doseAmount = parseFloat(document.getElementById('desiredDose').value);
    const doseUnit = document.getElementById('doseUnit')?.value || 'mcg';

    const resultDiv = document.getElementById('calcResult');
    const pullUnitsEl = document.getElementById('pullUnits');
    const visualLiquid = document.getElementById('visualLiquid');
    const resSyringe = document.getElementById('resSyringe');
    const resConcentration = document.getElementById('resConcentration');
    const resVolume = document.getElementById('resVolume');
    const errBox = document.getElementById('calcError');
    const errText = document.getElementById('calcErrorText');

    // Always show the result panel
    resultDiv.style.display = 'block';
    errBox.style.display = 'none';

    // Validation
    if (!peptideMg || !waterMl || !doseAmount || isNaN(peptideMg) || isNaN(waterMl) || isNaN(doseAmount)) {
        pullUnitsEl.innerHTML = '<span style="font-size:1.5rem;">⚠</span>';
        errText.textContent = 'Please fill in all fields with valid numbers.';
        errBox.style.display = 'block';
        visualLiquid.style.width = '0%';
        return;
    }
    if (peptideMg <= 0 || waterMl <= 0 || doseAmount <= 0) {
        errText.textContent = 'All values must be greater than zero.';
        errBox.style.display = 'block';
        return;
    }

    // Calculations
    const syringeVolumeMl = syringeSizeType === 50 ? 0.5 : (syringeSizeType === 30 ? 0.3 : 1.0);
    const doseMg = doseUnit === 'mcg' ? doseAmount / 1000 : doseAmount;
    const concentrationMgPerMl = peptideMg / waterMl;
    const volumeToInjectMl = doseMg / concentrationMgPerMl;
    const unitsToPull = volumeToInjectMl * 100; // IU on syringe
    const maxTicks = syringeSizeType;
    const percentage = Math.min((unitsToPull / maxTicks) * 100, 100);

    // Populate secondary stats
    if (resConcentration) resConcentration.textContent = concentrationMgPerMl.toFixed(3);
    if (resVolume) resVolume.textContent = volumeToInjectMl.toFixed(4);
    if (resSyringe) resSyringe.textContent = syringeVolumeMl + ' mL syringe';

    // Check if dose exceeds syringe capacity
    if (unitsToPull > maxTicks) {
        pullUnitsEl.innerHTML = '<span style="color:#ef4444;font-size:2rem;">TOO HIGH</span>';
        visualLiquid.style.width = '100%';
        visualLiquid.style.background = 'linear-gradient(90deg,#dc2626,#ef4444)';
        errText.textContent = `Dose (${unitsToPull.toFixed(1)} IU) exceeds your syringe capacity (${maxTicks} IU). Use a larger syringe or split the dose.`;
        errBox.style.display = 'block';
    } else {
        const roundedTicks = Math.round(unitsToPull * 10) / 10;
        pullUnitsEl.textContent = roundedTicks;
        pullUnitsEl.style.color = '';
        visualLiquid.style.width = percentage + '%';
        visualLiquid.style.background = percentage > 80
            ? 'linear-gradient(90deg,#d97706,#fbbf24)'
            : 'linear-gradient(90deg,#0284c7,#0bbed6)';
    }
}


// ====== Verification Tool ======
async function handleVerify(event) {
    event.preventDefault();
    const inputField = document.getElementById('repId');
    const resultDiv = document.getElementById('verifyResult');
    const repId = inputField.value.trim().toUpperCase();
    
    resultDiv.className = 'verify-result glass-panel-darker mt-6 max-w-2xl mx-auto';
    if (repId === '') return;
    
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-accent"></i> Checking secure database...';
    
    try {
        const { data: repRow, error: repErr } = await supabase
            .from('representatives')
            .select('*')
            .eq('rep_id', repId)
            .single();
        const data = repErr ? { status: 'error' } : { status: 'success', rep: repRow };
        
        // Brief artificial delay for realistic secure checking feel
        setTimeout(() => {
            if (data.status === 'success' && data.rep) {
                const rep = data.rep;
                
                if (rep.status === 'Suspended') {
                    resultDiv.className = 'verify-result error glass-panel mt-6 max-w-2xl mx-auto';
                    resultDiv.style.borderLeft = '4px solid var(--warning-yellow, #eab308)';
                    resultDiv.innerHTML = `
                        <div class="flex" style="align-items:center; gap:0.5rem; margin-bottom:0.75rem; color: #eab308;">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size:1.5rem;"></i>
                            <strong style="font-size:1.125rem;">Suspended Representative</strong>
                        </div>
                        <p style="margin:0; font-size:0.95rem; color:var(--text-muted);">The ID <strong class="text-white">${repId}</strong> belongs to an agent whose account is currently suspended. Please DO NOT proceed.</p>`;
                } else {
                    resultDiv.className = 'verify-result success glass-panel mt-6 max-w-2xl mx-auto';
                    resultDiv.style.borderLeft = '4px solid var(--success-green)';
                    resultDiv.innerHTML = `
                        <div class="flex" style="align-items:center; gap:0.5rem; margin-bottom:0.75rem; color:var(--success-green);">
                            <i class="fa-solid fa-circle-check" style="font-size:1.5rem;"></i>
                            <strong style="font-size:1.125rem;">Official Rep confirmed. It is safe to proceed with your inquiry.</strong>
                        </div>
                        <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 0.5rem; font-size: 0.95rem;">
                            <strong class="text-white">Agent Name:</strong> <span class="text-muted">${rep.name}</span><br>
                            <strong class="text-white">Territory assigned:</strong> <span class="text-muted">${rep.territory}</span><br>
                            <p class="mt-2 text-sm text-muted m-0"><i class="fa-solid fa-lock text-accent mr-2"></i>You are securely communicating with an authorized Qingli Peptide representative.</p>
                        </div>`;
                }
            } else {
                resultDiv.className = 'verify-result error glass-panel mt-6 max-w-2xl mx-auto';
                resultDiv.style.borderLeft = '4px solid var(--danger-red)';
                resultDiv.innerHTML = `
                    <div class="flex" style="align-items:center; gap:0.5rem; margin-bottom:0.75rem; color:var(--danger-red);">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size:1.5rem;"></i>
                        <strong style="font-size:1.125rem;">Warning: Not Found</strong>
                    </div>
                    <p style="margin:0; font-size:0.95rem; color:var(--text-muted);">ID not recognized. DO NOT SEND FUNDS. Report this person to us immediately.</p>`;
            }
        }, 800);
    } catch (e) {
        console.error('Verification error', e);
        resultDiv.innerHTML = '<span class="text-danger">A connection error occurred. Please try again later.</span>';
    }
}

// Search Autocomplete Handler
function handleSearchAutocomplete(query) {
    const resultsContainer = document.getElementById('searchAutocomplete');
    if (!resultsContainer) return;

    if (!query || query.trim().length < 2) {
        resultsContainer.classList.remove('has-content');
        resultsContainer.innerHTML = '';
        return;
    }

    const filtered = products.filter(p => 
        (p.name && p.name.toLowerCase().includes(query.toLowerCase())) || 
        (p.category && p.category.toLowerCase().includes(query.toLowerCase()))
    ).slice(0, 6);

    if (filtered.length === 0) {
        resultsContainer.innerHTML = '<div class="p-6 text-center text-muted text-sm font-medium">No molecular matches found.</div>';
    } else {
        resultsContainer.innerHTML = filtered.map(p => `
            <div class="search-item-premium" onclick="window.location.href='product.html?id=${p.id}'">
                <img src="${p.image_path || p.image}" class="search-item-img-premium" onerror="this.src='https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&q=80&w=100';">
                <div class="search-item-info">
                    <span class="search-item-name-premium">${p.name}</span>
                    <span class="search-item-cat-premium">${p.category}</span>
                </div>
                <i class="fa-solid fa-chevron-right search-item-arrow"></i>
            </div>
        `).join('');
    }

    resultsContainer.classList.add('has-content');
}

function toggleLanguageDropdown(e) {
    if (e) e.stopPropagation();
    const dropdown = document.getElementById('langDropdown');
    dropdown.classList.toggle('active');
    
    // Close other dropdowns
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) userDropdown.classList.remove('active');
    const searchPortal = document.getElementById('searchAutocomplete');
    if (searchPortal) searchPortal.classList.remove('has-content');
}

function toggleUserDropdown(e) {
    if (e) e.stopPropagation();
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('active');
    
    // Close other dropdowns
    const langDropdown = document.getElementById('langDropdown');
    if (langDropdown) langDropdown.classList.remove('active');
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        if(this.closest('.nav-links') && document.getElementById('authModal') && document.getElementById('authModal').classList.contains('active')) return;
        
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            const headerHeight = window.scrollY > 50 ? 70 : 90;
            window.scrollTo({
                top: targetElement.offsetTop - headerHeight,
                behavior: 'smooth'
            });
        }
    });
});

// ====== Custom Language Selector Logic ======
function toggleLanguageDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('langDropdown');
    if(dropdown) {
        dropdown.classList.toggle('active');
    }
}

// Initialize Google Translate and restore language preference
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing Google Translate...');
    
    // Check for saved language preference
    const savedLang = localStorage.getItem('preferredLanguage') || 'en';
    const langCurrent = document.querySelector('.lang-current');
    const langMap = {
        'en': { selected: 'EN' },
        'es': { selected: 'ES' },
        'fr': { selected: 'FR' },
        'de': { selected: 'DE' },
        'zh-CN': { selected: 'ZH' },
        'ja': { selected: 'JA' }
    };
    
    // Set initial language display
    if (langCurrent && langMap[savedLang]) {
        langCurrent.innerText = langMap[savedLang].selected;
        
        // Update active state
        document.querySelectorAll('.lang-option').forEach(opt => {
            opt.classList.remove('active');
            if (opt.getAttribute('onclick')?.includes(`'${savedLang}'`)) {
                opt.classList.add('active');
            }
        });
    }
    
    // Wait for Google Translate to load, then apply saved language
    if (savedLang !== 'en') {
        applyLanguageWhenReady(savedLang);
    }
});

// Helper to poll for google translate and apply
function applyLanguageWhenReady(lang) {
    const langMap = {
        'en': 'en', 'es': 'es', 'fr': 'fr', 'de': 'de', 'zh-CN': 'zh-CN', 'ja': 'ja'
    };
    const googleCode = langMap[lang] || lang;
    
    let attempts = 0;
    const interval = setInterval(() => {
        const gtCombo = document.querySelector('.goog-te-combo');
        if (gtCombo) {
            clearInterval(interval);
            if (gtCombo.value !== googleCode) {
                gtCombo.value = googleCode;
                gtCombo.dispatchEvent(new Event('change'));
            }
        }
        attempts++;
        if (attempts > 40) clearInterval(interval); // give up after 20 seconds
    }, 500);
}

// Enhanced changeLanguage function triggered by user click
function changeLanguage(lang) {
    console.log('Changing language to:', lang);
    
    // Save preference
    localStorage.setItem('preferredLanguage', lang);
    
    const langMap = {
        'en': { name: 'English', code: 'EN', googleCode: 'en' },
        'es': { name: 'Español', code: 'ES', googleCode: 'es' },
        'fr': { name: 'Français', code: 'FR', googleCode: 'fr' },
        'de': { name: 'Deutsch', code: 'DE', googleCode: 'de' },
        'zh-CN': { name: '中文', code: 'ZH', googleCode: 'zh-CN' },
        'ja': { name: '日本語', code: 'JA', googleCode: 'ja' }
    };
    
    // Update UI
    document.querySelectorAll('.lang-option').forEach(opt => {
        opt.classList.remove('active');
        if (opt.getAttribute('onclick')?.includes(`'${lang}'`)) {
            opt.classList.add('active');
        }
    });

    const langDropdown = document.getElementById('langDropdown');
    if (langDropdown) langDropdown.classList.remove('active');
    
    const langCurrent = document.querySelector('.lang-current');
    if (langCurrent && langMap[lang]) {
        langCurrent.innerText = langMap[lang].code;
    }
    
    // Smooth Google Translate without reload
    const googleCode = langMap[lang]?.googleCode || lang;
    const domain = window.location.hostname;
    
    // Set cookies specifically formatted for Google Translate
    document.cookie = `googtrans=/en/${googleCode}; path=/; max-age=31536000`;
    if (domain !== 'localhost') {
        document.cookie = `googtrans=/en/${googleCode}; path=/; domain=${domain}; max-age=31536000`;
    }
    
    // Trigger Google Translate widget
    applyLanguageWhenReady(lang);
}

// Close dropdowns on click outside
document.addEventListener('click', (e) => {
    const langSelector = document.querySelector('.lang-selector-premium');
    const langDropdown = document.getElementById('langDropdown');
    if (langSelector && !langSelector.contains(e.target) && langDropdown) {
        langDropdown.classList.remove('active');
    }
    
    const userWrapper = document.querySelector('.user-portal-wrapper');
    const userDropdown = document.getElementById('userDropdown');
    if (userWrapper && !userWrapper.contains(e.target) && userDropdown) {
        userDropdown.classList.remove('active');
    }
    
    const searchWrapper = document.querySelector('.integrated-search');
    const searchPortal = document.getElementById('searchAutocomplete');
    if (searchWrapper && !searchWrapper.contains(e.target) && searchPortal) {
        searchPortal.classList.remove('has-content');
    }
});

// Function to handle age verification rejection
function handleAgeRejection() {
    const textEl = document.getElementById('ageModalText');
    const buttonsEl = document.getElementById('ageModalButtons');
    
    if (textEl && buttonsEl) {
        textEl.innerHTML = '<span style="color: var(--danger-red); font-weight: 700; font-size: 1.5rem;">ACCESS DENIED</span><br><br>We apologize, but you must be at least 21 years old to access this site.<br><br><small>Redirecting you to Google...</small>';
        buttonsEl.style.display = 'none';
        
        // Add a "lockout" style to the body
        document.body.style.overflow = 'hidden';
        document.body.style.filter = 'blur(10px) grayscale(100%)';
        document.body.style.pointerEvents = 'none';
        
        // Keep the modal visible and clear of filters
        const modal = document.getElementById('ageModal');
        if (modal) {
            modal.style.pointerEvents = 'auto';
            modal.style.filter = 'none';
        }

        // Force redirect after 3 seconds
        setTimeout(() => {
            window.location.href = 'https://www.google.com';
        }, 3000);
    }
}

// Function to check age verification status
function checkAgeVerification() {
    const modal = document.getElementById('ageModal');
    if (!modal) return;
    
    if (localStorage.getItem('age_verified') === 'true') {
        modal.classList.remove('active');
        modal.style.cssText = 'display: none !important;';
        document.body.style.overflow = 'auto'; // ensure scrolling is enabled
    } else {
        modal.classList.add('active');
        // Extreme bulletproofing to prevent it from closing like a flash
        modal.style.cssText = 'display: flex !important; z-index: 999999 !important; opacity: 1 !important; visibility: visible !important; pointer-events: auto !important;';
        document.body.style.overflow = 'hidden'; // block background scrolling until verified
    }
}

function acceptAge() {
    localStorage.setItem('age_verified', 'true');
    const modal = document.getElementById('ageModal');
    if (modal) {
        modal.classList.remove('active');
        modal.style.cssText = 'display: none !important;';
        document.body.style.overflow = 'auto'; // re-enable scrolling
    }
}

// Expanding Search UX Handler
function toggleSearch(e) {
    e.stopPropagation();
    const searchBox = document.getElementById('searchBox');
    const searchInput = document.getElementById('catalogSearch');
    
    if (searchBox.classList.contains('active')) {
        if (!searchInput.value) {
            searchBox.classList.remove('active');
        }
    } else {
        searchBox.classList.add('active');
        setTimeout(() => searchInput.focus(), 100);
    }
}

// Close search on click outside
document.addEventListener('click', (e) => {
    const searchBox = document.getElementById('searchBox');
    const resultsContainer = document.getElementById('searchAutocomplete');
    if (searchBox && !searchBox.contains(e.target)) {
        const searchInput = document.getElementById('catalogSearch');
        if (!searchInput.value) {
            searchBox.classList.remove('active');
        }
        if (resultsContainer) {
            resultsContainer.classList.remove('has-content');
        }
    }
});

// Initialization hook
document.addEventListener('DOMContentLoaded', () => {
    checkAgeVerification();
    const btn21 = document.getElementById('21plus-button');
    if (btn21) btn21.addEventListener('click', acceptAge);
    
    fetchProducts();
    checkAuthSession();
    fetchCustomMarqueeMessages();

    // Header Scroll Effect - Professional State Management
    const header = document.querySelector('.header');
    let headerScrollTicking = false;
    window.addEventListener('scroll', () => {
        if (!headerScrollTicking) {
            window.requestAnimationFrame(() => {
                if (window.scrollY > 30) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                headerScrollTicking = false;
            });
            headerScrollTicking = true;
        }
    }, { passive: true });
});

// Setup Mobile Menu Toggle Functionality
function toggleMobileMenu() {
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('mobileOverlay');
    const trigger = document.getElementById('mobileTrigger');
    if (!sidebar || !overlay) return;

    const isOpen = sidebar.classList.contains('active');

    if (isOpen) {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        if (trigger) trigger.classList.remove('active');
        document.body.style.overflow = '';
    } else {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        if (trigger) trigger.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

// Close mobile menu on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('mobileSidebar');
        if (sidebar && sidebar.classList.contains('active')) {
            toggleMobileMenu();
        }
    }
});

// ====== Testimonial Slider Logic ======
let currentTestimonialIndex = 0;
let testimonials = [];

async function loadTestimonials() {
    try {
        const { data, error } = await supabase
            .from('site_reviews')
            .select('display_name as name, overall_rating as rating, review_text as comment')
            .eq('status', 'approved')
            .order('submitted_at', { ascending: false });
            
        if (!error && data && data.length > 0) {
            testimonials = data;
        }
    } catch (e) {
        console.error('Error loading testimonials', e);
    }
    
    // Always call render. If data failed to load, the length is 0 and it falls back to default local reviews.
    renderTestimonials();
    updateDots();
}

function renderTestimonials() {
    const container = document.getElementById('testimonialContainer');
    if (!container) return;
    
    if (testimonials.length === 0) {
        // Fallback to high-quality default reviews if database is empty or fails
        const defaultTestimonials = [
            { rating: 5, name: "Dr. A. Sterling, Lead Researcher", comment: "The HPLC results are consistent and the QingLi procurement process is seamless. Discreet, fast shipping and professional-grade support." },
            { rating: 5, name: "Marcus V., Independent Lab", comment: "Unbeatable purity. I've tested batches from 4 different suppliers this year and QingLi consistently returns >99% on mass spec. Incredible reliability." },
            { rating: 5, name: "Elena R., Clinical Director", comment: "The wholesale portal is incredibly easy to use. Communication with their active reps (especially over WhatsApp) makes large-scale procurement stress-free." },
            { rating: 4, name: "Dr. J. Chen, University Research", comment: "Excellent compounds with full COA documentation. Shipping took an extra day on my last order, but the quality of the product makes it completely worth the wait." },
            { rating: 5, name: "Sarah T., Peptide Specialist", comment: "Their custom synthesis service is top-tier. Highly recommend for any serious lab looking for a long-term, trustworthy supplier." }
        ];
        
        container.innerHTML = defaultTestimonials.map(review => {
            const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
            let headline = "High-Purity Research Standards";
            if (review.rating < 5) headline = "Reliable Research Partner";
            
            return `
                <div class="testimonial-slide">
                    <div class="testimonial-stars" style="color:#facc15; font-size:1.2rem; margin-bottom:1rem;">${stars}</div>
                    <h4 class="testimonial-title" style="color:var(--text-main); margin-bottom:0.5rem; font-size:1.1rem;">${headline}</h4>
                    <p class="testimonial-quote" style="color:var(--text-muted); font-style:italic; line-height:1.6; margin-bottom:1.5rem;">"${review.comment}"</p>
                    <div class="testimonial-author" style="color:var(--accent-cyan); font-weight:600; font-size:0.9rem;">— ${review.name}</div>
                </div>
            `;
        }).join('');
        
        // Ensure slider length calculation works for default array
        testimonials = defaultTestimonials; 
        updateSliderPosition();
        return;
    }
    
    container.innerHTML = testimonials.map(review => {
        const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
        let headline = "High-Purity Research Standards";
        if (review.rating < 5) headline = "Reliable Research Partner";
        if (review.rating < 3) headline = "Verified Feedback";

        return `
            <div class="testimonial-slide">
                <div class="testimonial-stars">${stars}</div>
                <h4 class="testimonial-title">${headline}</h4>
                <p class="testimonial-quote">"${review.comment}"</p>
                <div class="testimonial-author">— ${review.name}</div>
            </div>
        `;
    }).join('');
    
    updateSliderPosition();
}

function updateSliderPosition() {
    const container = document.getElementById('testimonialContainer');
    if (container) {
        container.style.transform = `translateX(-${currentTestimonialIndex * 100}%)`;
    }
}

function updateDots() {
    const dotsContainer = document.getElementById('sliderDots');
    if (!dotsContainer) return;
    
    // Fallback to array length or 5 for default fallback
    const totalSlides = testimonials.length > 0 ? testimonials.length : 5;
    dotsContainer.innerHTML = '';
    
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.className = `slider-dot ${i === currentTestimonialIndex ? 'active' : ''}`;
        dot.onclick = () => goToSlide(i);
        dotsContainer.appendChild(dot);
    }
}

function nextTestimonial() {
    const totalSlides = testimonials.length > 0 ? testimonials.length : 5;
    currentTestimonialIndex = (currentTestimonialIndex + 1) % totalSlides;
    updateSliderPosition();
    updateDots();
}

function prevTestimonial() {
    const totalSlides = testimonials.length > 0 ? testimonials.length : 5;
    currentTestimonialIndex = (currentTestimonialIndex - 1 + totalSlides) % totalSlides;
    updateSliderPosition();
    updateDots();
}

function goToSlide(index) {
    currentTestimonialIndex = index;
    updateSliderPosition();
    updateDots();
}

// Auto-slide testimonials every 5 seconds
setInterval(nextTestimonial, 5000);

// Newsletter functionality
function handleNewsletterSubmit(event) {
    event.preventDefault();
    const email = event.target.querySelector('input[type="email"]').value;
    
    // Create success notification
    const notification = document.createElement('div');
    notification.className = 'newsletter-notification glass-panel';
    notification.innerHTML = `
        <div style="padding: 1rem; text-align: center;">
            <i class="fa-solid fa-check-circle text-success mr-2"></i>
            <span>Successfully subscribed to research updates!</span>
        </div>
    `;
    notification.style.cssText = `
        position: fixed; top: 100px; right: 20px; z-index: 10000;
        max-width: 300px; animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(notification);
    
    // Clear form
    event.target.reset();
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
    
    console.log('Newsletter subscription:', email);
}

// Back to top functionality
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Show/hide back to top button based on scroll position
let bttScrollTicking = false;
window.addEventListener('scroll', () => {
    if (!bttScrollTicking) {
        window.requestAnimationFrame(() => {
            const backToTopBtn = document.getElementById('backToTop');
            if (backToTopBtn) {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.add('visible');
                } else {
                    backToTopBtn.classList.remove('visible');
                }
            }
            bttScrollTicking = false;
        });
        bttScrollTicking = true;
    }
}, { passive: true });

// Add slide-in animation for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .newsletter-notification {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #fff;
        border-radius: 0.75rem;
    }
    
    .translation-notice {
        animation: slideIn 0.3s ease;
    }
`;
document.head.appendChild(style);

// Smooth scroll to reviews section
function scrollToReviews() {
    const reviewsSection = document.getElementById('trust-reviews');
    if (reviewsSection) {
        window.scrollTo({
            top: reviewsSection.offsetTop - 80,
            behavior: 'smooth'
        });
    }
}

// Content Protection Logic
document.addEventListener('contextmenu', (e) => e.preventDefault());

document.addEventListener('keydown', (e) => {
    // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
    if (
        e.keyCode === 123 || 
        (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) || 
        (e.ctrlKey && e.keyCode === 85)
    ) {
        e.preventDefault();
        return false;
    }
});

// Premium Reveal Animation Logic
function reveal() {
    const reveals = document.querySelectorAll(".reveal");
    for (let i = 0; i < reveals.length; i++) {
        const windowHeight = window.innerHeight;
        const elementTop = reveals[i].getBoundingClientRect().top;
        const elementVisible = 150;
        if (elementTop < windowHeight - elementVisible) {
            reveals[i].classList.add("active");
        }
    }
}

window.addEventListener("scroll", reveal);
window.addEventListener("load", reveal); // Initial check

// Load testimonials on page load
document.addEventListener('DOMContentLoaded', () => {
    loadTestimonials();
});

// ==========================================
// REP VERIFICATION
// ==========================================
window.handleVerify = async function(e) {
    if (e) e.preventDefault();
    const repIdEl = document.getElementById('repId');
    const resultEl = document.getElementById('verifyResult');
    const button = e.target.querySelector('button[type="submit"]');

    if (!repIdEl || !resultEl) return;
    
    const repId = repIdEl.value.trim();
    if (!repId) return;

    if (button) {
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';
        button.disabled = true;
    }

    try {
        const { data: reps, error } = await supabaseClient
            .from('representatives')
            .select('*')
            .eq('rep_id', repId)
            .eq('status', 'Active');

        if (error) {
            console.error(error);
            resultEl.innerHTML = `
                <div class="alert-glass mt-4 text-left p-4" style="border:1px solid rgba(239, 68, 68, 0.3); border-left:4px solid #ef4444; background: rgba(239, 68, 68, 0.05);">
                    <h4 class="text-danger m-0 mb-2"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Verification Error</h4>
                    <p class="text-sm m-0 text-muted">A network error occurred. Please try again later.</p>
                </div>
            `;
        } else if (reps && reps.length > 0) {
            const rep = reps[0];
            resultEl.innerHTML = `
                <div class="alert-glass mt-4 text-left p-4" style="border:1px solid rgba(16, 185, 129, 0.3); border-left:4px solid #10b981; background: rgba(16, 185, 129, 0.05); border-radius: 0.5rem;">
                    <div class="flex-between align-start mb-2">
                        <h4 class="text-success m-0"><i class="fa-solid fa-shield-check mr-2"></i> Verified Representative</h4>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid #10b981;">ACTIVE</span>
                    </div>
                    <p class="text-lg text-white font-bold m-0 mt-2 mb-1">${rep.name}</p>
                    <p class="text-sm text-accent m-0 font-mono mb-3">ID REF: ${rep.rep_id}</p>
                    ${rep.territory ? `<p class="text-sm m-0 text-muted border-top-glass pt-2"><i class="fa-solid fa-map-location-dot mr-2"></i> Authorized Territory: ${rep.territory}</p>` : ''}
                </div>
            `;
        } else {
            resultEl.innerHTML = `
                <div class="alert-glass mt-4 text-left p-4" style="border:1px solid rgba(239, 68, 68, 0.4); border-left:4px solid #ef4444; background: rgba(239, 68, 68, 0.1); border-radius: 0.5rem;">
                    <h4 class="text-danger m-0 mb-3"><i class="fa-solid fa-circle-xmark mr-2"></i> Security Alert: Invalid ID</h4>
                    <p class="text-sm text-white mb-3">The ID <strong>${repId}</strong> is not recognized as an active Qingli Peptide representative.</p>
                    <div class="p-3" style="background: rgba(0,0,0,0.3); border-radius: 4px;">
                        <p class="text-xs m-0 text-danger" style="line-height: 1.5;">
                            <i class="fa-solid fa-hand mr-1"></i> Please cease communications immediately and report this contact attempt to <strong>admin@qinglipeptide.com</strong> to prevent fraud.
                        </p>
                    </div>
                </div>
            `;
        }
    } catch (err) {
        console.error('Verify error:', err);
        resultEl.innerHTML = `<div class="text-danger mt-4">Failed to connect to verification server.</div>`;
    } finally {
        if (button) {
            button.innerHTML = 'Verify Rep';
            button.disabled = false;
        }
    }
}
