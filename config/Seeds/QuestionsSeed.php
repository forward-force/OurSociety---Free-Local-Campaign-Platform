<?php
declare(strict_types=1);

use Cake\Utility\Text;
use OurSociety\Migration as App;

/**
 * Questions seeder.
 *
 * Seeds the `questions` table from the `questions.csv` file.
 */
class QuestionsSeed extends App\AbstractSeed
{
    public function run(): void
    {
        $table = $this->table('questions');
        $this->assertEmptyTable($table);

        $categoryIds = [];
        foreach ($table->getAdapter()->fetchAll('SELECT id, name FROM categories') as $row) {
            $categoryIds[$row['name']] = $row['id'];
        }

        $data = [];
        foreach ($this->getCsvRecords('questions.csv') as $record) {
            $data[] = [
                'id' => Text::uuid(),
                'question' => $record['Question'],
                'type' => $record['Answer A'] === 'Yes' ? 'yes/no' : 'scale',
                'category_id' => $categoryIds[$record['Type']],
            ];
        }

        $table->insert($data)->save();
    }
}
