@echo off
setlocal
cd /d "%~dp0.."
php vendor\bin\phpunit --colors=always
set "result=%ERRORLEVEL%"
endlocal & exit /b %result%
