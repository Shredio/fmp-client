<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Payload;

use Shredio\TypeSchema\Mapper\Jit\Attribute\CompileObjectMapper;

#[CompileObjectMapper(identifier: 'symbol')]
final readonly class PressRelease
{

	/**
	 * @param non-empty-string $symbol
	 * @param non-empty-string $publishedDate
	 * @param non-empty-string $publisher
	 * @param non-empty-string $title
	 * @param non-empty-string|null $image
	 * @param non-empty-string $site
	 * @param non-empty-string $text
	 * @param non-empty-string $url
	 */
	public function __construct(
		public string $symbol,
		public string $publishedDate,
		public string $publisher,
		public string $title,
		public ?string $image,
		public string $site,
		public string $text,
		public string $url,
	)
	{
	}

	/**
	 * @return array{symbol: non-empty-string, publishedDate: non-empty-string, publisher: non-empty-string, title: non-empty-string, image: non-empty-string|null, site: non-empty-string, text: non-empty-string, url: non-empty-string}
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'publishedDate' => $this->publishedDate,
			'publisher' => $this->publisher,
			'title' => $this->title,
			'image' => $this->image,
			'site' => $this->site,
			'text' => $this->text,
			'url' => $this->url,
		];
	}

}
