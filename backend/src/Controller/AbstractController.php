<?php

namespace App\Controller;

use App\Service\RequestValidator\AbstractRequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AbstractController extends BaseController
{
    protected function redirectBack(
        AbstractRequestValidator $request,
        $message = 'Operation executed successfully!'
    ): RedirectResponse {
        $request->getSession()->set('success', ['message' => $message]);

        return $this->redirect($request->headers()->get('referer', '/'));
    }
}
