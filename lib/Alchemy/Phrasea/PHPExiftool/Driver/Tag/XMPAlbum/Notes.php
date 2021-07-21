<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPAlbum;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Notes extends AbstractTag
{

    protected $Id = 'Notes';

    protected $Name = 'Notes';

    protected $FullName = 'XMP::Album';

    protected $GroupName = 'XMP-album';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-album';

    protected $g2 = 'Image';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Notes';

}
