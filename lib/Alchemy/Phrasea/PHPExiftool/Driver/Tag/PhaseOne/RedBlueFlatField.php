<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\PhaseOne;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class RedBlueFlatField extends AbstractTag
{

    protected $Id = 1035;

    protected $Name = 'RedBlueFlatField';

    protected $FullName = 'PhaseOne::SensorCalibration';

    protected $GroupName = 'PhaseOne';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'PhaseOne';

    protected $g2 = 'Camera';

    protected $Type = 'undef';

    protected $Writable = false;

    protected $Description = 'Red Blue Flat Field';

    protected $flag_Binary = true;

    protected $flag_Permanent = true;

}
