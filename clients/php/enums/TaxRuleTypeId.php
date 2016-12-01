<?php
/*
 * AvaTax Enum Class
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
 */class TaxRuleTypeId extends AvaTaxEnum 
{

    const RateRule = "RateRule";
    const RateOverrideRule = "RateOverrideRule";
    const BaseRule = "BaseRule";
    const ExemptEntityRule = "ExemptEntityRule";
    const ProductTaxabilityRule = "ProductTaxabilityRule";
    const NexusRule = "NexusRule";
}
