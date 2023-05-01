@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../blade-ui-kit/blade-icons/bin/blade-icons-generate
php "%BIN_TARGET%" %*
