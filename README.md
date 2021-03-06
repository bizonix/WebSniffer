php Based Sniffer 4
===================

#О программе

**php Based Sniffer 4** — многофункциональный веб-сниффер, написанный на языке PHP, частично доступный для свободного скачивания. Он прост, удобен в использовании и является незаменимым инструментом любого хакера специалиста по веб-безопасности.

Сниффер позволяет перехватывать IP и USER-AGENT пользователя, URL страницы, с которой пришел запрос, а также произвольную информацию, переданную браузером в QUERY (после знака ?). Подробнее об этом читайте в FAQ (в комплекте программы).

Сниффер не требует установки. Достаточно разместить все файлы дистрибутива на любом доступном хосте с поддержкой PHP и установить необходимые права, следуя инструкциям в файле readme.txt.

Последняя версия — 4.1 (выпущена 08.07.2006). Дальнейшая разработка в ближайшее время не планируется.

#Функции

Вот краткий обзор функционала сниффера:

 * грамотный, интуитивно понятный интерфейс, в котором всё учтено
 * поддержка всех известных браузеров
 * система фильтров, позволяющая отбирать из лога только нужные записи
 * фильтрация запросов (еще до попадения в лог)
 * функция “Волшебная палочка”, позволяющая быстро заходить на сайт с перехваченными cookies
 * удаление отмеченных записей
 * помечание новых записей
 * возможность авторизованного доступа
 * отправка оповещений на e-mail, ICQ
 * поддержка различных стилей оформления (4 стиля в комплекте)
 * совместимость с предыдущими версиями (достаточно перенести файл data.txt)
 * экспорт (сохранение) данных
 * подробный FAQ

… и не только. 

#Сравнение версий

|                                    | Multi | Deluxe | Personal | Public |
| :--------------------------------: |:-----:|:------:|:--------:|:------:|
| Удаление записей                   |   +   |   +    |     +    |    -   |
| Система фильтров                   |   +   |   +    |     +    |    +   |
| Постраничная навигация             |   +   |   +    |     +    |    +   |
| Basic-авторизация                  |   +   |   +    |     +    |    -   | 
| Фильтрация запросов                |   -   |   +    |     -    |    -   |
| Функция “Волшебная палочка”<img src="http://kanick.ru/sniffer/wand.gif">|   -   |   +    |     -    |    -   |
| Помечание новых записей            |   +   |   +    |     -    |    -   |
| Отправка оповещений на e-mail, ICQ |   -   |   +    |     -    |    -   |
| Экспорт данных                     |   +   |   +    |     -    |    -   |
| Кол-во включенных стилей           |   4   |   4    |1 (*Simple*)|1 (*Simple*)|
| Тонкая настройка                   |   +   |   +    |     -    |    -   |
| Быстрая замена GIF-картинки        |   +   |   -    |     -    |    -   |
| Подробный FAQ                      |   +   |   +    |     -    |    -   |

В версии *Multi*, не считая многопользовательского интерфейса (у каждого пользователя свой лог), есть еще администраторская панель и два отдельных FAQ’а для пользователей и администратора.

#Preview версии Deluxe

Вы можете полюбоваться вышеупомянутыми достоинствами и испробовать функционал сниффера на странице превью (удалять и добавлять записи нельзя):

[<div style="text-align:center"><img src="http://kanick.ru/sniffer/preview.jpg"></div>](http://kanick.ru/sniffer/preview/log.php)

#Помощь (Donate)

Мои реквизиты следующие:

* Webmoney: Z887052833571 (пересчитывайте по курсу ЦБ), R115088509119
* Яндекс.Деньги: 4100159084988

#Автор

**Kanick**. На самых первых порах в создании сниффера также помогали **Егорыч+++**, **a33**, **chin**.

