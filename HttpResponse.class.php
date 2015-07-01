<?php
	
	/**
 	 * @author Ibrahim Tounkara
 	 */
	
	class HttpResponse{
		
		/**
		 * Property : protocol
		 */
			public $protocol;
		
		/**
		 * Property : Reason-Phrase
		 */
			public $reasonphrase;
		
		
		/**
		 * Property : response_code
		 */
			public $response_code;
				
		
		/**
		 * Property: response_header
		 */
			public $content_type;
		
		
		/**
		 * Property : data
		 */
			public $data;
			
			
			
			
			
			private $statusCodes = array(
					100 => "Continue",
					101 => "Switching Protocols",
					200 => "OK",
					201 => "Created",
					202 => "Accepted",
					203 => "Non-Authoritative Information",
					204 => "No Content",
					205 => "Reset Content",
					206 => "Partial Content",
					300 => "Multiple Choices",
					301 => "Moved Permanently",
					302 => "Found",
					303 => "See Other",
					304 => "Not Modified",
					305 => "Use Proxy",
					306 => "(Unused)",
					307 => "Temporary Redirect",
					400 => "Bad Request",
					401 => "Unauthorized",
					402 => "Payment Required",
					403 => "Forbidden",
					404 => "Not Found",
					405 => "Method Not Allowed",
					406 => "Not Acceptable",
					407 => "Proxy Authentication Required",
					408 => "Request Timeout",
					409 => "Conflict",
					410 => "Gone",
					411 => "Length Required",
					412 => "Precondition Failed",
					413 => "Request Entity Too Large",
					414 => "Request-URI Too Long",
					415 => "Unsupported Media Type",
					416 => "Requested Range Not Satisfiable",
					417 => "Expectation Failed",
					500 => "Internal Server Error",
					501 => "Not Implemented",
					502 => "Bad Gateway",
					503 => "Service Unavailable",
					504 => "Gateway Timeout",
					505 => "HTTP Version Not Supported"
			);
			
			
			
			
			
			
			

		//Getters and setters
	
			public function getprotocol(){
				
				return $this->protocol;
			}

			
			public function getreasonphrase(){
				
				 return $this->reasonphrase;
			}
			
			
			public function getresponsecode(){
				return $this->response_code;	
			}
			
			
			public function getcontent_type(){
				return $this->content_type;
			}
		
			
			public function getdata(){

				return $this->data;
				
			}
			
			
			public function setprotocol($protocol){
				
				$this->protocol = $protocol;
			}
			
			
			public function setreasonphrase($httpcode){
				
				foreach ($this->statusCodes as $phrase){
						
					if($httpcode == $phrase[0]){
						$message = $phrase[1];
						$this->reasonphrase = $message;
						return $message;
					}
				}
				
			}
			
			
			
			
			public function setresponsecode($code){
				$this->response_code = $code;
			}
			
			
			public function setcontent_type($content_type){
				$this->content_type = $content_type;
			}
			
			
			public function setdata($data){
				$this->data = $data;
			}
			
			
			
			
			
			
			/*
			public function __construct($code,$header,$data){
				$this->response_code	= $code;
				$this->response_header 	= $header;
				$this->data				= $data;
			}
			*/
		    
			
			
			public function sendResponse(){
				
				//http_response_code($this->getresponsecode());
				/*header("HTTP/1.0 304 Not Modified");
				header($this->getversionhttp(),true,$this->getresponsecode());
				*/
				header('Accept:'.$this->getcontent_type());	
				header('Status:'.$this->getresponsecode().' '.$this->getreasonphrase());
				header('Content-Type:'.$this->getcontent_type());
				header($this->getprotocol());
				
				//$h = apache_response_headers();
						
				return json_encode($this->getdata());
			}
			
		
		
	}
