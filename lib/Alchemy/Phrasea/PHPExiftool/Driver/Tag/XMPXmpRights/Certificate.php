<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPXmpRights;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Certificate extends AbstractTag
{

    protected $Id = 'Certificate';

    protected $Name = 'Certificate';

    protected $FullName = 'XMP::xmpRights';

    protected $GroupName = 'XMP-xmpRights';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-xmpRights';

    protected $g2 = 'Author';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Certificate';

}
