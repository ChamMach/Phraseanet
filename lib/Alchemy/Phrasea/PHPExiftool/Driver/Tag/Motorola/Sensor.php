<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Motorola;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Sensor extends AbstractTag
{

    protected $Id = 26206;

    protected $Name = 'Sensor';

    protected $FullName = 'Motorola::Main';

    protected $GroupName = 'Motorola';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'Motorola';

    protected $g2 = 'Camera';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Sensor';

    protected $flag_Permanent = true;

}
