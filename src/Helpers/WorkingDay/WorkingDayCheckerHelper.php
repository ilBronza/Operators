<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;
use GuzzleHttp\Client;

use function dd;
use function in_array;
use function json_decode;

class WorkingDayCheckerHelper
{
	static function getHolidaysArrayByDate(Carbon $date)
	{
		return cache()->remember(
			'italianHolidaysByYear' . $date->year,
			3600 * 24 * 365,
			function() use ($date)
			{
				$endpoint = "https://openholidaysapi.org/PublicHolidays?countryIsoCode=IT&languageIsoCode=IT&validFrom=" . $date->year . "-01-01&validTo=" . $date->year + 1 . "-01-01";
				$client = new Client();

				$response = $client->request('GET', $endpoint);

				// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

				$statusCode = $response->getStatusCode();
				$content = $response->getBody();

				$rawResult = json_decode($response->getBody()->getContents());

				$result = [];

				foreach($rawResult as $row)
				{
					if(! $row->nationwide)
						continue;

					$result[] = $row->startDate;
				}

				return $result;
			});
	}

	static function isHoliday(Carbon $date) : bool
	{
		return in_array($date->format('Y-m-d'), static::getHolidaysArrayByDate($date));
	}

	static function isWeekend(Carbon $date) : bool
	{
		return $date->getDaysFromStartOfWeek() >= 5;
	}

	static function isWeekendOrHoliday(Carbon $date)
	{
		if(static::isWeekend($date))
			return true;

		if(static::isHoliday($date))
			return true;

		return false;
	}
}