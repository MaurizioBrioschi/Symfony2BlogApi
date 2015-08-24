# Symfony2BlogApi
"Blog" Api to insert data in a mysql database with these functions:
- Create a topic
- Delete a topic
- List all topics
- Show a specific topic

- Create an article for a topic
- Delete an article
- List all articles from a topic
- Show a specific article

##INSTALL (from linux)
- Create a schema into database, for exemple use the name *blog*
- Restore mysql database structure ```$  mysql -uUser -pMyPassword blog < blog_structure.sql```
- ```$ composer install```
- Configure database parameters inside *app/config/parameters.yml*
- See the routes in symfony for the application: ```$ php app/console router:debug``` 
  
  All the routes with **/api/** prefix are for the API.  

##Run test with 
```
$ phpunit -c app
```
