@echo off
setlocal
cd /d "%~dp0.."
php vendor\bin\phpunit --teamcity --colors=always
set "result=%ERRORLEVEL%"
endlocal & exit /b %result%
