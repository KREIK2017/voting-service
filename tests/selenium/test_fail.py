from selenium import webdriver
from selenium.webdriver.common.by import By
import time

BASE_URL = "http://127.0.0.1:8000"
USER_EMAIL = "voter@test.com"
WRONG_PASSWORD = "wrong_password_123"

driver = webdriver.Chrome()

try:
    driver.get(f"{BASE_URL}/login")
    print("Сторінка логіну:", driver.current_url)
    time.sleep(1)

    driver.find_element(By.NAME, "email").send_keys(USER_EMAIL)
    driver.find_element(By.NAME, "password").send_keys(WRONG_PASSWORD)
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    time.sleep(2)

    current_url = driver.current_url
    print("URL після спроби логіну:", current_url)

    if "/polls" in current_url or "/dashboard" in current_url:
        print("ТЕСТ ПРОЙДЕНО: вхід виконано")
    else:
        print("ТЕСТ ПРОВАЛЕНО: вхід не виконано (невірний пароль)")

        errors = driver.find_elements(By.CSS_SELECTOR, ".invalid-feedback, .alert-danger")
        for err in errors:
            text = err.text.strip()
            if text:
                print("Повідомлення помилки:", text)

    driver.save_screenshot("test_fail_browser.png")
    print("Скрін браузера збережено: test_fail_browser.png")
finally:
    driver.quit()
