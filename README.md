# TeraBlaze

TeraBlaze is a PHP MVC framework for developing web applications. It is an open source framework released under MIT License.

## Installation, Configuration and Testing
### Requirements
 1. Web server
 2. PHP
 3. MySQL (*optional*)
 4. Composer (*optional*) 

### There are two ways to install **TeraBlaze**. 
 1. Direct download
 2. Via Composer (*recommended*). 

#### 1. Direct download
Simply download the latest stable version from GitHub and extract to the desired web-accessible folder on your server.

#### 2. Via Composer (*recommended*)
Navigate to the desired web-accessible folder on your server and run the following command from the command line.

    composer create-project terablaze/terablaze ./
to install in the current folder  or

    composer create-project terablaze/terablaze project-folder
to install in the 'project-folder'

### Configuration
Though the default **TeraBlaze** configuration settings is enough to get you started, it is advisable and in some cases compulsory to go through the `application/configuration/configuration.php`  file and configure accordingly.

It is also necessary to configure the other configuration files in the `application/configuration` directory if you will be using some of the built in libraries.

### Testing
Navigate to the **TeraBlaze** root folder via the command line and run 

    php -S localhost:8000
visit `http://localhost:8000` in your browser.

If you are not using the build in PHP server, visit your installation folder in your browser through your server *(could be a live domain, remote ip or localhost if you are running on a local machine)*. 

If you see the **TeraBlaze** welcome page without errors, then your installation is successful.

The controller to the welcome page is `application/controllers/Home.php` and the view file is `application/views/welcome.php`

## Using

### Controllers
Create your controllers in the `application/controllers` folder.

Controllers are simply PHP classes. The class name and the class file name must be the same including the case used. That is, if your controller class is `User`, it must be in `User.php` file in the `application/controllers` folder.

Your controller class should extend the base controller `\TeraBlaze\Controller` i.e. `User extends \TeraBlaze\Controller`

It is a good practice that models be loaded in the controllers.


### Models
Create your models in the `application/models` folder.

Models are simply PHP classes. The class name and the class file name must be the same including the case used. That is, if your model class is `User_model`, it must be in `User_model.php` file in the `application/models` folder.

Your model class should extend the base model`\TeraBlaze\Model` i.e. `User extends \TeraBlaze\Model`

To load a model, simply instantiate the model as you would any other PHP class. If the model and the class where it is being instantiated from share the same namespace, then the starting `\` should not be used, but if not, it should be used. 

That is

`$instance = new User_model()`  if namespace is shared

and 

`$instance = new \User_model()`  if namespace is not shared

### Views 
Create your views in the `application/views` folder.

Views are simply PHP files and should be loaded from the controllers

To load your view files, simply use 

`$this->load_view('home')` if the view file is `application/views/home.php`

If the view file is not directly in the `application/views` directory, use the relative path to the view file like this:

`$this->load_view('user/login')` if the view file is `application/views/user/login.php`

Pass an optional second argument which is an array and is extracted internally and therefore available in the view as variables.

For instance:

    $data = array(
	    'name' = 'TeraBlaze',
	    'type' = 'MVC Framework',
	    'language' = 'PHP'
    );
    
    $this->load_view('home', $data);
The code above will make the variables `$name`, `$type` and `$language` available in the `application/views/home.php` view file


## Contributing
Simply fork the project and make pull requests. Try to go through the code so as to not deviate too much from our code style.

If you are interested in joining the development team, simply mail `teraboxxinc@gmail.com`

Note that the email used here will likely change soon.