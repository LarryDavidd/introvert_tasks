<?php

namespace Modules\Booking\Controllers;

use Modules\Booking\Services\Index as LeadsService;
use Modules\_base\Controller as BaseController;
use Modules\Booking\Models\Index as ModelsIndex;
use System\Template;

class Index extends BaseController{
	protected ModelsIndex $model;
	protected LeadsService $service;

	public function __construct(){
		$this->model = ModelsIndex::getInstance();
		$this->service = new LeadsService();
	}

	public function index(){
		$this->title = 'Booking';
		
		$this->content = Template::render(__DIR__ . '/../Views/v_main.php', []);
	}

  public function dates(){
    $availableDates = $this->service->getAvailableDates();
    header('Content-Type: application/json');
    echo json_encode($availableDates);
    exit;
	}
}