# Docker Setup for VoteParty

## การติดตั้งและรัน

### ขั้นตอนการใช้งาน Docker

1. **คัดลอกไฟล์ Environment**
   ```bash
   cp .env.docker .env
   ```

2. **สร้าง APP_KEY**
   ```bash
   docker-compose run --rm app php artisan key:generate
   ```

3. **Build และรัน Docker containers**
   ```bash
   docker-compose up -d --build
   ```

4. **รัน Database Migrations**
   ```bash
   docker-compose exec app php artisan migrate
   ```

5. **ตั้งค่า Permissions (ถ้าจำเป็น)**
   ```bash
   docker-compose exec app chown -R www-data:www-data /var/www/html/storage
   docker-compose exec app chmod -R 755 /var/www/html/storage
   ```

## การเข้าถึงแอปพลิเคชัน

- **เว็บไซต์หลัก**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - Username: voteparty_user
  - Password: voteparty_pass

## คำสั่งที่มีประโยชน์

### ดู Logs
```bash
docker-compose logs -f app
```

### รันคำสั่ง Artisan
```bash
docker-compose exec app php artisan [command]
```

### เข้าไปใน Container
```bash
docker-compose exec app bash
```

### หยุดการทำงาน
```bash
docker-compose down
```

### หยุดและลบ Volumes
```bash
docker-compose down -v
```

### Rebuild Containers
```bash
docker-compose up -d --build
```

## Services ที่รวมอยู่

1. **app** - Laravel Application (PHP 8.2-FPM + Nginx)
2. **db** - MySQL 8.0 Database
3. **redis** - Redis Cache/Session Storage
4. **phpmyadmin** - Web-based MySQL Administration

## การแก้ไข Configuration

### Database
แก้ไขใน `docker-compose.yml` ส่วน `db.environment`:
- MYSQL_DATABASE
- MYSQL_USER
- MYSQL_PASSWORD

### Ports
แก้ไขใน `docker-compose.yml` ส่วน `ports`:
- App: `"8000:80"` -> เปลี่ยน 8000 เป็น port ที่ต้องการ
- Database: `"3306:3306"`
- phpMyAdmin: `"8080:80"`

## Troubleshooting

### ถ้าเจอปัญหา Permission
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### ถ้าต้องการ Clear Cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### ถ้าต้องการ Rebuild Assets
```bash
docker-compose exec app npm run build
```
