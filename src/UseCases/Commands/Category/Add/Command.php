<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Category\Add;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class Command
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}
