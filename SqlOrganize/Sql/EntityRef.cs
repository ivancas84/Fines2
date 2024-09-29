namespace SqlOrganize.Sql
{
    public class EntityRef
    {
        public string entityName { get; set; } //tabla foranea que hace referencia a la tabla actual
        public string fieldName { get; set; } //campo fk por el cual se hace referencia al id de la tabla actual
    }
}
