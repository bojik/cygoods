<?php
declare(strict_types=1);

namespace App\Form\Helper;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use function array_merge;

final class FormErrors
{
    private function __construct()
    {
    }

    /**
     * @param FormView $formView
     * @param bool $idAsName
     * @return array
     */
    public static function getFlatArrayErrors(FormView $formView, bool $idAsName = true): array
    {
        $values = [];
        /** @var FormView $subView */
        foreach ($formView->vars['form']->children as $subView) {
            if (isset($subView->vars['errors']) && count($subView->vars['errors']) > 0) {
                foreach ($subView->vars['errors'] as $error) {
                    $name = $idAsName ? $subView->vars['id'] : $subView->vars['name'];
                    /** @var FormError $error */
                    $values[$name][] = $error->getMessage();
                }
            } elseif (count($subView->children)) {
                $values = array_merge($values, self::getFlatArrayErrors($subView));
            }
        }

        return $values;
    }
}