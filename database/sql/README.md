# Database SQL Files

วางไฟล์ .sql ทั้งหมดในโฟลเดอร์นี้เพื่อ import เข้า database

## ไฟล์ที่ต้องมี:
- registercmpnewyears.sql
- newyearvotes.sql
- newyearonepath.sql
- newyearimagepath.sql

## วิธี Import:

### แบบที่ 1: ใช้ Docker
```bash
# Copy ไฟล์เข้า container
docker cp database/sql/registercmpnewyears.sql voteparty_db:/tmp/
docker cp database/sql/newyearvotes.sql voteparty_db:/tmp/
docker cp database/sql/newyearonepath.sql voteparty_db:/tmp/
docker cp database/sql/newyearimagepath.sql voteparty_db:/tmp/

# Import ทีละไฟล์
docker compose exec db mysql -u root -proot_password voteparty < /tmp/registercmpnewyears.sql
docker compose exec db mysql -u root -proot_password voteparty < /tmp/newyearvotes.sql
docker compose exec db mysql -u root -proot_password voteparty < /tmp/newyearonepath.sql
docker compose exec db mysql -u root -proot_password voteparty < /tmp/newyearimagepath.sql
```

### แบบที่ 2: ใช้ PHPMyAdmin
1. เปิด http://localhost:8080
2. Login: root / root_password
3. เลือก database: voteparty
4. คลิก Import > เลือกไฟล์ .sql > Go
