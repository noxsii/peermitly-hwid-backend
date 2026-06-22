<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Data\LicenseKeys\LicenseKeyConfiguration;
use App\Data\LicenseKeys\LicenseKeyGenerationContext;
use App\Enums\LicenseKeyGeneratorType;
use App\Http\Requests\LicenseKeys\PreviewLicenseKeyTypeRequest;
use App\Services\LicenseKeys\Generators\LicenseKeyGeneratorFactory;
use Illuminate\Http\JsonResponse;

final readonly class LicenseKeyTypePreviewController
{
    public function __construct(
        private LicenseKeyGeneratorFactory $factory,
    ) {}

    public function preview(PreviewLicenseKeyTypeRequest $request): JsonResponse
    {
        $type = LicenseKeyGeneratorType::from($request->string('generator_type')->toString());
        $configuration = LicenseKeyConfiguration::from($type, $request->array('configuration'));

        $samples = [];

        for ($i = 0; $i < 3; $i++) {
            $samples[] = $this->factory->generate($configuration, LicenseKeyGenerationContext::empty());
        }

        return new JsonResponse(['samples' => $samples]);
    }
}
