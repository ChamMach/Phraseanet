<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\MIEAudio;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class RelatedAudioFile extends AbstractTag
{

    protected $Id = 'data';

    protected $Name = 'RelatedAudioFile';

    protected $FullName = 'MIE::Audio';

    protected $GroupName = 'MIE-Audio';

    protected $g0 = 'MIE';

    protected $g1 = 'MIE-Audio';

    protected $g2 = 'Audio';

    protected $Type = 'undef';

    protected $Writable = true;

    protected $Description = 'Related Audio File';

    protected $flag_Binary = true;

}
