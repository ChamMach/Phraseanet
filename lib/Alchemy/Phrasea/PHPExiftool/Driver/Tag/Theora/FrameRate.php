<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Theora;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class FrameRate extends AbstractTag
{

    protected $Id = 15;

    protected $Name = 'FrameRate';

    protected $FullName = 'Theora::Identification';

    protected $GroupName = 'Theora';

    protected $g0 = 'Theora';

    protected $g1 = 'Theora';

    protected $g2 = 'Video';

    protected $Type = 'rational64u';

    protected $Writable = false;

    protected $Description = 'Frame Rate';

}
