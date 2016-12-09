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
 * Represents a batch of uploaded documents.
 */
final class BatchModel
{
    /**
     * @var Int32? The unique ID number of this batch.
     */
    public $id;

    /**
     * @var String The user-friendly readable name for this batch.
     */
    public $name;

    /**
     * @var Int32? The Account ID number of the account that owns this batch.
     */
    public $accountId;

    /**
     * @var Int32? The Company ID number of the company that owns this batch.
     */
    public $companyId;

    /**
     * @var BatchType The type of this batch.
     */
    public $type;

    /**
     * @var BatchStatus? This batch's current processing status
     */
    public $status;

    /**
     * @var String Any optional flags provided for this batch
     */
    public $options;

    /**
     * @var String The agent used to create this batch
     */
    public $batchAgent;

    /**
     * @var DateTime? The date/time when this batch started processing
     */
    public $startedDate;

    /**
     * @var Int32? The number of records in this batch; determined by the server
     */
    public $recordCount;

    /**
     * @var Int32? The current record being processed
     */
    public $currentRecord;

    /**
     * @var DateTime? The date/time when this batch was completely processed
     */
    public $completedDate;

    /**
     * @var DateTime? The date when this record was created.
     */
    public $createdDate;

    /**
     * @var Int32? The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var DateTime? The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var Int32? The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

    /**
     * @var List<BatchFileModel> The list of files contained in this batch.
     */
    public $files;

}
