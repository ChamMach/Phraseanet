<?php

/*
 * This file is part of Media-Alchemyst.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\MediaAlchemyst\Specification;

class Audio extends AbstractSpecification
{
    protected $audioKiloBitrate;
    protected $audioCodec;
    protected $audioSampleRate;
    protected $fileType;
    protected $audioChannel;

    public function getType()
    {
        return self::TYPE_AUDIO;
    }

    public function getAudioKiloBitrate()
    {
        return $this->audioKiloBitrate;
    }

    public function setAudioKiloBitrate($kiloBitrate)
    {
        $this->audioKiloBitrate = $kiloBitrate;
    }

    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    public function setAudioCodec($audioCodec)
    {
        $this->audioCodec = $audioCodec;
    }

    public function getAudioSampleRate()
    {
        return $this->audioSampleRate;
    }

    public function setAudioSampleRate($audioSampleRate)
    {
        $this->audioSampleRate = $audioSampleRate;
    }

    public function setAudioChannels($audioChannel)
    {
        $this->audioChannel = $audioChannel;
    }

    public function getAudioChannels()
    {
        return $this->audioChannel;
    }
}
