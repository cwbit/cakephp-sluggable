<?php
namespace Sluggable\Test\TestCase\Utility;

use Sluggable\Utility\Slug;
use Cake\TestSuite\TestCase;
use \Cake\ORM\Entity;

/**
 * Sluggable\Utility\Slug Test Case
 */
class SlugTest extends TestCase
{
    public function testBasicSlug()
    {
        $slug = Slug::generate('slug me');
        $this->assertEquals('slug-me', $slug);

        $slug = Slug::generate('SLUG(!@#(ME');
        $this->assertEquals('slug-me', $slug);

        $slug = Slug::generate('a really long slug that i just made');
        $this->assertEquals('a-really-long-slug-that-i-just-made', $slug);

    }

    public function testPatternedSlug()
    {
        $data = [
            'id' => 123,
            'name' => 'abc',
            'description' => 'Hello, World!',
        ];

        $slug = Slug::generate(':id-:name', $data);
        $this->assertEquals('123-abc', $slug);

        $slug = Slug::generate(':description', $data);
        $this->assertEquals('hello-world', $slug);
    }

    public function testPatternedEntitySlug()
    {
        $data = new Entity([
            'id' => 123,
            'name' => 'abc',
            'description' => 'Hello, World!',
        ]);

        $slug = Slug::generate(':id-:name', $data);
        $this->assertEquals('123-abc', $slug);

        $slug = Slug::generate(':description', $data);
        $this->assertEquals('hello-world', $slug);
    }

    public function testNonStandardReplacement()
    {
        $slug = Slug::generate('dr who', [], '.');
        $this->assertEquals('dr.who', $slug);
    }
}
