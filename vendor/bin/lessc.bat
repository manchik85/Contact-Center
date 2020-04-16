@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../kylekatarnls/less.php/bin/lessc
php "%BIN_TARGET%" %*
