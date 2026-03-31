// === supabase-config.js v5.0 ===
// The Supabase UMD build exposes a global called 'supabase' (the module).
// We cannot overwrite it, so we create a distinct 'window.supabaseClient' for our app logic.

const SUPABASE_URL = 'https://ujwmzcdpilmgaiwrligq.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVqd216Y2RwaWxtZ2Fpd3JsaWdxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQ1MzY0ODgsImV4cCI6MjA5MDExMjQ4OH0.-NwyaFNcAYS8lCiz_ofuV9KlsWyz-sWnxVCeGR992Rk';

(function () {
    try {
        const _module = window.supabase;
        if (!_module || typeof _module.createClient !== 'function') {
            throw new Error('Supabase UMD module not found on window.supabase. Check CDN script order.');
        }
        // Initialize the actual connection client
        window.supabaseClient = _module.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
        console.log('[Supabase] ✅ Client initialized as window.supabaseClient');
    } catch (e) {
        console.error('[Supabase] ❌ Initialization failed:', e.message);
        // Fallback mock
        window.supabaseClient = {
            auth: {
                getSession: () => Promise.resolve({ data: { session: null }, error: null }),
                onAuthStateChange: () => ({ data: { subscription: { unsubscribe: () => {} } } }),
                signInWithPassword: () => Promise.reject(new Error('Supabase not initialized')),
                signUp: () => Promise.reject(new Error('Supabase not initialized')),
                signOut: () => Promise.resolve()
            },
            from: () => ({
                select: () => ({ order: () => Promise.resolve({ data: [], error: null }), eq: () => ({ single: () => Promise.resolve({ data: null, error: null }) }) }),
                insert: () => Promise.resolve({ error: null }),
                upsert: () => Promise.resolve({ error: null }),
                update: () => ({ eq: () => Promise.resolve({ error: null }) }),
                delete: () => ({ eq: () => Promise.resolve({ error: null }) })
            }),
            storage: { from: () => ({ upload: () => Promise.resolve({ error: null }), getPublicUrl: () => ({ data: { publicUrl: '' } }) }) }
        };
    }
})();
