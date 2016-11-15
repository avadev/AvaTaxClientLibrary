using Avalara.AvaTax.RestClient;
using Avalara.AvaTax.RestClient.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

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
            MainAsync(args).Wait();
        }

        public async static Task MainAsync(string[] args)
        {
            // Connect to the server
            var client = new AvaTaxClient("ConsoleTest", "1.0", Environment.MachineName, AvaTaxEnvironment.Sandbox);
            client.WithSecurity(args[0], args[1]);

            // Call Ping
            var pingResult = await client.Ping();
            Console.WriteLine(pingResult.Version);

            // Call fetch
            try {
                var companies = await client.QueryCompanies(null, null, 0, 0, null);
                Console.WriteLine(companies.ToString());

                // Initialize a company and fetch it back
                var init = await client.CompanyInitialize(new CompanyInitializationModel()
                {
                    City = "Bainbridge Island",
                    CompanyCode = Guid.NewGuid().ToString("N"),
                    Country = "US",
                    Email = "bob@example.org",
                    FaxNumber = null,
                    FirstName = "Bob",
                    LastName = "Example",
                    Line1 = "100 Ravine Lane",
                    Line2 = null,
                    Line3 = null,
                    MobileNumber = null,
                    Name = "Bob Example",
                    PhoneNumber = "206 555 1212",
                    PostalCode = "98110",
                    Region = "WA",
                    TaxpayerIdNumber = "123456789",
                    Title = "Owner",
                    VatRegistrationId = null
                });
                Console.WriteLine(init.ToString());

                // Fetch it back
                var fetchBack = await client.GetCompany(init.Id.Value, "Locations");
                Console.WriteLine(fetchBack.ToString());

            } catch (AvaTaxError ex) {
                Console.WriteLine(ex.error.ToString());
            }
        }
    }
}
