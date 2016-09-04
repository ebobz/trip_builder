 # Trip Builder

API for agencies manage their trips.
  - Language: PHP (5.6)
  - Database: SQLite3
  - Requirements: Curl and mod-rewrite



## Install instructions
    
 - Download zip file and extract it to desired location
 - Configure Apache host path to /public
 - Make sure database directory is writeable to all users

```sh
chmod 777 trip_builder/db 
```
 - Make sure log directory is writeable to all users
```sh
chmod 777 trip_builder/log 
```



## Todo List
 - Add security throught some sort of access token
 - Implement memcached on thirdy party API calls
 - Limit requests to avoid DOS attacks
 - Use a better logger

 