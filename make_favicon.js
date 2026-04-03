// make_favicon.js — convert Logo.jpeg → Logo.png for use as favicon
const fs = require('fs');
const path = require('path');

// Check if sharp is available
let hasSharp = false;
try { require.resolve('sharp'); hasSharp = true; } catch(e) {}

let hasJimp = false;
try { require.resolve('jimp'); hasJimp = true; } catch(e) {}

if (hasSharp) {
  const sharp = require('sharp');
  Promise.all([
    sharp('Logo.jpeg').resize(192, 192).png().toFile('Logo.png'),
    sharp('Logo.jpeg').resize(32, 32).png().toFile('favicon-32.png'),
    sharp('Logo.jpeg').resize(180, 180).png().toFile('apple-touch-icon.png'),
  ]).then(() => console.log('✅ Done with sharp')).catch(console.error);
} else if (hasJimp) {
  const Jimp = require('jimp');
  Jimp.read('Logo.jpeg').then(img => {
    return Promise.all([
      img.clone().resize(192,192).writeAsync('Logo.png'),
      img.clone().resize(32,32).writeAsync('favicon-32.png'),
      img.clone().resize(180,180).writeAsync('apple-touch-icon.png'),
    ]);
  }).then(() => console.log('✅ Done with jimp')).catch(console.error);
} else {
  // Fallback: just copy the JPEG with PNG extension
  // (modern browsers can read JPEG bytes even from a .png file for favicons)
  fs.copyFileSync('Logo.jpeg', 'Logo.png');
  fs.copyFileSync('Logo.jpeg', 'favicon-32.png');
  fs.copyFileSync('Logo.jpeg', 'apple-touch-icon.png');
  console.log('✅ Copied Logo.jpeg as Logo.png (no converter available — install sharp or jimp for proper conversion)');
}
