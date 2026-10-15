@echo off
setlocal
cd /d "%~dp0.."
php tools\generate.php
set "result=%ERRORLEVEL%"
endlocal & exit /b %result%
