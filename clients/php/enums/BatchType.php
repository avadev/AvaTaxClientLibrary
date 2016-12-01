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
 */class BatchType extends AvaTaxEnum 
{

    const AvaCertUpdate = "AvaCertUpdate";
    const AvaCertUpdateAll = "AvaCertUpdateAll";
    const BatchMaintenance = "BatchMaintenance";
    const CompanyLocationImport = "CompanyLocationImport";
    const DocumentImport = "DocumentImport";
    const ExemptCertImport = "ExemptCertImport";
    const ItemImport = "ItemImport";
    const SalesAuditExport = "SalesAuditExport";
    const SstpTestDeckImport = "SstpTestDeckImport";
    const TaxRuleImport = "TaxRuleImport";
    const TransactionImport = "TransactionImport";
    const UPCBulkImport = "UPCBulkImport";
    const UPCValidationImport = "UPCValidationImport";
}
