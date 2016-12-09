<?php 
namespace Avalara;
/*
 * AvaTax API Client Library
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   AvaTax client libraries
 * @package    Avalara.AvaTaxClient
 * @author     Ted Spence <ted.spence@avalara.com>
 * @author     Bob Maidens <bob.maidens@avalara.com>
 * @copyright  2004-2016 Avalara, Inc.
 * @license    https://www.apache.org/licenses/LICENSE-2.0
 * @version    2.16.12-30
 * @link       https://github.com/avadev/AvaTaxClientLibrary
 */


/**
 * Represents one file in a batch upload.
 */
final class BatchFileModel
{
    /**
     * @var Int32? The unique ID number assigned to this batch file.
     */
    public $id;

    /**
     * @var Int32? The unique ID number of the batch that this file belongs to.
     */
    public $batchId;

    /**
     * @var String Logical Name of file (e.g. "Input" or "Error").
     */
    public $name;

    /**
     * @var String Content of the batch file.
     */
    public $content;

    /**
     * @var Int32? Size of content, in bytes.
     */
    public $contentLength;

    /**
     * @var String Content mime type (e.g. text/csv).  This is used for HTTP downloading.
     */
    public $contentType;

    /**
     * @var String File extension (e.g. CSV).
     */
    public $fileExtension;

    /**
     * @var Int32? Number of errors that occurred when processing this file.
     */
    public $errorCount;

}
