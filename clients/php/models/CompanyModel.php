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
final class CompanyModel extends AbstractEntity
{
    /**
     * @var Int32
     */
    public $id;

    /**
     * @var Int32
     */
    public $accountId;

    /**
     * @var Int32?
     */
    public $parentCompanyId;

    /**
     * @var String
     */
    public $sstPid;

    /**
     * @var String
     */
    public $companyCode;

    /**
     * @var String
     */
    public $name;

    /**
     * @var Boolean?
     */
    public $isDefault;

    /**
     * @var Int32?
     */
    public $defaultLocationId;

    /**
     * @var Boolean?
     */
    public $isActive;

    /**
     * @var String
     */
    public $taxpayerIdNumber;

    /**
     * @var Boolean?
     */
    public $hasProfile;

    /**
     * @var Boolean?
     */
    public $isReportingEntity;

    /**
     * @var DateTime?
     */
    public $sstEffectiveDate;

    /**
     * @var String
     */
    public $defaultCountry;

    /**
     * @var String
     */
    public $baseCurrencyCode;

    /**
     * @var RoundingLevelId?
     */
    public $roundingLevelId;

    /**
     * @var Boolean?
     */
    public $warningsEnabled;

    /**
     * @var Boolean?
     */
    public $isTest;

    /**
     * @var TaxDependencyLevelId?
     */
    public $taxDependencyLevelId;

    /**
     * @var Boolean?
     */
    public $inProgress;

    /**
     * @var String
     */
    public $businessIdentificationNo;

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
     * @var List<ContactModel>
     */
    public $contacts;

    /**
     * @var List<ItemModel>
     */
    public $items;

    /**
     * @var List<LocationModel>
     */
    public $locations;

    /**
     * @var List<NexusModel>
     */
    public $nexus;

    /**
     * @var List<SettingModel>
     */
    public $settings;

    /**
     * @var List<TaxCodeModel>
     */
    public $taxCodes;

    /**
     * @var List<TaxRuleModel>
     */
    public $taxRules;

    /**
     * @var List<UPCModel>
     */
    public $upcs;

}