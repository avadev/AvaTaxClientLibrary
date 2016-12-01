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
final class LocationQuestionModel extends AbstractEntity
{
    /**
     * @var Int32
     */
    public $id;

    /**
     * @var String
     */
    public $question;

    /**
     * @var String
     */
    public $description;

    /**
     * @var String
     */
    public $regularExpression;

    /**
     * @var String
     */
    public $exampleValue;

    /**
     * @var String
     */
    public $jurisdictionName;

    /**
     * @var JurisdictionType?
     */
    public $jurisdictionType;

    /**
     * @var String
     */
    public $jurisdictionCountry;

    /**
     * @var String
     */
    public $jurisdictionRegion;

}