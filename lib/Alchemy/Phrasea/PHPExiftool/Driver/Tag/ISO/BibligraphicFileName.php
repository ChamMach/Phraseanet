<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ISO;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class BibligraphicFileName extends AbstractTag
{

    protected $Id = 776;

    protected $Name = 'BibligraphicFileName';

    protected $FullName = 'ISO::PrimaryVolume';

    protected $GroupName = 'ISO';

    protected $g0 = 'ISO';

    protected $g1 = 'ISO';

    protected $g2 = 'Other';

    protected $Type = 'string';

    protected $Writable = false;

    protected $Description = 'Bibligraphic File Name';

    protected $MaxLength = 37;

}
