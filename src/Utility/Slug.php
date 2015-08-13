<?php

namespace Sluggable\Utility;

use Cake\Utility\Inflector;
use Cake\Utility\Text;

class Slug
{
    /**
     * Turns a string (and optionally a dynamic, data-injected string) into a slugged value
     * @param $pattern string a simple string (e.g. 'slug me') or Text::insert-friendly string (e.g. ':id-:name')
     * @param $data mixed an Array or Entity of data to Text::insert inject into $pattern
     * @param $replacement string the character to replace non-slug-friendly characters with (default '-')
     * @return string the slugged string
     */
    public static function generate($pattern, $data = [], $replacement = '-')
    {
        # if given an Entity object, covert it to a hydrated array
        $data = ($data instanceof \Cake\ORM\Entity) ? json_decode(json_encode($data->jsonSerialize()), true) : $data;

        # build the slug
        $value = Text::insert($pattern, $data);           # inject data into pattern (if applicable)
        $value = Inflector::slug($value, $replacement);   # slug it
        $value = strtolower($value);                      # convert to lowercase

        return $value;
    }
}
