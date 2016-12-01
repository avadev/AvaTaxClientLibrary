<?php
/*
 * AvaTax Model
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com
 */
final class BatchModel extends AbstractEntity
{
    /**
     * @var Int32?
     */
    public $id;

    /**
     * @var String
     */
    public $name;

    /**
     * @var Int32?
     */
    public $accountId;

    /**
     * @var Int32?
     */
    public $companyId;

    /**
     * @var BatchType
     */
    public $type;

    /**
     * @var BatchStatus?
     */
    public $status;

    /**
     * @var String
     */
    public $options;

    /**
     * @var String
     */
    public $batchAgent;

    /**
     * @var DateTime?
     */
    public $startedDate;

    /**
     * @var Int32?
     */
    public $recordCount;

    /**
     * @var Int32?
     */
    public $currentRecord;

    /**
     * @var DateTime?
     */
    public $completedDate;

    /**
     * @var DateTime?
     */
    public $createdDate;

    /**
     * @var Int32?
     */
    public $createdUserId;

    /**
     * @var DateTime?
     */
    public $modifiedDate;

    /**
     * @var Int32?
     */
    public $modifiedUserId;

    /**
     * @var List<BatchFileModel>
     */
    public $files;

}