# PlurkBot-TaipeiWeather
This is a Bot for broadcasting weather in Taipei on Plurk.
Here is the bot account on Plurk: http://www.plurk.com/taipei_weather

## How to Use
1. Find a web hosting that provides php hosting.
2. Upload all the files to the server.
3. Register a Plurk App and retrieve the App Key and App Secret.
4. Register a Imgur App and retrieve the App Key.
5. Edit config.sample.php, enter your Plurk App Key, App Secret, User Token and User Token Secret.
6. Edit config.sample.php, enter your Imgur App Key.
7. Edit config.sample.php, set a authorization token.
8. Rename config.sample.php to config.php.
9. Add cron jobs as following.

## Cron Jobs
All cron jobs are GET requests, with Authorization header with value set in previous section.
- now.php: This file adds new plurk that indicates the weather status right now. Execute this file once an hour.
- helper.php: This file adds new plurk that indicates weather forast. Execute this every 12 hours.
- bing.php: This file adds new plurk of Bing Wallpaper.