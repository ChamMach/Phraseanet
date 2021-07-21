<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\HTMLProd;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class RecEngineer extends AbstractTag
{

    protected $Id = 'recengineer';

    protected $Name = 'RecEngineer';

    protected $FullName = 'HTML::prod';

    protected $GroupName = 'HTML-prod';

    protected $g0 = 'HTML';

    protected $g1 = 'HTML-prod';

    protected $g2 = 'Document';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Rec Engineer';

}
