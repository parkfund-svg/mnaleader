# (주)엠앤에이리더 M&A LEADER

건설업 M&A 및 신규등록 서비스를 제공하는 웹사이트입니다.

## 🚀 시작하기

### 필수 요구사항
- PHP 8.0 이상
- MySQL 5.7 이상 또는 MariaDB 10.2 이상
- Apache/Nginx 웹서버
- (선택) Docker 및 Docker Compose (로컬 개발용)

### Docker를 사용한 로컬 개발

1. **저장소 클론**
```bash
git clone [your-repository-url]
cd 엠앤에이리더
```

2. **환경 설정 파일 생성**
```bash
cp config.php.example config.php
# config.php 파일을 열어 데이터베이스 설정을 수정하세요
```

3. **데이터베이스 초기화 (선택사항)**
```bash
# database.sql을 docker/db/init/ 폴더에 복사하면 자동으로 초기화됩니다
cp database.sql docker/db/init/
```

4. **Docker 컨테이너 시작**
```bash
docker compose up -d --build
```

5. **브라우저에서 접속**
- 메인 사이트: http://localhost:8000
- 관리자 페이지: http://localhost:8000/admin
- 기본 관리자 계정: `admin` / `admin123!` (운영 환경에서는 반드시 변경하세요)

### Docker 없이 설치하기

1. **데이터베이스 생성**
```bash
mysql -u root -p < database.sql
```

2. **환경 설정**
```bash
cp config.php.example config.php
# config.php에서 데이터베이스 연결 정보 수정
```

3. **업로드 폴더 생성**
```bash
mkdir uploads
chmod 755 uploads
```

4. **웹서버 설정**
- Apache: DocumentRoot를 프로젝트 루트로 설정
- Nginx: root를 프로젝트 루트로 설정

## 📁 프로젝트 구조

```
엠앤에이리더/
├── admin/              # 관리자 페이지
│   ├── dashboard.php   # 대시보드
│   ├── consultations.php  # 상담신청 관리
│   ├── registrations.php  # 신규등록 신청 관리
│   └── transfer-companies.php  # 양도기업 관리
├── includes/           # 공통 헤더/푸터
├── images/             # 이미지 파일
├── config.php          # 환경 설정 (gitignore됨)
├── config.php.example  # 설정 파일 템플릿
├── database.sql        # 데이터베이스 스키마
└── docker-compose.yml  # Docker 설정
```

## 🔧 환경 변수 설정

`.env` 파일을 생성하거나 환경 변수로 설정할 수 있습니다:

```env
DB_HOST=localhost
DB_NAME=mnaleader
DB_USER=root
DB_PASS=your_password
```

## 🔐 보안 설정

운영 환경 배포 전 반드시 확인하세요:

1. `config.php`에서 `PASSWORD_SALT` 변경
2. 기본 관리자 비밀번호 변경
3. `error_reporting` 및 `display_errors` 비활성화
4. HTTPS 사용 시 `session.cookie_secure` 활성화
5. `.gitignore`에 `config.php`가 포함되어 있는지 확인

## 📝 주요 기능

- **양도기업 리스트**: 건설업 양도 매물 조회 및 관리
- **상담신청**: 온라인 상담 신청 및 관리
- **신규등록 신청**: 건설업 신규등록 신청 처리
- **관리자 대시보드**: 통계 및 관리 기능

## 🛠️ 백엔드 API

자세한 API 문서는 [API.md](API.md)를 참조하세요.

## 📦 Docker 컨테이너 관리

**컨테이너 중지 및 삭제**
```bash
docker compose down -v
```

**로그 확인**
```bash
docker compose logs -f
```

## 🤝 기여하기

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 라이선스

이 프로젝트는 사유 소프트웨어입니다.

## 📞 문의

- 회사: (주)엠앤에이리더 M&A LEADER
- 주소: 서울시 서초구 서초중앙로 110, B1층(A190호)
- 전화: 02-588-2844
- 팩스: 02-6944-8260
