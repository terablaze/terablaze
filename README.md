# TeraBlaze

TeraBlaze is a PHP MVC framework for developing web applications. It is an open source framework released under MIT License.

## Installation, Configuration and Running
### Requirements
 1. Web server
 2. PHP
 3. MySQL (*optional*)
 4. Composer

### Installation.
#### 1. Via Composer (*recommended*)
Navigate to the desired web-accessible folder on your server and run the following command from the command line.

    composer create-project terablaze/terablaze ./
to install in the current folder  or

    composer create-project terablaze/terablaze project-folder
to install in the 'project-folder'

### Configuration
First, create a `.env` file using the `.ent.template` file as a guide

Though the default **TeraBlaze** configuration settings is enough to get you started, it is advisable and in some cases compulsory to go through the `config`  directory and configure accordingly.

### Running
Navigate to your project's root folder via the command line and run 

    php -S localhost:8000
visit `http://localhost:8000` in your browser.

If you are not using the build in PHP server, visit your installation folder in your browser through your server *(could be a live domain, remote ip or localhost if you are running on a local machine)*. 

If you see the **TeraBlaze** welcome page without errors, then your installation is successful.

The controller to the welcome page is `src/App/Controller/WelcomeController.php` and the view file is `src/App/views/welcome.php`

## Using

### Controllers
Create your controllers in the `src/App/Controller` folder (You can use any directory as long as the Controller namespace/class matches the directory/file path).

Your controller class should extend the base controller `\TeraBlaze\Controller\Controller` to have access to some nice features the base controller offers


### Models
Create your models in the `src/App/Model` folder (You can use any directory as long as the Model namespace/class matches the directory/file path).

Models are simply PHP classes. The class name and the class file name must be the same including the case used. That is, if your model class is `User_model`, it must be in `User_model.php` file in the `application/models` folder.

Your model class should extend the base model`\TeraBlaze\Ripana\ORM\Model`

### Views 
Create your views in the `src/App/views` folder.

Views are simply PHP files and should be loaded from the controllers

To load your view files, simply use 

`$this->loadView('App::view_file')`

Pass an optional second argument which is an array and is extracted internally and therefore available in the view as variables.

For instance:

    $data = [
	    'name' = 'TeraBlaze',
	    'type' = 'MVC Framework',
	    'language' = 'PHP'
    ];
    
    $this->loadView('App::home', $data);
The code above will make the variables `$name`, `$type` and `$language` available in the `src/App/views/home.php` view file

To load a view from within another view, use `$this->includeView('App::included_view_file')`, this allows the included view have access to the variables extracted in the parent view

## Stability status
It is very important to note that this framework is currently very unstable and may change frequently for now. 
As much as we use it in several live projects(from really simple projects to very 
big and complex projects), we do not advise you to use in a production environment 
unless you are sure of what you're doing and you're willing to invest the time.

## Contributing
Simply fork the project and make pull requests. Try to go through the code to not deviate too much from our code style.

If you are interested in joining the development team, simply mail `tomiwa@teraboxx.com`

Note that the email used here will likely change soon.