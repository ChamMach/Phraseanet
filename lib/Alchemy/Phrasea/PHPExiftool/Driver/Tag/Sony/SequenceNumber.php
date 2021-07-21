<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Sony;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class SequenceNumber extends AbstractTag
{

    protected $Id = 'mixed';

    protected $Name = 'SequenceNumber';

    protected $FullName = 'mixed';

    protected $GroupName = 'Sony';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'Sony';

    protected $g2 = 'Camera';

    protected $Type = 'mixed';

    protected $Writable = true;

    protected $Description = 'Sequence Number';

    protected $flag_Permanent = true;

    protected $Values = array(
        0 => array(
            'Id' => 0,
            'Label' => 'Single',
        ),
        1 => array(
            'Id' => 255,
            'Label' => 'n/a',
        ),
        2 => array(
            'Id' => 0,
            'Label' => 'Single',
        ),
        3 => array(
            'Id' => 65535,
            'Label' => 'n/a',
        ),
    );

}
