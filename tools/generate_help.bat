@echo off
setlocal
cd /d "%~dp0.."
php tools\generate_help.php
endlocal
