<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\StaffMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class StaffMiddlewareTest extends TestCase
{
    public function test_staff_middleware_passes_admin(): void
    {
        $user = User::factory()->admin()->create();
        $request = Request::create('/admin/courses', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new StaffMiddleware;
        $response = $middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_staff_middleware_passes_instructor(): void
    {
        $user = User::factory()->instructor()->create();
        $request = Request::create('/admin/courses', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new StaffMiddleware;
        $response = $middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_staff_middleware_blocks_student(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $request = Request::create('/admin/courses', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new StaffMiddleware;

        try {
            $middleware->handle($request, fn () => new Response('OK'));
            $this->fail('Expected HttpException with status 403');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }
    }

    public function test_staff_middleware_blocks_guest(): void
    {
        $request = Request::create('/admin/courses', 'GET');
        $request->setUserResolver(fn () => null);

        $middleware = new StaffMiddleware;

        try {
            $middleware->handle($request, fn () => new Response('OK'));
            $this->fail('Expected HttpException with status 403');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }
    }
}
