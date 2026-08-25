@echo off
setlocal
@rem Regenerate keybindings.json from fman source, then upload to FTP.
cd /d "%~dp0.."

echo Generating keybindings...
php tools\generate_keybindings.php
if %ERRORLEVEL% neq 0 (
    echo.
    echo ERROR: Generation failed.
    pause
    exit /b 1
)

echo.
call "%~dp0sync_help.bat"
endlocal
