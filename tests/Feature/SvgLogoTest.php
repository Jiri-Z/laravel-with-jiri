<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SvgLogoTest extends TestCase
{
    #[Test]
    public function landing_page_has_complete_laravel_logo_svg(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('viewBox="0 0 62 65"', $content);
        $this->assertStringContainsString('24.9533 47.987Z', $content);
        $this->assertStringNotContainsString('49.9987...', $content);
    }

    #[Test]
    public function terms_page_has_complete_laravel_logo_svg(): void
    {
        $response = $this->get(route('terms'));

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('viewBox="0 0 62 65"', $content);
        $this->assertStringContainsString('24.9533 47.987Z', $content);
        $this->assertStringNotContainsString('49.9987...', $content);
    }

    #[Test]
    public function privacy_page_has_complete_laravel_logo_svg(): void
    {
        $response = $this->get(route('privacy'));

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('viewBox="0 0 62 65"', $content);
        $this->assertStringContainsString('24.9533 47.987Z', $content);
        $this->assertStringNotContainsString('49.9987...', $content);
    }
}
