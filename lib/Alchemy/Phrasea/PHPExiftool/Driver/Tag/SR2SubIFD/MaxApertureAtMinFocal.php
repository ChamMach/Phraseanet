<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\SR2SubIFD;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class MaxApertureAtMinFocal extends AbstractTag
{

    protected $Id = 29857;

    protected $Name = 'MaxApertureAtMinFocal';

    protected $FullName = 'Sony::SR2SubIFD';

    protected $GroupName = 'SR2SubIFD';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'SR2SubIFD';

    protected $g2 = 'Camera';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Max Aperture At Min Focal';

    protected $flag_Permanent = true;

}
