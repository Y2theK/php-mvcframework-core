<?php

namespace Y2thek\PhpMvcframeworkCore;

use Y2thek\PhpMvcframeworkCore\View;
use Y2thek\PhpMvcframeworkCore\Router;
use Y2thek\PhpMvcframeworkCore\Request;
use Y2thek\PhpMvcframeworkCore\Session;
use Y2thek\PhpMvcframeworkCore\Response;
use Y2thek\PhpMvcframeworkCore\UserModel;
use Y2thek\PhpMvcframeworkCore\db\Database;

class Application{

    public string $layout = 'main';

    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public static string $ROOT_DIR;
    public static Application $app;
    public Database $db;
    public ?UserModel $user;
    public View $view;

    public string $userClass;

    public ?Controller $controller = null;

    public function __construct(string $rootPath,array $config)
    {
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request,$this->response);
        $this->db = new Database($config['db']);
        $this->view = new View();


        $this->userClass = $config['userClass'];

        $primaryValue = $this->session->get('user');
        
        if($primaryValue){
            $primaryKey = $this->userClass::primaryKey();

           $this->user =  $this->userClass::findOne([$primaryKey => $primaryValue]);
        }else{
            $this->user = null;
        }

        
    }
    public function login(UserModel $user){
        $this->user = $user;
        $primaryKey =  $user->primaryKey();

        $primaryValue = $user->$primaryKey;

        $this->session->set('user',$primaryValue);

        return true;
    }

    public function logout(){
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest(){
        return !self::$app->user;
    }
    public function run(){
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode((int)$e->getCode());
            echo $this->view->renderView('errors/error',[
                'exception' => $e
            ]);
        }
    }
}