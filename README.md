# Blog engine
##Task
Need to write a blog from scratch (without using frameworks and libraries).
###Four pages:
1. List of posts
2. Opened post with comments
3. Add post
4. Authorization

###Functional:
1. An authorized user can add post.
2. Anyone can comment it.

NB templates and layout can be omitted.
 
It is important to write it nicely in terms of application OOP architecture.
##Solutions comments
Blog represented as REST API service and the small JavaScript client on jQuery. 
As a result of solving the problem, formed a small framework (to implement backend not use third-party libraries), below are its main features:
* The models are implemented on the pattern Date Mapper
* The configuration and components are introduced through Dependency Injection
* Implemented routing
* Implemented a simple ACL
* Connecting to a database is obtained through the factory, so you can easily implement any other relational database
* Base CSRF protection is carried out verification of X-Token in the request is available only to authorized users
* Entire configuration of the application is in `app/config.php` file
