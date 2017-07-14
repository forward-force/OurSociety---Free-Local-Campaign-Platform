<?php
declare(strict_types = 1);

namespace OurSociety\Test\Fixture;

use OurSociety\TestSuite\Fixture as App;
use OurSociety\Utility\Csv;

/**
 * QuestionsFixture
 *
 */
class QuestionsFixture extends App\TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'category_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'question' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'type' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'citizen_answer_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'politician_answer_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    public $defaults = [
        'citizen_answer_count' => 0,
        'politician_answer_count' => 0,
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'category_id' => '2d65b348-5442-414a-8aff-a45a4e160f60',
            'question' => 'Lorem ipsum dolor sit amet',
            'type' => 'Lorem ip',
        ],
    ];

    public function init(): void
    {
        collection(Csv::fromFile(CONFIG . 'Seeds' . DS . 'questions.csv')->toArray())
            ->each(function (array $record) {
                $this->records[] = [
                    'category_id' => $record['Type'],
                    'question' => $record['Question'],
                    'type' => $record['Answer A'] === 'Yes' ? 'yes/no' : 'scale',
                ];
            });

        parent::init();

    }
}
