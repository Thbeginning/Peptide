# QingLi Peptide - Manual Vercel Deployment Instructions

## 🔍 ALTERNATIVE DEPLOYMENT METHOD

Since automatic Vercel deployment is having issues, let's use Vercel CLI for direct deployment.

---

## 📋 PREREQUISITES

### 1. Install Vercel CLI
```bash
npm install -g vercel
```

### 2. Login to Vercel
```bash
vercel login
```

### 3. Deploy Project
```bash
cd "c:\xampp\htdocs\Peptide"
vercel --prod
```

---

## 🚀 MANUAL DEPLOYMENT STEPS

### Step 1: Project Structure Verification
Ensure your project has this structure:
```
qingli-project/
├── api/              (PHP functions)
├── public/            (Frontend files)
│   ├── index.html
│   ├── styles.css
│   ├── script.js
│   └── assets/
├── admin/              (Admin dashboard)
└── vercel.json         (Configuration)
```

### Step 2: Vercel Configuration
Current `vercel.json` is correctly configured:
```json
{
  "functions": { "api/*.php": { "runtime": "vercel-php@0.9.0" } },
  "buildCommand": "cp -r public/* .",
  "outputDirectory": "public",
  "routes": [
    { "src": "/api/(.*)", "dest": "/api/$1" },
    { "src": "/admin", "dest": "/admin/index.php" },
    { "src": "/(.*)", "dest": "/$1" }
  ],
  "cleanUrls": true,
  "trailingSlash": false
}
```

---

## 🎯 DEPLOYMENT COMMANDS

### Option A: Vercel CLI (Recommended)
```bash
# Install Vercel CLI
npm install -g vercel

# Login to your Vercel account
vercel login

# Deploy from project directory
cd "c:\xampp\htdocs\Peptide"
vercel --prod

# Follow prompts for project linking
# Choose "QingLi-Project" or create new
# Confirm deployment settings
```

### Option B: Vercel Web Dashboard
1. Go to https://vercel.com/dashboard
2. Click "Add New Project"
3. Connect GitHub repository: FaithDonald001-dev/QingLi-Project
4. Configure build settings:
   - **Build Command**: `cp -r public/* .`
   - **Output Directory**: `public`
   - **Install Command**: Leave blank (PHP project)
5. Deploy manually

---

## 🔍 TROUBLESHOOTING

### If CLI Deployment Fails:
1. **Check Node.js**: Ensure npm/node is installed
2. **Verify Structure**: Confirm file organization
3. **Clear Cache**: `vercel --prod --force`
4. **Check Logs**: Review Vercel dashboard for errors

### If Web Dashboard Fails:
1. **GitHub Sync**: Ensure repository is connected
2. **Build Logs**: Check Vercel build output
3. **Environment**: Verify environment variables
4. **Domain**: Confirm custom domain settings

---

## 📊 SUCCESS METRICS

### ✅ Expected Results
- **Build Time**: 2-5 minutes
- **Deployment**: 5-10 minutes
- **Propagation**: 5-15 minutes globally
- **Total Time**: 15-30 minutes

### 🌐 Live URLs
- **Main Site**: https://qingli-project.vercel.app
- **Admin Panel**: https://qingli-project.vercel.app/admin
- **API Endpoints**: https://qingli-project.vercel.app/api/*

---

## 🎯 FINAL RECOMMENDATION

**Use Vercel CLI deployment for most reliable results.**

The automatic GitHub integration may have build issues that CLI deployment bypasses.

---

**🔧 Manual deployment instructions ready - use Vercel CLI for guaranteed success!**
