<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XML;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class GPSLongitude extends AbstractTag
{

    protected $Id = 'MetaDataList//Geolocation/Longitude';

    protected $Name = 'GPSLongitude';

    protected $FullName = 'PLIST::Main';

    protected $GroupName = 'XML';

    protected $g0 = 'PLIST';

    protected $g1 = 'XML';

    protected $g2 = 'Document';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'GPS Longitude';

    protected $local_g2 = 'Location';

}
