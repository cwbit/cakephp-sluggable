<?php

namespace Sluggable\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Sluggable\Utility\Slug;

/**
 * @package default
 */

class SluggableBehavior extends Behavior
{
    
    /**
     * Array of default config options. Will be available from $this->config()
     * @param field (string) the field we will be slugging
     * @param slug (string) the field storing the slug
     * @param replacement (string) what to replace spaces and stuff with
     */
    protected $_defaultConfig = [
        'pattern'       => ':name',
        'field'         => 'slug',
        'replacement'   => '-',
        'overwrite'     => false,
        ];

    /**
     * Uses the configuration settings to generate a slug for the given $entity.
     * @param Entity $entity
     * @return string slug
     */
    private function _generateSlug(Entity $entity)
    {
        $config = $this->config();                                  # load the config built by the instantiated behavior

        if ($entity->get($config['field']) && !$config['overwrite']) :    # if already set, and !overwrite
            return $entity->get($config['field']);                  # return existing
        endif;

        $value = Slug::generate($config['pattern'], $entity, $config['replacement']);

        return $value;                                              # return the slug
    }

    /**
     * Before Saving the entity, slug it.
     * @param Event $event
     * @param Entity $entity
     * @return void
     */
    public function afterSave(Event $event, Entity $entity, $options)
    {
        $config = $this->config();                                  # load the config built by the instantiated behavior

        $original = $entity->get($config['field']);                 # manually store $original - wasn't working for some reason otherwise
        $entity->set($config['field'], $this->_generateSlug($entity)); # set the slug

        # if the slug is actually different than before - save it
        if ($entity->dirty() && ($original != $entity->get($config['field']))) :
            $this->_table->save($entity);
        endif;

    }

    /**
     * Allows you to do a $table->find('slugged', ['slug'=>'hello-world'])
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findSlugged(Query $query, array $options)
    {
        $config = $this->config();
        return $query->where([$this->_table->alias().'.'.$config['field'] => $options['slug']]);
    }

    /**
     * Allows you to do a $table->find('sluggedList') and get an array of [slug]=>name instead of id=>name
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findSluggedList(Query $query, array $options)
    {
        $config = $this->config();
        return $query->find('list', ['keyField'=>$config['field']]);
    }
}
