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

class Animation extends Image
{
    protected $delay = 800;
    protected $loops = 0;

    public function getType()
    {
        return self::TYPE_ANIMATION;
    }

    public function getDelay()
    {
        return $this->delay;
    }

    public function setDelay($delay)
    {
        $this->delay = $delay;
    }

    public function getLoops()
    {
        return $this->loops;
    }

    public function setLoops($loops)
    {
        $this->loops = $loops;
    }
}
