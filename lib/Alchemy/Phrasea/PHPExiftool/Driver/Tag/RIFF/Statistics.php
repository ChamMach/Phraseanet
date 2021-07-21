<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\RIFF;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Statistics extends AbstractTag
{

    protected $Id = 'STAT';

    protected $Name = 'Statistics';

    protected $FullName = 'RIFF::Info';

    protected $GroupName = 'RIFF';

    protected $g0 = 'RIFF';

    protected $g1 = 'RIFF';

    protected $g2 = 'Audio';

    protected $Type = 'string';

    protected $Writable = false;

    protected $Description = 'Statistics';

    protected $local_g2 = 'Video';

    protected $Values = array(
        0 => array(
            'Id' => 0,
            'Label' => 'Bad',
        ),
        1 => array(
            'Id' => 1,
            'Label' => 'OK',
        ),
    );

}
