<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\MPF0;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class PitchAngle extends AbstractTag
{

    protected $Id = 45580;

    protected $Name = 'PitchAngle';

    protected $FullName = 'MPF::Main';

    protected $GroupName = 'MPF0';

    protected $g0 = 'MPF';

    protected $g1 = 'MPF0';

    protected $g2 = 'Image';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Pitch Angle';

}
