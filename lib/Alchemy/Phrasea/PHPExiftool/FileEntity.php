<?php

/**
 * This file is part of the PHPExiftool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool;

use Alchemy\Phrasea\PHPExiftool\Driver\Metadata\MetadataBag;
use Alchemy\Phrasea\PHPExiftool\Driver\Value\ValueInterface;
use Doctrine\Common\Cache\ArrayCache;

/**
 *
 *
 * @author      Romain Neutron - imprec@gmail.com
 * @license     http://opensource.org/licenses/MIT MIT
 */
class FileEntity implements \IteratorAggregate
{

    /**
     *
     * @var \DOMDocument
     */
    private $dom;

    /**
     *
     * @var \SplFileInfo
     */
    private $file;

    /**
     *
     * @var ArrayCache
     */
    private $cache;

    /**
     *
     * @var RDFParser
     */
    private $parser;

    /**
     * Construct a new FileEntity
     *
     * @param  string       $file
     * @param  \DOMDocument $dom
     * @param  RDFParser    $parser
     * @return FileEntity
     */
    public function __construct($file, \DOMDocument $dom, RDFParser $parser)
    {
        $this->dom = $dom;
        $this->file = $file;

        $this->cache = new ArrayCache();

        $this->parser = $parser->open($dom->saveXML());

        return $this;
    }

    public function getIterator()
    {
        return $this->getMetadatas()->getIterator();
    }

    /**
     *
     * @return MetadataBag
     */
    public function getMetadatas()
    {
        $key = realpath($this->file);

        if ($this->cache->contains($key)) {
            return $this->cache->fetch($key);
        }

        $metadatas = $this->parser->ParseMetadatas();

        $this->cache->save($key, $metadatas);

        return $metadatas;
    }

    /**
     *
     * @var string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Execute a user defined query to retrieve metadata
     *
     * @param string $query
     *
     * @return ValueInterface
     */
    public function executeQuery($query)
    {
        return $this->parser->Query($query);
    }

}
