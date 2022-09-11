# Тестовое задание PHP Developer (Perfect Panel)

## Задание 1 

### Реализация с помощью MySql
````
SELECT users.id, CONCAT(first_name, " ", last_name) as name, author, GROUP_CONCAT(books.name SEPARATOR ', ') as books FROM users
INNER JOIN user_books ON users.id = user_id
INNER JOIN books ON book_id = books.id
WHERE users.age >= 7 and users.age <= 17
GROUP BY author, users.id
HAVING count(books.id) = 2 AND (SELECT count(distinct author) FROM user_books
INNER JOIN books ON book_id = books.id
WHERE user_id = users.id)
= 1;
````

## Задание 2 

## Описание

### JSON API сервис для работы с курсами обмена валют для биткоина(BTC).</br>

### Основной стек: PHP, Yii2, Docker. </br>

#### Функционал сервиса включает в себя два метода:</br>
  rates: Получение всех курсов с учетом коммисии = 2% (GET запрос)</br>
  
  convert: Запрос на обмен валюты с учетом коммисии = 2% (POST запрос)</br>

Для авторизации используется фиксированный токен cd25. Токен передается в заголовках запросов. Тип Authorization: Bearer </br>

## Инструкция по развертыванию и тестированию. </br>

### Развертывание 
  1. Скачать публичный репозиторий.
  2. Открыть в терминале дерикторию 
  ````
  Test-PHP-Developer-Perfect-Panel--main/ex2/advance
  ````
  3. Запустить Docker desctop
  4. Выполнить в терминале комманду 
  ````
    docker-compose up
  ````
  В процессе работы контейнера используются следующийе порты: 20080, 21080, 22080. 
  
### Тестирование

Тестирование API удобнее проводить с использование Postman или аналогичной программы.

Формат запросов к API: 
````
localhost:20080/method=<method_name>&<parameter>=<value> 
````
</br>

В заголовке запроса необходимо указать токен авторизации cd25. Тип Authorization: Bearer. </br>

#### Параметры get запроса rates
  1. method=rates (обязательный параметр).
  2. currency= "value" (Необязательный параметр интересующей валюты в формате USD, RUB, EUR и т. п.).

#### Параметры post запроса convert
  1. method=convert (обязательный параметр).
  2. currency_from = "value" (обязательный параметр конвертируемой валюты).
  3. currency_to = "value" (обязательный параметр валюты, в которую происходит конвертация).
  4. value = "value" (обязательный параметр колличества конвертируемой валюты).
