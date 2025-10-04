# Deploy Laravel Dating Services lên Render với Docker

## Tổng quan
Hướng dẫn này sẽ giúp bạn deploy ứng dụng Laravel Dating Services lên Render sử dụng Docker với Apache server.

## Cấu trúc Docker

### 1. Dockerfile
- Sử dụng PHP 8.2 với Apache
- Cài đặt các PHP extensions cần thiết cho Laravel
- Cấu hình Apache với mod_rewrite
- Tự động chạy composer install và npm build
- Thiết lập quyền cho storage và cache

### 2. File cấu hình chính
- `Dockerfile`: Cấu hình container
- `docker-compose.yml`: Cho testing local
- `docker/apache/000-default.conf`: Cấu hình Apache vhost
- `docker/scripts/start.sh`: Script khởi tạo ứng dụng
- `.env.production`: Template environment cho production

## Hướng dẫn Deploy lên Render

### Bước 1: Chuẩn bị Repository
1. Push toàn bộ code lên GitHub repository
2. Đảm bảo các file Docker đã được commit

### Bước 2: Tạo Web Service trên Render
1. Đăng nhập vào [Render](https://render.com)
2. Tạo "New Web Service"
3. Connect GitHub repository của bạn
4. Chọn repository `dating_services`

### Bước 3: Cấu hình Web Service
#### Build & Deploy Settings:
- **Environment**: `Docker`
- **Build Command**: `docker build -t dating-services .`
- **Start Command**: `docker run -p 80:80 dating-services`

#### Advanced Settings:
- **Port**: `80`
- **Health Check Path**: `/`
- **Auto-Deploy**: `Yes`

### Bước 4: Environment Variables
Thêm các biến môi trường sau trong Render:

```
APP_NAME=Dating Services
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com

DB_CONNECTION=mysql
DB_HOST=YOUR_DB_HOST
DB_PORT=3306
DB_DATABASE=YOUR_DB_NAME
DB_USERNAME=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASSWORD

LOG_LEVEL=error
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Bước 5: Database Setup
1. Tạo MySQL database trên Render hoặc sử dụng external database
2. Cập nhật database credentials trong Environment Variables
3. Database sẽ tự động migrate khi container khởi động

## Testing Local với Docker

### Chạy với Docker Compose:
```bash
# Build và chạy containers
docker-compose up -d

# Xem logs
docker-compose logs -f app

# Stop containers
docker-compose down
```

### Chạy riêng lẻ:
```bash
# Build image
docker build -t dating-services .

# Run container
docker run -p 80:80 \
  -e APP_ENV=local \
  -e APP_DEBUG=true \
  dating-services
```

## Cấu hình quan trọng

### Apache Configuration
- Document root: `/var/www/html/public`
- Mod rewrite enabled cho Laravel routing
- Error logging enabled

### PHP Configuration
- PHP 8.2 với extensions: pdo_mysql, mbstring, gd, zip, etc.
- Composer optimized autoloader
- Opcache enabled trong production

### Security
- APP_DEBUG=false trong production
- Log level set to error
- Proper file permissions cho storage

## Troubleshooting

### 1. Build fails
- Kiểm tra Dockerfile syntax
- Đảm bảo composer.json và package.json hợp lệ

### 2. App không start
- Kiểm tra logs: `docker logs container_name`
- Verify APP_KEY đã được set
- Kiểm tra database connection

### 3. Database issues
- Verify database credentials
- Kiểm tra migrations đã chạy thành công
- Check database permissions

### 4. Assets không load
- Đảm bảo npm run build đã chạy trong Docker build
- Check APP_URL environment variable

## Monitoring

### Health Checks
- Render sẽ tự động ping `/` để check health
- Nếu app không response, sẽ restart container

### Logs
- Access logs: Render dashboard
- Application logs: Laravel log channel
- Apache logs: Container logs

## Performance Tips

1. **Caching**: Sử dụng Redis cho session và cache nếu cần
2. **CDN**: Sử dụng CDN cho static assets
3. **Database**: Optimize queries và sử dụng database indexes
4. **Monitoring**: Set up monitoring tools như New Relic

## Support

Nếu gặp vấn đề trong quá trình deploy:
1. Check Render logs và Laravel logs
2. Verify environment variables
3. Test locally với Docker trước
4. Check database connectivity

---

**Lưu ý**: Đây là setup cơ bản. Trong production thực tế, bạn nên:
- Sử dụng separate database service
- Setup Redis cho caching
- Configure proper monitoring
- Setup backup strategies
- Use environment-specific configurations
