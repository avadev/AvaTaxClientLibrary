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
 */class DocumentType extends AvaTaxEnum 
{

    const SalesOrder = "SalesOrder";
    const SalesInvoice = "SalesInvoice";
    const PurchaseOrder = "PurchaseOrder";
    const PurchaseInvoice = "PurchaseInvoice";
    const ReturnOrder = "ReturnOrder";
    const ReturnInvoice = "ReturnInvoice";
    const InventoryTransferOrder = "InventoryTransferOrder";
    const InventoryTransferInvoice = "InventoryTransferInvoice";
    const ReverseChargeOrder = "ReverseChargeOrder";
    const ReverseChargeInvoice = "ReverseChargeInvoice";
    const Any = "Any";
}
