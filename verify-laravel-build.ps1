# PowerShell script để verify build requirements cho Laravel thuần

Write-Host "🔍 Checking Laravel build requirements..." -ForegroundColor Blue
Write-Host ""

$requiredFiles = @(
    "composer.json",
    "Dockerfile",
    "docker/scripts/start.sh",
    "docker/apache/000-default.conf"
)

$allGood = $true

foreach ($file in $requiredFiles) {
    if (Test-Path $file) {
        Write-Host "✅ $file found" -ForegroundColor Green
    } else {
        Write-Host "❌ $file not found" -ForegroundColor Red
        $allGood = $false
    }
}

Write-Host ""

# Kiểm tra composer.lock
if (Test-Path "composer.lock") {
    Write-Host "✅ composer.lock found" -ForegroundColor Green
} else {
    Write-Host "⚠️ composer.lock not found - run 'composer install' first" -ForegroundColor Yellow
}

Write-Host ""

if ($allGood) {
    Write-Host "🎉 Laravel build requirements satisfied!" -ForegroundColor Green
    Write-Host "🚀 Ready to deploy to Render (Laravel only)!" -ForegroundColor Green
    Write-Host ""
    Write-Host "📋 Next steps:" -ForegroundColor Yellow
    Write-Host "1. Commit and push changes to GitHub" -ForegroundColor White
    Write-Host "2. Create Web Service on Render" -ForegroundColor White
    Write-Host "3. Set Environment Variables" -ForegroundColor White
    Write-Host "4. Deploy!" -ForegroundColor White
    Write-Host ""
    Write-Host "ℹ️  Note: Frontend assets (Vite/React) are disabled for faster builds" -ForegroundColor Cyan
}
else {
    Write-Host "❌ Some requirements are missing!" -ForegroundColor Red
    Write-Host "Please fix the issues above before deploying." -ForegroundColor Yellow
}

Write-Host ""
