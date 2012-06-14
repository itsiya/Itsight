<?php

require_once 'Environment.php';
require_once 'Request.php';
require_once 'Response.php';
require_once 'Header.php';
require_once 'View.php';
require_once 'Auth.php';
require_once 'AuthModel.php';
require_once 'ItsightAPI.php';

require_once './config/database.php';

class ItsightController {
    protected $environment;

    protected $request;

    protected $response;

	protected $view;

	protected $config;

	protected $template;
	
	protected $auth;

	public $test;

	public function __construct( $userSettings = array() ) {
		$this->config = $this->getDefultConfig();
        $this->environment = ItsightEnvironment::getInstance();
		$this->request = new ItsightHttpRequest($this->environment);
		$this->response = new ItsightHttpResponse();
		$this->view = new ItsightView();
		$this->templet = $this->config['DefaultXmlView'] ;
		$this->auth = null;
		$this->config['RenderOption'] = $this->environment['PATH_INFO_TYPE'];
		//print_r($this->environment);
	}
	public function getDefultConfig(){
		return array(
			'DefaultXmlView'=>'default.php',
			'DefaultHtmlView'=>'index.php',
			'RenderOption'=>'json'
			);
	}
	public function run(){
		$className = $this->request->getCallClassName();
		try{
			ob_start();
			if (! file_exists ( "./api/AppAPI.php" )){
				$this->halt(404,"require FILE 'api/AppAPI.php'");
			}
			require_once "./api/AppAPI.php";
				
			if (! file_exists ( "./api/" . $className . ".php")){
				$this->halt(404,"require FILE 'api/" . $className . ".php'");
			}
			require_once "./api/" . $className . ".php";

			$user_class = new $className($this);
			$user_class->data = $this->request->params();
			

			//auth
			if(in_array('Auth',$user_class->uses) ) {
				//print_r(DATABASE_CONFIG::default_db());
				$this->auth = new ItsightAuth(DATABASE_CONFIG::default_db());
				$user_class->Auth = $this->auth;
				$user_class->beforeAuthCheck();
				$user_key = $this->request->params('key');
				if(isset($user_key)) {
					//echo $user_key;
					$user = $this->auth->checkUserKey($user_key);
					if (isset($user)) {
						$user_class->User = $user;
						$user_class->authCheckSuccess();
					} else {
						$user_class->authCheckFail();
						throw new Exception('Auth error : key check fails');
						
					}
				} else {
					$user_class->authCheckFail();
					throw new Exception('Auth error : miss Key');
					
				}
			}

			$user_class->beforeFilter();

			
			if ($this->request->isGet()){
				$user_class->get();
			}
			else if($this->request->isPost()){
				$user_class->post();
			}
			else if($this->request->isPut()){
				$user_class->put();
			}
			else if($this->request->isDelet()){
				$user_class->delet();
			}
			else if($this->request->isHead()){
				$user_class->head();
			}
			else if($this->request->isOption()){
				$user_class->option();
			}
			else if($this->request->isAjax()){
			}
			else{
	/*
				if (is_callable($method))
				{

				};
	*/
			}


			$user_class->afterFilter();

			$user_class->beforeRender();
			$this->response->write(ob_get_clean());
			//render
			$this->render($this->view->getData(),$this->templet,$this->config['RenderOption']);
			ob_start();
			$user_class->afterRender();
			$this->response->write(ob_get_clean());
		}catch( Exception $e){
			$this->response()->write($e);
            $this->response()->write(ob_get_contents());
		}

		list($status, $header, $body) = $this->response->finalize();
		
		//Send headers
        if ( headers_sent($filename, $linenum) === false ) {

            //Send status
            if ( strpos(PHP_SAPI, 'cgi') === 0 ) {
                header(sprintf('Status: %s', ItsightHttpResponse::getMessageForCode($status)));
            } else {
                header(sprintf('HTTP/1.1 %s', ItsightHttpResponse::getMessageForCode($status)));
            }

            //Send headers
            foreach ( $header as $name => $value ) {
                $hValues = explode("\n", $value);
                foreach ( $hValues as $hVal ) {
                    header("$name: $hVal", false);
                }
            }
        }else{
			echo $filename.$linenum;
		}

        //Send body
        echo $body;




	}

    public function environment() {
        return $this->environment;
    }


    public function request() {
        return $this->request;
    }


    public function response() {
        return $this->response;
    }

    /***** HELPERS *****/
	public function setViewData() {
		$args = func_get_args();
		//print_r($args);
		$this->view->setData($args[0]);
	}
	public function setTemplet($templet) {
		$this->templet = $templet;

	}

	public function setRenderOption($option) {
		$this->config['RenderOption'] = $option;

	}

	protected function render($data=array(),$templet='default.php',$option='json') {
		if($option == 'json'){
			$this->response->write(json_encode($data));
			$this->response->offsetSet('Content-Type','application/json');

		}
		else if($option == 'xml'){
			$this->view->setTemplatesDirectory('./view');
			$this->response->write($this->view->render($templet));
			$this->response->offsetSet('Content-Type','text/xml');
		}
		else if($option == 'html'){
			$this->view->setTemplatesDirectory('./view');
			$this->response->write($this->view->render($templet));
		}
		else{}
	}


    public function root() {
        return rtrim($_SERVER['DOCUMENT_ROOT'], '/') . rtrim($this->request->getRootUri(), '/') . '/';
    }


    protected function cleanBuffer() {
        if ( ob_get_level() !== 0 ) {
            ob_clean();
        }
    }


    public function stop() {
        throw new Exception();
    }


    public function halt( $status, $message = '' ) {
        $this->cleanBuffer();
        $this->response->status($status);
        $this->response->body($message);
        $this->stop(); 
	}


    public function pass() {
        $this->cleanBuffer();
    }


    public function contentType( $type ) {
        $this->response['Content-Type'] = $type;
    }


    public function status( $code ) {
        $this->response->status($code);
    }


    public function redirect( $url, $status = 302 ) {
        $this->response->redirect($url, $status);
        $this->halt($status, $url); 
	}

}

$Itsight = new ItsightController();
$Itsight->run();



?>
 
