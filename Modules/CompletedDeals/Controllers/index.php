<?php

namespace Modules\CompletedDeals\Controllers;

use Modules\CompletedDeals\Services\Index as ClientService;
use Modules\_base\Controller as BaseController;
use Modules\CompletedDeals\Models\Index as ModelsIndex;
use System\Template;

class Index extends BaseController{

	public $dateFrom = '2024-01-20';
	public $dateTo = '2024-01-30';
	protected ModelsIndex $model;
	protected ClientService $service;

	public function __construct(){
		$this->model = ModelsIndex::getInstance();
		$this->service = new ClientService();
	}

	public function index(){
		$this->title = 'Home page';
		$clients = $this->service->getClients();
		$totalSum = 0;
		$leadsData = [];

		foreach ($clients as $client) {
			if ($this->service->checkIsEmpty($client)) {
				$clientLeadsSum = $this->service->getLeadsSum($this->dateFrom, $this->dateTo);
				
				$leadsData[] = [
					'id' => $client['id'],
					'name' => $client['name'],
					'sum' => $clientLeadsSum
				];
				
				$totalSum += $clientLeadsSum;
			} else {
				$clientLeadsSum = 0;
				$leadsData[] = [
					'id' => $client['id'],
					'name' => $client['name'],
					'sum' => $clientLeadsSum
				];
			}
		}
		
		$this->content = Template::render(__DIR__ . '/../Views/v_all.php', [
			'leadsData' => $leadsData,
			'total_sum' => $totalSum,
			'date_from' => date('Y-m-d H:i:s', strtotime($this->dateFrom)),
			'date_to' => date('Y-m-d H:i:s', strtotime($this->dateTo))
		]);
	}
}