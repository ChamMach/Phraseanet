<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\NikonCustom;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class VerticalFuncButtonPlusDials extends AbstractTag
{

    protected $Id = '43.1';

    protected $Name = 'VerticalFuncButtonPlusDials';

    protected $FullName = 'NikonCustom::SettingsD4';

    protected $GroupName = 'NikonCustom';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'NikonCustom';

    protected $g2 = 'Camera';

    protected $Type = 'int8u';

    protected $Writable = true;

    protected $Description = 'Vertical Func Button Plus Dials';

    protected $flag_Permanent = true;

    protected $Values = array(
        0 => array(
            'Id' => 0,
            'Label' => 'None',
        ),
        1 => array(
            'Id' => 1,
            'Label' => 'Choose Image Area (FX/DX/5:4)',
        ),
        2 => array(
            'Id' => 2,
            'Label' => 'Shutter Speed & Aperture Lock',
        ),
        3 => array(
            'Id' => 3,
            'Label' => 'One Step Speed / Aperture',
        ),
        4 => array(
            'Id' => 4,
            'Label' => 'Choose Non-CPU Lens Number',
        ),
        5 => array(
            'Id' => 5,
            'Label' => 'Active D-Lighting',
        ),
        6 => array(
            'Id' => 6,
            'Label' => 'Shooting Bank Menu',
        ),
        7 => array(
            'Id' => 7,
            'Label' => 'ISO Sensitivity',
        ),
        8 => array(
            'Id' => 8,
            'Label' => 'Exposure Mode',
        ),
        9 => array(
            'Id' => 9,
            'Label' => 'Exposure Compensation',
        ),
        10 => array(
            'Id' => 10,
            'Label' => 'Metering',
        ),
    );

}
