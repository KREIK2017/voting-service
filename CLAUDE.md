# Сервіс онлайн-голосувань — Контекст проєкту

## ⚠️ Важливо для Claude Code

Цей файл — твій основний контекст. Завжди дотримуйся цих правил. Перед будь-якою дією перевіряй чи відповідає вона цьому документу.

## Технічний стек

- **PHP:** 8.2+
- **Laravel:** 13.x
- **БД:** MySQL (через XAMPP), база: `voting_service`
- **Frontend (Blade частина):** Bootstrap 5 (тільки CDN, без npm)
- **Frontend (SPA частина):** Next.js 15 (App Router, TypeScript)
- **API:** Laravel Sanctum (token-based)
- **Email:** Mailtrap для розробки, Gmail SMTP для продакшену
- **Локалізація:** українська (uk) + англійська (en)

## Структура папок

```
voting-service/
├── (Laravel backend — тут працюємо)
└── frontend/  (Next.js — створимо на Етапі 8)
```

## Завдання — повне ТЗ

Тема: **Сервіс онлайн-голосувань**

Дві частини:
1. **Full-stack Laravel** з Blade.php фронтендом
2. **Next.js SPA** що підключається через API і повторює весь функціонал

### Обов'язкові вимоги

- ✅ Аутентифікація (реєстрація, логін, логаут)
- ✅ Email-верифікація на реальну пошту під час реєстрації
- ✅ Авторизація за ролями (мінімум 2 ролі)
- ✅ Мінімум 2 сутності з CRUD операціями
- ✅ Зв'язок один-до-багатьох між сутностями
- ✅ Локалізація українською та англійською
- ✅ API що дублює весь функціонал Blade-частини
- ✅ Next.js підключається до API і повторює весь функціонал

## Бізнес-логіка — Сервіс онлайн-голосувань

### Ролі користувачів

1. **admin** — створює, редагує, видаляє голосування та варіанти; бачить повну статистику
2. **voter** — переглядає активні голосування, голосує (один раз за кожне), бачить результати

### Сутності та зв'язки

```
User (1) ────< (N) Poll          [Один admin створює багато Polls]
Poll (1) ────< (N) Option         [Один Poll має багато Options]
User (1) ────< (N) Vote           [Один voter робить багато Votes]
Poll (1) ────< (N) Vote           [За один Poll багато Votes]
Option (1) ──< (N) Vote           [За один Option багато Votes]
```

**Сутності що задовольняють вимогу ТЗ:**
- Poll → Option (1-до-багатьох) ✅
- Poll → Vote (1-до-багатьох) ✅

### Структура таблиць

**users** (стандартна Laravel + role)
- id, name, email, password, email_verified_at
- role: ENUM('admin', 'voter'), default 'voter'
- timestamps

**polls**
- id
- user_id (FK → users.id) — хто створив
- title (string)
- description (text, nullable)
- is_active (boolean, default true)
- starts_at (timestamp, nullable)
- ends_at (timestamp, nullable)
- allow_multiple (boolean, default false) — чи можна обрати кілька варіантів
- timestamps

**options**
- id
- poll_id (FK → polls.id, CASCADE)
- text (string)
- order (integer, default 0)
- timestamps

**votes**
- id
- user_id (FK → users.id)
- poll_id (FK → polls.id)
- option_id (FK → options.id)
- timestamps
- UNIQUE(user_id, poll_id, option_id) — один голос за варіант

### Бізнес-правила

1. Voter може проголосувати за Poll тільки якщо `is_active=true`
2. Якщо `allow_multiple=false` — voter може обрати тільки один варіант (один Vote на Poll)
3. Якщо `allow_multiple=true` — voter може обрати кілька варіантів (але не один і той самий двічі)
4. Результати (кількість голосів за кожен варіант) бачать всі авторизовані
5. Admin бачить хто як проголосував (детальна статистика)
6. Voter бачить тільки агреговані результати (скільки голосів за варіант, без імен)

### Сторінки (Blade)

**Публічні:**
- `/` — головна: список активних голосувань + опис сервісу
- `/login` — логін
- `/register` — реєстрація (з вибором ролі: Voter/Admin)
- `/email/verify` — верифікація email

**Для авторизованих:**
- `/dashboard` — кабінет (різний для admin/voter)

**Тільки admin:**
- `/admin/polls` — список всіх голосувань (CRUD)
- `/admin/polls/create` — форма створення
- `/admin/polls/{id}` — деталі + список варіантів + статистика
- `/admin/polls/{id}/edit` — редагування
- `/admin/polls/{id}/options/create` — додати варіант
- `/admin/polls/{id}/options/{oid}/edit` — редагувати варіант
- `/admin/polls/{id}/votes` — детальна статистика (хто як голосував)

**Тільки voter:**
- `/polls` — список активних голосувань
- `/polls/{id}` — деталі голосування + форма голосування
- `/polls/{id}/results` — результати після голосування
- `/my-votes` — мої голосування

### API endpoints (Sanctum)

Префікс: `/api/v1/`

**Auth:**
- POST `/api/v1/register`
- POST `/api/v1/login`
- POST `/api/v1/logout`
- GET `/api/v1/me`

**Polls:**
- GET `/api/v1/polls` — список (voter: тільки active, admin: всі)
- POST `/api/v1/polls` — створити (admin)
- GET `/api/v1/polls/{id}` — деталі з варіантами
- PUT `/api/v1/polls/{id}` — оновити (admin, owner)
- DELETE `/api/v1/polls/{id}` — видалити (admin, owner)
- GET `/api/v1/polls/{id}/results` — результати

**Options:**
- POST `/api/v1/polls/{id}/options` — додати варіант (admin)
- PUT `/api/v1/options/{id}` — оновити (admin)
- DELETE `/api/v1/options/{id}` — видалити (admin)

**Votes:**
- POST `/api/v1/polls/{id}/vote` — проголосувати (voter)
- GET `/api/v1/my-votes` — мої голосування
- GET `/api/v1/polls/{id}/votes` — детально (admin only)

## Локалізація

Файли: `lang/uk/` і `lang/en/`
- `messages.php` — навігація, кнопки, загальні тексти
- `polls.php` — все про голосування
- `auth.php` — авторизація
- `validation.php` — валідація

## Стандарти коду

- Контролери тонкі, логіка в моделях/сервісах
- Валідація через Form Requests
- API відповіді через API Resources
- Авторизація через Policies
- PSR-12, моделі в однині (Poll, Option, Vote)

## ❌ Чого НЕ робити

- НЕ використовувати Tailwind в Laravel частині (тільки Bootstrap 5 CDN)
- НЕ використовувати Livewire / Inertia
- НЕ створювати зайвих сутностей
- НЕ забувати локалізацію — жодного hardcoded тексту
- НЕ дозволяти голосувати двічі за один варіант

## Порядок розробки (8 етапів)

1. **Етап 1:** Моделі + міграції + сідери
2. **Етап 2:** Auth (Breeze) + ролі + email-верифікація
3. **Етап 3:** Layout + Bootstrap 5 + локалізація uk/en
4. **Етап 4:** CRUD голосувань (admin)
5. **Етап 5:** CRUD варіантів відповідей (admin)
6. **Етап 6:** Логіка голосування + результати (voter)
7. **Етап 7:** API (Sanctum)
8. **Етап 8:** Next.js фронтенд

Кожен етап = окремий git commit.
