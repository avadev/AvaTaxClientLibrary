using Avalara.AvaTax.RestClient;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace AvaTaxDesktop.Controls
{
    /// <summary>
    /// Shortcut for quickly defining an address
    /// </summary>
    public partial class AddressControl : UserControl
    {
        public AddressControl()
        {
            InitializeComponent();
        }

        public AddressInfo Address
        {
            get
            {
                var countryitem = cbxCountry.SelectedItem as ComboBoxItem;
                var regionitem = cbxRegion.SelectedItem as ComboBoxItem;
                return new AddressInfo()
                {
                    city = txtCity.Text,
                    country = countryitem.Tag as string,
                    line1 = txtLine1.Text,
                    line2 = txtLine2.Text,
                    line3 = txtLine3.Text,
                    postalCode = txtPostalCode.Text,
                    region = regionitem.Tag as string
                };
            }
        }

        private static FetchResult<IsoCountryModel> _countries = null;
        private async void cbxCountry_Loaded(object sender, RoutedEventArgs e)
        {
            if (_countries == null) {
                _countries = await App.Client.ListCountriesAsync(null, null, null, "name asc");
            }
            foreach (var c in _countries.value) {
                cbxCountry.Items.Add(new ComboBoxItem()
                {
                    Content = c.name,
                    Tag = c.code,
                    IsSelected = (c.code == "US")
                });
            }
        }

        private static FetchResult<IsoRegionModel> _regions = null;
        private async void cbxCountry_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (_regions == null) {
                _regions = await App.Client.ListRegionsAsync(null, null, null, "name asc");
            }
            cbxRegion.Items.Clear();
            if (cbxCountry.SelectedItem is ComboBoxItem) {
                foreach (var r in _regions.value) {
                    if (r.countryCode == ((ComboBoxItem)cbxCountry.SelectedItem).Tag as string) {
                        cbxRegion.Items.Add(new ComboBoxItem()
                        {
                            Content = r.name,
                            Tag = r.code
                        });
                    }
                }
            }
        }
    }
}
