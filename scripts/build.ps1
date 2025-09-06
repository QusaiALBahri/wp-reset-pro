$ErrorActionPreference = "Stop"

$Plugin = "wp-reset-pro"
$Out = "dist"
$MainFile = Join-Path $Plugin "wp-reset-pro.php"
$VersionLine = (Select-String -Path $MainFile -Pattern "Version:\s*[0-9]+\.[0-9]+\.[0-9]+").Matches.Value
$Version = ($VersionLine -split "\s+")[-1]

if (Test-Path $Out) { Remove-Item -Recurse -Force $Out }
New-Item -ItemType Directory -Force -Path (Join-Path $Out $Plugin) | Out-Null

# Optional composer optimize
if (Test-Path (Join-Path $Plugin "composer.json")) {
  if (Get-Command composer -ErrorAction SilentlyContinue) {
    Push-Location $Plugin
    try { composer dump-autoload -o } catch {}
    Pop-Location
  }
}

robocopy $Plugin (Join-Path $Out $Plugin) /E /XD .git .github node_modules tests /XF .phpcs.xml composer.lock CHANGELOG.md ROADMAP.md | Out-Null

$ZipName = "$($Plugin)-v$($Version).zip"
if (Test-Path $ZipName) { Remove-Item $ZipName }
Compress-Archive -Path (Join-Path $Out $Plugin) -DestinationPath $ZipName
Write-Host "Built $ZipName"
