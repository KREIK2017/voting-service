# ЛАБОРАТОРНА РОБОТА № 3

**Тема роботи:** Створення сайту-візитки, автоматизація тестів.

**Мета роботи:** Опанувати навички використання мови застосування UML.

## Завдання

1) Командою створити сайт-візитку для свого проєкту: наявність адаптивності та меню (наприклад Navbar) обов'язкові! (максимально 1 бал);

2) Розмістити сайт-візитівку засобами github pages або аналогічним (максимально 1 бал);

3) Індивідуально: два розроблених Test Case для Selenium. Один сценарій провальний, другий вдалий (максимально 2 бали).

## Виконання завдання

**Рисунок 1 – Сайт-візитка проєкту «Сервіс онлайн-голосувань»**

Сайт-візитка містить наступні розділи: головний банер з назвою проєкту та коротким описом, секцію «Можливості» (створення опитувань, голосування, перегляд результатів, ролі admin/voter, локалізація), секцію «Технології» (Laravel 13, MySQL, Bootstrap 5, Next.js, Sanctum), секцію «Команда» (4 учасники з ролями), секцію «Контакти». Адаптивність забезпечена через Bootstrap 5 (grid system, breakpoints). Навігаційне меню (Navbar) фіксується вгорі сторінки та містить анкорні посилання на секції. На мобільних пристроях меню перетворюється на «гамбургер».

Розміщено на GitHub Pages: `https://[username].github.io/voting-service-landing/`

## Тест-кейси для Selenium (один успішний, один провальний)

**Test Case 1** – перевіряється базовий позитивний сценарій роботи сервісу онлайн-голосувань:

– відкривається сторінка авторизації;
– вводяться коректні облікові дані виборця;
– виконується вхід у систему;
– після цього відкривається сторінка зі списком активних опитувань;
– знаходяться кнопки переходу до голосування (клас `btn-vote` або текст «Проголосувати»);
– якщо хоча б одна кнопка є — виконується клік для переходу на сторінку опитування.

**Test Case 2** – перевіряє стан кнопок голосування для вже завершених або вже проголосованих опитувань:

– після логіну відкривається сторінка списку опитувань;
– шукаються кнопки за XPath: `//button[contains(text(),'Ви вже проголосували')]` або `//a[contains(text(),'Переглянути результати')]`;
– для кожного елемента перевіряється атрибут `disabled` або клас `disabled`.

## Лістинг коду успішного тесту

```python
from selenium import webdriver
from selenium.webdriver.common.by import By
import time

driver = webdriver.Chrome()

driver.get("http://localhost:8000/login")
print(driver.current_url)
time.sleep(2)

driver.find_element(By.NAME, "email").send_keys("voter@test.com")
driver.find_element(By.NAME, "password").send_keys("password")

driver.find_element(By.TAG_NAME, "button").click()
time.sleep(2)

driver.get("http://localhost:8000/polls")
time.sleep(2)

buttons = driver.find_elements(By.CLASS_NAME, "btn-vote")

print("Знайдено кнопок голосування:", len(buttons))

if len(buttons) > 0:
    buttons[0].click()
    print("Тест пройдено: перехід на сторінку голосування виконано")
else:
    print("Кнопки голосування не знайдені")

driver.quit()
```

**Рисунок 2 – Результат виконання успішного тестування**

## Лістинг коду провального тесту

```python
from selenium import webdriver
from selenium.webdriver.common.by import By
import time

driver = webdriver.Chrome()
driver.get("http://localhost:8000/login")

time.sleep(1)

driver.find_element(By.NAME, "email").send_keys("voter@test.com")
driver.find_element(By.NAME, "password").send_keys("password")
driver.find_element(By.TAG_NAME, "button").click()

time.sleep(2)

driver.get("http://localhost:8000/polls")
time.sleep(2)

buttons = driver.find_elements(
    By.XPATH,
    "//button[contains(text(),'Ви вже проголосували')]"
)

print("Знайдено:", len(buttons))

for btn in buttons:
    if btn.get_attribute("disabled"):
        print("Кнопка заблокована (вже проголосовано)")
    else:
        print("Кнопка активна! (помилка — має бути disabled)")

driver.quit()
```

**Рисунок 3 – Результат виконання провального тестування**

## Висновки

Під час виконання лабораторної роботи створено сайт-візитку для проєкту «Сервіс онлайн-голосувань», наявні адаптивність (Bootstrap 5 grid) та меню (Navbar з гамбургер-меню на мобільних пристроях). Сайт-візитку розміщено на GitHub Pages. Розроблено 2 Test Case для Selenium: один сценарій провальний, другий вдалий. Перший перевіряє базовий позитивний сценарій роботи сервісу — авторизацію виборця та перехід до сторінки голосування зі списку активних опитувань. Другий перевіряє стан кнопок для вже проголосованих опитувань (контроль атрибута `disabled`). Здобуті знання щодо автоматизації функціонального тестування за допомогою Selenium WebDriver використано на практиці.
