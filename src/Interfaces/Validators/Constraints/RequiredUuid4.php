<?php

declare(strict_types=1);

namespace App\Interfaces\Validators\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
final class RequiredUuid4 extends Assert\Compound
{
    /**
     * @param array<array-key, mixed> $options
     *
     * @return Constraint
     */
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\Uuid($options, versions: [Assert\Uuid::V4_RANDOM], strict: true),
            new Assert\NotBlank($options),
        ];
    }
}
