@echo off
echo Cleaning cache and restarting dev server...
echo.

REM Kill any running node processes for this project
taskkill /F /IM node.exe 2>nul

REM Clean cache directories
if exist node_modules\.cache rmdir /s /q node_modules\.cache
if exist build rmdir /s /q build

echo.
echo Cache cleared! Now starting dev server...
echo.
echo IMPORTANT: After server starts, do a HARD REFRESH in your browser:
echo Press Ctrl + Shift + R (or Ctrl + F5)
echo.

npm start
