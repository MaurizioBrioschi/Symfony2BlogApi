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
- Clone this repo: ```$ git clone git@github.com:ridesoft/Symfony2BlogApi.git```
- Create a schema into database, for exemple use the name *blog*
- Restore mysql database structure ```$  mysql -uUser -pMyPassword blog < blog_structure.sql```
- ```$ composer install```
- Configure database parameters inside *app/config/parameters.yml*
- See the routes in symfony for the application: ```$ php app/console router:debug``` 
  
  All the routes with **/api/** prefix are for the API.
  
  The parameter **{_format}** in the route is the format of the output, this can be **json**,**xml**,**html** 
  

##Run test with 
```
$ phpunit -c app
```
