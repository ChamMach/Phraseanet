<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\PhotoCD;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class AuthoringSoftwareRelease extends AbstractTag
{

    protected $Id = 9;

    protected $Name = 'AuthoringSoftwareRelease';

    protected $FullName = 'PhotoCD::Main';

    protected $GroupName = 'PhotoCD';

    protected $g0 = 'PhotoCD';

    protected $g1 = 'PhotoCD';

    protected $g2 = 'Image';

    protected $Type = 'int8u';

    protected $Writable = false;

    protected $Description = 'Authoring Software Release';

    protected $MaxLength = 2;

}
