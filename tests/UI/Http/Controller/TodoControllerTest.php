<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Controller;

use App\Application\Todo\Command\CreateTodoItemCommand;
use App\Application\Todo\Command\CreateTodoItemCommandHandler;
use App\Domain\User\User;
use App\UI\Http\Controller\TodoController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class TodoControllerTest extends TestCase
{
    public function testCreateReturnsCreatedResponseOnSuccess(): void
    {
        $handler = $this->createMock(CreateTodoItemCommandHandler::class);
        $handler->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(function (CreateTodoItemCommand $command) {
                return $command->userId === 1 && $command->text === 'New task';
            }));

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);

        $request = new Request([], [], [], [], [], [], json_encode(['text' => 'New task']));

        $controller = new TodoController();
        $response = $controller->create($request, $handler, $user);

        $this->assertSame(201, $response->getStatusCode());
    }

    public function testCreateReturnsBadRequestIfTextIsEmpty(): void
    {
        $handler = $this->createMock(CreateTodoItemCommandHandler::class);
        $handler->expects($this->never())->method('__invoke');

        $user = $this->createMock(User::class);
        
        $request = new Request([], [], [], [], [], [], json_encode(['text' => '  ']));

        $controller = new TodoController();
        $response = $controller->create($request, $handler, $user);

        $this->assertSame(400, $response->getStatusCode());
    }
}
