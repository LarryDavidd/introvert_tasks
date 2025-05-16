<?php

namespace Modules\Booking\Services;

use Introvert\ApiClient;
use Introvert\Configuration;

class Index{
  private const HOST_URI = 'https://api.s1.yadrocrm.ru/';

  // replace to env
  private const API_KEY = '23bc075b710da43f0ffb50ff9e889aed';

  private const STATUS_IDS = [7716511, 453854, 654881];
  private const DATE_FIELD_ID = 1523889;
  private const BATCH_SIZE = 50;
  private const N = 5;
  
  protected $api;

  public function __construct()
  {
    $this->api = new ApiClient();
    Configuration::getDefaultConfiguration()
			->setHost(self::HOST_URI)
			->setApiKey('key', self::API_KEY);
  }

  public function getAvailableDates(): array
  {
    $dailyBookings = $this->initializeDailyBookings();
    $this->fetchAndProcessLeads($dailyBookings);
    return $this->filterAvailableDates($dailyBookings);
  }

  private function initializeDailyBookings(): array
  {
    $startDate = new \DateTime();
		$endDate = (clone $startDate)->modify('30 days');
    $dailyBookings = [];

    $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);

		foreach ($period as $date) {
			$dailyBookings[$date->format('Y-m-d')] = 0;
		}
    
    return $dailyBookings;
  }

  private function fetchAndProcessLeads(array &$dailyBookings): void
  {
    $offset = 0;
    $hasMoreLeads = true;

    while ($hasMoreLeads) {
      $leadsBatch = $this->fetchLeadsBatch($offset);
      
      if (empty($leadsBatch)) {
        $hasMoreLeads = false;
      } else {
        $this->processLeadsBatch($leadsBatch, $dailyBookings);
        $offset += self::BATCH_SIZE;
      }
    }
  }

  private function fetchLeadsBatch(int $offset): array
  {
    return $this->api->lead->getAll(
      null,
      self::STATUS_IDS,
      null,
      null,
      self::BATCH_SIZE,
      $offset
    )['result'] ?? [];
  }

  private function processLeadsBatch(array $leads, array &$dailyBookings): void
  {
    foreach ($leads as $lead) {
      $bookingDate = $this->extractLeadBookingDate($lead);
      
      if ($bookingDate !== null && isset($dailyBookings[$bookingDate])) {
        $dailyBookings[$bookingDate]++;
      }
    }
  }

  private function extractLeadBookingDate(array $lead): ?int
  {
    foreach ($lead['custom_fields'] as $field) {
      if ($field['id'] == self::DATE_FIELD_ID && !empty($field['values'][0]['value'])) {
        $dateValue = strtotime($field['values'][0]['value']);
        return strtotime('today', $dateValue);
      }
    }
    
    return null;
  }

  private function filterAvailableDates(array $dailyBookings): array
  {
    return array_filter($dailyBookings, callback: fn($count) => 
       $count < self::N
    );
  }
} 