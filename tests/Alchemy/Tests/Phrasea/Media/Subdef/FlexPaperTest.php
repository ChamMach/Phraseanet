<?php

namespace Alchemy\Tests\Phrasea\Media\Subdef;

use Alchemy\Phrasea\Media\Subdef\FlexPaper;
use Alchemy\Phrasea\Media\Subdef\Subdef;
use Alchemy\Phrasea\MediaAlchemyst\Specification\Flash as FlashSpecification;
use Alchemy\Tests\Tools\TranslatorMockTrait;

/**
 * @group functional
 * @group legacy
 */
class FlexPaperTest extends \PhraseanetTestCase
{
    use TranslatorMockTrait;

    /**
     * @var FlexPaper
     */
    protected $object;

    public function setUp()
    {
        parent::setUp();
        $this->object = new FlexPaper($this->createTranslatorMock());
    }

    /**
     * @covers Alchemy\Phrasea\Media\Subdef\FlexPaper::getType
     */
    public function testGetType()
    {
        $this->assertEquals(Subdef::TYPE_FLEXPAPER, $this->object->getType());
    }

    /**
     * @covers Alchemy\Phrasea\Media\Subdef\FlexPaper::getDescription
     */
    public function testGetDescription()
    {
        $this->assertTrue(is_string($this->object->getDescription()));
    }

    /**
     * @covers Alchemy\Phrasea\Media\Subdef\FlexPaper::getMediaAlchemystSpec
     */
    public function testGetMediaAlchemystSpec()
    {
        $this->assertInstanceOf(FlashSpecification::class, $this->object->getMediaAlchemystSpec());
    }
}
