<?php

namespace App\Model;

use InvalidArgumentException;
use JsonSerializable;
use Webmozart\Assert\Assert;

readonly class UserNotification implements JsonSerializable
{
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $ctaUrl,
    ) {
        $set = $title || $description || $ctaUrl;
        if (!$set) {
            throw new InvalidArgumentException('A user notification must have at least one property set: title, description or ctaUrl');
        }
        if ($ctaUrl && ! filter_var($ctaUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('CTA URL is not a valid URL');
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'cta' => $this->ctaUrl,
        ];
    }
}
