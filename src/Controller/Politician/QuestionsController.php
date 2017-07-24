<?php
declare(strict_types = 1);

namespace OurSociety\Controller\Politician;

use OurSociety\Model\Table\QuestionsTable;
use Psr\Http\Message\ResponseInterface as Response;
use OurSociety\Controller\AppController;

class QuestionsController extends AppController
{
    /**
     * @route GET /citizen/your-voice
     * @routeName citizen:questions
     */
    public function index(): ?Response
    {
        /** @var QuestionsTable $questions */
        $questions = $this->loadModel('Questions');

        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            $questions->saveAnswers($data);
            $this->refreshAuth();

            return $this->redirect(['_name' => 'politician:dashboard']);
        }

        $this->set(['questions' => $questions->getBatch()]);

        return null;
    }
}