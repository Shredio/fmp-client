<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\PressRelease;
use Tests\TestCase;

final class PressReleasesLatestTest extends TestCase
{

	public function testPressReleasesLatest(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/press-releases-latest.json');

		$pressReleases = iterator_to_array($client->pressReleasesLatest(20));

		$this->assertNotEmpty($pressReleases);
		$this->assertSame((new PressRelease(
			symbol: 'SYNA',
			publishedDate: '2025-09-08 13:16:00',
			publisher: 'GlobeNewsWire',
			title: 'Synaptics to demonstrate how AI-native processing enables MSOs and OTT streamers to deliver new features at IBC 2025',
			image: null,
			site: 'globenewswire.com',
			text: 'AMSTERDAM, Sept. 08, 2025 (GLOBE NEWSWIRE) -- Synaptics® Incorporated (Nasdaq: SYNA ) will be at IBC 2025 from September 12-15 with a full program of demonstrations showcasing how embedding artificial intelligence (AI) and AI-native processing in set-top boxes (STBs) and over-the-top (OTT) streaming devices creates a vast opportunity for video service providers to enrich the viewing experience for their customers.',
			url: 'https://www.globenewswire.com/news-release/2025/09/08/3146368/35894/en/Synaptics-to-demonstrate-how-AI-native-processing-enables-MSOs-and-OTT-streamers-to-deliver-new-features-at-IBC-2025.html',
		))->toArray(), $pressReleases[0]->toArray());
	}

	public function testPressReleasesLatestWithParameters(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/press-releases-latest.json');

		$pressReleases = iterator_to_array($client->pressReleasesLatest(10, 1));

		$this->assertNotEmpty($pressReleases);
		$this->assertInstanceOf(PressRelease::class, $pressReleases[0]);
	}

}
