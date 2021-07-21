<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ItemList;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class PlayGap extends AbstractTag
{

    protected $Id = 'pgap';

    protected $Name = 'PlayGap';

    protected $FullName = 'QuickTime::ItemList';

    protected $GroupName = 'ItemList';

    protected $g0 = 'QuickTime';

    protected $g1 = 'ItemList';

    protected $g2 = 'Audio';

    protected $Type = 'int8u';

    protected $Writable = true;

    protected $Description = 'Play Gap';

    protected $Values = array(
        0 => array(
            'Id' => 0,
            'Label' => 'Insert Gap',
        ),
        1 => array(
            'Id' => 1,
            'Label' => 'No Gap',
        ),
    );

}
