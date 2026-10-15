@echo off
setlocal
@rem Deploy fman web-help-system to FTP (public/sites/fast-file-manager/help) via ftp-sync.
call "%~dp0deploy_mirror.bat"
set "result=%ERRORLEVEL%"
endlocal & exit /b %result%
