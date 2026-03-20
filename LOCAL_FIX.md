# Local Development Setup Guide

## 🚀 QUICK FIXES FOR LOCAL DEVELOPMENT

### Issue 1: Products Not Loading
**Problem**: JavaScript calls `api/products.php` but needs web server

**Solution**: Start XAMPP Apache server
1. Open XAMPP Control Panel
2. Start Apache service
3. Visit: http://localhost/Peptide/

### Issue 2: Authentication Not Visible  
**Problem**: Auth modal exists but may be hidden by CSS

**Solution**: Check browser console for errors
1. Open browser developer tools
2. Look for JavaScript errors
3. Check network tab for failed API calls

### Issue 3: Database Connection
**Status**: ✅ WORKING (60 products found)

## 🔧 TESTING STEPS

### 1. Start Local Server
```bash
# Start XAMPP Apache
# Visit: http://localhost/Peptide/
```

### 2. Test API Endpoints
```bash
# Test products API
http://localhost/Peptide/api/products.php

# Test auth API  
http://localhost/Peptide/api/auth.php
```

### 3. Check Browser Console
- Open Developer Tools (F12)
- Look for JavaScript errors
- Check Network tab for API calls

## 📋 KNOWN ISSUES & FIXES

### ✅ Database: Working
- 60 products in database
- Connection successful

### ❌ Frontend: Needs Web Server
- JavaScript API calls need Apache
- Static files need HTTP server

### ❌ Authentication: Needs Testing
- Modal exists in HTML
- JavaScript functions present
- Needs browser testing

## 🎯 NEXT ACTIONS

1. **Start XAMPP Apache**
2. **Test in browser**
3. **Check console errors**
4. **Fix any remaining issues**

## 🌐 LOCAL URLS
- **Main Site**: http://localhost/Peptide/
- **Admin**: http://localhost/Peptide/admin/
- **API Base**: http://localhost/Peptide/api/
