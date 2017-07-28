<?php
/**
 * @var \OurSociety\View\AppView $this
 * @var \OurSociety\Model\Entity\Question $question The question being asked.
 * @var \OurSociety\Model\Entity\User $currentUser The currently authenticated user.
 * @var int $number The question number in the current batch of questions.
 */
use Cake\Utility\Text;
use OurSociety\Model\Entity\Answer;

$getError = function (\OurSociety\Model\Entity\Question $question, string $field): ?string {
    if ($question->answers === null) {
        return null;
    }

    $errors = $question->answers[0]->getError($field);

    if (count($errors) === 0) {
        return null;
    }

    return $this->Form->formatTemplate('error', ['content' => implode('', $errors)]);
};

$importanceError = $getError($question, 'importance');
$answerError = $getError($question, 'answer');
?>
<article class="panel panel-default js-question">
    <div class="panel-heading">
        <div class="pull-right small text-muted">
            Not sure?
            <?= $this->Html->link(__('Answer Later'), sprintf('#question-%d', $number), [
                'aria-controls' => sprintf('question-%d', $number),
                'class' => ['js-question-link', 'text-info'],
                'data-toggle' => 'collapse',
            ]) ?>
        </div>
        <h3 class="panel-title"><?= __('Question #{number}', ['number' => $number]) ?></h3>
    </div>
    <div class="panel-collapse collapse in" id="question-<?= $number ?>" aria-expanded="true">
        <div class="panel-body">
            <div class="row row-md-flex-center">
                <div class="col-md-6">
                    <blockquote>
                        <?= $this->Html->icon($question->category->slug, ['iconSet' => 'topic', 'height' => 100, 'width' => 100]) ?>
                        <p><?= $question->question ?></p>
                        <footer>Category: <cite><?= $question->category->name ?></cite></footer>
                    </blockquote>

                    <fieldset class="form-question-importance">
                        <legend><?= __('How important is this topic to you?') ?></legend>
                        <div <?= $importanceError !== null ? ' class="has-error"' : null ?>>
                            <?= $importanceError ?>
                            <?= $this->Form->control(sprintf('%d.answers.0.importance', $number - 1), [
                                'inline' => true,
                                'required' => false,
                                'label' => false,
                                'type' => 'radio',
                                'class' => ['has-error'],
                                'options' => Answer::IMPORTANCE
                            ]) ?>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-6 form-question-answers">
                    <?= $this->Form->hidden(sprintf('%d.id', $number - 1), ['value' => $question->id]) ?>
                    <?= $this->Form->hidden(sprintf('%d.answers.0.id', $number - 1), ['value' => Text::uuid()]) ?>
                    <?= $this->Form->hidden(sprintf('%d.answers.0.question_id', $number - 1), ['value' => $question->id]) ?>
                    <?= $this->Form->hidden(sprintf('%d.answers.0.user_id', $number - 1), ['value' => $currentUser->id]) ?>
                    <div <?= $answerError !== null ? ' class="has-error"' : null ?>>
                        <?= $answerError ?>
                        <?= $this->Form->control(sprintf('%d.answers.0.answer', $number - 1), [
                            'required' => false,
                            'label' => false,
                            'type' => 'radio',
                            'options' => $question->type === 'scale' ? Answer::ANSWERS_SCALE : Answer::ANSWERS_BOOL
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer small">
            <?= $this->Html->link(__('Report this question'), '#', ['class' => 'text-muted']) ?>
        </div>
    </div>
</article>
