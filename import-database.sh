#!/bin/bash
# Bash script สำหรับ import database บน Ubuntu/Linux
# ใช้งาน: ./import-database.sh

echo "=== Import Database to VoteParty ==="

SQL_FILES=(
    "database/sql/registerempnewyears.sql"
    "database/sql/newyearvotes.sql"
    "database/sql/newyearopenvote.sql"
    "database/sql/newyearimagepath.sql"
)

# ตรวจสอบว่า containers กำลังรันอยู่
echo -e "\nChecking if containers are running..."
if ! docker compose ps | grep -q "voteparty_db.*Up"; then
    echo "Error: Database container is not running!"
    echo "Please run: docker compose up -d"
    exit 1
fi

# Import แต่ละไฟล์
for file in "${SQL_FILES[@]}"; do
    if [ -f "$file" ]; then
        echo -e "\nImporting: $file"
        
        # Copy ไฟล์เข้า container
        docker cp "$file" voteparty_db:/tmp/import.sql
        
        # Import เข้า database
        docker compose exec -T db mysql -u root -proot_password voteparty < "$file"
        
        if [ $? -eq 0 ]; then
            echo "✓ Successfully imported: $file"
        else
            echo "✗ Failed to import: $file"
        fi
    else
        echo "✗ File not found: $file"
    fi
done

echo -e "\n=== Import Complete ==="
echo -e "\nVerify tables:"
docker compose exec db mysql -u root -proot_password voteparty -e "SHOW TABLES;"
