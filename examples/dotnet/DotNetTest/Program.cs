using Avalara.AvaTax.RestClient;
using System;
using System.Collections.Generic;

namespace ConsoleTest
{
    class Program
    {
        /// <summary>
        /// To debug this application, call app must be called with args[0] as username and args[1] as password
        /// </summary>
        /// <param name="args"></param>
        public static void Main(string[] args)
        {
            // Connect to the server
            var client = new AvaTaxClient("ConsoleTest", "1.0", Environment.MachineName, AvaTaxEnvironment.Sandbox);
            client.WithSecurity(args[0], args[1]);

            // Call Ping
            var pingResult = client.Ping();
            Console.WriteLine(pingResult.version);

            // Call fetch
            try
            {
                var companies = client.QueryCompanies(null, null, 0, 0, null);
                Console.WriteLine(companies.ToString());

                // Initialize a company and fetch it back
                var init = client.CompanyInitialize(new CompanyInitializationModel()
                {
                    city = "Bainbridge Island",
                    companyCode = Guid.NewGuid().ToString("N").Substring(0, 25),
                    country = "US",
                    email = "bob@example.org",
                    faxNumber = null,
                    firstName = "Bob",
                    lastName = "Example",
                    line1 = "100 Ravine Lane",
                    line2 = null,
                    line3 = null,
                    mobileNumber = null,
                    name = "Bob Example",
                    phoneNumber = "206 555 1212",
                    postalCode = "98110",
                    region = "WA",
                    taxpayerIdNumber = "123456789",
                    title = "Owner",
                    vatRegistrationId = null
                });
                Console.WriteLine(init.ToString());

                // Fetch it back
                var fetchBack = client.GetCompany(init.id, "Locations");
                Console.WriteLine(fetchBack.ToString());

                // Execute a transaction
                var t = new TransactionBuilder(client, init.companyCode, DocumentType.SalesInvoice, "ABC")
                    .WithAddress(TransactionAddressType.SingleLocation, "521 S Weller St", null, null, "Seattle", "WA",
                        "98104", "US")
                    .WithLine(100.0m, 1, "P0000000")
                    .WithLineTaxOverride(TaxOverrideType.TaxAmount, "Test", 50m)
                    .WithLine(200m)
                    .WithExemptLine(50m, "NT")
                    .Create();

                Console.WriteLine(t.ToString());

                // Define a location
                var locations = client.CreateLocations(init.id, new List<LocationModel>()
                {
                    new LocationModel()
                    {
                        companyId = init.id,
                        locationCode = "FIELD",
                        line1 = "123 Main Street",
                        city = "Irvine",
                        region = "CA",
                        postalCode = "92615",
                        country = "US",
                        addressCategoryId = AddressCategoryId.Storefront,
                        addressTypeId = AddressTypeId.Location
                    }
                });
                Console.WriteLine(locations[0].ToString());

                // Create one item form this company
                var items = new List<ItemModel>();
                items.Add(new ItemModel()
                {
                    companyId = init.id,
                    itemCode = "WIDGET1",
                    taxCode = "P0000000",
                    description = "My Widget"
                });
                var createdItems = client.CreateItems(init.id, items);
                Console.WriteLine(createdItems);

                // Now create a point-of-sale file for this location
                var contents = client.BuildPointOfSaleDataForLocation(init.id, locations[0].id, null, null, null, null);
                Console.WriteLine(contents);

            } catch (AvaTaxError ex)
            {
                Console.WriteLine(ex.error.ToString());
            } catch (InvalidOperationException ex)
            {
                Console.WriteLine($"Incorrect method sequence. {ex.Message}");
            }
            // Finished
            Console.WriteLine("Done");
        }
    }
}
