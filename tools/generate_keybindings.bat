@echo off
setlocal
cd /d "%~dp0.."
php tools\generate_keybindings.php
set "result=%ERRORLEVEL%"
endlocal & exit /b %result%
