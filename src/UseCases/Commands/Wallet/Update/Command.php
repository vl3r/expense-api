<?php

declare(strict_types=1);

namespace App\UseCases\Commands\Wallet\Update;

use Symfony\Component\Validator\Constraints as Assert;
final class Command
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public readonly string $id,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public string $name,
    ){
    }
}
