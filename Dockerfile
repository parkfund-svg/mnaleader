# PHP 8.2 Apache 이미지 사용
FROM php:8.2-apache

# 작업 디렉토리 설정
WORKDIR /var/www/html

# 시스템 패키지 업데이트 및 필요한 도구 설치
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache 모듈 활성화
RUN a2enmod rewrite

# PHP 설정 복사
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# 프로젝트 파일 복사
COPY . /var/www/html/

# 업로드 폴더 생성 및 권한 설정
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/uploads

# 포트 노출
EXPOSE 80

# Apache 시작
CMD ["apache2-foreground"]
