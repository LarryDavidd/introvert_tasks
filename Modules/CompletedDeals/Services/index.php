<?php

namespace Modules\CompletedDeals\Services;

use Introvert\ApiClient;
use Introvert\Configuration;

class Index{
  const HOST_URI = 'https://api.s1.yadrocrm.ru/';

  const STATUS_CLOSED = [142];

  const PACKAGE_COUNT = 50;
  
  protected $api;

  public function __construct()
  {
    $this->api = new ApiClient();
  }

  public function getClients(): array
  {
    return [
      [
        'id' => 1,
        'name' => 'intrdev',
        'api' => '23bc075b710da43f0ffb50ff9e889aed',
      ],
      [
        'id' => 2,
        'name' => 'artedegrass0',
        'api' => '35v35y4u3b5fy45y4guk3y5qu4k5u45',
      ]
    ];
  }

  public function checkIsEmpty(array $client): bool
  {
    try {
      Configuration::getDefaultConfiguration()
        ->setHost(host: self::HOST_URI)
        ->setApiKey('key', $client['api']);
      $this->api->account->info();
      return true;
    } catch (\Exception $e) {  
      error_log($e->getMessage() . $e->getFile() . $e->getLine());
      return false;
    }
  }

  public function getLeadsSum(string $dateFrom, string $dateTo): float
  {
    try {
      $hasMore  = true;
      $totalSum = 0;
      $offset = 0;

      while ($hasMore) {
        $res = $this->api->lead->getAll(null, self::STATUS_CLOSED, null, null, self::PACKAGE_COUNT, $offset);
        
        if (empty($res['result'])) {
          $hasMore = false;
        } else {
          foreach ($res['result'] as $lead) {
            if ($lead['date_create'] >= strtotime($dateFrom) && $lead['date_close'] <= strtotime($dateTo)) {
              $totalSum += (float)$lead['price'];
            }
          }
          $offset += 50;
        }
      }

      return $totalSum;
    } catch (\Exception $e) {  
      error_log($e->getMessage() . $e->getFile() . $e->getLine());
      return false;
    }
  }
} 