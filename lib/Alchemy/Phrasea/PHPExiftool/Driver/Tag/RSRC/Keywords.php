<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\RSRC;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Keywords extends AbstractTag
{

    protected $Id = 'STR#_0x0080';

    protected $Name = 'Keywords';

    protected $FullName = 'RSRC::Main';

    protected $GroupName = 'RSRC';

    protected $g0 = 'RSRC';

    protected $g1 = 'RSRC';

    protected $g2 = 'Document';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Keywords';

}
