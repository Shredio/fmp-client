<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class PeersBulk
{

	/**
	 * @param non-empty-string $symbol
	 * @param list<non-empty-string> $peers
	 */
	public function __construct(
		public string $symbol,
		public array $peers,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, peers: list<non-empty-string>}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'peers' => $this->peers,
		];
	}

}
