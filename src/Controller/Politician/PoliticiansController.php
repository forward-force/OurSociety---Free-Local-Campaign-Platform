<?php
declare(strict_types=1);

namespace OurSociety\Controller\Politician;

use Cake\Event\Event;
use Cake\ORM\Query;
use OurSociety\Controller\CrudController;
use OurSociety\Model\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Politicians Controller
 *
 * @method User[] paginate($object = null, array $settings = [])
 */
class PoliticiansController extends CrudController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->modelClass = 'Users';
        $this->Crud->disable(['index', 'delete']);
    }

    public function view(): ?Response
    {
        $this->set([
            'politician' => $this->loadModel('Users')
                ->find('politician')
                ->where(['slug' => $this->Auth->user('slug')])
                ->firstOrFail(),
        ]);

        return null;
    }

    public function edit(): ?Response
    {
        $this->Crud->on('beforeFind', function (Event $event) {
            /** @var Query $query */
            $query = $event->getSubject()->query;
            $query->where(['Users.id' => $this->Auth->user('id')], [], true);
        });

        $this->Crud->on('afterSave', function (Event $event) {
            if ($event->getSubject()->success === true) {
                $this->refreshAuth();
            }
        });

        $this->Crud->on('beforeRedirect', function (Event $event) {
            $event->getSubject()->url = ['_name' => 'politician:profile'];
        });

        return $this->Crud->execute();
    }
}