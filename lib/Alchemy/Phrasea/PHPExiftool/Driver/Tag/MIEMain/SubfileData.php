<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\MIEMain;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class SubfileData extends AbstractTag
{

    protected $Id = 'data';

    protected $Name = 'SubfileData';

    protected $FullName = 'MIE::Main';

    protected $GroupName = 'MIE-Main';

    protected $g0 = 'MIE';

    protected $g1 = 'MIE-Main';

    protected $g2 = 'Other';

    protected $Type = 'undef';

    protected $Writable = true;

    protected $Description = 'Subfile Data';

    protected $flag_Binary = true;

}
