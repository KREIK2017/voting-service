# ЛАБОРАТОРНА РОБОТА № 4

**Тема роботи:** Звіт з другого Sprint (Sprint review meeting).

**Мета роботи:** Опанувати навички командної роботи.

## Завдання

1) Враховуючи обмеженість робочого часу проведення лабораторної роботи Scrum – майстер має підготувати доповідь, що включає в себе звіт з проробленої роботи. (10-15 хвилин, оцінка за АДЕКВАТНИЙ звіт 2 балів);

2) Кожен учасник команди відповідає на питання, що виникають стосовно його частини роботи (2 бали);

3) Команда демонструє наступний Sprint Backlog (1 бали).

## Виконання завдання

### Таблиця 1 – Sprint Backlog для другого Sprint

| ID | Tasks | Estimate (години) | Story Point | Sprint | Description |
|---|---|---|---|---|---|
| 6 | Реалізація реєстрації та email-верифікації | 12 | 21 | 2 | Реєстрація з вибором ролі (admin / voter), відправлення листа підтвердження через Mailtrap, обробка переходу. |
| 7 | Реалізація сторінки входу та виходу | 8 | 13 | 2 | Сторінка з формою для входу в систему, перевірка пароля через bcrypt, регенерація сесії, redirect на dashboard. |

**Рисунок 1 – Створення таблиці користувачів (міграція)**

**Рисунок 2 – Підключення до бази даних (.env)**

### Лістинг коду міграції користувачів (`create_users_table` + `add_role_to_users_table`)

```php
// database/migrations/0001_01_01_000000_create_users_table.php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});

// database/migrations/2026_04_30_060053_add_role_to_users_table.php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin', 'voter'])
        ->default('voter')
        ->after('password');
});
```

### Підключення до бази даних (`.env`)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voting_service
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=hello@voting-service.local
```

### Лістинг коду контролера реєстрації (`RegisteredUserController.php`)

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:'.User::ROLE_ADMIN.','.User::ROLE_VOTER],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
```

**Рисунок 3 – Сторінка реєстрації**

### Лістинг коду контролера входу/виходу (`AuthenticatedSessionController.php`)

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
```

### Лістинг Form Request для логіну з захистом від brute-force (`LoginRequest.php`)

```php
public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}

public function ensureIsNotRateLimited(): void
{
    if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
        return;
    }

    event(new Lockout($this));
    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
        'email' => trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]),
    ]);
}
```

**Рисунок 4 – Сторінка входу**

**Рисунок 5 – Email-верифікація через Mailtrap**

### Реалізація email-верифікації

Модель `User` імплементує контракт `MustVerifyEmail`, що активує middleware `verified` для захищених маршрутів:

```php
// app/Models/User.php
class User extends Authenticatable implements MustVerifyEmail
{
    // ...
}
```

```php
// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/polls', [VoteController::class, 'index'])->name('polls.index');
    Route::get('/polls/{poll}/vote', [VoteController::class, 'show']);
    // ...
});
```

При реєстрації спрацьовує `event(new Registered($user))`, який автоматично відправляє email з посиланням-токеном для підтвердження пошти.

**Рисунок 6 – Маршрут виходу з системи (`logout`)**

**Рисунок 7 – Dashboard після успішного входу**

### Захист маршрутів за ролями (middleware `role:admin`)

Для розмежування доступу адміністратора та виборця створено middleware `role`, що перевіряє атрибут `role` користувача:

```php
// app/Http/Middleware/EnsureUserHasRole.php
public function handle(Request $request, Closure $next, string $role): Response
{
    if ($request->user()?->role !== $role) {
        abort(403);
    }

    return $next($request);
}
```

```php
// routes/web.php
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('polls', AdminPollController::class);
        // ...
    });
```

## Висновки

Під час виконання лабораторної роботи підготовлено доповідь, що включає в себе звіт з проробленої роботи в рамках другого Sprint. Реалізовано функціонал реєстрації користувачів з вибором ролі (адміністратор / виборець), email-верифікацію через Mailtrap, сторінку входу з захистом від brute-force атак (RateLimiter, 5 спроб) та вихід з регенерацією сесії. Налаштовано middleware `role` для розмежування доступу до маршрутів адміністратора та виборця. Паролі зберігаються у захешованому вигляді (bcrypt). Сформовано звіт відповідно до вимог. Здобуті знання щодо реалізації аутентифікації та авторизації у фреймворку Laravel використано на практиці.
