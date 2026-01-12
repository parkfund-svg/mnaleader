# Railway 저장소가 안 보일 때 해결 방법

## 문제: GitHub 로그인 후 저장소가 안 보임

### 해결 방법 1: GitHub 권한 확인 및 재설정 (가장 중요!)

1. **GitHub Settings로 이동**
   - https://github.com/settings/applications 접속
   - 또는 GitHub → 우측 상단 프로필 → Settings → Applications → Authorized OAuth Apps

2. **Railway 앱 찾기**
   - "Railway" 검색
   - Railway 앱 클릭

3. **권한 확인**
   - "Configure" 버튼 클릭
   - "Repository access" 섹션 확인
   - **"All repositories" 또는 "Only select repositories" 확인**
   - 만약 "Only select repositories"라면:
     - `parkfund-svg/mnaleader` 저장소가 선택되어 있는지 확인
     - 없으면 "Select repositories" 클릭하여 추가

4. **권한 저장**
   - "Save" 클릭

5. **Railway로 돌아가기**
   - Railway 웹사이트로 돌아가기
   - 브라우저 새로고침 (Ctrl + F5)
   - "New Project" → "Deploy from GitHub repo" 다시 시도

### 해결 방법 2: Railway에서 GitHub 재연결

1. **Railway 설정**
   - Railway 대시보드 → 우측 상단 프로필 클릭
   - "Settings" 선택
   - "Connected Accounts" 또는 "Integrations" 찾기
   - GitHub 연결 해제
   - 다시 GitHub 연결 → 이때 모든 저장소 권한 허용

2. **새로고침**
   - 페이지 새로고침 후 다시 시도

### 해결 방법 3: 직접 저장소 URL 입력

만약 위 방법이 안 되면:

1. Railway에서 "New Project" 클릭
2. "Deploy from GitHub repo" 선택
3. 저장소가 안 보이면:
   - "Import from GitHub" 버튼 옆의 "Or paste a repository URL" 클릭
   - 또는 직접 URL 입력 필드 찾기
4. 다음 URL 입력:
   ```
   https://github.com/parkfund-svg/mnaleader
   ```

### 해결 방법 4: 저장소가 실제로 존재하는지 확인

1. GitHub에서 직접 확인:
   - https://github.com/parkfund-svg/mnaleader 접속
   - 저장소가 보이는지 확인
   - Public/Private 확인

2. Private 저장소인 경우:
   - Railway가 Private 저장소에 접근할 수 있도록 권한 필요
   - GitHub Settings → Applications → Railway에서 Private 저장소 권한 확인

### 해결 방법 5: GitHub Organization 권한 확인

만약 `parkfund-svg`가 Organization이라면:

1. **Organization Settings 확인**
   - https://github.com/organizations/parkfund-svg/settings/applications
   - Railway 앱이 승인되어 있는지 확인
   - 승인되지 않았다면 "Authorize" 클릭

2. **Third-party access 확인**
   - Organization Settings → Third-party access
   - Railway가 승인되어 있는지 확인

## 빠른 체크리스트

- [ ] GitHub Settings → Applications → Railway에서 저장소 권한 확인
- [ ] Railway에서 GitHub 재연결
- [ ] 브라우저 새로고침 (Ctrl + F5)
- [ ] Private 저장소라면 권한 허용 확인
- [ ] Organization 저장소라면 Organization 권한 확인
- [ ] 저장소 URL 직접 입력 시도

## 여전히 안 되면?

1. **Railway 지원팀 문의**
   - Railway Discord: https://discord.gg/railway
   - 또는 Railway 대시보드에서 Support 문의

2. **대안: Render 사용**
   - Render는 때때로 더 나은 저장소 검색 기능 제공
   - `RENDER_DEPLOY.md` 참조

---

**가장 흔한 원인**: GitHub에서 Railway 앱의 저장소 접근 권한이 제한되어 있는 경우입니다!
