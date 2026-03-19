<?php

namespace App\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\Todo\Query\GetTodosForUserQuery;
use App\Application\Todo\Query\GetTodosForUserQueryHandler;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('home/index.html.twig');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(GetTodosForUserQueryHandler $todosHandler): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Should happen automatically because of security config, but just in case
        }

        /** @var \App\Domain\User\User $dbUser */
        $dbUser = $user;

        $dtos = $todosHandler(new GetTodosForUserQuery($dbUser->getId()));

        return $this->render('home/dashboard.html.twig', [
            'todos' => $dtos,
        ]);
    }
}
