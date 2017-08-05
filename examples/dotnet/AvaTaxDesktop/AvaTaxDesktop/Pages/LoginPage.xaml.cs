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

namespace AvaTaxDesktop.Pages
{
    /// <summary>
    /// Interaction logic for LoginPage.xaml
    /// </summary>
    public partial class LoginPage : Page
    {
        public string Username
        {
            get
            {
                return txtUsername.Text;
            }
        }

        public string Password
        {
            get
            {
                return txtPassword.Password;
            }
        }

        public string Environment
        {
            get
            {
                var cbi = cbxEnvironment.SelectedItem as ComboBoxItem;
                if (cbi == null || cbi.Tag == null) return null;
                return cbi.Tag.ToString();
            }
        }

        public LoginPage()
        {
            InitializeComponent();

            // Load previously saved settings
            txtUsername.Text = Properties.Settings.Default.Username;
            txtPassword.Password = Properties.Settings.Default.Password;
            foreach (var customEnvironment in Properties.Settings.Default.CustomEnvironments.Split(',')) {
                cbxEnvironment.Items.Add(new ComboBoxItem()
                {
                    Content = customEnvironment,
                    Tag = customEnvironment
                });
            }
            foreach (ComboBoxItem item in cbxEnvironment.Items) {
                if (item.Tag != null) {
                    item.IsSelected = (item.Tag.ToString() == Properties.Settings.Default.Environment);
                }
            }
        }

        private async void btnLogin_Click(object sender, RoutedEventArgs e)
        {
            App.Client = new Avalara.AvaTax.RestClient.AvaTaxClient("AvaTaxDesktop", "1.0", "", new Uri(Environment))
                .WithSecurity(Username, Password);
            var pingResult = await App.Client.PingAsync();
            if (pingResult.authenticated == true) {

                // Save settings
                if (chkRemember.IsChecked == true) {
                    Properties.Settings.Default.Username = txtUsername.Text;
                    Properties.Settings.Default.Password = txtPassword.Password;
                } else {
                    Properties.Settings.Default.Username = "";
                    Properties.Settings.Default.Password = "";
                }
                Properties.Settings.Default.Environment = Environment;
                Properties.Settings.Default.Save();

                // Go to the invoice page
                MainWindow.Instance.frmMain.Navigate(new InvoicePage());
            }
        }

        private void cbxEnvironment_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            var cbi = cbxEnvironment.SelectedItem as ComboBoxItem;
            if (cbi != null) {
                if (cbi.Tag == null) {
                    // TODO - Pop up messagebox and ask for other url
                }
            }
        }
    }
}
