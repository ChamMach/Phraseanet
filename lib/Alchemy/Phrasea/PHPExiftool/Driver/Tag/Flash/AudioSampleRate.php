<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Flash;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class AudioSampleRate extends AbstractTag
{

    protected $Id = 'mixed';

    protected $Name = 'AudioSampleRate';

    protected $FullName = 'mixed';

    protected $GroupName = 'Flash';

    protected $g0 = 'Flash';

    protected $g1 = 'Flash';

    protected $g2 = 'mixed';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Audio Sample Rate';

    protected $local_g2 = 'mixed';

}
