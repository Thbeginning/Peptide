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
            const response = await fetch('api/settings.php?action=fetch', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.status === 'success' && data.settings) {
                // Parse messages
                if (data.settings.contact_marquee) {
                    this.messages = data.settings.contact_marquee
                        .split(/\r?\n/)
                        .map(line => line.trim())
                        .filter(line => line.length > 0);
                }
                
                // Parse settings
                if (data.settings.marquee_speed) {
                    this.settings.speed = parseInt(data.settings.marquee_speed) || 30;
                }
                
                if (data.settings.marquee_color) {
                    this.settings.color = data.settings.marquee_color;
                    this.settings.theme = this.getColorTheme(data.settings.marquee_color);
                }
                
                if (data.settings.marquee_pause_hover !== undefined) {
                    this.settings.pauseOnHover = Boolean(data.settings.marquee_pause_hover);
                }
                
                if (data.settings.marquee_auto_refresh !== undefined) {
                    this.settings.autoRefresh = Boolean(data.settings.marquee_auto_refresh);
                }
            }
            
            // Set default messages if none provided
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
            
            // Fallback to default messages
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
            const response = await fetch('api/settings.php?action=save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    contact_marquee: messages.join('\n'),
                    marquee_speed: speed,
                    marquee_color: color
                })
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                this.messages = messages;
                this.settings.speed = speed;
                this.settings.color = color;
                this.settings.theme = this.getColorTheme(color);
                
                this.render();
                
                // Close admin panel
                document.querySelector('.marquee-admin-panel')?.remove();
                
                // Show success notification
                this.showNotification('Marquee settings saved successfully!');
            } else {
                alert('Error saving settings: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error saving marquee settings:', error);
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

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded - starting initialization...');
    professionalMarquee = new ProfessionalMarquee();
    
    // Force background immediately
    forceCinematicBackground();
    
    // Force background every 500ms
    setInterval(forceCinematicBackground, 500);
    
    // Also force on window resize
    window.addEventListener('resize', forceCinematicBackground);
    
    // Force on scroll
    window.addEventListener('scroll', forceCinematicBackground);
    
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
    
    try {
        console.log('Fetching products from: api/products.php?action=fetch_all');
        const res = await fetch('api/products.php?action=fetch_all');
        console.log('API response status:', res.status);
        
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        
        const data = await res.json();
        console.log('API response data:', data);
        
        if (data.status === 'success') {
            products = data.products;
            console.log('Products loaded:', products.length);
            
            // Artificial delay for smooth skeleton transition
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
        } else {
            throw new Error(data.message || 'API returned error status');
        }
    } catch(e) {
        console.error('Error fetching products:', e);
        const errorMessage = `
            <div class="text-center py-20 text-danger">
                <i class="fa-solid fa-exclamation-triangle fa-2x mb-4"></i>
                <h4>Failed to load compounds</h4>
                <p class="text-muted">Error: ${e.message}</p>
                <small class="text-muted">Check browser console for details</small>
            </div>
        `;
        if (grid) grid.innerHTML = errorMessage;
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
            <div class="text-center mt-8 hide-when-expanded">
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
        
        const contactPhone = typeof globalSettings !== 'undefined' && globalSettings.whatsapp_clean ? globalSettings.whatsapp_clean : '1234567890';
        const waLink = `https://wa.me/${contactPhone}?text=${encodeURIComponent("I'm interested in pricing for " + product.name)}`;

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
        console.log('Checking auth session...');
        const res = await fetch('api/auth.php?action=check');
        console.log('Auth check response status:', res.status);
        
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        
        const data = await res.json();
        console.log('Auth check data:', data);
        
        if (data.logged_in) {
            currentUser = data.user;
            const authGuest = document.getElementById('authGuest');
            const authLogged = document.getElementById('authLogged');
            if (authGuest) authGuest.style.display = 'none';
            if (authLogged) authLogged.style.display = 'flex';
            
            const navUserName = document.getElementById('navUserName');
            if (navUserName) navUserName.innerText = currentUser.name;

            const adminLink = document.getElementById('adminDashboardLink');
            if (adminLink) {
                adminLink.style.display = currentUser.role === 'admin' ? 'block' : 'none';
            }
            
            const mobGuest = document.getElementById('mobileAuthGuest');
            const mobLogged = document.getElementById('mobileAuthLogged');
            const mobUserName = document.getElementById('mobileNavUserName');
            if(mobGuest) mobGuest.style.display = 'none';
            if(mobLogged) mobLogged.style.display = 'block';
            if(mobUserName) mobUserName.innerText = currentUser.name;
            
            fetchCart();
        } else {
            currentUser = null;
            const authGuest = document.getElementById('authGuest');
            const authLogged = document.getElementById('authLogged');
            if (authGuest) authGuest.style.display = 'flex';
            if (authLogged) authLogged.style.display = 'none';
            
            const mobGuest = document.getElementById('mobileAuthGuest');
            const mobLogged = document.getElementById('mobileAuthLogged');
            if(mobGuest) mobGuest.style.display = 'block';
            if(mobLogged) mobLogged.style.display = 'none';
        }
    } catch (e) { 
        console.error('Error checking auth:', e);
        // Show guest UI as fallback
        currentUser = null;
        const authGuest = document.getElementById('authGuest');
        const authLogged = document.getElementById('authLogged');
        if (authGuest) authGuest.style.display = 'flex';
        if (authLogged) authLogged.style.display = 'none';
        
        const mobGuest = document.getElementById('mobileAuthGuest');
        const mobLogged = document.getElementById('mobileAuthLogged');
        if(mobGuest) mobGuest.style.display = 'block';
        if(mobLogged) mobLogged.style.display = 'none';
    }
}

async function handleLogin(e) {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const errorDiv = document.getElementById('loginError');
    
    try {
        const res = await fetch('api/auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'login', email, password })
        });
        const data = await res.json();
        if (data.status === 'success') {
            if (data.user && data.user.role === 'admin') {
                window.location.href = 'admin/index.php';
            } else {
                closeAuthModal();
                checkAuthSession();
            }
        } else {
            errorDiv.style.display = 'block';
            errorDiv.innerText = data.message;
        }
    } catch(e) { console.error(e); }
}

async function handleRegister(e) {
    e.preventDefault();
    const name = document.getElementById('regName').value;
    const email = document.getElementById('regEmail').value;
    const password = document.getElementById('regPassword').value;
    const errorDiv = document.getElementById('regError');
    
    try {
        const res = await fetch('api/auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'register', name, email, password })
        });
        const data = await res.json();
        if (data.status === 'success') {
            closeAuthModal();
            checkAuthSession();
        } else {
            errorDiv.style.display = 'block';
            errorDiv.innerText = data.message;
        }
    } catch(e) { console.error(e); }
}

async function logoutUser() {
    await fetch('api/auth.php?action=logout');
    checkAuthSession();
    cartItems = [];
    renderCartUI();
}

// ====== CART LOGIC ======
async function addToCart(productId, productName) {
    if (!currentUser) {
        openAuthModal();
        return;
    }
    
    try {
        const res = await fetch('api/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add', product_id: productId, product_name: productName, quantity: 1 })
        });
        const data = await res.json();
        if (data.status === 'success') {
            fetchCart(); // refresh the drawer
            
            // tiny animation feedback
            const btn = document.querySelector(`.product-card[data-id="${productId}"] .btn-primary`);
            if (btn) {
                const standardText = btn.innerHTML;
                btn.innerHTML = `<i class="fa-solid fa-check mr-2"></i> Added`;
                btn.style.background = 'var(--success-green)';
                setTimeout(() => {
                    btn.innerHTML = standardText;
                    btn.style.background = '';
                }, 1500);
            }
        }
    } catch (e) { console.error(e); }
}

async function fetchCart() {
    if (!currentUser) return;
    try {
        const res = await fetch('api/cart.php?action=fetch');
        const data = await res.json();
        if (data.status === 'success') {
            cartItems = data.cart;
            renderCartUI();
        }
    } catch (e) { console.error(e); }
}

async function removeFromCart(itemId) {
    try {
        const res = await fetch('api/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'remove', item_id: itemId })
        });
        const data = await res.json();
        if (data.status === 'success') {
            fetchCart();
        }
    } catch (e) { console.error(e); }
}

async function updateCartQuantity(itemId, newQty) {
    if (newQty < 1) return; // Optional: auto remove if < 1, but we prevent it here
    try {
        const res = await fetch('api/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'update', item_id: itemId, quantity: newQty })
        });
        const data = await res.json();
        if (data.status === 'success') {
            fetchCart();
        }
    } catch (e) {
        console.error(e);
    }
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
    const btn = e.target.querySelector('button[type="submit"]');
    const oldText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
    btn.disabled = true;

    const data = {
        action: 'submit_quote',
        shipping_city: document.getElementById('inqCity').value,
        shipping_country: document.getElementById('inqCountry').value,
        contact_method: document.getElementById('inqContactMethod').value,
        contact_detail: document.getElementById('inqContactDetail').value,
        message: document.getElementById('inqMessage').value,
        research_only_confirmed: document.getElementById('inqResearchOnly').checked
    };

    try {
        const res = await fetch('api/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const respData = await res.json();
        
        if (respData.status === 'success') {
            document.getElementById('inquiryModal').classList.remove('active');
            document.getElementById('successMessageText').innerText = respData.message;
            document.getElementById('successModal').classList.add('active');
            e.target.reset();
            fetchCart(); // This will empty the cart UI
        } else {
            alert(respData.message || 'An error occurred.');
        }
    } catch(err) {
        console.error(err);
        alert('Server connection error.');
    } finally {
        btn.innerHTML = oldText;
        btn.disabled = false;
    }
}

// ====== MODAL UI LOGIC ======
function openAuthModal(tab = 'login') {
    document.getElementById('authModal').classList.add('active');
    document.getElementById('loginError').style.display = 'none';
    document.getElementById('regError').style.display = 'none';
    switchAuthTab(tab);
}
function closeAuthModal() {
    document.getElementById('authModal').classList.remove('active');
}
function switchAuthTab(tab) {
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('registerForm').style.display = 'none';
    
    if (tab === 'login') {
        document.querySelector('.auth-tab:nth-child(1)').classList.add('active');
        document.getElementById('loginForm').style.display = 'block';
    } else {
        document.querySelector('.auth-tab:nth-child(2)').classList.add('active');
        document.getElementById('registerForm').style.display = 'block';
    }
}
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
    try {
        const res = await fetch('api/user_quotes.php?action=fetch_history');
        const data = await res.json();
        
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
    
    try {
        const res = await fetch('api/user_quotes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'duplicate', quote_id: quoteId })
        });
        const data = await res.json();
        
        if (data.status === 'success') {
            document.getElementById('accountModal').classList.remove('active');
            fetchCart();
            setTimeout(() => toggleCart(), 300); // Open drawer after slight delay
        } else {
            alert(data.message);
        }
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
    const doseUnit = document.getElementById('doseUnit').value;

    if (!peptideMg || !waterMl || !doseAmount) return alert("Please ensure all inputs have valid numbers.");

    let syringeVolumeMl = syringeSizeType === 50 ? 0.5 : (syringeSizeType === 30 ? 0.3 : 1.0);
    let doseMg = doseUnit === 'mcg' ? doseAmount / 1000 : doseAmount;
    const concentrationMgPerMl = peptideMg / waterMl;
    const volumeToInjectMl = doseMg / concentrationMgPerMl;
    const unitsToPull = volumeToInjectMl * 100;
    const maxTicks = syringeSizeType;

    const resultDiv = document.getElementById('calcResult');
    const pullUnitsSpan = document.getElementById('pullUnits');
    const visualLiquid = document.getElementById('visualLiquid');

    resultDiv.style.display = 'block';

    if (unitsToPull > maxTicks) {
        pullUnitsSpan.innerHTML = "TOO HIGH";
        pullUnitsSpan.style.color = "var(--danger-red)";
        visualLiquid.style.width = "100%";
        visualLiquid.style.background = "var(--danger-red)";
        document.getElementById('resSyringe').parentElement.innerText = `Dose exceeds syringe capacity.`;
    } else {
        const roundedTicks = Number(unitsToPull.toFixed(1));
        pullUnitsSpan.innerText = roundedTicks;
        pullUnitsSpan.style.color = "inherit";
        document.getElementById('resSyringe').innerText = syringeVolumeMl + "mL";

        const percentage = (roundedTicks / maxTicks) * 100;
        visualLiquid.style.width = `${percentage}%`;
        visualLiquid.style.background = "linear-gradient(90deg, var(--accent-blue), var(--accent-cyan))";
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
        const res = await fetch(`api/verify_rep.php?rep_id=${encodeURIComponent(repId)}`);
        const data = await res.json();
        
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
        'en': { code: 'EN' },
        'es': { code: 'ES' },
        'fr': { code: 'FR' },
        'de': { code: 'DE' },
        'zh-CN': { code: 'ZH' },
        'ja': { code: 'JA' }
    };
    
    // Set initial language display
    if (langCurrent && langMap[savedLang]) {
        langCurrent.innerText = langMap[savedLang].code;
        
        // Update active state
        document.querySelectorAll('.lang-option').forEach(opt => {
            opt.classList.remove('active');
            if (opt.getAttribute('onclick').includes(`'${savedLang}'`)) {
                opt.classList.add('active');
            }
        });
    }
    
    // Wait for Google Translate to load, then apply saved language
    setTimeout(() => {
        if (savedLang !== 'en') {
            changeLanguage(savedLang);
        }
    }, 1000);
});

// Enhanced changeLanguage function
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
        if (opt.getAttribute('onclick').includes(`'${lang}'`)) {
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
    
    // Set cookies
    document.cookie = `googtrans=/en/${googleCode}; path=/; domain=${domain}; max-age=31536000`;
    document.cookie = `googtrans=/en/${googleCode}; path=/; max-age=31536000`;
    
    // Try to trigger Google Translate widget
    setTimeout(() => {
        const gtCombo = document.querySelector('.goog-te-combo');
        if (gtCombo) {
            gtCombo.value = googleCode;
            gtCombo.dispatchEvent(new Event('change'));
        }
    }, 100);
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
    if (localStorage.getItem('age_verified') === 'true') {
        const modal = document.getElementById('ageModal');
        if (modal) {
            modal.classList.remove('active');
            modal.style.display = 'none';
        }
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
    fetchProducts();
    checkAuthSession();
    fetchCustomMarqueeMessages();

    // Header Scroll Effect - Professional State Management
    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 30) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});

// Setup Mobile Menu Toggle Functionality
function toggleMobileMenu() {
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('mobileOverlay');
    if (sidebar && overlay) {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }
}

// ====== Testimonial Slider Logic ======
let currentTestimonialIndex = 0;
let testimonials = [];

async function loadTestimonials() {
    try {
        const res = await fetch('api/reviews.php?action=fetch_approved');
        const data = await res.json();
        if (data.status === 'success') {
            testimonials = data.reviews;
            renderTestimonials();
            updateDots();
        }
    } catch (e) {
        console.error('Error loading testimonials', e);
    }
}

function renderTestimonials() {
    const container = document.getElementById('testimonialContainer');
    if (!container) return;
    
    if (testimonials.length === 0) {
        container.innerHTML = `
            <div class="testimonial-slide">
                <div class="testimonial-stars">★★★★★</div>
                <h4 class="testimonial-title">Professional Quality Standards</h4>
                <p class="testimonial-quote">"The HPLC results are consistent and the QingLi procurement process is seamless. Discreet, fast shipping and professional-grade support."</p>
                <div class="testimonial-author">— Dr. A. Sterling, Lead Researcher</div>
            </div>
        `;
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
    
    const totalSlides = testimonials.length || 1;
    dotsContainer.innerHTML = '';
    
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.className = `slider-dot ${i === currentTestimonialIndex ? 'active' : ''}`;
        dot.onclick = () => goToSlide(i);
        dotsContainer.appendChild(dot);
    }
}

function nextTestimonial() {
    const totalSlides = testimonials.length || 1;
    currentTestimonialIndex = (currentTestimonialIndex + 1) % totalSlides;
    updateSliderPosition();
    updateDots();
}

function prevTestimonial() {
    const totalSlides = testimonials.length || 1;
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
window.addEventListener('scroll', () => {
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    }
});

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
