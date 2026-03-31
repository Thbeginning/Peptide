-- === supabase-schema.sql ===
-- Run this entire file in your Supabase SQL Editor (supabase.com â†’ Project â†’ SQL Editor)
-- This sets up all the tables, seed data, Row Level Security policies, and Storage buckets.

-- ============================================================
-- 1. ENABLE UUID EXTENSION
-- ============================================================
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- ============================================================
-- 2. PRODUCTS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.products (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    category TEXT NOT NULL,
    image_path TEXT NOT NULL,
    description TEXT,
    purity TEXT,
    coa_path TEXT,
    coa_paths JSONB,
    specification TEXT,
    form TEXT,
    storage TEXT,
    overview TEXT,
    applications TEXT,
    target_users TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE public.products ENABLE ROW LEVEL SECURITY;
-- Public: anyone can read products
DROP POLICY IF EXISTS "Public can read products" ON public.products;
CREATE POLICY "Public can read products" ON public.products FOR SELECT USING (true);
-- Admin only: insert, update, delete (admin role checked via metadata)
DROP POLICY IF EXISTS "Admin can insert products" ON public.products;
CREATE POLICY "Admin can insert products" ON public.products FOR INSERT WITH CHECK (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);
DROP POLICY IF EXISTS "Admin can update products" ON public.products;
CREATE POLICY "Admin can update products" ON public.products FOR UPDATE USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);
DROP POLICY IF EXISTS "Admin can delete products" ON public.products;
CREATE POLICY "Admin can delete products" ON public.products FOR DELETE USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- ============================================================
-- 3. CART ITEMS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.cart_items (
    id BIGSERIAL PRIMARY KEY,
    user_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
    product_id TEXT NOT NULL,
    product_name TEXT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE public.cart_items ENABLE ROW LEVEL SECURITY;
-- Users can only manage their own cart
DROP POLICY IF EXISTS "Users manage own cart" ON public.cart_items;
CREATE POLICY "Users manage own cart" ON public.cart_items FOR ALL USING (auth.uid() = user_id);

-- ============================================================
-- 4. QUOTE REQUESTS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.quote_requests (
    id BIGSERIAL PRIMARY KEY,
    user_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
    shipping_city TEXT NOT NULL,
    shipping_country TEXT NOT NULL,
    contact_method TEXT DEFAULT 'email',
    contact_detail TEXT NOT NULL,
    message TEXT,
    research_only_confirmed BOOLEAN DEFAULT TRUE,
    status TEXT DEFAULT 'Pending',
    internal_notes TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE public.quote_requests ENABLE ROW LEVEL SECURITY;
DROP POLICY IF EXISTS "Users can read own quotes" ON public.quote_requests;
CREATE POLICY "Users can read own quotes" ON public.quote_requests FOR SELECT USING (auth.uid() = user_id);
DROP POLICY IF EXISTS "Users can insert quotes" ON public.quote_requests;
CREATE POLICY "Users can insert quotes" ON public.quote_requests FOR INSERT WITH CHECK (auth.uid() = user_id);
DROP POLICY IF EXISTS "Admin can read all quotes" ON public.quote_requests;
CREATE POLICY "Admin can read all quotes" ON public.quote_requests FOR SELECT USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);
DROP POLICY IF EXISTS "Admin can update quotes" ON public.quote_requests;
CREATE POLICY "Admin can update quotes" ON public.quote_requests FOR UPDATE USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);
DROP POLICY IF EXISTS "Admin can delete quotes" ON public.quote_requests;
CREATE POLICY "Admin can delete quotes" ON public.quote_requests FOR DELETE USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- ============================================================
-- 5. QUOTE REQUEST ITEMS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.quote_request_items (
    id BIGSERIAL PRIMARY KEY,
    quote_id BIGINT NOT NULL REFERENCES public.quote_requests(id) ON DELETE CASCADE,
    product_id TEXT NOT NULL,
    product_name TEXT NOT NULL,
    quantity INT DEFAULT 1
);

ALTER TABLE public.quote_request_items ENABLE ROW LEVEL SECURITY;
DROP POLICY IF EXISTS "Users can read own quote items" ON public.quote_request_items;
CREATE POLICY "Users can read own quote items" ON public.quote_request_items FOR SELECT USING (
    EXISTS (SELECT 1 FROM public.quote_requests q WHERE q.id = quote_id AND q.user_id = auth.uid())
);
DROP POLICY IF EXISTS "Users can insert quote items" ON public.quote_request_items;
CREATE POLICY "Users can insert quote items" ON public.quote_request_items FOR INSERT WITH CHECK (
    EXISTS (SELECT 1 FROM public.quote_requests q WHERE q.id = quote_id AND q.user_id = auth.uid())
);
DROP POLICY IF EXISTS "Admin can manage quote items" ON public.quote_request_items;
CREATE POLICY "Admin can manage quote items" ON public.quote_request_items FOR ALL USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- ============================================================
-- 6. SITE REVIEWS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.site_reviews (
    id BIGSERIAL PRIMARY KEY,
    display_name TEXT NOT NULL,
    overall_rating INT NOT NULL CHECK (overall_rating BETWEEN 1 AND 5),
    communication_professional BOOLEAN DEFAULT FALSE,
    shipping_discreet_timely BOOLEAN DEFAULT FALSE,
    product_lab_standards BOOLEAN DEFAULT FALSE,
    review_text TEXT NOT NULL,
    status TEXT DEFAULT 'pending',
    submitted_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE public.site_reviews ENABLE ROW LEVEL SECURITY;
-- Anyone can read approved reviews
DROP POLICY IF EXISTS "Public can read approved reviews" ON public.site_reviews;
CREATE POLICY "Public can read approved reviews" ON public.site_reviews FOR SELECT USING (status = 'approved');
-- Anyone can submit a review
DROP POLICY IF EXISTS "Anyone can submit review" ON public.site_reviews;
CREATE POLICY "Anyone can submit review" ON public.site_reviews FOR INSERT WITH CHECK (true);
-- Admin can read and manage all reviews
DROP POLICY IF EXISTS "Admin can read all reviews" ON public.site_reviews;
CREATE POLICY "Admin can read all reviews" ON public.site_reviews FOR SELECT USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);
DROP POLICY IF EXISTS "Admin can update reviews" ON public.site_reviews;
CREATE POLICY "Admin can update reviews" ON public.site_reviews FOR UPDATE USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);
DROP POLICY IF EXISTS "Admin can delete reviews" ON public.site_reviews;
CREATE POLICY "Admin can delete reviews" ON public.site_reviews FOR DELETE USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- ============================================================
-- 7. SETTINGS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.settings (
    setting_key TEXT PRIMARY KEY,
    setting_value TEXT NOT NULL,
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE public.settings ENABLE ROW LEVEL SECURITY;
-- Anyone can read settings (marquee, contact info shown on frontend)
DROP POLICY IF EXISTS "Public can read settings" ON public.settings;
CREATE POLICY "Public can read settings" ON public.settings FOR SELECT USING (true);
-- Admin only can update settings
DROP POLICY IF EXISTS "Admin can manage settings" ON public.settings;
CREATE POLICY "Admin can manage settings" ON public.settings FOR ALL USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- Seed default settings
INSERT INTO public.settings (setting_key, setting_value) VALUES
    ('support_email', 'admin@qinglipeptide.com'),
    ('whatsapp_no', '+1234567890'),
    ('contact_marquee', 'â€¢ GLOBAL EXPORT QUALITY
â€¢ GUARANTEED CUSTOMS CLEARANCE
â€¢ UNMATCHED PURITY STANDARDS
â€¢ 24/7 TECHNICAL SUPPORT
â€¢ DISCREET WORLDWIDE SHIPPING'),
    ('marquee_speed', '30'),
    ('marquee_color', '#0bbed6')
ON CONFLICT (setting_key) DO NOTHING;

-- ============================================================
-- 8. SITE CONTENT TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.site_content (
    id BIGSERIAL PRIMARY KEY,
    hero_headline TEXT,
    hero_subheadline TEXT,
    hero_cta TEXT,
    hero_cta2 TEXT,
    hero_rating NUMERIC(3,1),
    about_title TEXT,
    about_description TEXT,
    about_mission TEXT,
    calc_title TEXT,
    calc_description TEXT,
    calc_default_water NUMERIC(3,1),
    quality_title TEXT,
    quality_description TEXT,
    quality_purity NUMERIC(4,1),
    seo_title TEXT,
    seo_description TEXT,
    seo_keywords TEXT,
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE public.site_content ENABLE ROW LEVEL SECURITY;
DROP POLICY IF EXISTS "Public can read content" ON public.site_content;
CREATE POLICY "Public can read content" ON public.site_content FOR SELECT USING (true);
DROP POLICY IF EXISTS "Admin can manage content" ON public.site_content;
CREATE POLICY "Admin can manage content" ON public.site_content FOR ALL USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- ============================================================
-- 9. REPRESENTATIVES TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.representatives (
    id BIGSERIAL PRIMARY KEY,
    rep_id TEXT UNIQUE NOT NULL,
    name TEXT NOT NULL,
    territory TEXT,
    status TEXT DEFAULT 'Active',
    created_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE public.representatives ENABLE ROW LEVEL SECURITY;
-- Anyone can lookup a rep (for the verification tool)
DROP POLICY IF EXISTS "Public can lookup reps" ON public.representatives;
CREATE POLICY "Public can lookup reps" ON public.representatives FOR SELECT USING (true);
DROP POLICY IF EXISTS "Admin can manage reps" ON public.representatives;
CREATE POLICY "Admin can manage reps" ON public.representatives FOR ALL USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- ============================================================
-- 10. COMMUNICATIONS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.communications (
    id BIGSERIAL PRIMARY KEY,
    customer_name TEXT,
    message TEXT,
    type TEXT,
    status TEXT DEFAULT 'open',
    date DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    created_by TEXT
);

ALTER TABLE public.communications ENABLE ROW LEVEL SECURITY;
DROP POLICY IF EXISTS "Admin can manage communications" ON public.communications;
CREATE POLICY "Admin can manage communications" ON public.communications FOR ALL USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- ============================================================
-- 11. CUSTOMERS (CRM) TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS public.customers (
    id BIGSERIAL PRIMARY KEY,
    name TEXT,
    email TEXT,
    stage TEXT DEFAULT 'new',
    value NUMERIC(10,2) DEFAULT 0,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    created_by TEXT
);

ALTER TABLE public.customers ENABLE ROW LEVEL SECURITY;
DROP POLICY IF EXISTS "Admin can manage customers" ON public.customers;
CREATE POLICY "Admin can manage customers" ON public.customers FOR ALL USING (
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);

-- ============================================================
-- 12. SEED PRODUCTS (44 initial products)
-- ============================================================
INSERT INTO public.products (id, name, category, image_path, description, purity) VALUES
('aod9604','AOD9604','Peptide','COA/peptide product cover .jpeg','Fat loss peptide fragment derived from hGH.','>99.0%'),
('ara290','Ara290','Peptide','COA/peptide product cover .jpeg','Synthetic peptide designed to target the erythropoietin receptor.','>98.5%'),
('botox','BOTOX','Cosmetic','COA/peptide product cover .jpeg','Botulinum toxin type A research grade.','>99.1%'),
('bpc-tb-blend','BPC157 + TB500','Peptide Blend','COA/peptide product cover .jpeg','Synergistic tissue repair and angiogenesis blend.','>99.0%'),
('bpc-157','BPC-157','Peptide','COA/peptide product cover .jpeg','Body Protection Compound. Synthetically produced sequence utilized extensively for tissue repair research.','>99.2%'),
('cjc-dac','CJC-1295 DAC','Peptide','COA/peptide product cover .jpeg','Long-acting GHRH analog with Drug Affinity Complex.','>98.8%'),
('cjc-nodac','CJC-1295 NO DAC','Peptide','COA/peptide product cover .jpeg','Shorter acting GHRH analog (Mod GRF 1-29).','>99.0%'),
('cjc-ipa','CJC1295 NO DAC + IPA','Peptide Blend','COA/peptide product cover .jpeg','Standard growth hormone secretagogue blend.','>99.0%'),
('cagrilin','Cagrilintide','Amylin Analog','COA/peptide product cover .jpeg','Long-acting amylin analog for metabolic research.','>99.2%'),
('cerebrolysin','Cerebrolysin','Nootropic','COA/peptide product cover .jpeg','Peptide mixture with neurotrophic factors.','>98.5%'),
('dsip','DSIP','Peptide','COA/peptide product cover .jpeg','Delta Sleep-Inducing Peptide.','>98.9%'),
('epithalon','Epithalon','Peptide','COA/peptide product cover .jpeg','Synthetic pineal peptide derivative currently studied for telomerase activation and anti-aging.','>99.0%'),
('ghk-cu','GHK-CU','Copper Peptide','COA/peptide product cover .jpeg','Copper peptide naturally occurring and used in cosmetic/wound healing research.','>99.3%'),
('ghrp-6','GHRP-6','Peptide','COA/peptide product cover .jpeg','First-generation growth hormone releasing hexapeptide.','>98.7%'),
('glow','GLOW','Cosmetic Blend','COA/peptide product cover .jpeg','Specialized dermal research blend.','>99.0%'),
('glutathione','Glutathione','Antioxidant','COA/peptide product cover .jpeg','Master antioxidant for cellular defense studies.','>99.5%'),
('hcg','HCG','Hormone','COA/peptide product cover .jpeg','Human Chorionic Gonadotropin research grade.','>99.0%'),
('hgh','HGH','Hormone','COA/peptide product cover .jpeg','Somatropin (Human Growth Hormone).','>99.5%'),
('hmg','HMG','Hormone','COA/peptide product cover .jpeg','Human Menopausal Gonadotropin.','>99.0%'),
('igf-lr3','IGF-1 LR3','Peptide','COA/peptide product cover .jpeg','Long-acting insulin-like growth factor 1 analog.','>98.8%'),
('igf-des','IGF-1 DES','Peptide','COA/peptide product cover .jpeg','Truncated, highly potent insulin-like growth factor 1.','>98.5%'),
('ipamorelin','Ipamorelin','Peptide','COA/peptide product cover .jpeg','Selective growth hormone secretagogue and ghrelin receptor agonist for longevity models.','>98.8%'),
('klow','KLOW','Specialty','COA/peptide product cover .jpeg','Proprietary research compound.','>99.0%'),
('kpv','KPV','Peptide','COA/peptide product cover .jpeg','Anti-inflammatory tripeptide derived from alpha-MSH.','>99.2%'),
('ll37','LL-37','Peptide','COA/peptide product cover .jpeg','Cathelicidin antimicrobial peptide.','>98.5%'),
('lemon-bottle','Lemon Bottle','Lipolytic','COA/peptide product cover .jpeg','Advanced lipolysis research solution.','>99.0%'),
('mots-c','MOTS-C','Mitochondrial Peptide','COA/peptide product cover .jpeg','Mitochondrial-derived peptide linked to metabolic regulation.','>99.1%'),
('mt-2','Melanotan II','Peptide','COA/peptide product cover .jpeg','Synthetic analog of alpha-melanocyte-stimulating hormone.','>99.0%'),
('nad-plus','NAD+','Coenzyme','COA/peptide product cover .jpeg','Nicotinamide adenine dinucleotide for cellular respiration studies.','>99.5%'),
('pnc-27','PNC-27','Peptide','COA/peptide product cover .jpeg','Anti-cancer research peptide targeting HDM-2.','>98.5%'),
('pt-141','Bremelanotide (PT-141)','Peptide','COA/peptide product cover .jpeg','Melanocortin receptor agonist.','>99.0%'),
('pinealon','Pinealon','Peptide','COA/peptide product cover .jpeg','Short peptide for brain function research.','>98.8%'),
('retatrutide','Retatrutide','Triple Agonist','COA/peptide product cover .jpeg','GIP, GLP-1, and glucagon receptor triple agonist.','>99.2%'),
('slu-pp-332','SLU-PP-332','ERR Agonist','COA/peptide product cover .jpeg','Estrogen-related receptor agonist for metabolic studies.','>98.5%'),
('ss-31','Elamipretide (SS-31)','Mitochondrial Peptide','COA/peptide product cover .jpeg','Targets inner mitochondrial membrane.','>99.0%'),
('selank','Selank','Peptide','COA/peptide product cover .jpeg','Synthetic analog of the human tetrapeptide tuftsin.','>98.9%'),
('semaglutide','Semaglutide','GLP-1','COA/peptide product cover .jpeg','Long-acting GLP-1 receptor agonist. A staple compound in in-vitro metabolic and glycemic studies.','>99.1%'),
('semax','Semax','Peptide','COA/peptide product cover .jpeg','Heptapeptide utilized for neuroprotection research.','>98.8%'),
('snap-8','Snap-8','Cosmetic Peptide','COA/peptide product cover .jpeg','Octapeptide mimicking N-terminal end of SNAP-25.','>99.0%'),
('survodutide','Survodutide','Dual Agonist','COA/peptide product cover .jpeg','Glucagon/GLP-1 receptor dual agonist.','>99.1%'),
('tb-500','TB-500','Peptide','COA/peptide product cover .jpeg','Thymosin Beta-4 synthetic analog. Focus of cellular repair, angiogenesis, and recovery studies.','>98.5%'),
('tesamorelin','Tesamorelin','Peptide','COA/peptide product cover .jpeg','Synthetic GHRH analog for lipodystrophy research.','>99.2%'),
('thymalin','Thymalin','Peptide','COA/peptide product cover .jpeg','Thymic peptide for immunoregulation studies.','>98.8%'),
('ta1','Thymosin Alpha-1','Peptide','COA/peptide product cover .jpeg','Major component of Thymosin Fraction 5.','>99.0%'),
('tirzepatide','Tirzepatide','GLP-1/GIP','COA/peptide product cover .jpeg','Novel dual GIP and GLP-1 receptor agonist highly sought after for advanced metabolic research.','>99.5%'),
('vip','VIP','Peptide','COA/peptide product cover .jpeg','Vasoactive Intestinal Peptide.','>98.5%')
ON CONFLICT (id) DO NOTHING;

-- ============================================================
-- 13. MAKE YOUR ADMIN ACCOUNT
-- ============================================================
-- IMPORTANT: After registering your admin account on the site:
-- 1. Find your user UUID in Supabase Auth dashboard (Authentication â†’ Users)
-- 2. Run this SQL replacing YOUR_ADMIN_UUID with your actual UUID:
--
-- UPDATE auth.users
-- SET raw_user_meta_data = raw_user_meta_data || '{"role": "admin"}'::jsonb
-- WHERE id = 'YOUR_ADMIN_UUID';
--
-- This gives your account the 'admin' role so you can access the admin dashboard.

-- ============================================================
-- 14. STORAGE BUCKETS
-- ============================================================
-- Run this to create the storage buckets for file uploads:
INSERT INTO storage.buckets (id, name, public) VALUES ('products', 'products', true) ON CONFLICT DO NOTHING;
INSERT INTO storage.buckets (id, name, public) VALUES ('coa-files', 'coa-files', true) ON CONFLICT DO NOTHING;
INSERT INTO storage.buckets (id, name, public) VALUES ('logos', 'logos', true) ON CONFLICT DO NOTHING;

-- Storage policies â€” allow public reads, admin writes
DROP POLICY IF EXISTS "Public can view product images" ON storage.objects;
CREATE POLICY "Public can view product images" ON storage.objects FOR SELECT USING (bucket_id IN ('products','coa-files','logos'));
DROP POLICY IF EXISTS "Admin can upload product images" ON storage.objects;
CREATE POLICY "Admin can upload product images" ON storage.objects FOR INSERT WITH CHECK (
    bucket_id IN ('products','coa-files','logos') AND
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);
DROP POLICY IF EXISTS "Admin can delete product images" ON storage.objects;
CREATE POLICY "Admin can delete product images" ON storage.objects FOR DELETE USING (
    bucket_id IN ('products','coa-files','logos') AND
    (auth.jwt() -> 'user_metadata' ->> 'role') = 'admin'
);
