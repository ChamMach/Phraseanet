<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ICCProfile;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class UCRBG extends AbstractTag
{

    protected $Id = 'bfd ';

    protected $Name = 'UCRBG';

    protected $FullName = 'ICC_Profile::Main';

    protected $GroupName = 'ICC_Profile';

    protected $g0 = 'ICC_Profile';

    protected $g1 = 'ICC_Profile';

    protected $g2 = 'Image';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Under Color Removal and Black Gen.';

}
