<?php

declare(strict_types=1);

namespace MeowList\UI\Http\Controller;

use MeowList\Application\Todo\Command\ChangeTodoItemStatusCommand;
use MeowList\Application\Todo\Command\ChangeTodoItemStatusCommandHandler;
use MeowList\Application\Todo\Command\CreateTodoItemCommand;
use MeowList\Application\Todo\Command\CreateTodoItemCommandHandler;
use MeowList\Application\Todo\Command\DeleteTodoItemCommand;
use MeowList\Application\Todo\Command\DeleteTodoItemCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo_create', methods: ['POST'])]
    public function create(
        Request $request,
        CreateTodoItemCommandHandler $handler,
        UserInterface $user
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $text = $data['text'] ?? '';

        if (trim($text) === '') {
            return new JsonResponse(['error' => 'Text cannot be empty'], Response::HTTP_BAD_REQUEST);
        }

        // We assume User ID is available via getUser()->getId() depending on how User represents id
        // In our User class, getId() returns the ID.
        /** @var \MeowList\Domain\User\User $dbUser */
        $dbUser = $user;
        
        $command = new CreateTodoItemCommand($dbUser->getId(), trim($text));
        $handler($command);

        return new JsonResponse(['success' => true], Response::HTTP_CREATED);
    }

    #[Route('/todo/{id}/status', name: 'app_todo_status_change', methods: ['PATCH'])]
    public function changeStatus(
        int $id,
        Request $request,
        ChangeTodoItemStatusCommandHandler $handler,
        UserInterface $user
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $isDone = (bool) ($data['isDone'] ?? false);

        /** @var \MeowList\Domain\User\User $dbUser */
        $dbUser = $user;

        try {
            $command = new ChangeTodoItemStatusCommand($id, $dbUser->getId(), $isDone);
            $handler($command);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => 'Not found or unauthorized'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['success' => true]);
    }

    #[Route('/todo/{id}', name: 'app_todo_delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        DeleteTodoItemCommandHandler $handler,
        UserInterface $user
    ): JsonResponse {
        /** @var \MeowList\Domain\User\User $dbUser */
        $dbUser = $user;

        try {
            $command = new DeleteTodoItemCommand($id, $dbUser->getId());
            $handler($command);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => 'Not found or unauthorized'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['success' => true]);
    }
}
