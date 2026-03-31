
INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('tirz-peptide', 'Tirz Peptide', 'Peptide', 'products/Tirz-600x600.webp', 'Tirz peptide, high-purity lyophilized powder. Widely used in metabolic and wellness-related research by peptide resellers, wellness centers, and aesthetic clinics.', '≥ 99%', 'coa-files/Tirz COA (30.54mg).webp', '1box/10vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Tirz is a synthetic research peptide often studied for its role in supporting metabolic regulation and body composition management. Due to its advanced structure, it has attracted attention in both scientific and wellness-oriented research communities.', '•	Studies on metabolic activity modulation
•	Body weight and composition management models
•	Research on appetite response pathways
•	Wellness and body optimization studies', '✔️ Peptide sourcing companies and international distributors
✔️ Fitness and performance professionals
✔️ Wellness centers and anti-aging facilities
✔️ Aesthetic clinics and beauty hospitals
✔️ Boutique spas and cosmetic procurement teams
✔️ Scientific labs focused on body composition research')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('reta-peptide', 'Reta Peptide', 'Peptide', 'products/Reta-600x600.webp', 'Reta peptide, research-grade peptide widely used in metabolic and wellness studies. Trusted by fitness experts, wellness centers, and beauty clinics.', '≥ 99%', 'coa-files/Reta COA (10mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry environment', 'Reta is a multi-functional research peptide known for its activity in metabolic and energy regulation studies. It has been explored in various models related to weight management, body recomposition, and wellness optimization.', '•	Body composition and metabolic research
•	Energy balance and appetite control models
•	Studies related to weight and fitness optimization
•	Wellness and aesthetic body management exploration', '✔️ Fitness and body transformation specialists
✔️ Wellness and anti-aging centers
✔️ Aesthetic medical clinics and beauty hospitals
✔️ Peptide sourcing agents and global resellers
✔️ Laboratories focused on body regulation studies')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('tesam-peptide', 'Tesam Peptide', 'Peptide', 'products/Tesam-400x400.webp', 'Tesam peptide, high-purity research peptide used in body composition and anti-aging studies. Ideal for clinics, spas, and wellness research centers.', '≥ 99%', 'coa-files/Tesam COA (10mg).webp', '1box/10vial', 'Lyophilized powder', 'Store at -20°C in a dry, cool environment', 'Tesam is a synthetic peptide analog often researched for its role in regulating body fat distribution and promoting lean mass retention. It is of growing interest in both fitness and rejuvenation-focused study environments.', '•	Body fat and lean mass regulation models
•	Anti-aging and wellness studies
•	Physique enhancement research
•	Recovery and growth response applications', '✔️ Anti-aging and rejuvenation clinics
✔️ Beauty hospitals and cosmetic centers
✔️ Peptide wholesalers and distributors
✔️ Research labs in fitness and wellness sectors
✔️ Spa and wellness treatment centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('bpc-157-peptide', 'BPC-157 Peptide', 'Peptide', 'products/BPC-157-400x400.webp', 'High-purity BPC-157 peptide (10mg), widely used in research and performance-focused environments. Trusted by fitness professionals and peptide distributors.', '≥ 99%', 'coa-files/BPC157 COA (10mg).webp', '1box/10vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'BPC-157 is a synthetic research peptide known for its potential in promoting cellular regeneration and physical recovery. It is widely used in performance-related research and by those exploring advanced recovery strategies in physically demanding environments.', '•	Cellular regeneration studies
•	Muscle and tendon recovery optimization
•	Physical performance and stress response support
•	Inflammation response modulation', '✔️ Fitness professionals and trainers
✔️ Athletes seeking enhanced recovery solutions
✔️ Peptide sourcing companies and global distributors
✔️ Research-focused organizations and labs')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('tb-500-peptide', 'TB-500 Peptide', 'Peptide', 'products/BPC157-TB500-400x400.webp', 'TB-500 peptide (10mg), high-purity lyophilized powder used in performance and recovery research. Ideal for fitness professionals and peptide sourcing companies.', '≥ 99%', 'coa-files/TB500 COA (10mg).webp', '1box/10vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry environment', 'TB-500 is a synthetic research peptide modeled after a naturally occurring protein fragment known for its role in cellular activity. It is commonly explored for its potential in supporting physical recovery, mobility enhancement, and cellular response studies.', '•	Studies on muscle and tissue recovery
•	Mobility and flexibility research
•	Performance optimization support
•	Cellular function and response analysis', '✔️ Fitness professionals and strength coaches
✔️ Athletes involved in intense physical routines
✔️ Peptide wholesalers and sourcing companies
✔️ Research institutions and private labs')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('bpc-157-plus-tb500-peptide', 'BPC-157 + TB500 Peptide', 'Peptide', 'products/BPC157-TB500-400x400.webp', 'BPC-157 + TB500 peptide, high-purity research peptides for joint and tissue recovery. Ideal for fitness professionals, athletes, and peptide distributors.', '≥ 99%', '', '10mg each vial (BPC-157 & TB500)', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'The combination of BPC-157 and TB500 peptides is widely used in recovery and regenerative research. BPC-157 is known for its potential in promoting tissue healing, while TB500 supports joint and tendon repair. This dual peptide combination is increasingly popular in performance and rehabilitation research.', '•	Soft tissue and joint recovery studies
•	Tendon and ligament healing models
•	Post-exercise recovery and muscle regeneration
•	Studies on collagen synthesis and tissue repair
•	Inflammation reduction research', '✔️ Athletes and fitness professionals
✔️ Sports medicine clinics and rehabilitation centers
✔️ Peptide wholesalers and sourcing companies
✔️ Research labs focusing on tissue regeneration
✔️ Wellness centers and recovery-focused spas')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('glow-peptide', 'GLOW Peptide', 'Peptide', 'products/GLOW-400x400.webp', 'GLOW peptide, high-purity peptide designed for skin health and rejuvenation studies. Ideal for beauty clinics, wellness centers, and peptide distributors.', '≥ 99%', 'coa-files/GLOW COA (70mg).webp', '70mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'GLOW Peptide is a synthetic peptide developed for promoting skin health, radiance, and rejuvenation. It is widely studied for its potential in improving skin elasticity, reducing wrinkles, and promoting collagen synthesis, making it a popular choice for beauty and wellness professionals.', '•	Skin rejuvenation and anti-aging studies
•	Collagen production and skin elasticity research
•	Brightening and pigmentation studies
•	Anti-wrinkle and skin tone enhancement research
•	Peptide influence on skin cell regeneration and renewal', '✔️ Beauty clinics and dermatology centers
✔️ Wellness and anti-aging spas
✔️ Peptide wholesalers and beauty product distributors
✔️ Research labs focusing on skin health and rejuvenation
✔️ Aesthetic professionals and wellness consultants')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('botox-peptide', 'BOTOX Peptide', 'Peptide', 'products/Botulinum-toxin-400x400.webp', 'BOTOX peptide, a high-purity research peptide used in wrinkle reduction and skin rejuvenation studies. Trusted by aesthetic clinics and peptide distributors.', '≥ 99%', '', '10mg/10vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'BOTOX Peptide is a synthetic peptide used in aesthetic research focused on skin rejuvenation and wrinkle reduction. It mimics the effects of botulinum toxin, and is commonly used in studies related to facial muscle relaxation, skin smoothing, and anti-aging interventions.', '•	Wrinkle reduction and facial muscle relaxation studies
•	Skin rejuvenation and anti-aging research
•	Peptide applications for cosmetic and aesthetic treatments
•	Studies on muscle inhibition for cosmetic purposes', '✔️ Aesthetic clinics and beauty hospitals
✔️ Anti-aging spas and wellness centers
✔️ Peptide wholesalers and beauty product distributors
✔️ Research institutions focused on cosmetic treatments
✔️ Global peptide resellers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('aod9604-peptide', 'AOD9604 Peptide', 'Peptide', 'products/AOD9604-400x400.webp', 'AOD9604 peptide, high-purity lyophilized powder designed for metabolic and performance-related research. Ideal for labs, fitness professionals, and peptide resellers.', '≥ 98%', 'coa-files/AOD9604 COA.webp', '1box/10via', 'Lyophilized powder', 'Store at -20°C in a dry, dark place', 'AOD9604 is a synthetic peptide fragment derived from human growth factor sequences. It has been widely researched for its potential in metabolic studies, body composition regulation, and physical performance support.', '•	Body composition optimization studies
•	Metabolic activity research
•	Physical performance and endurance response
•	Recovery and stress adaptation models', '✔️ Fitness professionals and trainers
✔️ Peptide distributors and sourcing agents
✔️ Athletic performance research teams
✔️ Laboratories focusing on metabolic studies')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('nadplus-peptide', 'NAD+ Peptide', 'Peptide', 'products/NAD-400x400.webp', 'NAD+ peptide, high-purity peptide used in energy metabolism, anti-aging, and longevity research. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', 'coa-files/NAD+ COA (500mg).webp', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'NAD+ (Nicotinamide Adenine Dinucleotide) is a crucial coenzyme in the body involved in energy production, DNA repair, and cellular metabolism. As NAD+ levels naturally decline with age, it has become a significant focus in anti-aging and longevity research. NAD+ peptides are being studied for their potential to replenish cellular NAD+ levels, improve mitochondrial function, enhance energy metabolism, and promote longevity.', '•	Energy metabolism and mitochondrial function studies
•	Anti-aging and longevity research
•	NAD+ replenishment and cellular repair
•	Studies on metabolic rate and fat oxidation
•	Research on NAD+ boosting compounds for health optimization', '✔️ Wellness centers and anti-aging clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on longevity and energy metabolism
✔️ Fitness professionals and athletes
✔️ Health optimization and rejuvenation centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('epithalon-peptide', 'Epithalon Peptide', 'Peptide', 'products/Epithalon-400x400.webp', 'Epithalon peptide, known for longevity-related research. A popular choice among anti-aging labs, wellness centers, and beauty spas.', '≥ 99%', 'coa-files/Epithalon COA (10mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a dry, protected area', 'Epithalon is a research peptide frequently studied for its potential in longevity and rejuvenation models. Its popularity has grown among facilities exploring anti-aging and cellular regulation strategies.', '•	Longevity and anti-aging research
•	Cellular regeneration models
•	Oxidative stress response studies
•	Wellness and vitality-related experiments', '✔️ Anti-aging and rejuvenation clinics
✔️ Beauty hospitals and wellness centers
✔️ Global peptide distributors
✔️ Research labs focusing on longevity')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('cagr-peptide', 'Cagr Peptide', 'Peptide', 'products/cagrilin-400x400.webp', 'Cagr peptide, high-purity research peptide designed for metabolic and weight-focused studies. Trusted by peptide distributors and wellness clinics.', '≥ 99%', 'coa-files/Cagrilin COA (10mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry environment', 'Cagr is a synthetic research peptide explored for its impact on appetite regulation and body composition management. It’s increasingly studied in advanced metabolic and weight optimization models.', '•	Weight control and appetite regulation research
•	Body composition and metabolic studies
•	Satiety signal pathway analysis
•	Wellness-focused performance studies', '✔️ Peptide sourcing companies and distributors
✔️ Beauty clinics and wellness spas
✔️ Fitness professionals and transformation coaches
✔️ Research teams focused on metabolic models')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('melanotan-ii-mt-2-peptide', 'Melanotan II (MT-2) Peptide', 'Peptide', 'products/MT-2-400x400.webp', 'MT-2 peptide, lyophilized research-grade peptide commonly used in pigmentation and appearance-related studies. Favored by aesthetic centers and peptide labs.', '≥ 99%', 'coa-files/MT-2 COA (10mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a dry, shaded place', 'MT-2 (Melanotan II) is a synthetic peptide studied for its effect on pigmentation and melanogenesis models. It’s frequently researched in appearance-focused and aesthetic science sectors.', '•	Skin tone and pigmentation research
•	Tanning response pathways
•	Melanogenesis and peptide function studies
•	Beauty and appearance optimization studies', '✔️ Aesthetic clinics and skin wellness spas
✔️ Beauty-focused peptide sourcing companies
✔️ Research labs exploring pigmentation peptides
✔️ Anti-aging and rejuvenation centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('mots-c-peptide', 'MOTS-c Peptide', 'Peptide', 'products/MOTS-C-400x400.webp', 'MOTS-c peptide, a high-purity mitochondrial peptide used in energy, metabolism, and performance research. Popular among labs and health clinics.', '≥ 99%', 'coa-files/MOTS-c COA (10mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C, avoid light and moisture', 'MOTS-c is a mitochondrial-derived peptide widely studied for its role in metabolic regulation, energy production, and cellular protection. It’s a rising subject in performance and anti-aging research.', '•	Mitochondrial function and energy studies
•	Cellular protection and metabolism regulation
•	Fatigue resistance and stress models
•	Longevity and endurance performance research', '✔️ Health optimization centers
✔️ Longevity and rejuvenation spas
✔️ Peptide resellers and sourcing companies
✔️ Scientific research institutions')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('pinealon-peptide', 'Pinealon Peptide', 'Peptide', 'products/Pinealon-400x400.webp', 'Pinealon peptide, high-purity peptide used in cognitive enhancement, neuroprotection, and anti-aging research. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', 'coa-files/Pinealon COA (5mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Pinealon is a synthetic peptide derived from pineal gland proteins. It is studied for its potential neuroprotective, anti-aging, and cognitive enhancement effects. Pinealon is of growing interest in research related to cognitive function, memory, neurodegenerative disease prevention, and overall brain health. It is also explored for its potential to improve sleep quality and help with stress regulation.', '•	Cognitive function and memory enhancement studies
•	Neuroprotection and brain health research
•	Anti-aging and longevity studies
•	Peptide influence on sleep quality and stress management
•	Studies related to neurodegenerative disease prevention', '✔️ Wellness centers and brain health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on cognitive function and aging
✔️ Anti-aging professionals and neurohealth consultants
✔️ Sleep and stress regulation specialists')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('sema-peptide', 'Sema Peptide', 'Peptide', 'products/Sema-400x400.webp', 'Sema peptide, high-purity lyophilized powder. Widely used in metabolic and wellness-related research by peptide resellers, wellness centers, and aesthetic clinics.', '≥ 99%', 'coa-files/Sema COA (10mg).webp', '1box/10vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Sema is a synthetic research peptide often studied for its role in supporting metabolic regulation and body composition management. Due to its advanced structure, it has attracted attention in both scientific and wellness-oriented research communities.', '•	Studies on metabolic activity modulation
•	Body weight and composition management models
•	Research on appetite response pathways
•	Wellness and body optimization studies', '✔️ Peptide sourcing companies and international distributors
✔️ Fitness and performance professionals
✔️ Wellness centers and anti-aging facilities
✔️ Aesthetic clinics and beauty hospitals
✔️ Boutique spas and cosmetic procurement teams
✔️ Scientific labs focused on body composition research')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('vip-peptide', 'VIP Peptide', 'Peptide', 'products/VIP-400x400.webp', 'VIP peptide, high-purity peptide used for immune modulation, inflammation reduction, and neuroprotection studies. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Vasoactive Intestinal Peptide (VIP) is a neuropeptide involved in various physiological functions, including immune modulation, inflammation reduction, and neuroprotection. VIP is studied for its ability to improve cellular function, support healthy immune responses, and reduce inflammation, making it a popular choice for research on autoimmune diseases, neurodegenerative conditions, and general health optimization.', '•	Immune modulation and immune system enhancement
•	Anti-inflammatory and neuroprotective studies
•	Research on peptides for autoimmune disease management
•	Peptide effects on brain and cognitive health
•	Studies related to inflammation and cellular regeneration', '✔️ Wellness centers and immune health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on immune modulation and neuroprotection
✔️ Health optimization and anti-aging specialists
✔️ Neurology and autoimmune disease treatment centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('thymosin-alpha-1-peptide', 'Thymosin-Alpha-1 Peptide', 'Peptide', 'products/Thymosin-Alpha-1-400x400.webp', 'Thymosin-Alpha-1 peptide, high-purity peptide used for immune modulation, anti-aging, and wound healing research. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Thymosin-Alpha-1 is a synthetic peptide derived from the thymus gland, known for its role in regulating the immune system and enhancing immune response. It is widely researched for its potential in immune support, wound healing, and anti-aging. Thymosin-Alpha-1 has been explored in studies related to immune modulation, chronic infection management, and cellular regeneration.', '•	Immune system modulation and immune response enhancement
•	Wound healing and tissue regeneration studies
•	Anti-aging and longevity research
•	Peptide influence on immune cell activation and proliferation
•	Research on Thymosin-Alpha-1’s impact on chronic diseases and infections', '✔️ Wellness centers and immune health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on immune system and healing
✔️ Health optimization professionals and regenerative medicine specialists
✔️ Medical centers dealing with chronic infections and immune dysfunction')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('thymalin-peptide', 'Thymalin Peptide', 'Peptide', 'products/Thymalin-400x400.webp', 'Thymalin peptide, high-purity peptide used for immune system support, anti-aging, and cellular regeneration research. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Thymalin is a synthetic peptide derived from thymic proteins that plays a crucial role in modulating immune function. It is widely studied for its potential to boost immune response, support cellular regeneration, and reduce inflammation. Thymalin has been explored for its applications in immune system enhancement, anti-aging, and overall vitality restoration.', '•	Immune system modulation and immune support studies
•	Anti-aging and longevity research
•	Cellular regeneration and tissue repair studies
•	Research on peptide influence on inflammation reduction
•	Studies on thymus function and immune cell production', '✔️ Wellness centers and immune health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on immune system and anti-aging
✔️ Health optimization professionals and vitality specialists
✔️ Regenerative medicine centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('survodutide-peptide', 'Survodutide Peptide', 'Peptide', 'products/Survodutide-400x400.webp', 'Survodutide peptide, high-purity peptide used for metabolic and body composition research. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Survodutide is a synthetic peptide studied for its potential to regulate metabolism, enhance body composition, and support fat loss. It is increasingly explored in metabolic health studies and research focused on energy expenditure, insulin sensitivity, and body fat reduction. Survodutide has gained attention for its role in improving physical performance and overall metabolic function.', '•	Metabolic regulation and fat loss studies
•	Body composition and muscle mass optimization
•	Peptide influence on insulin sensitivity and energy metabolism
•	Studies on peptide effects on weight management and fat oxidation
•	Research on metabolic diseases and obesity treatment', '✔️ Fitness professionals and bodybuilders
✔️ Wellness centers and metabolic health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on metabolic health
✔️ Health optimization centers and weight loss specialists')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('ss-31-peptide', 'SS-31 Peptide', 'Peptide', 'products/SS-31-400x400.webp', 'SS 31 peptide, high-purity peptide used for mitochondrial health, anti-aging, and cellular regeneration studies. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', 'coa-files/SS-31 COA (10mg).webp', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'SS 31 is a synthetic peptide that is widely studied for its potential to protect and improve mitochondrial function. It is known for its role in promoting cellular energy production and reducing oxidative stress, which are essential for anti-aging and longevity research. SS-31 has been explored in studies related to mitochondrial dysfunction, aging, and regenerative health.', '•	Mitochondrial health and function studies
•	Anti-aging and longevity research
•	Cellular regeneration and repair
•	Peptide influence on oxidative stress reduction
•	Research on mitochondrial dysfunction in age-related diseases', '✔️ Wellness centers and anti-aging clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on mitochondrial health
✔️ Health optimization and longevity specialists
✔️ Regenerative medicine professionals')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('snap-8-peptide', 'Snap-8 Peptide', 'Peptide', 'products/Snap-8-400x400.webp', 'Snap-8 peptide, high-purity peptide used for wrinkle reduction, skin rejuvenation, and anti-aging studies. Ideal for beauty clinics, wellness centers, and peptide distributors.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Snap-8 is a synthetic peptide and a modified version of acetyl hexapeptide-3, studied for its ability to reduce the appearance of wrinkles and fine lines. It works by inhibiting the release of neurotransmitters, which helps relax facial muscles and smooth the skin, making it a popular peptide in anti-aging and skin rejuvenation research.', '•	Wrinkle reduction and anti-aging studies
•	Skin rejuvenation and smoothing research
•	Peptide influence on muscle relaxation and facial lines
•	Studies on skin elasticity and collagen production
•	Research on peptides for reducing signs of aging', '✔️ Beauty clinics and dermatology centers
✔️ Wellness and anti-aging spas
✔️ Peptide wholesalers and beauty product distributors
✔️ Research labs focusing on skin health and rejuvenation
✔️ Cosmetic professionals and dermatologists')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('slu-pp-332-peptide', 'SLU-PP-332 Peptide', 'Peptide', 'products/SLU-PP-332-400x400.webp', 'SLU-PP-332 peptide, high-purity peptide used in cognitive function and neuroprotective research. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vial/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'SLU-PP-332 is a synthetic peptide studied for its potential neuroprotective and cognitive-enhancing properties. It is explored for its effects on brain health, focusing on cognitive function improvement, neuroprotection, and overall brain regeneration. Research on SLU-PP-332 has shown its potential to support mental clarity, memory retention, and brain aging prevention.', '•	Cognitive function and memory enhancement studies
•	Neuroprotective research for brain health
•	Peptide influence on brain regeneration and repair
•	Studies related to neurodegenerative disease prevention
•	Research on cognitive disorders and aging', '✔️ Wellness centers and cognitive health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on brain health and cognitive function
✔️ Neurologists and mental health professionals
✔️ Health optimization centers and brain regeneration specialists')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('semax-peptide', 'Semax Peptide', 'Peptide', 'products/Semax-400x400.webp', 'Semax peptide, high-purity peptide used for cognitive enhancement, memory improvement, and neuroprotection studies. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', 'coa-files/Semax COA (10mg).webp', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Semax is a synthetic peptide derived from the naturally occurring neurotrophic peptide, which is studied for its potential cognitive-enhancing, neuroprotective, and memory-boosting effects. It is commonly explored for its impact on brain health, particularly in improving cognitive function, memory retention, and stress response.', '•	Cognitive enhancement and memory improvement studies
•	Neuroprotective research for brain health
•	Stress response and mood stabilization studies
•	Peptide influence on learning and retention
•	Studies on peptide effects in neurodegenerative diseases', '✔️ Wellness centers and brain health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on cognitive function and neuroprotection
✔️ Neuroscientists and mental health professionals
✔️ Health optimization and rejuvenation centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('selank-peptide', 'Selank Peptide', 'Peptide', 'products/Selank-400x400.webp', 'Selank peptide, high-purity peptide used for cognitive enhancement, anxiety reduction, and stress management research. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', 'coa-files/Selank COA (10mg).webp', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Selank is a synthetic peptide developed for its neuroprotective and cognitive-enhancing properties. It is widely studied for its potential to improve memory, reduce anxiety, and manage stress. Selank has gained popularity in research related to cognitive function, mood enhancement, and its potential use in mental health treatments.', '•	Cognitive enhancement and memory improvement studies
•	Anxiety reduction and mood stabilization research
•	Stress management and relaxation studies
•	Neuroprotective and brain health applications
•	Peptide influence on neurotransmitter systems (e.g., serotonin, dopamine)', '✔️ Wellness centers and mental health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on cognitive and mood-enhancing peptides
✔️ Medical professionals in neurology and psychiatry
✔️ Health optimization centers and stress management specialists')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('pt-141-peptide', 'PT-141 Peptide', 'Peptide', 'products/PT-141-400x400.webp', 'PT-141 peptide, high-purity peptide used for sexual health and arousal studies. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'PT-141 (Bremelanotide) is a synthetic peptide studied for its potential to enhance sexual function and arousal. It works by activating the melanocortin receptors in the brain, which play a crucial role in sexual behavior. PT-141 is commonly researched for its effects on libido enhancement and treatment of sexual dysfunction.', '•	Sexual health and arousal studies
•	Research on libido enhancement and sexual dysfunction treatments
•	Peptide influence on brain receptors related to sexual behavior
•	Studies on the effects of PT-141 in sexual wellness
•	Research on alternative treatments for erectile dysfunction', '✔️ Wellness centers and sexual health clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on sexual wellness and dysfunction treatments
✔️ Health optimization and rejuvenation centers
✔️ Medical professionals in sexual health')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('pnc-27-peptide', 'PNC-27 Peptide', 'Peptide', 'products/PNC-27-400x400.webp', 'PNC-27 peptide, high-purity peptide known for its anti-cancer potential and targeted tumor therapy research. Ideal for peptide distributors, research labs, and wellness centers.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'PNC-27 is a synthetic peptide being studied for its potential to target and induce apoptosis (programmed cell death) in cancer cells. It is a peptide derived from the protein p53, known for its tumor-suppressing activity. Research on PNC-27 primarily focuses on its ability to selectively bind to cancer cells, making it a promising candidate in targeted cancer therapies.', '•	Anti-cancer and tumor-targeting research
•	Apoptosis induction in cancer cells
•	Peptide influence on cellular cancer suppression
•	Studies related to tumor cell selectivity and targeting
•	Research into targeted cancer therapies and personalized medicine', '✔️ Research labs focusing on cancer therapy
✔️ Peptide wholesalers and distributors
✔️ Oncologists and cancer treatment specialists
✔️ Cancer research institutions
✔️ Pharmaceutical companies researching cancer treatments')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('ll37-peptide', 'LL37 Peptide', 'Peptide', 'products/LL37-400x400.webp', 'LL37 peptide, high-purity antimicrobial peptide used in immune support, skin health, and inflammation studies. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'LL37 is a naturally occurring antimicrobial peptide known for its immune-modulating, anti-inflammatory, and wound-healing properties. It plays a critical role in the body’s first line of defense against pathogens, and it is widely studied in the fields of immune support, skin health, and tissue repair. LL37 is particularly beneficial in promoting skin rejuvenation, reducing inflammation, and supporting overall skin barrier function.', '•	Antimicrobial and immune modulation studies
•	Skin health and wound healing research
•	Inflammation reduction and anti-inflammatory studies
•	Peptide influence on skin regeneration and repair
•	Studies related to cellular protection and defense mechanisms', '✔️ Wellness centers and dermatology clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on immune response and skin health
✔️ Beauty and aesthetic professionals
✔️ Health optimization centers and tissue regeneration specialists')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('lemon-bottle-peptide', 'Lemon Bottle Peptide', 'Peptide', 'products/Lemon-Bottle-400x400.webp', 'Lemon Bottle peptide , high-purity peptide used in weight loss and body composition research. Ideal for fitness professionals, wellness centers, and peptide distributors.', '≥ 99%', '', '10vials/boxl', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Lemon Bottle Peptide is a synthetic peptide designed for research in weight loss and body composition optimization. It is increasingly used in metabolic studies to explore its potential effects on fat metabolism and appetite regulation. This peptide has gained popularity among researchers focusing on body fat reduction, energy expenditure, and metabolic health.', '•	Weight loss and fat metabolism studies
•	Appetite regulation and energy balance research
•	Body composition optimization and fat loss enhancement
•	Peptide influence on metabolic rate
•	Research into peptides that influence appetite suppression and thermogenesis', '✔️ Fitness professionals and bodybuilders
✔️ Wellness and weight loss centers
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on metabolism and fat loss
✔️ Health optimization centers and slimming clinics')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('kpv-peptide', 'KPV Peptide', 'Peptide', 'products/KPV-400x400 (1).webp', 'KPV peptide, high-purity peptide known for its anti-inflammatory and healing properties. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'KPV (Lysine-Proline-Valine) is a peptide derived from α-MSH (alpha-melanocyte-stimulating hormone), studied for its potent anti-inflammatory, skin-healing, and immune-boosting properties. It is increasingly used in research related to inflammation, wound healing, and tissue regeneration. KPV peptide has gained attention for its potential to promote tissue repair and reduce inflammation.', '•	Anti-inflammatory and immune response studies
•	Skin healing and wound regeneration research
•	Research on tissue repair and cellular regeneration
•	Peptide influence on inflammation reduction
•	Studies related to oxidative stress and tissue repair pathways', '✔️ Wellness centers and rehabilitation clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on immune system and tissue healing
✔️ Beauty and dermatology centers
✔️ Health optimization professionals')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('ipamorelin-peptide', 'Ipamorelin Peptide', 'Peptide', 'products/Ipamorelin-400x400 (1).webp', 'Full Product Description', '≥ 99%', 'coa-files/Ipamorelin COA (10mg).webp', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Ipamorelin is a selective growth hormone secretagogue that stimulates the pituitary gland to release growth hormone. It is widely studied for its ability to promote muscle growth, support fat loss, enhance recovery, and improve overall wellness. Its use in fitness and wellness research has made it a popular choice among athletes and professionals focused on performance enhancement.', '•	Growth hormone secretion and regulation studies
•	Muscle growth and tissue repair research
•	Fat loss and body composition optimization
•	Recovery enhancement and regeneration studies
•	Anti-aging and wellness research', '✔️ Fitness professionals and bodybuilders
✔️ Sports medicine and rehabilitation centers
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on muscle growth and fat loss
✔️ Wellness centers specializing in recovery and rejuvenation')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('igf-des-peptide', 'IGF-DES Peptide', 'Peptide', 'products/IGF-DES-400x400 (1).webp', 'IGF-DES peptide, high-purity growth factor peptide used for muscle growth, tissue repair, and performance enhancement studies. Ideal for fitness professionals, peptide distributors, and wellness centers.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'IGF-DES (Insulin-like Growth Factor – Domain E) is a truncated version of IGF-1, with a shorter peptide chain. This modification increases its potency in promoting muscle growth and tissue repair while having a more focused effect on anabolic processes, making it ideal for research in performance enhancement and recovery.', '•	Muscle growth and tissue regeneration studies
•	Anabolic and catabolic process regulation
•	Peptide influence on protein synthesis and muscle repair
•	Fat loss and body composition optimization
•	Research on growth factors for recovery enhancement', '✔️ Fitness professionals and bodybuilders
✔️ Sports medicine and rehabilitation centers
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on muscle growth and regeneration
✔️ Wellness centers specializing in body optimization')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('igf-1-lr3-peptide', 'IGF-1 LR3 Peptide', 'Peptide', 'products/IGF-1LR3-400x400.webp', 'IGF-1 LR3 peptide, high-purity growth factor peptide used for muscle growth, tissue repair, and anti-aging research. Ideal for fitness professionals, wellness centers, and peptide distributors.', '≥ 99%', 'coa-files/IGF-1LR3 COA (1mg).webp', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'IGF-1 LR3 (Insulin-like Growth Factor-1 Long R3) is a synthetic peptide analog of IGF-1, designed to enhance the biological activity of the original hormone. It is widely used in research related to muscle growth, tissue repair, and anti-aging due to its potent anabolic effects and ability to stimulate cell growth and regeneration.', '•	Muscle growth and tissue regeneration studies
•	Anti-aging and longevity research
•	Fat loss and body composition optimization
•	Cell growth and wound healing models
•	Peptide influence on protein synthesis and muscle repair', '✔️ Fitness professionals and bodybuilders
✔️ Sports medicine and rehabilitation centers
✔️ Peptide wholesalers and distributors
✔️ Research labs focused on growth factors and performance enhancement
✔️ Wellness and anti-aging clinics')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('hmg-peptide', 'HMG Peptide', 'Peptide', 'products/HMG-400x400.webp', 'HMG peptide, high-purity peptide used in fertility and hormonal regulation studies. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Human Menopausal Gonadotropin (HMG) is a hormone peptide commonly used in fertility research and treatment. It is composed of luteinizing hormone (LH) and follicle-stimulating hormone (FSH), which play vital roles in regulating reproductive health. HMG peptide is widely studied for its role in stimulating ovulation and regulating hormonal balance in both men and women.', '•	Fertility and reproductive health studies
•	Ovulation stimulation and hormonal regulation
•	Peptide influence on reproductive cycles
•	Hormonal therapy and restoration research
•	Clinical applications for fertility treatment', '✔️ Fertility clinics and reproductive health centers
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on fertility and hormonal regulation
✔️ Wellness centers and hormone balance professionals
✔️ Health optimization centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('hgh-peptide', 'HGH Peptide', 'Peptide', 'products/HGH-400x400.webp', 'HGH peptide, high-purity peptide used in growth hormone secretion and anti-aging studies. Ideal for muscle growth, recovery, and wellness research.', '≥ 99%', '', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Human Growth Hormone (HGH) is a peptide hormone crucial for growth, development, and cellular regeneration. It is widely studied for its effects on muscle growth, fat loss, tissue repair, and anti-aging. HGH is highly sought after for its potential to improve athletic performance, promote recovery, and enhance overall wellness.', '•	Growth hormone secretion and regulation studies
•	Muscle growth and tissue repair research
•	Anti-aging and longevity studies
•	Fat loss and body composition optimization
•	Peptide influence on recovery and regeneration', '✔️ Fitness professionals and bodybuilders
✔️ Anti-aging and wellness clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on growth hormone and performance
✔️ Sports medicine and rehabilitation centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('hcg-peptide', 'HCG Peptide', 'Peptide', 'products/HCG-400x400.webp', 'HCG peptide, high-purity peptide known for its role in metabolic and hormonal regulation. Ideal for fitness professionals, wellness centers, and peptide distributors.', '≥ 99%', 'coa-files/HCG COA (5000iu).webp', 'vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Human Chorionic Gonadotropin (HCG) is a glycoprotein hormone that plays a crucial role in metabolic and hormonal balance. It is widely studied for its effects on fat loss, metabolic regulation, and its use in fertility treatments. In the peptide research field, HCG is often explored for its potential in stimulating testosterone production and supporting fat-burning processes.', '•	Metabolic and fat loss studies
•	Hormonal regulation and balance
•	Fertility research and reproductive health
•	Peptide influence on testosterone stimulation
•	Weight management and body composition studies', '✔️ Fitness professionals and bodybuilders
✔️ Wellness centers and hormone balance clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on metabolic and hormonal regulation
✔️ Fertility and reproductive health clinics')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('glutathione-peptide', 'Glutathione Peptide', 'Peptide', 'products/Glutathione-400x400.webp', 'Glutathione peptide, high-purity peptide known for its antioxidant properties and skin health benefits. Ideal for wellness centers, beauty clinics, and peptide distributors.', '≥ 99%', 'coa-files/Glutathione COA (1500mg).webp', '10vials/box', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'Glutathione is a powerful antioxidant peptide naturally found in the body. It is widely studied for its role in detoxification, immune support, and skin health, particularly for its ability to brighten the skin and reduce oxidative stress. This peptide is highly sought after for its rejuvenating properties, especially in beauty and wellness applications.', '•	Antioxidant and detoxification studies
•	Skin lightening and pigmentation research
•	Anti-aging and cellular regeneration studies
•	Immune system support research
•	Peptide influence on oxidative stress and inflammation reduction', '✔️ Beauty clinics and dermatology centers
✔️ Wellness and anti-aging spas
✔️ Peptide wholesalers and beauty product distributors
✔️ Research labs focusing on skin health, aging, and detoxification
✔️ Health optimization centers and wellness professionals')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('ghrp-6-peptide', 'GHRP-6 Peptide', 'Peptide', 'products/GHRP-6-400x400.webp', 'GHRP-6 peptide, high-purity peptide used in growth hormone secretion studies. Ideal for muscle growth, fat loss, and fitness research.', '≥ 99%', 'coa-files/GHRP-6 COA (10mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'GHRP-6 (Growth Hormone Releasing Peptide-6) is a synthetic peptide that stimulates the release of growth hormone from the pituitary gland. It is widely researched for its effects on muscle growth, fat loss, and recovery, making it a popular choice for performance enhancement and fitness-related studies.', '•	Growth hormone secretion and regulation studies
•	Muscle growth and tissue repair research
•	Fat loss and body composition optimization
•	Recovery and endurance studies
•	Peptide influence on hunger regulation and appetite control', '✔️ Fitness professionals and bodybuilders
✔️ Sports medicine and rehabilitation centers
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on muscle growth and fat loss
✔️ Laboratories studying recovery and growth hormone effects')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('ghk-cu-peptide', 'GHK-CU Peptide', 'Peptide', 'products/GHK-CU-400x400.webp', 'GHK-CU peptide, high-purity peptide known for its skin rejuvenation and tissue repair properties. Ideal for beauty clinics, wellness centers, and peptide distributors.', '≥ 99%', '', '50mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'GHK-CU is a naturally occurring copper peptide known for its skin regeneration, anti-aging, and wound healing properties. It is widely studied in research focused on tissue repair, skin rejuvenation, and the overall enhancement of skin health, making it a popular choice in aesthetic and wellness applications.', '•	Skin rejuvenation and anti-aging studies
•	Tissue regeneration and wound healing models
•	Collagen production and skin elasticity research
•	Peptide influence on cellular repair and regeneration
•	Beauty and wellness treatments focusing on skin health', '✔️ Beauty clinics and dermatology centers
✔️ Wellness and anti-aging spas
✔️ Peptide wholesalers and beauty product distributors
✔️ Research labs focused on skin health and tissue repair
✔️ Aesthetic professionals and wellness consultants')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('dsip-peptide', 'DSIP Peptide', 'Peptide', 'products/DSIP-400x400.webp', 'DSIP peptide, high-purity research peptide used for sleep regulation and stress response studies. Ideal for wellness centers, peptide distributors, and research labs.', '≥ 99%', 'coa-files/DSIP COA (5mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'DSIP (Delta Sleep-Inducing Peptide) is a naturally occurring peptide studied for its role in regulating sleep patterns and managing stress. It is widely used in research focused on improving sleep quality, enhancing recovery, and modulating hormonal balance related to rest.', '•	Sleep regulation and circadian rhythm studies
•	Stress response and relaxation models
•	Sleep quality and recovery research
•	Peptide influence on neurotransmitter and hormone balance
•	Studies on neuropeptide pathways involved in sleep', '✔️ Wellness centers and sleep clinics
✔️ Peptide wholesalers and distributors
✔️ Research institutions focused on sleep and stress management
✔️ Laboratories researching neuropeptides and recovery processes
✔️ Health optimization and anti-aging clinics')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('cjc-1295-no-dac-plus-ipamorelin-peptide', 'CJC-1295 NO DAC + Ipamorelin Peptide', 'Peptide', 'products/CJC1295-NO-DAC-IPA-400x400.webp', 'CJC-1295 NO DAC + Ipamorelin peptide, high-purity growth hormone releasing peptides used for muscle growth, fat loss, and anti-aging research. Ideal for fitness professionals and wellness centers.', '≥ 99%', 'coa-files/CJC1295 NO DAC+IPA COA.webp', '10mg each vial (CJC-1295 NO DAC & Ipamorelin)', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'The combination of CJC-1295 NO DAC and Ipamorelin is a powerful peptide stack known for promoting growth hormone release, which can help in muscle growth, fat loss, and anti-aging research. CJC-1295 NO DAC stimulates growth hormone secretion, while Ipamorelin enhances the body’s natural GH production. This stack is widely used for performance optimization and wellness studies.', '•	Growth hormone release and regulation studies
•	Muscle development and tissue regeneration research
•	Fat loss and body composition optimization
•	Anti-aging and rejuvenation studies
•	Research on synergistic peptide effects in fitness and wellness', '✔️ Fitness professionals and bodybuilders
✔️ Anti-aging clinics and wellness centers
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on muscle growth and fat loss
✔️ Sports medicine and recovery specialists')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('cjc-1295-no-dac-peptide', 'CJC-1295 NO DAC Peptide', 'Peptide', 'products/CJC-1295-NO-DAC-400x400.webp', 'CJC-1295 NO DAC peptide, high-purity growth hormone releasing peptide used for muscle growth and anti-aging research. Trusted by peptide distributors and wellness professionals.', '≥ 99%', 'coa-files/CJC-1295-NO-DAC COA (5mg).webp', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'CJC-1295 NO DAC is a synthetic peptide known for its ability to stimulate growth hormone secretion without the extended half-life of its DAC variant. It’s used in research focused on muscle growth, fat loss, and anti-aging, making it a popular choice for fitness and wellness studies.', '•	Growth hormone release and regulation studies
•	Muscle development and tissue repair research
•	Fat loss and body composition studies
•	Anti-aging and rejuvenation research
•	Peptide stability and secretion studies', '✔️ Fitness professionals and bodybuilders
✔️ Anti-aging and wellness clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on growth hormone and fat loss
✔️ Sports medicine and rehabilitation centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('cjc-1295-dac-peptide', 'CJC-1295 DAC Peptide', 'Peptide', 'products/CJC-1295-DAC-400x400.webp', 'CJC-1295 DAC peptide, high-purity growth hormone releasing peptide used in performance and longevity research. Trusted by peptide distributors and wellness centers.', '≥ 99%', '', '5mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry place', 'CJC-1295 DAC is a synthetic peptide that stimulates growth hormone release by increasing secretion from the pituitary gland. This peptide has a prolonged half-life, making it ideal for performance enhancement, muscle growth, and anti-aging studies.', '•	Growth hormone release and modulation studies
•	Muscle and tissue growth research
•	Anti-aging and longevity research
•	Performance enhancement in sports and fitness
•	Peptide stability and extended-release studies', '✔️ Athletes and fitness professionals
✔️ Wellness and anti-aging clinics
✔️ Peptide wholesalers and distributors
✔️ Research labs focusing on growth hormone and recovery
✔️ Sports medicine and rehabilitation centers')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;

INSERT INTO public.products (id, name, category, image_path, description, purity, coa_path, specification, form, storage, overview, applications, target_users)
VALUES ('cerebrolysin-peptide', 'Cerebrolysin Peptide', 'Peptide', 'products/cerebrolysim-400x400.webp', 'Cerebrolysin peptide, high-purity neuropeptide used in cognitive and neuroprotective research. Ideal for research labs, wellness centers, and peptide distributors.', '≥ 99%', '', '10mg/vial', 'Lyophilized powder', 'Store at -20°C in a cool, dry environment', 'Cerebrolysin is a neuropeptide that has been widely studied for its potential neuroprotective and cognitive-enhancing properties. It has gained attention in both cognitive function research and neurodegenerative disease studies due to its potential to support brain health, improve memory, and protect neurons.', '•	Neuroprotective and cognitive function studies
•	Memory enhancement and neuroplasticity research
•	Research on neurodegenerative diseases
•	Brain health and regeneration models', '✔️ Research labs focusing on cognitive and neuroprotective research
✔️ Wellness centers exploring brain health and cognitive enhancement
✔️ Peptide wholesalers and global distributors
✔️ Neurogenesis and neuroplasticity researchers
✔️ Clinics and institutions focused on cognitive wellness
is it now off line, i noticed alot of issues, i do noy know if the error is from the code or deployment, when i entered the site it to me to the front end of the page and it showed me that return to dashboard meaning someone could techinaclly get to the admin dashboard, please coorect that, please go through the full codes and correct all bug')
ON CONFLICT (id) DO UPDATE SET
name = EXCLUDED.name,
category = EXCLUDED.category,
image_path = EXCLUDED.image_path,
description = EXCLUDED.description,
purity = EXCLUDED.purity,
coa_path = EXCLUDED.coa_path,
specification = EXCLUDED.specification,
form = EXCLUDED.form,
storage = EXCLUDED.storage,
overview = EXCLUDED.overview,
applications = EXCLUDED.applications,
target_users = EXCLUDED.target_users;
