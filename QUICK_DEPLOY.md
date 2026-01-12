# 🚀 빠른 배포 가이드 (GitHub 자동 배포)

이 프로젝트를 GitHub에 연결하여 무료 서버에서 자동으로 배포하는 방법입니다.

## ⚠️ Railway 무료 티어 제한 안내

**Railway 무료 티어는 데이터베이스만 배포 가능합니다!** 웹 서비스는 유료 플랜이 필요합니다.

👉 **Render 사용을 추천합니다** (무료 웹 서비스 제공)

---

## 🏆 완전 무료 추천: InfinityFree (PHP + MySQL 모두 무료) ⭐⭐⭐

**가장 쉬운 방법**: InfinityFree는 웹 서비스와 MySQL 데이터베이스를 모두 무료로 제공합니다!

**빠른 시작**: `DEPLOYMENT.md`의 "InfinityFree" 섹션 참조

---

## 옵션 2: Render (웹 서비스 무료) + Railway DB (데이터베이스만 무료) ⭐⭐

### 1단계: GitHub에 푸시

```bash
git add .
git commit -m "Add Railway deployment config"
git push origin main
```

### 2단계: Railway 설정

1. **Railway 접속**: https://railway.app
2. **GitHub로 로그인**

3. **⚠️ 저장소가 안 보이면 (중요!)**:
   - GitHub → Settings → Applications → Authorized OAuth Apps → Railway
   - "Configure" 클릭
   - "Repository access"에서 `parkfund-svg/mnaleader` 저장소 선택 또는 "All repositories" 선택
   - 저장 후 Railway로 돌아가서 새로고침

4. **"New Project"** 클릭
5. **"Deploy from GitHub repo"** 선택
6. **저장소 선택** (`parkfund-svg/mnaleader` 또는 `mnaleader`)
   
   **또는 직접 URL 입력**:
   - 저장소가 안 보이면 "Import from GitHub" 옆의 "Or paste a repository URL" 클릭
   - `https://github.com/parkfund-svg/mnaleader` 입력

### 3단계: MySQL 데이터베이스 추가

1. 프로젝트에서 **"+ New"** 클릭
2. **"Database"** > **"Add MySQL"** 선택
3. 데이터베이스가 자동 생성됩니다

### 4단계: 웹 서비스 환경 변수 설정

1. 웹 서비스 선택
2. **"Variables"** 탭 클릭
3. 다음 변수 추가 (MySQL 서비스의 변수를 참조):

```
DB_HOST=${{MySQL.MYSQLHOST}}
DB_NAME=${{MySQL.MYSQLDATABASE}}
DB_USER=${{MySQL.MYSQLUSER}}
DB_PASS=${{MySQL.MYSQLPASSWORD}}
SITE_URL=${{RAILWAY_PUBLIC_DOMAIN}}
```

**변수 참조 방법:**
- MySQL 서비스 선택 > Variables 탭
- 각 변수 옆의 "Reference" 버튼 클릭
- 복사된 값을 웹 서비스 Variables에 붙여넣기

### 5단계: 도메인 설정

1. 웹 서비스 > **"Settings"** 탭
2. **"Generate Domain"** 클릭하여 무료 도메인 생성
3. 또는 **"Custom Domain"**에서 자신의 도메인 추가

### 6단계: 데이터베이스 초기화

1. MySQL 서비스 > **"Connect"** 탭에서 연결 정보 확인
2. 외부 MySQL 클라이언트로 연결
3. `database.sql` 파일 import

또는 Railway CLI 사용:
```bash
npm i -g @railway/cli
railway login
railway link
railway connect mysql
# database.sql 실행
```

### 완료! 🎉

이제 GitHub에 푸시할 때마다 자동으로 배포됩니다!

- 사이트 URL: Railway에서 제공한 도메인
- 관리자 페이지: `https://your-domain.railway.app/admin`
- 기본 계정: `admin` / `admin123!` (변경 권장)

---

## 대안: Render

Render를 사용하려면:

1. https://render.com 접속
2. GitHub로 로그인
3. "New +" > "Web Service"
4. 저장소 연결
5. 설정:
   - Environment: `Docker`
   - Dockerfile Path: `./Dockerfile`
6. 환경 변수 설정
7. 배포 시작

**주의**: Render는 PostgreSQL을 기본 제공하므로, MySQL이 필요한 경우 외부 MySQL 서비스(PlanetScale 등)를 사용해야 합니다.

자세한 내용은 `RENDER_DEPLOY.md` 참조

---

## 문제 해결

### 배포가 안 될 때
- Railway/Render의 로그 확인
- 환경 변수가 모두 설정되었는지 확인
- MySQL 서비스가 실행 중인지 확인

### 데이터베이스 연결 오류
- 환경 변수 값이 올바른지 확인
- MySQL 서비스의 Variables에서 실제 값 확인

### 파일 업로드 오류
- `uploads/` 폴더 권한 확인
- 클라우드 스토리지(S3 등) 사용 권장

---

**더 자세한 내용:**
- Railway: `RAILWAY_DEPLOY.md` 참조
- Render: `RENDER_DEPLOY.md` 참조
