<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Category\Update;

use App\Interfaces\Validators\Constraints\RequiredUuid4;
use Symfony\Component\Validator\Constraints as Assert;
final readonly class Command
{
    public function __construct(
        #[RequiredUuid4]
        public string $id,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}
