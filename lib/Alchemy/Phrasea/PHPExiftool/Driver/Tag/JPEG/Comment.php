<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\JPEG;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Comment extends AbstractTag
{

    protected $Id = 'mixed';

    protected $Name = 'Comment';

    protected $FullName = 'JPEG::Main';

    protected $GroupName = 'JPEG';

    protected $g0 = 'JPEG';

    protected $g1 = 'JPEG';

    protected $g2 = 'Other';

    protected $Type = 'mixed';

    protected $Writable = false;

    protected $Description = 'Comment';

}
