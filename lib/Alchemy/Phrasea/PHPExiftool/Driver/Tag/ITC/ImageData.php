<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ITC;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class ImageData extends AbstractTag
{

    protected $Id = 'data';

    protected $Name = 'ImageData';

    protected $FullName = 'ITC::Main';

    protected $GroupName = 'ITC';

    protected $g0 = 'ITC';

    protected $g1 = 'ITC';

    protected $g2 = 'Other';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Image Data';

}
