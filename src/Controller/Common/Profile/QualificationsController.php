<?php
declare(strict_types=1);

namespace OurSociety\Controller\Common\Profile;

use OurSociety\Controller\AppController;
use OurSociety\Model\Entity\Qualification;

/**
 * Qualifications Controller
 *
 * @method Qualification[] paginate($object = null, array $settings = [])
 */
class QualificationsController extends AppController
{
    //public function initialize(): void
    //{
    //    parent::initialize();
    //
    //    $this->modelClass = 'PoliticianQualifications';
    //}
    //
    //public function index(): ?Response
    //{
    //    $this->Crud->action()->setConfig([
    //        'scaffold' => [
    //            'actions_blacklist' => ['export'],
    //            'fields' => [
    //                'name' => ['title' => 'Qualification Title'],
    //                'institution' => ['title' => 'Institution Name'],
    //                'started' => ['title' => 'Date Started', 'formatter' => function ($field, $value) {
    //                    /** @var Date $value */
    //                    return $value ? $value->format('M Y') : null;
    //                }],
    //                'ended' => ['title' => 'Date Ended', 'formatter' => function ($field, $value) {
    //                    /** @var Date $value */
    //                    return $value ? $value->format('M Y') : null;
    //                }],
    //            ],
    //        ],
    //    ]);
    //
    //    $this->Crud->on('beforePaginate', function (Event $event) {
    //        $event->getSubject()->query = $event->getSubject()->query
    //            ->matching('Politicians', function (Query $query): Query {
    //                return $query->where(['Politicians.slug' => $this->getIdentity()->slug]);
    //            });
    //    });
    //
    //    return $this->Crud->execute();
    //}
    //
    //public function view(): ?Response
    //{
    //    return $this->Crud->execute();
    //}
    //
    //public function add(): ?Response
    //{
    //    return $this->_form();
    //}
    //
    //public function edit(): ?Response
    //{
    //    return $this->_form();
    //}
    //
    //private function _form(): ?Response
    //{
    //    $this->Crud->action()->setConfig([
    //        'scaffold' => [
    //            'actions_blacklist' => ['export'],
    //            'fields' => [
    //                'name' => ['label' => 'Qualification Title'],
    //                'institution' => ['label' => 'Institution Name'],
    //                'started' => ['label' => 'Date Started'] + $this->getFieldOptionsForYearMonth(),
    //                'ended' => ['label' => 'Date Ended'] + $this->getFieldOptionsForYearMonth(),
    //            ],
    //        ],
    //    ]);
    //
    //    $this->Crud->on('beforeSave', function (Event $event) {
    //        /** @var Qualification $qualification */
    //        $qualification = $event->getSubject()->entity;
    //        $qualification->id = Text::uuid();
    //        $qualification->politician_id = $this->getIdentity()->id;
    //    });
    //
    //    return $this->Crud->execute();
    //}
}