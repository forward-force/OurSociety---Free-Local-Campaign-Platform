<?php
declare(strict_types=1);

namespace OurSociety\View\Helper;

use LilHermit\Bootstrap4\View\Helper as Bootstrap4;

//use Cake\Log\Log;
//use Cake\Routing\Exception\MissingRouteException;
//use Cake\View\View;
//use OurSociety\View\Widget;

/**
 * FormHelper.
 *
 * @property HtmlHelper $Html
 */
class FormHelper extends Bootstrap4\FormHelper
{
    public function postButton($title, $url = null, array $options = null): string
    {
        $options = $options ?? [];
        $options += ['icon' => null];

        if ($options['icon'] !== null) {
            $title = sprintf('%s %s', $this->Html->icon($options['icon']), $title);
        }

        return parent::postButton($title, $url, $options); // @todo Change the autogenerated stub
    }

    //    public function __construct(View $View, array $config = [])
    //    {
    //        $defaultConfig = [
    //            'widgets' => [
    //                //'answer' => [Widget\AnswerWidget::class],
    //                'checkbox' => [Widget\CheckboxWidget::class],
    //                'datetime' => [Widget\DateTimeWidget::class, 'select'],
    //                'editor' => [Widget\EditorWidget::class],
    //                //'importance' => [Widget\ImportanceWidget::class],
    //                'select' => [Widget\SelectBoxWidget::class],
    //                'zip' => [Widget\ZipWidget::class, 'text'],
    //            ],
    //        ];
    //
    //        $checkboxTemplate = <<<HTML
    //<input class="custom-control-input" type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>
    //<span class="custom-control-indicator"></span>
    //HTML;
    //        $dateWidgetTemplate = <<<HTML
    //<ul class="list-inline">
    //<li class="list-inline-item year">{{year}}</li>
    //<li class="list-inline-item month">{{month}}</li>
    //<li class="list-inline-item day">{{day}}</li>
    //<li class="list-inline-item hour">{{hour}}</li>
    //<li class="list-inline-item minute">{{minute}}</li>
    //<li class="list-inline-item second">{{second}}</li>
    //<li class="list-inline-item meridian">{{meridian}}</li>
    //</ul>
    //HTML;
    //
    //        $defaultConfig += [
    //            'templates' => [
    //                'checkbox' => $checkboxTemplate,
    //                'dateWidget' => $dateWidgetTemplate,
    //                'help' => '<small class="form-text text-muted">{{content}}</small>'
    //            ],
    //            'templateSet' => [
    //                'default' => [
    //                    'checkboxContainer' => '<div class="custom-controls-stacked">{{content}}{{help}}</div>',
    //                    'checkboxContainerError' => '<div class="custom-controls-stacked has-error">{{content}}{{error}}{{help}}</div>',
    //                ],
    //            ],
    //        ];
    //
    //        parent::__construct($View, $defaultConfig + $config);
    //    }
    //
    public function control($fieldName, array $options = []): string
    {
        $options['placeholder'] = $options['placeholder'] ?? $options['label'] ?? null;

        return parent::control($fieldName, $options); // @todo Change the autogenerated stub
    }

    //    /**
    //     * {@inheritdoc}
    //     *
    //     * @param string|array|null $url
    //     */
    //    public function postLink($title, $url = null, array $options = []): string
    //    {
    //        try {
    //            return parent::postLink($title, $url, $options);
    //        } /** @noinspection BadExceptionsProcessingInspection */ catch (MissingRouteException $exception) {
    //            Log::warning(sprintf('Missing link "%s": %s', $title, is_array($url) ? json_encode($url) : $url));
    //            return '';
    //        }
    //    }
    //
    //    /**
    //     * {@inheritdoc}
    //     *
    //     * @param string|array $label
    //     */
    //    protected function _inputLabel($fieldName, $label, $options): string
    //    {
    //        $label = $label ?? [];
    //
    //        if ($options['type'] === 'checkbox') {
    //            if (is_string($label)) {
    //                $label = ['text' => $label];
    //            }
    //            $label = $this->injectClasses(['custom-control', 'custom-checkbox'], $label);
    //        }
    //
    //        return parent::_inputLabel($fieldName, $label, $options);
    //    }
}
