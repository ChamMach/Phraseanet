<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPDroneDji;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class FlightRollDegree extends AbstractTag
{

    protected $Id = 'FlightRollDegree';

    protected $Name = 'FlightRollDegree';

    protected $FullName = 'DJI::XMP';

    protected $GroupName = 'XMP-drone-dji';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-drone-dji';

    protected $g2 = 'Location';

    protected $Type = 'real';

    protected $Writable = true;

    protected $Description = 'Flight Roll Degree';

}
