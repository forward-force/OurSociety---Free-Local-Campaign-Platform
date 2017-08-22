<?php
declare(strict_types = 1);

namespace OurSociety\Model\Table\Answers\Callback;

use ArrayObject;
use Cake\Database\Connection;
use Cake\Event\Event;
use Cake\ORM\Query;
use OurSociety\Model\Entity\Answer;
use OurSociety\Model\Entity\User;
use OurSociety\Model\Entity\ValueMatch;
use OurSociety\Model\Table\QuestionsTable;
use OurSociety\Model\Table\UsersTable;

trait AfterSave
{
    public function afterSave(Event $event, Answer $answer, ArrayObject $options): void
    {
        /** @var UsersTable $usersTable */
        $usersTable = $this->Users;
        /** @var User $userFrom */
        $userFrom = $answer->user;

        if ($userFrom === null) {
            $userFrom = $usersTable->get($answer->user_id);
        }

        $question = $answer->question;
        if ($question === null) {
            /** @var QuestionsTable $questionsTable */
            $questionsTable = $this->Questions;
            $question = $questionsTable->get($answer->question_id);
        }

        /** @var User[] $usersTo */
        $usersTo = $usersTable->find()->where([
            'answer_count >' => 0,
            'role' => $userFrom->isPolitician()
                ? User::ROLE_CITIZEN
                : User::ROLE_POLITICIAN,
        ])->all();

        $data = [];
        foreach ($usersTo as $userTo) {
            $dataDefault = [
                'citizen_id' => $userFrom->isPolitician() ? $userTo->id : $userFrom->id,
                'politician_id' => $userFrom->isPolitician() ? $userFrom->id : $userTo->id,
            ];
            $data[] = $dataDefault + [
                    'category_id' => $question->category_id,
                ];
        }

        $conditions = ['or' => []];
        foreach ((array)$data as $condition) {
            if ($condition['category_id'] === null) {
                unset($condition['category_id']);
                $condition['category_id IS'] = null;
            }
            $conditions['or'][] = $condition;
        }
        /** @var ValueMatch[] $outdatedMatches */
        $outdatedMatches = $usersTable->ValueMatches->find()->where($conditions)->all();

        foreach ($outdatedMatches as $outdatedMatch) {
            foreach ($data as &$datum) {
                if (
                    $datum['citizen_id'] === $outdatedMatch->citizen_id &&
                    $datum['politician_id'] === $outdatedMatch->politician_id &&
                    $datum['category_id'] === $outdatedMatch->category_id
                ) {
                    $datum['id'] = $outdatedMatch->id;
                }
            }
            unset($datum);
        }

        foreach ($data as &$datum) {
            /** @var Query $query */
            $query = $this->find();
            $citizenQuestionIds = $query->where(['user_id' => $datum['citizen_id']])->all()->extract('question_id')->toArray();
            $politicianCommonQuestionCount = $query->where([
                'user_id' => $datum['politician_id'],
                'question_id IN' => $citizenQuestionIds,
            ])->count();

            $sampleSize = $politicianCommonQuestionCount;
            if ($sampleSize === 0) {
                $errorPercentage = 1.0;
                $matchPercentage = 0.0;
                $trueMatchPercentage = 0.0;
            } else {
                $errorPercentage = (1 / $sampleSize) * 100;

                /** @var Connection $connection */
                $query = <<<SQL
    SELECT (SQRT(
    SUM(IFNULL((
    LEAST(ABS(citizen.answer), ABS(politician.answer)) /
GREATEST(ABS(citizen.answer), ABS(politician.answer))
), 1) * citizen.importance) / SUM(citizen.importance) * 100
*
SUM(IFNULL((
LEAST(ABS(citizen.answer), ABS(politician.answer)) /
GREATEST(ABS(citizen.answer), ABS(politician.answer))
), 1) * politician.importance) / SUM(politician.importance) * 100
)) AS match_percentage
FROM answers as citizen
LEFT JOIN answers as politician
ON politician.question_id = citizen.question_id
AND politician.user_id = ?
WHERE citizen.user_id = ?
ORDER BY citizen.question_id
SQL;

                $connection = $this->getConnection();
                $statement = $connection->execute($query, [$datum['citizen_id'], $datum['politician_id']]);
                $matchPercentage = $statement->fetch('assoc')['match_percentage'];

                $trueMatchPercentage = max($matchPercentage - $errorPercentage, 0);
            }

            $datum += [
                'true_match_percentage' => $trueMatchPercentage,
                'match_percentage' => $matchPercentage,
                'error_percentage' => $errorPercentage,
                'sample_size' => $sampleSize,
            ];
        }
        unset($datum);

        $updatedMatches = $usersTable->ValueMatches->patchEntities($outdatedMatches, $data);

        $usersTable->ValueMatches->saveMany($updatedMatches);
    }
}