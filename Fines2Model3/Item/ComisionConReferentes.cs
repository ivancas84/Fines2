using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class ComisionConReferentesItem : Data_comision_r
    {
        public ObservableCollection<string> referentes { get; set; } = new(); //label de referentes para ser visualizados

    }
}
