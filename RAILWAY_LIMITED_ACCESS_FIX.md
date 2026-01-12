# Railway "제한된 접근" (Limited Access) 해결 방법

## 빠른 해결 가이드

`mnaleader-web`이 보이지만 "제한된 접근" 메시지가 뜨는 경우입니다.

## ✅ 해결 방법 (단계별)

### 방법 1: GitHub에서 Railway App 권한 승인 (5분 소요)

#### 1단계: 저장소 Settings로 이동

1. https://github.com/parkfund-svg/mnaleader 접속
2. 저장소 페이지에서 **"Settings"** 클릭 (상단 메뉴)

#### 2단계: Integrations → Applications

1. 왼쪽 메뉴에서 **"Integrations"** 클릭
2. 그 아래 **"Applications"** 클릭

#### 3단계: Railway App 찾기 및 승인

1. **"Installed GitHub Apps"** 섹션에서 **"Railway"** 찾기
2. Railway 앱 클릭
3. **"Configure"** 버튼 클릭
4. 다음 설정 확인:
   - **Repository access**: `mnaleader` 저장소 선택 또는 "All repositories"
   - **Permissions**:
     - ✅ Contents: **Read and write**
     - ✅ Metadata: **Read-only**
     - ✅ Pull requests: **Read and write** (선택사항)
5. **"Save"** 클릭

#### 4단계: Organization 권한 (Organization인 경우)

만약 `parkfund-svg`가 Organization이라면:

1. https://github.com/organizations/parkfund-svg/settings/applications 접속
2. **"Installed GitHub Apps"** 탭 클릭
3. **"Railway"** 찾기
4. **"Configure"** 클릭
5. 저장소 권한 승인
6. **"Save"** 클릭

#### 5단계: Railway로 돌아가기

1. Railway 웹사이트로 돌아가기
2. 브라우저 새로고침 (Ctrl + F5)
3. 저장소 선택 시도

### 방법 2: Railway에서 직접 권한 승인

1. Railway에서 저장소가 "Limited access"로 표시되는 경우
2. 저장소 옆의 **"..."** 또는 **"Configure"** 버튼 클릭
3. "Grant access" 또는 "Authorize" 버튼 클릭
4. GitHub에서 권한 승인
5. Railway로 리다이렉트됨

### 방법 3: 저장소 URL 직접 입력 (권한 자동 요청)

1. Railway → **"New Project"** 클릭
2. **"Deploy from GitHub repo"** 선택
3. 저장소 목록에서 찾기 어려우면:
   - **"Or paste a repository URL"** 또는 URL 입력 필드 클릭
4. 다음 URL 입력:
   ```
   https://github.com/parkfund-svg/mnaleader
   ```
5. **"Deploy"** 클릭
6. GitHub 권한 승인 팝업이 나타나면 **"Authorize Railway"** 클릭

## 🔍 권한 확인 체크리스트

- [ ] GitHub 저장소 Settings → Integrations → Applications에서 Railway App 확인
- [ ] Railway App의 Repository access 권한 확인
- [ ] Organization인 경우 Organization Settings에서 Railway 승인 확인
- [ ] Railway에서 브라우저 새로고침 (Ctrl + F5)
- [ ] 저장소 URL 직접 입력 시도

## ⚠️ 여전히 안 되면?

### 추가 확인사항

1. **저장소가 Private인 경우**
   - Railway의 유료 플랜이 필요한 경우가 있음
   - 또는 Public으로 변경 후 다시 시도

2. **Organization 멤버 권한**
   - Organization의 Owner 또는 Admin 권한 필요
   - 일반 멤버는 저장소 권한 설정 불가

3. **Railway 지원팀 문의**
   - Railway Discord: https://discord.gg/railway
   - 또는 Railway 대시보드에서 Support 문의

## 📝 참고

- Railway는 저장소에 대한 **Read and write** 권한이 필요합니다
- Organization 저장소의 경우 **Organization Owner의 승인**이 필요할 수 있습니다
- 권한 승인 후 **약간의 시간이 걸릴 수 있습니다** (최대 5분)

---

**가장 빠른 해결**: 저장소 Settings → Integrations → Applications → Railway → Configure → 권한 승인!
