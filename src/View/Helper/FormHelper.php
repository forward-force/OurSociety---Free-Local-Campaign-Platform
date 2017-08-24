<?php
declare(strict_types=1);

namespace OurSociety\View\Helper;

use BootstrapUI\View\Helper as BootstrapUI;
use Cake\Log\Log;
use Cake\Routing\Exception\MissingRouteException;
use Cake\View\View;
use OurSociety\View\Widget;

/**
 * FormHelper.
 */
class FormHelper extends BootstrapUI\FormHelper
{
    public function __construct(View $View, array $config = [])
    {
        $defaultConfig = [
            'widgets' => [
                //'answer' => [Widget\AnswerWidget::class],
                'checkbox' => [Widget\CheckboxWidget::class],
                'editor' => [Widget\EditorWidget::class],
                //'importance' => [Widget\ImportanceWidget::class],
                'zip' => [Widget\ZipWidget::class, 'text'],
                // TODO: Implement better date/time widget with following requirements:
                // - Supports "date" also (not just "datetime"
                // - Easy to select DOB (a date many years in the past)
                // - Day field can be optional (for positions/qualifications/awards the day isn't important)
                //'datetime' => [CrudViewWidget\DateTimeWidget::class, 'select']
            ]
        ];

        parent::__construct($View, $defaultConfig + $config);
    }

    public function control($fieldName, array $options = [])
    {
        $options['placeholder'] = $options['placeholder'] ?? $options['label'] ?? null;

        return parent::control($fieldName, $options); // @todo Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     *
     * @param string|array|null $url
     */
    public function postLink($title, $url = null, array $options = []): string
    {
        try {
            return parent::postLink($title, $url, $options);
        } catch (MissingRouteException $exception) {
            Log::warning(sprintf('Missing link "%s": %s', $title, is_array($url) ? json_encode($url) : $url));
            return '';
        }
    }
}
