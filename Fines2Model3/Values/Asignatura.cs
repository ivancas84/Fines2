using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3      
{
    public class AsignaturaValues : EntityValues
    {
        public AsignaturaValues(Db _db, string? _fieldId = null) : base(_db, "asignatura", _fieldId)
        {
        }

        public override string ToString()
        {
            return GetStr("?", " ", "nombre", "codigo");
        }
    }
}
