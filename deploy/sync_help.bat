@echo off
setlocal
@rem Deploy fman web-help-system to FTP (public/sites/fman-plus-plus/help) via ftp-sync.
call "%~dp0deploy_mirror.bat"
set "result=%ERRORLEVEL%"
endlocal & exit /b %result%
