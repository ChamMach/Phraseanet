<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ExifIFD;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class HCUsage extends AbstractTag
{

    protected $Id = 34030;

    protected $Name = 'HCUsage';

    protected $FullName = 'Exif::Main';

    protected $GroupName = 'ExifIFD';

    protected $g0 = 'EXIF';

    protected $g1 = 'IFD0';

    protected $g2 = 'Image';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'HC Usage';

    protected $local_g1 = 'ExifIFD';

    protected $Values = array(
        0 => array(
            'Id' => 0,
            'Label' => 'CT',
        ),
        1 => array(
            'Id' => 1,
            'Label' => 'Line Art',
        ),
        2 => array(
            'Id' => 2,
            'Label' => 'Trap',
        ),
    );

}
