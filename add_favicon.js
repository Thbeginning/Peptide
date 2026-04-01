const fs = require('fs');

const files = fs.readdirSync('.', { withFileTypes: true })
    .filter(dirent => !dirent.isDirectory() && dirent.name.endsWith('.html'))
    .map(dirent => dirent.name);

for (const file of files) {
    let content = fs.readFileSync(file, 'utf8');
    if (!content.includes('<link rel="icon"')) {
        content = content.replace('<head>', '<head>\n    <!-- Favicon -->\n    <link rel="icon" type="image/jpeg" href="Logo.jpeg">');
        fs.writeFileSync(file, content);
        console.log(`Added favicon to ${file}`);
    }
}

if (fs.existsSync('./admin')) {
    const adminFiles = fs.readdirSync('./admin', { withFileTypes: true })
        .filter(dirent => !dirent.isDirectory() && dirent.name.endsWith('.html'))
        .map(dirent => `./admin/${dirent.name}`);

    for (const file of adminFiles) {
        let content = fs.readFileSync(file, 'utf8');
        if (!content.includes('<link rel="icon"')) {
            content = content.replace('<head>', '<head>\n    <!-- Favicon -->\n    <link rel="icon" type="image/jpeg" href="../Logo.jpeg">');
            fs.writeFileSync(file, content);
            console.log(`Added favicon to ${file}`);
        }
    }
}

console.log("Favicon injection complete!");
