<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\PhotoMechanic;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class CropRight extends AbstractTag
{

    protected $Id = 219;

    protected $Name = 'CropRight';

    protected $FullName = 'PhotoMechanic::SoftEdit';

    protected $GroupName = 'PhotoMechanic';

    protected $g0 = 'PhotoMechanic';

    protected $g1 = 'PhotoMechanic';

    protected $g2 = 'Image';

    protected $Type = 'int32s';

    protected $Writable = true;

    protected $Description = 'Crop Right';

}
