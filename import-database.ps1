# PowerShell script สำหรับ import database
# ใช้งาน: .\import-database.ps1

Write-Host "=== Import Database to VoteParty ===" -ForegroundColor Green

$sqlFiles = @(
    "database/sql/registerempnewyears.sql",
    "database/sql/newyearvotes.sql",
    "database/sql/newyearopenvote.sql",
    "database/sql/newyearimagepath.sql"
)

# ตรวจสอบว่า containers กำลังรันอยู่
Write-Host "`nChecking if containers are running..." -ForegroundColor Yellow
$containerStatus = docker compose ps --services --filter "status=running"

if ($containerStatus -notcontains "db") {
    Write-Host "Error: Database container is not running!" -ForegroundColor Red
    Write-Host "Please run: docker compose up -d" -ForegroundColor Yellow
    exit 1
}

# Import แต่ละไฟล์
foreach ($file in $sqlFiles) {
    if (Test-Path $file) {
        Write-Host "`nImporting: $file" -ForegroundColor Cyan
        
        # Copy ไฟล์เข้า container
        docker cp $file voteparty_db:/tmp/import.sql
        
        # Import เข้า database
        docker compose exec -T db mysql -u root -proot_password voteparty < $file
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✓ Successfully imported: $file" -ForegroundColor Green
        } else {
            Write-Host "✗ Failed to import: $file" -ForegroundColor Red
        }
    } else {
        Write-Host "✗ File not found: $file" -ForegroundColor Red
    }
}

Write-Host "`n=== Import Complete ===" -ForegroundColor Green
Write-Host "`nVerify tables:" -ForegroundColor Yellow
docker compose exec db mysql -u root -proot_password voteparty -e "SHOW TABLES;"
