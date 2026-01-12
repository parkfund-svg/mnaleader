# 백엔드 API 문서

## 개요

이 문서는 (주)엠앤에이리더 M&A LEADER 웹사이트의 백엔드 API 엔드포인트를 설명합니다.

## 인증

관리자 페이지는 세션 기반 인증을 사용합니다. 로그인 후 세션 쿠키가 자동으로 설정됩니다.

## 공개 API

### 양도기업 리스트 조회

**GET** `/transfer-companies.php`

양도기업 리스트를 조회합니다.

**쿼리 파라미터:**
- `page` (optional): 페이지 번호 (기본값: 1)
- `business_type` (optional): 업종 필터
- `status` (optional): 상태 필터 (available, negotiating, completed, cancelled)

**응답 예시:**
```json
{
  "companies": [
    {
      "id": 1,
      "company_name": "㈜한국종합건설",
      "business_type": "종합건설업",
      "license_type": "토목공사업",
      "capital_amount": 500000000,
      "asking_price": 1500000000,
      "location": "서울 강남구",
      "status": "available"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 5,
    "total_items": 100
  }
}
```

### 양도기업 상세 조회

**GET** `/transfer-company.php?id={id}`

특정 양도기업의 상세 정보를 조회합니다.

**응답 예시:**
```json
{
  "id": 1,
  "company_name": "㈜한국종합건설",
  "business_type": "종합건설업",
  "license_type": "토목공사업",
  "capital_amount": 500000000,
  "established_year": 2018,
  "location": "서울 강남구",
  "credit_rating": "A",
  "workforce": 5,
  "recent_revenue": 10000000000,
  "asking_price": 1500000000,
  "has_debt": false,
  "performance_summary": "실적 요약...",
  "special_notes": "특이사항...",
  "contact_method": "전화",
  "status": "available",
  "view_count": 150
}
```

## 관리자 API

모든 관리자 API는 로그인이 필요합니다.

### 관리자 로그인

**POST** `/admin/login.php`

**요청 본문:**
```json
{
  "username": "admin",
  "password": "password"
}
```

**응답:**
- 성공: 302 리다이렉트 to `/admin/dashboard.php`
- 실패: 로그인 페이지에 에러 메시지 표시

### 관리자 로그아웃

**GET** `/admin/logout.php`

세션을 종료하고 로그인 페이지로 리다이렉트합니다.

### 대시보드 통계

**GET** `/admin/dashboard.php`

대시보드 통계 데이터를 반환합니다.

**응답 예시:**
```json
{
  "stats": {
    "pending_consultations": 5,
    "total_consultations": 120,
    "active_transfers": 15,
    "total_transfers": 50,
    "pending_registrations": 3,
    "total_registrations": 45,
    "today_consultations": 2
  },
  "recent_consultations": [...],
  "recent_registrations": [...]
}
```

### 상담신청 목록

**GET** `/admin/consultations.php`

**쿼리 파라미터:**
- `page` (optional): 페이지 번호
- `status` (optional): 상태 필터
- `search` (optional): 검색어 (이름, 전화번호)

### 상담신청 상태 변경

**POST** `/admin/consultations.php`

**요청 본문:**
```json
{
  "action": "update_status",
  "id": 1,
  "status": "in_progress",
  "admin_note": "처리 중입니다"
}
```

### 양도기업 생성

**POST** `/admin/transfer-companies.php`

**요청 본문:**
```json
{
  "action": "create",
  "company_name": "㈜새로운건설",
  "business_type": "종합건설업",
  "license_type": "토목공사업",
  "capital_amount": 500000000,
  "asking_price": 1500000000,
  "location": "서울 강남구",
  "status": "available"
}
```

### 양도기업 수정

**POST** `/admin/transfer-company-edit.php`

**요청 본문:**
```json
{
  "id": 1,
  "company_name": "수정된 회사명",
  "asking_price": 2000000000,
  ...
}
```

### 양도기업 삭제

**POST** `/admin/transfer-company-delete.php`

**요청 본문:**
```json
{
  "id": 1
}
```

### 신규등록 신청 목록

**GET** `/admin/registrations.php`

**쿼리 파라미터:**
- `page` (optional): 페이지 번호
- `status` (optional): 상태 필터

### 신규등록 신청 상태 변경

**POST** `/admin/registrations.php`

**요청 본문:**
```json
{
  "action": "update_status",
  "id": 1,
  "status": "completed",
  "admin_note": "처리 완료"
}
```

## 유틸리티 함수

`config.php`에 정의된 유틸리티 함수들:

- `getDBConnection()`: 데이터베이스 연결 반환
- `sanitize($data)`: XSS 방지를 위한 데이터 정제
- `redirect($url)`: 리다이렉트
- `isLoggedIn()`: 로그인 상태 확인
- `requireLogin()`: 로그인 필요 시 리다이렉트
- `formatPrice($amount)`: 가격 포맷팅 (만원/억원)
- `formatDate($datetime)`: 날짜 포맷팅
- `logAdminActivity(...)`: 관리자 활동 로그 기록
- `getSetting($key)`: 사이트 설정 조회
- `jsonResponse($data, $statusCode)`: JSON 응답 반환

## 에러 처리

모든 API는 적절한 HTTP 상태 코드를 반환합니다:

- `200`: 성공
- `302`: 리다이렉트
- `400`: 잘못된 요청
- `401`: 인증 필요
- `404`: 리소스를 찾을 수 없음
- `500`: 서버 오류

## 데이터베이스 스키마

주요 테이블:

- `admins`: 관리자 계정
- `transfer_companies`: 양도기업 정보
- `consultations`: 상담신청
- `new_registrations`: 신규등록 신청
- `admin_logs`: 관리자 활동 로그
- `site_settings`: 사이트 설정

자세한 스키마는 `database.sql` 파일을 참조하세요.
