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
final class ErrorDetail extends AbstractEntity
{
    /**
     * @var ErrorCodeId?
     */
    public $code;

    /**
     * @var Int32?
     */
    public $number;

    /**
     * @var String
     */
    public $message;

    /**
     * @var String
     */
    public $description;

    /**
     * @var String
     */
    public $faultCode;

    /**
     * @var String
     */
    public $helpLink;

    /**
     * @var String
     */
    public $refersTo;

    /**
     * @var SeverityLevel?
     */
    public $severity;

}