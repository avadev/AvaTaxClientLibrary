using System;
using System.Collections.Generic;
#if PORTABLE
using System.Threading.Tasks;
#endif

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// Use this class to construct a transaction with convenient syntax
    /// </summary>
    public class TransactionBuilder
    {
        private CreateTransactionModel _model;
        private int _line_number;
        private AvaTaxClient _client;

        #region Constructor
        /// <summary>
        /// TransactionBuilder helps you construct a transaction API call correctly with necessary data
        /// </summary>
        /// <param name="client"></param>
        /// <param name="companyCode"></param>
        public TransactionBuilder(AvaTaxClient client, string companyCode, DocumentType type, string customerCode)
        {
            _model = new CreateTransactionModel
            {
                companyCode = companyCode,
                customerCode = customerCode,
                date = DateTime.UtcNow,
                type = type,
                lines = new List<LineItemModel>()
            };
            _line_number = 1;
            _client = client;
        }
        #endregion

        #region Builder Pattern
        /// <summary>
        /// Set the commit flag of the transaction.
        /// </summary>
        /// <returns></returns>
        public TransactionBuilder WithCommitFlag(bool? commit)
        {
            _model.commit = commit;
            return this;
        }

        /// <summary>
        /// Enable diagnostic information
        /// </summary>
        /// <returns></returns>
        public TransactionBuilder WithDiagnostics()
        {
            _model.debugLevel = TaxDebugLevel.Diagnostic;
            return this;
        }


        /// <summary>
        /// Set a specific discount amount
        /// </summary>
        /// <param name="discount"></param>
        /// <returns></returns>
        public TransactionBuilder WithDiscountAmount(decimal? discount)
        {
            _model.discount = discount;
            return this;
        }

        /// <summary>
        /// Set if discount is applicable for the current line
        /// </summary>
        /// <param name="discounted"></param>
        /// <returns></returns>
        public TransactionBuilder IsItemDiscounted(bool? discounted)
        {
            // Ensure this can only be invoked when a line has been created.
            if (_model.lines.Count <= 0)
            {
                throw new Exception("No lines have been added yet.");
            }

            var l = _model.lines[_model.lines.Count - 1];
            l.discounted = discounted;
            return this;
        }

        /// <summary>
        /// Set a specific transaction code
        /// </summary>
        /// <param name="code"></param>
        /// <returns></returns>
        public TransactionBuilder WithTransactionCode(string code)
        {
            _model.code = code;
            return this;
        }

        /// <summary>
        /// Set the document type
        /// </summary>
        /// <param name="type"></param>
        /// <returns></returns>
        public TransactionBuilder WithType(DocumentType type)
        {
            _model.type = type;
            return this;
        }

        /// <summary>
        /// Add a parameter at the document level
        /// </summary>
        /// <param name="name"></param>
        /// <param name="value"></param>
        /// <returns></returns>
        public TransactionBuilder WithParameter(string name, string value)
        {
            if (_model.parameters == null) _model.parameters = new Dictionary<string, string>();
            _model.parameters[name] = value;
            return this;
        }

        /// <summary>
        /// Add a parameter to the current line
        /// </summary>
        /// <param name="paramname"></param>
        /// <param name="paramvalue"></param>
        /// <returns></returns>
        public TransactionBuilder WithLineParameter(string paramname, string paramvalue)
        {
            // Ensure this can only be invoked when a line has been created.
            if (_model.lines.Count <= 0)
            {
                throw new Exception("No lines have been added yet.");
            }

            var l = _model.lines[_model.lines.Count - 1];
            if (l.parameters == null) l.parameters = new Dictionary<string, string>();
            l.parameters.Add(paramname, paramvalue);
            return this;
        }

        /// <summary>
        /// Add an address to this transaction
        /// </summary>
        /// <param name="type">Address Type. Can be ShipFrom, ShipTo, PointOfOrderAcceptance, PointOfOrderOrigin, SingleLocation.</param>
        /// <param name="line1">The street address, attention line, or business name of the location.</param>
        /// <param name="line2">The street address, business name, or apartment/unit number of the location.</param>
        /// <param name="line3">The street address or apartment/unit number of the location.</param>
        /// <param name="city">City of the location.</param>
        /// <param name="region">State or Region of the location.</param>
        /// <param name="postalCode">Postal/zip code of the location.</param>
        /// <param name="country">The two-letter country code of the location.</param>
        /// <returns></returns>
        public TransactionBuilder WithAddress(TransactionAddressType type, string line1, string line2, string line3, string city, string region, string postalCode, string country)
        {
            if (_model.addresses == null) _model.addresses = new Dictionary<TransactionAddressType, AddressInfo>();
            var ai = new AddressInfo
            {
                line1 = line1,
                line2 = line2,
                line3 = line3,
                city = city,
                region = region,
                postalCode = postalCode,
                country = country
            };
            _model.addresses[type] = ai;
            return this;
        }

        public TransactionBuilder WithLatLong(TransactionAddressType type, decimal latitude, decimal longitude)
        {
            var ai = new AddressInfo
            {
                latitude = latitude,
                longitude = longitude
            };
            _model.addresses[type] = ai;
            return this;
        }

        /// <summary>
        /// Add an address to this line
        /// </summary>
        /// <param name="type">Address Type. Can be ShipFrom, ShipTo, PointOfOrderAcceptance, PointOfOrderOrigin, SingleLocation.</param>
        /// <param name="line1">Street address, attention line, or business name of the location.</param>
        /// <param name="line2">Street address, business name, or apartment/unit number of the location.</param>
        /// <param name="line3">Street address or apartment/unit number of the location.</param>
        /// <param name="city">City of the location.</param>
        /// <param name="region">State or Region of the location.</param>
        /// <param name="postalCode">Postal/zip code of the location.</param>
        /// <param name="country">Two-letter country code of the location.</param>
        /// <returns></returns>
        public TransactionBuilder WithLineAddress(TransactionAddressType type, string line1, string line2, string line3, string city, string region, string postalCode, string country)
        {
            // Ensure this can only be invoked when a line has been created.
            if (_model.lines.Count <= 0)
            {
                throw new Exception("No lines have been added yet.");
            }

            var line = _model.lines[_model.lines.Count - 1];
            if (line.addresses == null) line.addresses = new Dictionary<TransactionAddressType, AddressInfo>();
            line.addresses[type] = new AddressInfo
            {
                line1 = line1,
                line2 = line2,
                line3 = line3,
                city = city,
                region = region,
                postalCode = postalCode,
                country = country
            };
            return this;
        }

        /// <summary>
        /// Add a document-level Tax Override to the transaction.
        ///  - A TaxDate override requires a valid DateTime object to be passed.
        /// TODO: Verify Tax Override constraints and add exceptions.
        /// </summary>
        /// <param name="type">Type of the Tax Override.</param>
        /// <param name="reason">Reason of the Tax Override.</param>
        /// <param name="taxAmount">Amount of tax to apply. Required for a TaxAmount Override.</param>
        /// <param name="taxDate">Date of a Tax Override. Required for a TaxDate Override.</param>
        /// <returns></returns>
        public TransactionBuilder WithGlobalTaxOverride(TaxOverrideType type, string reason, decimal taxAmount = 0, DateTime? taxDate = null)
        {
            _model.taxOverride = new TaxOverrideModel
            {
                type = type,
                reason = reason,
                taxAmount = taxAmount,
                taxDate = taxDate
            };

            // Continue building
            return this;
        }

        /// <summary>
        /// Add a line-level Tax Override to the current line.
        ///  - A TaxDate override requires a valid DateTime object to be passed.
        /// TODO: Verify Tax Override constraints and add exceptions.
        /// </summary>
        /// <param name="type">Type of the Tax Override.</param>
        /// <param name="reason">Reason of the Tax Override.</param>
        /// <param name="taxAmount">Amount of tax to apply. Required for a TaxAmount Override.</param>
        /// <param name="taxDate">Date of a Tax Override. Required for a TaxDate Override.</param>
        /// <returns></returns>
        public TransactionBuilder WithLineTaxOverride(TaxOverrideType type, string reason, decimal taxAmount = 0, DateTime? taxDate = null)
        {
            // Ensure this can only be invoked when a line has been created.
            if (_model.lines.Count <= 0)
            {
                throw new Exception("No lines have been added yet.");
            }

            // Address the DateOverride constraint.
            if (type.Equals(TaxOverrideType.TaxDate) && taxDate == null)
            {
                throw new Exception("A valid date is required for a Tax Date Tax Override.");
            }

            var line = _model.lines[_model.lines.Count - 1];
            line.taxOverride = new TaxOverrideModel
            {
                type = type,
                reason = reason,
                taxAmount = taxAmount,
                taxDate = taxDate
            };

            // Continue building
            return this;
        }

        /// <summary>
        /// Add a line to this transaction
        /// </summary>
        /// <param name="amount">Value of the item.</param>
        /// <param name="quantity">Quantity of the item.</param>
        /// <param name="taxCode">Tax Code of the item. If left blank, the default item (P0000000) is assumed.</param>
        /// <returns></returns>
        public TransactionBuilder WithLine(decimal amount, decimal quantity = 1, string taxCode = null)
        {
            var l = new LineItemModel
            {
                number = _line_number.ToString(),
                quantity = quantity,
                amount = amount,
                taxCode = taxCode
            };

            _model.lines.Add(l);
            _line_number++;

            // Continue building
            return this;
        }

        /// <summary>
        /// Add a line to this transaction
        /// </summary>
        /// <param name="amount">Value of the item.</param>
        /// <param name="type">Address Type. Can be ShipFrom, ShipTo, PointOfOrderAcceptance, PointOfOrderOrigin, SingleLocation.</param>
        /// <param name="line1">Street address, attention line, or business name of the location.</param>
        /// <param name="line2">Street address, business name, or apartment/unit number of the location.</param>
        /// <param name="line3">Street address or apartment/unit number of the location.</param>
        /// <param name="city">City of the location.</param>
        /// <param name="region">State or Region of the location.</param>
        /// <param name="postalCode">Postal/zip code of the location.</param>
        /// <param name="country">Two-letter country code of the location.</param>
        /// <returns></returns>
        public TransactionBuilder WithSeparateAddressLine(decimal amount, TransactionAddressType type, string line1, string line2, string line3, string city, string region, string postalCode, string country)
        {
            var l = new LineItemModel
            {
                number = _line_number.ToString(),
                quantity = 1,
                amount = amount
            };

            // Add this address
            l.addresses = new Dictionary<TransactionAddressType, AddressInfo>();
            l.addresses[type] = new AddressInfo
            {
                line1 = line1,
                line2 = line2,
                line3 = line3,
                city = city,
                region = region,
                postalCode = postalCode,
                country = country
            };

            // Put this line in the model
            _model.lines.Add(l);
            _line_number++;

            // Continue building
            return this;
        }

        /// <summary>
        /// Add a line with an exemption to this transaction
        /// </summary>
        /// <param name="amount"></param>
        /// <param name="exemptionCode"></param>
        /// <returns></returns>
        public TransactionBuilder WithExemptLine(decimal amount, string exemptionCode)
        {
            var l = new LineItemModel
            {
                number = _line_number.ToString(),
                quantity = 1,
                amount = amount,
                exemptionCode = exemptionCode
            };
            _model.lines.Add(l);
            _line_number++;

            // Continue building
            return this;
        }
        #endregion

        #region Create 
#if PORTABLE
        /// <summary>
        /// Create this transaction
        /// </summary>
        /// <returns></returns>
        public async Task<TransactionModel> CreateAsync()
        {
            return await _client.CreateTransactionAsync(_model);
        }

        /// <summary>
        /// Create this transaction
        /// </summary>
        /// <returns></returns>
        public TransactionModel Create()
        {
            return _client.CreateTransaction(_model);
        }
#else
        /// <summary>
        /// Create this transaction
        /// </summary>
        /// <param name="client"></param>
        /// <returns></returns>
        public TransactionModel Create()
        {
            return _client.CreateTransaction(_model);
        }
#endif

        /// <summary>
        /// For using this with an adjustment
        /// </summary>
        /// <returns></returns>
        public AdjustTransactionModel CreateAdjustmentRequest(string desc, AdjustmentReason reason)
        {
            return new AdjustTransactionModel
            {
                newTransaction = _model,
                adjustmentDescription = desc,
                adjustmentReason = reason
            };
        }
        #endregion
    }
}