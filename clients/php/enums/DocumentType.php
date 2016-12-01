<?php
class DocumentType extends AvaTaxEnum 
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
?>
