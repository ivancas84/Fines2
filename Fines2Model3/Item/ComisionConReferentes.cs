using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class ComisionConReferentesItem : Comision
    {
        public ObservableCollection<string> referentes { get; set; } = new(); //label de referentes para ser visualizados


    }
}
