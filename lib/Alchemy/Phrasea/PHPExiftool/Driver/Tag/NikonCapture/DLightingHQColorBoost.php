<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\NikonCapture;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class DLightingHQColorBoost extends AbstractTag
{

    protected $Id = 2;

    protected $Name = 'D-LightingHQColorBoost';

    protected $FullName = 'NikonCapture::DLightingHQ';

    protected $GroupName = 'NikonCapture';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'NikonCapture';

    protected $g2 = 'Image';

    protected $Type = 'int32u';

    protected $Writable = true;

    protected $Description = 'D-Lighting HQ Color Boost';

    protected $flag_Permanent = true;

}
