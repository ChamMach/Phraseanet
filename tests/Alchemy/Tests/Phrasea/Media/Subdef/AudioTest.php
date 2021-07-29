<?php

namespace Alchemy\Tests\Phrasea\Media\Subdef;

use Alchemy\Phrasea\Media\Subdef\Audio;
use Alchemy\Phrasea\Media\Subdef\Subdef;
use Alchemy\Tests\Tools\TranslatorMockTrait;
use MediaAlchemyst\Specification\Audio as MediaAlchemystAudio;

/**
 * @group functional
 * @group legacy
 */
class AudioTest extends \PhraseanetTestCase
{
    use TranslatorMockTrait;

    /**
     * @var Audio
     */
    protected $object;

    public function setUp()
    {
        parent::setUp();
        $this->object = new Audio($this->createTranslatorMock());
    }

    /**
     * @covers Alchemy\Phrasea\Media\Subdef\Audio::getType
     */
    public function testGetType()
    {
        $this->assertEquals(Subdef::TYPE_AUDIO, $this->object->getType());
    }

    /**
     * @covers Alchemy\Phrasea\Media\Subdef\Audio::getDescription
     */
    public function testGetDescription()
    {
        $this->assertTrue(is_string($this->object->getDescription()));
    }

    /**
     * @covers Alchemy\Phrasea\Media\Subdef\Audio::getMediaAlchemystSpec
     */
    public function testGetMediaAlchemystSpec()
    {
        $this->assertInstanceOf(MediaAlchemystAudio::class, $this->object->getMediaAlchemystSpec());
    }
}
