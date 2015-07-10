<?php
	
/**
 * @author Ibrahim Tounkara (PHP/Symfony Backend Developer)
 */
	 
	 
	require 'HttpResponse.class.php';
	require './Model/Car.class.php';
	require 'Objectable.php';
	
	class HttpRequest{
		
		/**
		 * Property: url_elements
		 * The Model requested in the URI. eg: /files
		 */
		protected $url_elements = '';
		
		
		/**
		 * Property: method
		 */
		protected $method = '';
		
		
		/**
		 * @property args
		 */
		protected $args = array();
		
		
		
		/**
		 * @property format
		 * Store the content-type header
		 */
		protected $format;
		
		
		
	//Getters and setters
		
		public function getmethod(){
			return $this->method;
		}
		
		public function geturlelements(){
			return $this->url_elements;	
		}
		
		
		public function getargs(){
			return $this->args;
		}
		
		
		public function getformat(){
			return $this->format;
		}
		
		
		
		public function __construct(){
			
			$this->method       = $_SERVER['REQUEST_METHOD'];
			$this->url_elements = explode('/', $_SERVER['PATH']);
			$this->args         = $this->extractArguments();
			
			return true;
		}
		

		
		
		/**
		 * @property table
		 * Extract the arguments coming from a exploded url
		 */
		protected function extractArguments(){
			
			$parameters   = array();
			$body         = file_get_contents("php://input"); //get the arguments from the url
			$content_type = false;
			
			//Firstable, pull all the GET vars
			if(isset($_SERVER['QUERY_STRING'])){
				parse_str($_SERVER['QUERY_STRING'],$parameters);
				
			}
			
		
			if(isset($_SERVER['CONTENT_TYPE'])){
				$content_type = $_SERVER['CONTENT_TYPE'];	
			}
			
			switch ($content_type) {
				
				case "application/json":
					$param = $this->objectContentJson($parameters);
				    break;
				    
				case "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8":
					$param = $this->objectContentHtml($parameters);
					break;
					
				case "text/xml":
					$param = $this->objectContentXml($parameters);
					break;
	
				default:
					;
				break;
			}

			return $param;
			
		}
		
		
		
		
		
		/**
		 * @param $body
		 * @param $parameters
		 * Initialise the format as JSON
		 */
		protected function objectContentJson($parameters){
			$body_param = json_decode($parameters);
			$parameterstab   = array();
			
			if($body_param){
				
				//loop the array, and index values
				foreach ($body_param as $param_name => $param_value){
					$parameterstab[$param_name] = $param_value;
				}
			}
			$this->format = 'json';
			return $parameterstab;
		}
		
		
		
		
		
	/**
	 * Initialise the format as HTML
	 * @param $body
	 * @param $parameters
	 */
		protected function objectContentHtml($parameters){
			//parse_str($parameters,$postvars);
			$parameterstab   = array();
			
			foreach ($parameters as $field => $value){
				$parameterstab[$field] = $value;
			}
			
			
			$this->format = 'html';
			return $parameterstab;
		}
		
		
		
		
	/**
	 * Initialise the format as XML
	 * @param $body
	 * @param $parameters
	 */
		protected function objectContentXml($parameters){
			/*$parser	= xml_parser_create();
			
			xml_set_default_handler($parser, "startElement", "stopElement");
			xml_set_character_data_handler($parser, 'char');
			xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING ,0);
			
			
			xml_parse($parser, $data) or die("XML Error: %s at %d",
						xml_error_string(xml_get_error_code($parser)),
						xml_get_current_line_number($parser));
			 	
			xml_parser_free($parser);*/
			
			$parameterstab   = array();
			
			//$xml = simplexml_load_string($body);
			
			$this->format = 'xml';
				
		}
		
		
		
	
		
		
   /**
	* Retrieve resources and return datas
	*/
			public function getRequest(){
				
				$tab    = $this->getargs();
				$value  = $tab['serialnumber'];
				$result = Car::getVehicle($value);
				
				$httpresponse = new HttpResponse();
				
				if(is_null($result) == false){
					
					$httpresponse->setprotocol($_SERVER['SERVER_PROTOCOL']);
					$httpresponse->setresponsecode(200);
					$httpresponse->setreasonphrase($httpresponse->getresponsecode());
					$httpresponse->setcontent_type('application/json');
					$httpresponse->setdata($result);
				}
				else{
					$httpresponse->setprotocol($_SERVER['SERVER_PROTOCOL']);
					$httpresponse->setresponsecode(404);
					$httpresponse->setreasonphrase($httpresponse->getresponsecode());
					$httpresponse->setcontent_type('Content-type: text/plain');
					$httpresponse->setdata('None existing car with this id');
				}
				echo $httpresponse->sendResponse();
			}

			
			
			
		
   /**
    *  Create a resource on the server
    */	
	  		public function postRequest(){
				
	  			$object = $this->getargs();
				
				$Car = new Car($object['sm'], $object['manufacturer'], $object['modelcar'], $object['typecar'], $object['r_date'], $object['drive_stick']);
	  			
				$result = $Car->setVehicle();
	  			
				$httpresponse = new HttpResponse();

	  			if($result != true){
	  				$httpresponse->setversionhttp($_SERVER['SERVER_PROTOCOL']);
	  				$httpresponse->setresponsecode(404);
	  				$httpresponse->setreasonphrase($httpresponse->getresponsecode());
	  				$httpresponse->setresponseheader('Content-type: text/plain');
	  				$httpresponse->setdata('Error happened during the insert process');
	  			}
	  			 else{
	  			 	$httpresponse->setversionhttp($_SERVER['SERVER_PROTOCOL']);
	  			 	$httpresponse->setresponsecode(200);
	  			 	$httpresponse->setreasonphrase($httpresponse->getresponsecode());
	  			 	$httpresponse->setresponseheader('Content-type: text/plain');
	  			 	$httpresponse->setdata('Your vehicle has been added');
	  			 }
	  			 
	  			return $httpresponse->sendResponse();
	  		}	
		
		
	  
	  
	/**
	 *  Change or update the resource content
	 */
			public function putRequest(){
				
			}
	  
			
	/**
	 *  Remove or delete a resource
	 */		
	  		public function deleteRequest(){
	  			
	  		}
	  
	  
	}
