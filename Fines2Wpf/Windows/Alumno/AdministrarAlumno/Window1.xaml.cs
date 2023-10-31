using Fines2Wpf.Model;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using System.Windows.Controls;
using Utils;


namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{


    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private DAO.Persona personaDAO = new(); //objeto de acceso a datos
        private ObservableCollection<Data_persona> personaData = new(); //datos consultados de la base de datos

        public Window1()
        {
            InitializeComponent();
            personaListBox.Visibility = Visibility.Collapsed; //al iniciar que no se vea la lista de opciones (estara vacia)
            personaListBox.ItemsSource = personaData;

            personaGroupBox.DataContext = new Data_persona(SqlOrganize.DataInitMode.Default);

        }


        private void SearchTextBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            if (this.personaListBox.SelectedIndex > -1)
                if (this.searchTextBox.Text.Equals(((Data_persona)this.personaListBox.SelectedItem).Label))
                    return;
            
            personaData.Clear();
            this.personaListBox.SelectedIndex = -1;
            
            if (string.IsNullOrEmpty(this.searchTextBox.Text) || this.searchTextBox.Text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
            {
                this.personaListBox.Visibility = Visibility.Collapsed;
                return;
            }

            this.personaListBox.Visibility = Visibility.Visible;

            IEnumerable<Dictionary<string, object>> list = personaDAO.SearchLikeQuery(this.searchTextBox.Text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto
            foreach(var item in list)
            {
                var v = (Values.Persona)ContainerApp.db.Values("persona").Set(item!);
                var o = item.Obj<Data_persona>();
                o.Label = v.ToString();
                personaData.Add(o);
            }
        }

        private void PersonaListBox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            this.personaListBox.Visibility = Visibility.Collapsed;

            if (this.personaListBox.SelectedIndex > -1)
            {
                var p = (Data_persona)this.personaListBox.SelectedItem;
                this.searchTextBox.Text = p.Label;
                personaGroupBox.DataContext = p;
            }
        }
    }
}
