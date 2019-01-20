<?php

use Tbruckmaier\Corcelacf\Models\Term;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Term as CorcelTerm;
use Illuminate\Support\Collection;

class FieldTermTest extends TestCase
{
    public function testTermField()
    {
        $term = factory(CorcelTerm::class)->create(['slug' => 'uncategorized']);


        $acfField = factory(Term::class)->states('taxonomy_single')->create();
        $this->addData($acfField, 'fake_taxonomy_single', $term->term_id);

        $this->assertInstanceOf(CorcelTerm::class, $acfField->value);
        $this->assertTrue($term->is($acfField->value));
    }

    public function testTermMultiple()
    {
        $term = factory(CorcelTerm::class)->create(['slug' => 'uncategorized']);
        $term2 = factory(CorcelTerm::class)->create(['slug' => 'test-term']);


        $acfField = factory(Term::class)->states('taxonomy')->create();
        $this->addData($acfField, 'fake_taxonomy', serialize([$term->term_id, $term2->term_id]));

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(2, $acfField->value->count());
        $this->assertInstanceOf(CorcelTerm::class, $acfField->value->first());
    }
}
