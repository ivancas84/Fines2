using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.ViewModels
{
    class Persona : Data_persona
    {
        public string? label { set; get; }

        public Persona InitLabel() {

            string s = "";
            s += nombres ?? "" + " ";
            s += apellidos ?? "" + " ";
            s += numero_documento ?? "";
            label = s.Trim();
            return this;
        }
    }
}
