<?php
/*
 * AvaTax Entity Model Class
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com>
 */
final class BatchFileModel extends AbstractEntity
{
    /**
     * @var Int32?
     */
    public $id;

    /**
     * @var Int32?
     */
    public $batchId;

    /**
     * @var String
     */
    public $name;

    /**
     * @var String
     */
    public $content;

    /**
     * @var Int32?
     */
    public $contentLength;

    /**
     * @var String
     */
    public $contentType;

    /**
     * @var String
     */
    public $fileExtension;

    /**
     * @var Int32?
     */
    public $errorCount;

}
