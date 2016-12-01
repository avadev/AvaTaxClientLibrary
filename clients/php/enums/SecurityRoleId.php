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
 */class SecurityRoleId extends AvaTaxEnum 
{

    const NoAccess = "NoAccess";
    const SiteAdmin = "SiteAdmin";
    const AccountOperator = "AccountOperator";
    const AccountAdmin = "AccountAdmin";
    const AccountUser = "AccountUser";
    const SystemAdmin = "SystemAdmin";
    const Registrar = "Registrar";
    const CSPTester = "CSPTester";
    const CSPAdmin = "CSPAdmin";
    const SystemOperator = "SystemOperator";
    const TechnicalSupportUser = "TechnicalSupportUser";
    const TechnicalSupportAdmin = "TechnicalSupportAdmin";
    const TreasuryUser = "TreasuryUser";
    const TreasuryAdmin = "TreasuryAdmin";
    const ComplianceUser = "ComplianceUser";
    const ComplianceAdmin = "ComplianceAdmin";
    const ProStoresOperator = "ProStoresOperator";
    const CompanyUser = "CompanyUser";
    const CompanyAdmin = "CompanyAdmin";
    const ComplianceTempUser = "ComplianceTempUser";
    const ComplianceRootUser = "ComplianceRootUser";
    const ComplianceOperator = "ComplianceOperator";
    const SSTAdmin = "SSTAdmin";
}
