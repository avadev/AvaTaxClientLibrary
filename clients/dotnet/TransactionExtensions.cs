using System;
using System.Collections.Generic;
using System.Text;
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
            _model = new CreateTransactionModel()
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
        /// Flag this transaction to commit
        /// </summary>
        /// <returns></returns>
        public TransactionBuilder WithCommit()
        {
            _model.commit = true;
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
        /// Add a parameter to the current line
        /// </summary>
        /// <param name="paramname"></param>
        /// <param name="paramvalue"></param>
        /// <returns></returns>
        public TransactionBuilder WithLineParameter(string paramname, string paramvalue)
        {
            var l = _model.lines[_model.lines.Count - 1];
            if (l.parameters == null) l.parameters = new Dictionary<string, string>();
            l.parameters.Add(paramname, paramvalue);
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
        /// Add an address to this transaction
        /// </summary>
        /// <param name="type"></param>
        /// <param name="line1"></param>
        /// <param name="city"></param>
        /// <param name="region"></param>
        /// <param name="postalCode"></param>
        /// <param name="country"></param>
        /// <returns></returns>
        public TransactionBuilder WithAddress(TransactionAddressType type, string line1, string city, string region, string postalCode, string country)
        {
            if (_model.addresses == null) _model.addresses = new Dictionary<TransactionAddressType, AddressInfo>();
            var ai = new AddressInfo()
            {
                line1 = line1,
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
            var ai = new AddressInfo()
            {
                latitude = latitude,
                longitude = longitude
            };
            _model.addresses[type] = ai;
            return this;
        }

        /// <summary>
        /// Add an address to this transaction
        /// </summary>
        /// <param name="type"></param>
        /// <param name="region"></param>
        /// <returns></returns>
        public TransactionBuilder WithLineAddress(TransactionAddressType type, string line1, string city, string region, string postalCode, string country)
        {
            var line = _model.lines[_model.lines.Count - 1];
            if (line.addresses == null) line.addresses = new Dictionary<TransactionAddressType, AddressInfo>();
            line.addresses[type] = new AddressInfo()
            {
                line1 = line1,
                city = city,
                region = region,
                postalCode = postalCode,
                country = country
            };
            return this;
        }

        /// <summary>
        /// Add a global tax override to this transaction
        /// </summary>
        /// <param name="type"></param>
        /// <param name="taxAmount"></param>
        /// <param name="taxDate"></param>
        /// <returns></returns>
        public TransactionBuilder WithGlobalTaxOverride(TaxOverrideType type, decimal taxAmount, DateTime taxDate)
        {
            _model.taxOverride = new TaxOverrideModel()
            {
                type = type,
                reason = "global tax override",
                taxAmount = taxAmount,
                taxDate = taxDate
            };

            // Continue building
            return this;
        }

        /// <summary>
        /// Add a line to this transaction
        /// </summary>
        /// <param name="amount"></param>
        /// <returns></returns>
        public TransactionBuilder WithLine(decimal amount, string taxCode = null)
        {
            var l = new LineItemModel()
            {
                number = _line_number.ToString(),
                quantity = 1,
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
        /// <param name="amount"></param>
        /// <returns></returns>
        public TransactionBuilder WithSeparateAddressLine(decimal amount, TransactionAddressType type, string line1, string line2, string line3, string city, string region, string postalCode, string country)
        {
            var l = new LineItemModel()
            {
                number = _line_number.ToString(),
                quantity = 1,
                amount = amount,
            };

            // Add this address
            l.addresses = new Dictionary<TransactionAddressType, AddressInfo>();
            l.addresses[type] = new AddressInfo()
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
        /// Add a line to this transaction
        /// </summary>
        /// <param name="amount"></param>
        /// <returns></returns>
        public TransactionBuilder WithExemptLine(decimal amount, string exemptionCode)
        {
            var l = new LineItemModel()
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
        /// <param name="client"></param>
        /// <returns></returns>
        public async Task<TransactionModel> CreateAsync()
        {
            return await _client.CreateTransactionAsync(_model);
        }

        /// <summary>
        /// Create this transaction
        /// </summary>
        /// <param name="client"></param>
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
            return new AdjustTransactionModel()
            {
                newTransaction = _model,
                adjustmentDescription = desc,
                adjustmentReason = reason
            };
        }
        #endregion
    }
}
