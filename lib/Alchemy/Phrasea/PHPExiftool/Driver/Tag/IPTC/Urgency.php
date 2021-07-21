<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\IPTC;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Urgency extends AbstractTag
{

    protected $Id = 10;

    protected $Name = 'Urgency';

    protected $FullName = 'IPTC::ApplicationRecord';

    protected $GroupName = 'IPTC';

    protected $g0 = 'IPTC';

    protected $g1 = 'IPTC';

    protected $g2 = 'Other';

    protected $Type = 'digits';

    protected $Writable = true;

    protected $Description = 'Urgency';

    protected $MaxLength = 1;

    protected $Values = array(
        0 => array(
            'Id' => 0,
            'Label' => '0 (reserved)',
        ),
        1 => array(
            'Id' => 1,
            'Label' => '1 (most urgent)',
        ),
        2 => array(
            'Id' => 2,
            'Label' => 2,
        ),
        3 => array(
            'Id' => 3,
            'Label' => 3,
        ),
        4 => array(
            'Id' => 4,
            'Label' => 4,
        ),
        5 => array(
            'Id' => 5,
            'Label' => '5 (normal urgency)',
        ),
        6 => array(
            'Id' => 6,
            'Label' => 6,
        ),
        7 => array(
            'Id' => 7,
            'Label' => 7,
        ),
        8 => array(
            'Id' => 8,
            'Label' => '8 (least urgent)',
        ),
        9 => array(
            'Id' => 9,
            'Label' => '9 (user-defined priority)',
        ),
    );

}
