<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Form\User\SignUpForm;
use App\HttpFoundation\JsonResponseFactory as JRF;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class SignUpController extends AbstractController
{
    /**
     * @Route("/sign-up/", name="app_sign_up")
     */
    public function signUp(Request $request): Response
    {
        $form = $this->createForm(SignUpForm::class);

        if ($request->isXmlHttpRequest()) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    return JRF::success();
                } else {
                    return JRF::form($form);
                }
            }
        }

        return $this->render('user/sign-up.html.twig', ['signUpForm' => $form->createView()]);
    }
}
