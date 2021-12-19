<?php
declare(strict_types=1);

namespace App\HttpFoundation;

use App\Form\Helper\FormErrors;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonResponseFactory
{
    private function __construct()
    {
    }

    public static function form(FormInterface $form, string $location = null): JsonResponse
    {
        if ($form->isValid()) {
            return self::success(null, $location);
        }
        $view = $form->createView();
        $fieldErrors = FormErrors::getFlatArrayErrors($view);
        $errors = [];
        foreach ($fieldErrors as $field => $error) {
            $errors[] = ['field' => $field, 'error' => $error];
        }
        if (count($form->getErrors()) > 0) {
            /** @var FormError $error */
            foreach ($form->getErrors() as $i => $error) {
                $errors[] = ['field' => '', 'error' => $error];
            }
        }
        return self::create(false, null, $errors, null);
    }

    /**
     * @param string|null $message
     * @param string|null $location
     * @return JsonResponse
     */
    public static function success(string $message = null, string $location = null): JsonResponse
    {
        return self::create(true, $message, null, $location);
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function fail(string $message = null): JsonResponse
    {
        return self::create(false, $message, null, null);
    }

    /**
     * @param bool $success
     * @param string|null $message
     * @param array|null $errors
     * @param string|null $location Location to redirect after success action
     * @return JsonResponse
     */
    public static function create(bool $success, ?string $message, ?array $errors, ?string $location): JsonResponse
    {
        $data = [
            'success' => $success,
        ];
        if ($message !== null) {
            $data['message'] = $message;
        }
        if ($errors !== null) {
            $data['errors'] = $errors;
        }
        if ($location !== null) {
            $data['location'] = $location;
        }

        return new JsonResponse($data);
    }
}
