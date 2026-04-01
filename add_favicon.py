import os
import glob

# Process root HTML files
files = glob.glob(r'd:\Peptide\*.html')
for f in files:
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    # Check if favicon already exists
    if '<link rel="icon"' not in content:
        content = content.replace('<head>', '<head>\n    <!-- Favicon -->\n    <link rel="icon" type="image/jpeg" href="Logo.jpeg">', 1)
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)
            print(f"Added favicon to {f}")

# Process admin HTML files (requires ../ path)
admin_files = glob.glob(r'd:\Peptide\admin\*.html')
for f in admin_files:
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    if '<link rel="icon"' not in content:
        content = content.replace('<head>', '<head>\n    <!-- Favicon -->\n    <link rel="icon" type="image/jpeg" href="../Logo.jpeg">', 1)
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)
            print(f"Added favicon to {f}")

print("Favicon injection complete!")
