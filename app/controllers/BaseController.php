<?php

class BaseController extends Controller {

	public $code;
	public $status;
	public $description;
	public $response;
	
	function check()
	{
		if(Request::get('clientID') == null or Request::get('clientID') != 'a2iosclient')
		{
			$this->status = 'error';
			$this->code = '503';
			$this->response = array(
				'status' => $this->status,
				'code' => 503,
				'description' => 'Wrong username or password');
		}
	}
	
	function checkToken()
	{
		if(Request::get('token') == null or Request::get('token') != session_id())
		{
			$this->status = 'error';
			$this->code = '503';
			$this->response = array(
				'status' => $this->status,
				'code' => 503,
				'description' => 'Invalid Token!');
		}
	}
	
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}