<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\FmpClient\Payload\PeersBulk;
use Tests\TestCase;

final class PeersBulkTest extends TestCase
{

	public function testPeersBulk(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/peers-bulk.csv');

		$peers = iterator_to_array($client->peersBulk());

		$this->assertNotEmpty($peers);
		$this->assertSame((new PeersBulk(
			symbol: '000001.SZ',
			peers: [
				'601818.SS',
				'002142.SZ',
				'600919.SS',
				'600016.SS',
				'601009.SS',
				'601229.SS',
				'601336.SS',
				'601169.SS',
				'600015.SS',
				'600926.SS',
			],
		))->toArray(), $peers[0]->toArray());
	}

	public function testPeersBulkSkipsEmptyItems(): void
	{
		$client = $this->createClient(__DIR__ . '/fixtures/peers-bulk-empty-items.csv');

		$peers = iterator_to_array($client->peersBulk());

		$this->assertCount(2, $peers);
		$this->assertSame((new PeersBulk(
			symbol: 'ALSN',
			peers: ['GNTX', 'ADNT', 'ALV', 'FOXF', 'DAN', 'APTV', 'LKQ', 'LEA', 'MGA', 'AXL', 'VC', 'DORM', 'BWA'],
		))->toArray(), $peers[0]->toArray());
		$this->assertSame((new PeersBulk(
			symbol: 'ARBG',
			peers: ['BACA', 'ALOR', 'ALSA', 'AFTR-UN', 'AOAO', 'SAMA', 'VHAQ', 'DTOC', 'OPA', 'GENQ'],
		))->toArray(), $peers[1]->toArray());
	}

}
