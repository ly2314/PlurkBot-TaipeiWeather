# PlurkBot-TaipeiWeather
This is a Bot for broadcasting weather in Taipei on Plurk.
Here is the bot account on Plurk: http://www.plurk.com/taipei_weather

## How to Use
1. Find a web hosting that provides php hosting.
2. Upload all the files to the server.
3. Register a Plurk App and retrieve the App Key and App Secret.
4. Edit plurk.php.sample, enter your App Key, App Secret, User Token and User Token Secret.
5. Rename plurk.php.sample to plurk.php.
6. Add cron jobs as following.

## Cron Jobs
- now.php: This file adds new plurk that indicates the weather status right now. Execute this file once an hour.
- helper.php: This file adds new plurk that indicates weather forast. Execute this every 12 hours.