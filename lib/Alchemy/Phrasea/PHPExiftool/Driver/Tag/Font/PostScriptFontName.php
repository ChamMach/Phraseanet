<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Font;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class PostScriptFontName extends AbstractTag
{

    protected $Id = 'mixed';

    protected $Name = 'PostScriptFontName';

    protected $FullName = 'mixed';

    protected $GroupName = 'Font';

    protected $g0 = 'Font';

    protected $g1 = 'Font';

    protected $g2 = 'Document';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'PostScript Font Name';

}
