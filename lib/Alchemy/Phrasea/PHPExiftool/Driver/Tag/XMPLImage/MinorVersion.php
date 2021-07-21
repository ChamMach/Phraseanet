<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPLImage;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class MinorVersion extends AbstractTag
{

    protected $Id = 'MinorVersion';

    protected $Name = 'MinorVersion';

    protected $FullName = 'XMP::LImage';

    protected $GroupName = 'XMP-LImage';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-LImage';

    protected $g2 = 'Image';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Minor Version';

}
