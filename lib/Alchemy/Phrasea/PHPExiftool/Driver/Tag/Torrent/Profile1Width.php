<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Torrent;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Profile1Width extends AbstractTag
{

    protected $Id = 'width';

    protected $Name = 'Profile1Width';

    protected $FullName = 'Torrent::Profiles';

    protected $GroupName = 'Torrent';

    protected $g0 = 'Torrent';

    protected $g1 = 'Torrent';

    protected $g2 = 'Document';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Profile 1 Width';

}
