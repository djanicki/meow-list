<?php

namespace App\UI\Http\Controller;

use App\Application\User\RegisterUser\RegisterUserCommand;
use App\Application\User\RegisterUser\RegisterUserHandler;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\User;
use App\UI\Http\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, RegisterUserHandler $registerUserHandler, Security $security): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            /** @var string $email */
            $email = $user->getEmail();

            $command = new RegisterUserCommand($email, $plainPassword);
            
            try {
                $user = $registerUserHandler->handle($command);

                // log the user in
                return $security->login($user, 'form_login', 'main');
            } catch (UserAlreadyExistsException $e) {
                $form->get('email')->addError(new FormError('There is already an account with this email address.'));
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
