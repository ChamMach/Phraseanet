<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ID3v23;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Popularimeter extends AbstractTag
{

    protected $Id = 'POPM';

    protected $Name = 'Popularimeter';

    protected $FullName = 'ID3::v2_3';

    protected $GroupName = 'ID3v2_3';

    protected $g0 = 'ID3';

    protected $g1 = 'ID3v2_3';

    protected $g2 = 'Audio';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Popularimeter';

}
