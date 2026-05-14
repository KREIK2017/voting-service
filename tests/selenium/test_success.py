from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

BASE_URL = "http://127.0.0.1:8000"
USER_EMAIL = "voter@test.com"
USER_PASSWORD = "password"

driver = webdriver.Chrome()
wait = WebDriverWait(driver, 10)

try:
    driver.get(f"{BASE_URL}/login")
    print("Current URL:", driver.current_url)
    time.sleep(2)

    driver.find_element(By.NAME, "email").send_keys(USER_EMAIL)
    driver.find_element(By.NAME, "password").send_keys(USER_PASSWORD)
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    time.sleep(2)

    driver.get(f"{BASE_URL}/polls")
    time.sleep(2)

    buttons = driver.find_elements(By.CSS_SELECTOR, "a.btn.btn-primary")
    print("Знайдено кнопок голосування:", len(buttons))

    if len(buttons) > 0:
        buttons[0].click()
        time.sleep(2)
        print("Тест пройдено: перехід на сторінку голосування виконано")
        print("URL сторінки голосування:", driver.current_url)
    else:
        print("Кнопки голосування не знайдені")

    driver.save_screenshot("test_success_browser.png")
    print("Скрін браузера збережено: test_success_browser.png")
finally:
    driver.quit()
