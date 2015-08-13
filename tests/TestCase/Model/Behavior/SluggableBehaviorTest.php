<?php
namespace Sluggable\Test\TestCase\Model\Behavior;

use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Sluggable\Model\Behavior\SluggableBehavior;

/**
 * Sluggable\Model\Behavior\Sluggable Test Case
 */
class SluggableBehaviorTest extends TestCase
{
    public $fixtures = [
        'core.articles',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->connection = ConnectionManager::get('test');
        
        $this->Articles = TableRegistry::get('Articles', [
            'connection' => $this->connection,
        ]);

    }
    public function tearDown()
    {
        parent::tearDown();

        unset($this->Articles);
        TableRegistry::clear();
    }

    public function testAutoSlug()
    {
        $settings = [
            'pattern' => ':title',
            ];

        $this->Articles->addBehavior('Sluggable.Sluggable', $settings);

        # create slug for first time
        $entity = $this->Articles->newEntity(['id'=>123, 'title' => 'dr who']);
        $entity = $this->Articles->save($entity);
        $this->assertEquals('dr-who', $entity->get('slug'));

        # attempt re-create with overwrite off (should fail)
        $entity->title = 'You\'ll Never Know';
        $entity = $this->Articles->save($entity);
        $this->assertEquals('dr-who', $entity->get('slug'));
    }

    public function testAutoSlugWithOverwrite()
    {
        $settings = [
            'pattern' => ':title',
            'overwrite' => true,
            ];

        $this->Articles->addBehavior('Sluggable.Sluggable', $settings);

        # create slug for first time
        $entity = $this->Articles->newEntity(['id'=>123, 'title' => 'dr who']);
        $entity = $this->Articles->save($entity);
        $this->assertEquals('dr-who', $entity->get('slug'));

        # attempt re-create with overwrite off (should succeed)
        $entity->title = 'You\'ll Never Know';
        $entity = $this->Articles->save($entity);
        $this->assertEquals('you-ll-never-know', $entity->get('slug'));

    }

    public function testNonStandardField()
    {
        $settings = [
            'pattern' => ':title',
            'field' => 'foo',
            ];

        $this->Articles->addBehavior('Sluggable.Sluggable', $settings);

        # create slug for first time
        $entity = $this->Articles->newEntity(['id'=>123, 'title' => 'dr who']);
        $entity = $this->Articles->save($entity);
        $this->assertEquals('dr-who', $entity->get('foo'));
        $this->assertEquals(null, $entity->get('slug'));

    }

    public function testNonStandardReplacement()
    {
        $settings = [
            'pattern' => ':title',
            'replacement' => '.',
            ];

        $this->Articles->addBehavior('Sluggable.Sluggable', $settings);

        # create slug for first time
        $entity = $this->Articles->newEntity(['id'=>123, 'title' => 'dr who']);
        $entity = $this->Articles->save($entity);
        $this->assertEquals('dr.who', $entity->get('slug'));

    }
}
