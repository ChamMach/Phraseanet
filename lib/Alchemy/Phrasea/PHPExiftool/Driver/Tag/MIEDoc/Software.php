<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\MIEDoc;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Software extends AbstractTag
{

    protected $Id = 'Software';

    protected $Name = 'Software';

    protected $FullName = 'MIE::Doc';

    protected $GroupName = 'MIE-Doc';

    protected $g0 = 'MIE';

    protected $g1 = 'MIE-Doc';

    protected $g2 = 'Document';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Software';

}
