<?php declare(strict_types = 1);

namespace Tests;

use Nette\Utils\FileSystem;
use Shredio\FmpClient\Exception\InvalidArgumentHandler;
use Shredio\FmpClient\FmpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	public static bool $fastTests = true;

	/**
	 * @param list<MockResponse> $responses
	 */
	protected function createClient(string $file, ?InvalidArgumentHandler $handler = null, array $responses = []): FmpClient
	{
		if (self::$fastTests && str_ends_with($file, '.csv')) {
			$contents = '';

			foreach (FileSystem::readLines($file, false) as $line => $content) {
				$contents .= $content;

				if ($line > 10) {
					break;
				}
			}

			$response = new MockResponse($contents);
		} else {
			$response = MockResponse::fromFile($file);
		}

		$mockClient = new MockHttpClient([
			$response,
			...$responses,
		]);

		return new FmpClient($mockClient, 'SECRET', $handler, strictMode: true);
	}

}
