<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\MOBI;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class UncompressedTextLength extends AbstractTag
{

    protected $Id = 1;

    protected $Name = 'UncompressedTextLength';

    protected $FullName = 'Palm::MOBI';

    protected $GroupName = 'MOBI';

    protected $g0 = 'Palm';

    protected $g1 = 'MOBI';

    protected $g2 = 'Document';

    protected $Type = 'int32u';

    protected $Writable = false;

    protected $Description = 'Uncompressed Text Length';

}
