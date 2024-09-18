
namespace SqlOrganize.Model
{
    /*
    Lectura de json
    */
    public class EntityTree
    {
        public string fieldId { get; set; }
        public string entityName { get; set; }
        public string fieldName { get; set; }
        public string refEntityName { get; set; }
        public string refFieldName { get; set; }
        public Dictionary<string, EntityTree> children { get; set; }

    }
}
