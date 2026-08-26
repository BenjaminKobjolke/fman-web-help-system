@echo off
setlocal
cd /d "%~dp0.."
php tools\generate.php
endlocal
