@echo off
title Qingli Peptide - Local Server
echo.
echo ========================================
echo   QINGLI PEPTIDE - Starting Local Server
echo ========================================
echo.
echo  Website will be available at:
echo  http://localhost:3000
echo.
echo  Press Ctrl+C to stop the server.
echo ========================================
echo.
npx -y serve . -p 3000 --no-clipboard
pause
