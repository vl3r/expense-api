<?php

declare(strict_types=1);

namespace App\Interfaces\Services\NelmioApiDoc;

use Nelmio\ApiDocBundle\Describer\DescriberInterface;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use OpenApi\Annotations\OpenApi;
use Symfony\Component\Yaml\Yaml;

final class ExternalSchemasDescriber implements DescriberInterface
{
    public function __construct(private string $docPath)
    {
    }

    public function describe(OpenApi $api): void
    {
        $externalDoc = $this->getExternalDocs();

        if (!empty($externalDoc)) {
            Util::merge($api, ['components' => ['schemas' => $externalDoc]]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function getExternalDocs(): array
    {
        return $this->load($this->docPath);
    }

    /**
     * @return array<string, mixed>
     */
    private function load(string $path): array
    {
        $config = Yaml::parseFile($path);

        if (!isset($config['imports'])) {
            return $config;
        }

        $result = [];

        /** @var array{resource: string} $import */
        foreach ($config['imports'] as $import) {
            $result += $this->load(dirname($path) . '/' . $import['resource']);
        }

        return $result;
    }
}
