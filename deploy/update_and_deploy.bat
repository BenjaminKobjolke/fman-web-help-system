@echo off
setlocal
@rem Regenerate keybindings + help + search data, then upload to FTP.
cd /d "%~dp0.."

echo Generating site data...
php tools\generate.php
if %ERRORLEVEL% neq 0 (
    echo.
    echo ERROR: Generation failed.
    if not defined TICKETS_WATCHER_COMMAND_RUN pause
    exit /b 1
)

echo.
call "%~dp0sync_help.bat"
set "result=%ERRORLEVEL%"
endlocal & exit /b %result%
